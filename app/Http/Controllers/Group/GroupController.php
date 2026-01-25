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

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Group::with(['course', 'teachers', 'activeStudents']);

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
            switch ($request->capacity_range) {
                case 'small':
                    $query->where('capacity', '<', 15);
                    break;
                case 'medium':
                    $query->whereBetween('capacity', [15, 25]);
                    break;
                case 'large':
                    $query->where('capacity', '>', 25);
                    break;
            }
        }

        // Days filter
        if ($request->filled('days')) {
            $query->whereJsonContains('days_of_week', $request->days);
        }

        $groups = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $totalGroups = Group::count();
        $activeGroups = Group::where('is_active', true)->count();
        $totalStudents = DB::table('group_students')
            ->where('status', 'active')
            ->distinct('student_id')
            ->count();
        $avgCapacity = Group::avg('capacity');

        // Courses for filter
        $courses = Cours::where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title', 'code']);

        return view('Group.index', compact(
            'groups',
            'totalGroups',
            'activeGroups',
            'totalStudents',
            'avgCapacity',
            'courses'
        ));
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
    public function store1(Request $request)
    {
        $validated = $request->validate([
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

        $validated['is_active'] = $request->has('is_active');

        $group = Group::create($validated);

        $lessons = [];
        $startDate = Carbon::parse($group->start_date);
        $endDate = Carbon::parse($group->end_date);

        // Tanlangan kunlarni kichik harfga o'tkazib olish (massiv)
        $selectedDays = $group->days_of_week;

        // Boshlanish va tugash sanasi oralig'idagi barcha kunlarni aylanamiz
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            // Agar kun tanlangan kunlar (monday, tuesday...) ichida bo'lsa
            if (in_array(strtolower($date->format('l')), $selectedDays)) {
                $lessons[] = [
                    'group_id' => $group->id,
                    'teacher_id' => $group->teacher_id, // Agar guruhda ustoz bo'lsa
                    'lesson_date' => $date->format('Y-m-d'),
                    'start_time' => $group->start_time,
                    'end_time' => $group->end_time,
                    'status' => 'scheduled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // 3. Bazaga ommaviy saqlash (Batch Insert)
        if (!empty($lessons)) {
            Lesson::insert($lessons);
        }
        return redirect()
            ->route('groups.index')
            ->with('success', "Guruh va " . count($lessons) . " ta dars muvaffaqiyatli yaratildi!");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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

        $validated['is_active'] = $request->has('is_active');
        $validated['days_of_week'] = array_map('strtolower', $validated['days_of_week']);


        $course = Cours::findOrFail($validated['cours_id']);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end   = Carbon::parse($validated['end_date'])->startOfDay();

        // KALENDAR OY FARQI (INT)
        $monthsDiff = (int) $start->diffInMonths($end);
        $maxMonths  = (int) $course->duration_months;

        if ($monthsDiff > $maxMonths) {
            return back()
                ->withInput()
                ->withErrors([
                    'end_date' => "Bu kurs maksimal {$maxMonths} oy davom etadi. Siz {$monthsDiff} oy tanladingiz."
                ]);
        }

        try {
            $lessonsCount = 0;

            DB::transaction(function () use ($validated, &$lessonsCount) {
                $group = Group::create($validated);

                $lessons = [];
                $period = CarbonPeriod::create($group->start_date, $group->end_date);

                foreach ($period as $date) {
                    if (in_array(strtolower($date->format('l')), $group->days_of_week)) {
                        $lessons[] = [
                            'group_id' => $group->id,
                            'teacher_id' => $group->teacher_id ?? null,
                            'lesson_date' => $date->toDateString(),
                            'start_time' => $group->start_time,
                            'end_time' => $group->end_time,
                            'status' => 'scheduled',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($lessons)) {
                    Lesson::insert($lessons);
                    $lessonsCount = count($lessons);
                }
            });

            return redirect()
                ->route('groups.index')
                ->with('success', "Guruh va {$lessonsCount} ta dars muvaffaqiyatli yaratildi!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group = Group::findOrFail($id);
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
    public function edit(string $id)
    {
        $group = Group::findOrFail($id);
        $courses = Cours::where('is_active', true)->get();
        return view('Group.edit', compact('group', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
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

        $validated['is_active'] = $request->has('is_active');
        $validated['days_of_week'] = array_map('strtolower', $validated['days_of_week']);

        $course = Cours::findOrFail($validated['cours_id']);

        // Sanalarni Carbon obyektiga o'tkazish
        $newStart = Carbon::parse($validated['start_date'])->startOfDay();
        $newEnd = Carbon::parse($validated['end_date'])->startOfDay();

        $monthsDiff = (int) $newStart->diffInMonths($newEnd);

        if ($monthsDiff > $course->duration_months) {
            return back()->withErrors(['end_date' => "Kurs muddati maksimal {$course->duration_months} oy bo'lishi kerak."]);
        }

        // Jadval o'zgarganini aniqroq tekshirish
        // (format('Y-m-d') ishlatish string solishtirishda xatolikni oldini oladi)
        $scheduleChanged = (
            $group->start_date->format('Y-m-d') !== $newStart->format('Y-m-d') ||
            $group->end_date->format('Y-m-d') !== $newEnd->format('Y-m-d') ||
            $group->start_time !== $validated['start_time'] ||
            $group->end_time !== $validated['end_time'] ||
            serialize($group->days_of_week) !== serialize($validated['days_of_week'])
        );

        try {
            DB::transaction(function () use ($group, $validated) {
                $group->update($validated);

                // 1. Yangi jadval bo'yicha barcha kerakli sanalarni hisoblaymiz
                $startDate = now()->startOfDay()->max(Carbon::parse($group->start_date));
                $endDate = Carbon::parse($group->end_date);

                $period = CarbonPeriod::create($startDate, $endDate);

                // Performance uchun selectedDays array'ini flip qilib olamiz
                $selectedDays = array_flip(array_map('strtolower', $group->days_of_week));

                $newDates = [];
                foreach ($period as $date) {
                    if (isset($selectedDays[strtolower($date->format('l'))])) {
                        $newDates[] = $date->toDateString();
                    }
                }

                // 2. Bazadagi mavjud kelajakdagi darslarni olamiz
                $existingLessons = $group->lessons()
                    ->where('lesson_date', '>=', now()->toDateString())
                    ->select('id', 'lesson_date') // faqat kerakli ustunlarni olamiz
                    ->get();

                $existingDates = $existingLessons->pluck('lesson_date')->toArray();

                // 3. Ortiqcha darslarni o'chirish (davomatlari yo'qlar)
                $datesToDelete = array_diff($existingDates, $newDates);
                if (!empty($datesToDelete)) {
                    $group->lessons()
                        ->whereIn('lesson_date', $datesToDelete)
                        ->whereDoesntHave('attendances')
                        ->delete();
                }

                // 4. Yangi darslarni qo'shish
                $datesToAdd = array_diff($newDates, $existingDates);
                $newLessonsData = [];
                $now = now();
                foreach ($datesToAdd as $dateString) {
                    $newLessonsData[] = [
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

                // Agar juda katta bo'lsa, chunk qilib insert qilish mumkin
                if (!empty($newLessonsData)) {
                    foreach (array_chunk($newLessonsData, 500) as $chunk) {
                        Lesson::insert($chunk);
                    }
                }

                // 5. Mavjud darslarni update qilish (davomatlari yo'qlar)
                $group->lessons()
                    ->where('lesson_date', '>=', now()->toDateString())
                    ->whereDoesntHave('attendances')
                    ->update([
                        'start_time' => $group->start_time,
                        'end_time' => $group->end_time,
                        'teacher_id' => $group->teacher_id ?? null,
                    ]);
            });

            return redirect()->route('groups.index')
                ->with('success', 'Guruh jadvali muvaffaqiyatli sinxronizatsiya qilindi!');
        } catch (\Exception $e) {
            return back()->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return redirect()
            ->route('groups.index')
            ->with('success', 'Guruh muvaffaqiyatli o\'chirildi!');
    }
}
