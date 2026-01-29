<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Group;
use App\Models\Lesson;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    // Constants for magic numbers
    private const PAGINATION_PER_PAGE = 15;
    private const BATCH_INSERT_SIZE = 500;
    private const CAPACITY_SMALL_MAX = 15;
    private const CAPACITY_MEDIUM_MIN = 15;
    private const CAPACITY_MEDIUM_MAX = 25;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Group::with(['course', 'teachers', 'activeStudents']);

        // Apply filters
        $this->applyFilters($query, $request);

        $groups = $query->latest()->paginate(self::PAGINATION_PER_PAGE)->withQueryString();

        // Get optimized statistics
        $statistics = $this->getStatistics();

        // Courses for filter
        $courses = Cours::where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title', 'code']);

        return view('Group.index', array_merge(
            compact('groups', 'courses'),
            $statistics
        ));
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request): void
    {
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Course filter
        if ($request->filled('course_id')) {
            $query->where('cours_id', $request->course_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Capacity filter
        if ($request->filled('capacity_range')) {
            $this->applyCapacityFilter($query, $request->capacity_range);
        }

        // Days filter
        if ($request->filled('days')) {
            $query->whereJsonContains('days_of_week', $request->days);
        }
    }

    /**
     * Apply capacity range filter
     */
    private function applyCapacityFilter($query, string $range): void
    {
        switch ($range) {
            case 'small':
                $query->where('capacity', '<', self::CAPACITY_SMALL_MAX);
                break;
            case 'medium':
                $query->whereBetween('capacity', [self::CAPACITY_MEDIUM_MIN, self::CAPACITY_MEDIUM_MAX]);
                break;
            case 'large':
                $query->where('capacity', '>', self::CAPACITY_MEDIUM_MAX);
                break;
        }
    }

    /**
     * Get optimized statistics (single query instead of multiple)
     */
    private function getStatistics(): array
    {
        $groupStats = Group::selectRaw('
            COUNT(*) as total_groups,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_groups,
            AVG(capacity) as avg_capacity
        ')->first();

        $totalStudents = DB::table('group_students')
            ->where('status', 'active')
            ->distinct('student_id')
            ->count();

        return [
            'totalGroups' => $groupStats->total_groups ?? 0,
            'activeGroups' => $groupStats->active_groups ?? 0,
            'totalStudents' => $totalStudents,
            'avgCapacity' => round($groupStats->avg_capacity ?? 0, 1),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $courses = Cours::where('is_active', true)->get();
        $preselectedCourseId = $request->input('course_id');
        return view('Group.create', compact('courses', 'preselectedCourseId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateGroupData($request);

        // Normalize days of week
        $validated['is_active'] = $request->has('is_active');
        $validated['days_of_week'] = array_map('strtolower', $validated['days_of_week']);

        // Validate course duration
        $course = Cours::findOrFail($validated['cours_id']);
        $validationError = $this->validateCourseDuration($validated, $course);

        if ($validationError) {
            return $validationError;
        }

        try {
            $lessonsCount = 0;

            DB::transaction(function () use ($validated, &$lessonsCount) {
                $group = Group::create($validated);
                $lessonsCount = $this->generateLessonsForGroup($group);
            });

            return redirect()
                ->route('groups.index')
                ->with('success', "Guruh va {$lessonsCount} ta dars muvaffaqiyatli yaratildi!");
        } catch (\Exception $e) {
            Log::error('Group creation failed', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Validate group data
     */
    private function validateGroupData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'cours_id' => 'required|exists:cours,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'capacity' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);
    }

    /**
     * Validate course duration against selected dates
     */
    private function validateCourseDuration(array $validated, Cours $course)
    {
        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end = Carbon::parse($validated['end_date'])->startOfDay();

        $monthsDiff = (int) $start->diffInMonths($end);
        $maxMonths = (int) $course->duration_months;

        if ($monthsDiff > $maxMonths) {
            return back()
                ->withInput()
                ->withErrors([
                    'end_date' => "Bu kurs maksimal {$maxMonths} oy davom etadi. Siz {$monthsDiff} oy tanladingiz."
                ]);
        }

        return null;
    }

    /**
     * Generate lessons for a group
     */
    private function generateLessonsForGroup(Group $group): int
    {
        $lessons = [];
        $period = CarbonPeriod::create($group->start_date, $group->end_date);

        // Optimize lookup with array flip
        $selectedDays = array_flip(array_map('strtolower', $group->days_of_week));
        $now = now();
        $primaryTeacher = $group->teachers()->wherePivot('is_primary', true)->first();
        $teacherId = $primaryTeacher ? $primaryTeacher->id : null;

        foreach ($period as $date) {
            if (isset($selectedDays[strtolower($date->format('l'))])) {
                $lessons[] = [
                    'group_id' => $group->id,
                    'teacher_id' => $teacherId,
                    'lesson_date' => $date->toDateString(),
                    'start_time' => $group->start_time,
                    'end_time' => $group->end_time,
                    'status' => 'scheduled',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Batch insert with chunking for large datasets
        if (!empty($lessons)) {
            foreach (array_chunk($lessons, self::BATCH_INSERT_SIZE) as $chunk) {
                Lesson::insert($chunk);
            }
        }

        return count($lessons);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $group->load(['course', 'teachers', 'activeStudents']);

        $stats = [
            'total_students' => $group->activeStudents()->count(),
            'total_teachers' => $group->teachers()->count(),
            'capacity_used' => $group->capacity > 0
                ? round(($group->activeStudents()->count() / $group->capacity) * 100)
                : 0,
            'days_count' => count($group->days_of_week ?? [])
        ];

        return view('Group.show', compact('group', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        $courses = Cours::where('is_active', true)->get();
        return view('Group.edit', compact('group', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $this->validateGroupData($request);

        // Normalize data
        $validated['is_active'] = $request->has('is_active');
        $validated['days_of_week'] = array_map('strtolower', $validated['days_of_week']);

        // Validate course duration
        $course = Cours::findOrFail($validated['cours_id']);
        $validationError = $this->validateCourseDuration($validated, $course);

        if ($validationError) {
            return $validationError;
        }

        // Check if schedule changed (FIXED: sort before comparison)
        $scheduleChanged = $this->hasScheduleChanged($group, $validated);

        try {
            DB::transaction(function () use ($group, $validated, $scheduleChanged) {
                $group->update($validated);

                // Only regenerate lessons if schedule actually changed
                if ($scheduleChanged) {
                    $this->synchronizeLessons($group);
                }
            });

            return redirect()->route('groups.index')
                ->with('success', 'Guruh jadvali muvaffaqiyatli yangilandi!');
        } catch (\Exception $e) {
            Log::error('Group update failed', [
                'group_id' => $group->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    /**
     * Check if schedule has changed (FIXED: proper array comparison)
     */
    private function hasScheduleChanged(Group $group, array $validated): bool
    {
        $newStart = Carbon::parse($validated['start_date']);
        $newEnd = Carbon::parse($validated['end_date']);

        // Sort arrays before comparison to avoid false positives
        $oldDays = $group->days_of_week;
        $newDays = $validated['days_of_week'];
        sort($oldDays);
        sort($newDays);

        return (
            $group->start_date->format('Y-m-d') !== $newStart->format('Y-m-d') ||
            $group->end_date->format('Y-m-d') !== $newEnd->format('Y-m-d') ||
            $group->start_time !== $validated['start_time'] ||
            $group->end_time !== $validated['end_time'] ||
            $oldDays !== $newDays
        );
    }

    /**
     * Synchronize lessons with updated group schedule
     */
    private function synchronizeLessons(Group $group): void
    {
        // 1. Yangi talab qilinadigan sanalarni hisoblash
        $newDates = $this->calculateRequiredLessonDates($group);

        // 2. Mavjud asosiy o'qituvchini olish
        $primaryTeacher = $group->teachers()->wherePivot('is_primary', true)->first();
        $teacherId = $primaryTeacher ? $primaryTeacher->id : null;

        // 3. Kelajakdagi mavjud darslarni olish (Race condition oldini olish uchun lock bilan)
        $existingLessons = $group->lessons()
            ->where('lesson_date', '>=', now()->toDateString())
            ->lockForUpdate()
            ->get(['id', 'lesson_date']);

        $existingDates = $existingLessons->pluck('lesson_date')->toArray();

        // 4. Keraksiz darslarni o'chirish (Davomat olinmagan bo'lsa)
        $datesToDelete = array_diff($existingDates, $newDates);
        if (!empty($datesToDelete)) {
            $deleted = $group->lessons()
                ->whereIn('lesson_date', $datesToDelete)
                ->whereDoesntHave('attendances')
                ->delete();

            Log::info('Sync: Keraksiz darslar o‘chirildi', [
                'group_id' => $group->id,
                'count' => $deleted
            ]);
        }

        // 5. Yangi sanalar uchun darslar qo'shish
        $datesToAdd = array_diff($newDates, $existingDates);
        if (!empty($datesToAdd)) {
            // MUHIM: createLessonsForDates metodiga teacher_id ham uzatilishi kerak yoki 
            // u metod ichida primaryTeacher ni o'zi aniqlashi kerak
            $this->createLessonsForDates($group, $datesToAdd);
        }

        // 6. Mavjud darslarni yangilash (Vaqt yoki o'qituvchi o'zgargan bo'lishi mumkin)
        $group->lessons()
            ->where('lesson_date', '>=', now()->toDateString())
            ->whereDoesntHave('attendances')
            ->update([
                'start_time' => $group->start_time,
                'end_time' => $group->end_time,
                'teacher_id' => $teacherId,
            ]);
    }

    /**
     * Calculate required lesson dates based on group schedule
     */
    private function calculateRequiredLessonDates(Group $group): array
    {
        $startDate = now()->startOfDay()->max(Carbon::parse($group->start_date));
        $endDate = Carbon::parse($group->end_date);

        $period = CarbonPeriod::create($startDate, $endDate);

        // Optimize lookup with array flip
        $selectedDays = array_flip(array_map('strtolower', $group->days_of_week));

        $dates = [];
        foreach ($period as $date) {
            if (isset($selectedDays[strtolower($date->format('l'))])) {
                $dates[] = $date->toDateString();
            }
        }

        return $dates;
    }

    /**
     * Create lessons for specific dates
     */
    private function createLessonsForDates(Group $group, array $dates): void
    {
        $lessons = [];
        $now = now();

        foreach ($dates as $dateString) {
            $lessons[] = [
                'group_id' => $group->id,
                'teacher_id' => $group->teacher_id ?? null,
                'lesson_date' => $dateString,
                'start_time' => $group->start_time,
                'end_time' => $group->end_time,
                'status' => 'scheduled',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Batch insert with chunking
        if (!empty($lessons)) {
            foreach (array_chunk($lessons, self::BATCH_INSERT_SIZE) as $chunk) {
                Lesson::insert($chunk);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        try {
            $group->delete();

            return redirect()
                ->route('groups.index')
                ->with('success', 'Guruh muvaffaqiyatli o\'chirildi!');
        } catch (\Exception $e) {
            Log::error('Group deletion failed', [
                'group_id' => $group->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Guruhni o\'chirib bo\'lmadi: ' . $e->getMessage());
        }
    }
}
