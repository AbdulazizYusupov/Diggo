<?php

namespace App\Http\Controllers\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\LessonChange;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();

        // Teacher profilini olish (agar foydalanuvchi teacher bo'lsa)
        $teacherProfile = $user->teacher;

        // Base query
        $query = Lesson::with(['group.course', 'teacher', 'attendances']);

        // Role-based filtering
        if ($user->hasRole('teacher')) {
            $query->where('teacher_id', optional($teacherProfile)->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by group
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        // Filter by teacher (faqat admin uchun)
        if ($request->filled('teacher_id') && $user->hasRole('admin')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('lesson_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('lesson_date', '<=', $request->date_to);
        }

        // Search by topic
        if ($request->filled('search')) {
            $query->where('topic', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'lesson_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $lessons = $query->paginate(15);

        // Statistics - role ga qarab
        if ($user->hasRole('teacher')) {
            $t_id = optional($teacherProfile)->id;

            $totalLessons = Lesson::where('teacher_id', $t_id)->count();
            $scheduledLessons = Lesson::where('teacher_id', $t_id)->where('status', 'scheduled')->count();
            $completedLessons = Lesson::where('teacher_id', $t_id)->where('status', 'completed')->count();
            $cancelledLessons = Lesson::where('teacher_id', $t_id)->where('status', 'cancelled')->count();
            $todayLessons = Lesson::where('teacher_id', $t_id)->whereDate('lesson_date', today())->count();
        } else {
            $totalLessons = Lesson::count();
            $scheduledLessons = Lesson::where('status', 'scheduled')->count();
            $completedLessons = Lesson::where('status', 'completed')->count();
            $cancelledLessons = Lesson::where('status', 'cancelled')->count();
            $todayLessons = Lesson::whereDate('lesson_date', today())->count();
        }

        // For filters - role ga qarab
        if ($user->hasRole('teacher')) {
            $t_id = optional($teacherProfile)->id;

            $groups = Group::where('is_active', true)
                ->whereHas('lessons', function ($q) use ($t_id) {
                    $q->where('teacher_id', $t_id);
                })
                ->with('course')
                ->orderBy('name')
                ->get();

            $teachers = collect();
        } else {
            $groups = Group::where('is_active', true)
                ->with('course')
                ->orderBy('name')
                ->get();

            $teachers = User::whereHas('teacher')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('Lesson.index', compact(
            'lessons',
            'totalLessons',
            'scheduledLessons',
            'completedLessons',
            'cancelledLessons',
            'todayLessons',
            'groups',
            'teachers'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();

        $teachers = Teacher::all();

        return view('Lesson.create', compact('groups', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'lesson_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'topic' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
        ]);

        // Check if lesson already exists for this group on this date and time
        $exists = Lesson::where('group_id', $validated['group_id'])
            ->where('lesson_date', $validated['lesson_date'])
            ->where('start_time', $validated['start_time'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['lesson_date' => 'Bu guruhda shu sana va vaqtda dars allaqachon mavjud.'])->withInput();
        }

        Lesson::create($validated);

        return redirect()->route('lessons.index')
            ->with('success', 'Dars muvaffaqiyatli yaratildi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lesson = Lesson::with([
            'group.course',
            'teacher',
            'attendances.student.user',
            'changes.changer', // O'zgarishlar tarixi
            'attendances' => function ($query) {
                $query->orderBy('status');
            }
        ])->findOrFail($id);

        // Attendance statistics
        $totalStudents = $lesson->group->activeStudents()->count();
        $presentCount = $lesson->attendances()->where('status', 'present')->count();
        $absentCount = $lesson->attendances()->where('status', 'absent')->count();
        $lateCount = $lesson->attendances()->where('status', 'late')->count();
        $excusedCount = $lesson->attendances()->where('status', 'excused')->count();

        return view('Lesson.show', compact(
            'lesson',
            'totalStudents',
            'presentCount',
            'absentCount',
            'lateCount',
            'excusedCount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lesson = Lesson::with('group')->findOrFail($id);

        $groups = Group::where('is_active', true)
            ->with('course')
            ->orderBy('name')
            ->get();

        $teachers = User::whereHas('teacher')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('Lesson.edit', compact('lesson', 'groups', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     * O'ZGARISHLAR TARIXI BILAN!
     */
    public function update(Request $request, string $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'teacher_id' => 'nullable|exists:users,id',
            'lesson_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'topic' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'cancellation_reason' => 'nullable|required_if:status,cancelled|string',
            'change_reason' => 'nullable|string|max:500', // O'zgarish sababi
        ]);

        // Check for duplicates (excluding current lesson)
        $exists = Lesson::where('group_id', $validated['group_id'])
            ->where('lesson_date', $validated['lesson_date'])
            ->where('start_time', $validated['start_time'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['lesson_date' => 'Bu guruhda shu sana va vaqtda dars allaqachon mavjud.'])->withInput();
        }

        // MUHIM: O'ZGARISHLARNI SAQLASH
        DB::transaction(function () use ($lesson, $validated, $request) {
            $changes = [];

            // Sana o'zgarganmi?
            if ($lesson->lesson_date->format('Y-m-d') !== $validated['lesson_date']) {
                $changes['old_date'] = $lesson->lesson_date;
                $changes['new_date'] = $validated['lesson_date'];
            }

            // Boshlanish vaqti o'zgarganmi?
            if ($lesson->start_time !== $validated['start_time']) {
                $changes['old_start_time'] = $lesson->start_time;
                $changes['new_start_time'] = $validated['start_time'];
            }

            // Agar o'zgarishlar bo'lsa, LessonChange ga yozamiz
            if (!empty($changes)) {
                LessonChange::create([
                    'lesson_id' => $lesson->id,
                    'old_date' => $changes['old_date'] ?? null,
                    'new_date' => $changes['new_date'] ?? null,
                    'old_start_time' => $changes['old_start_time'] ?? null,
                    'new_start_time' => $changes['new_start_time'] ?? null,
                    'change_reason' => $validated['change_reason'] ?? 'Sabab ko\'rsatilmagan',
                    'changed_by' => Auth::id(),
                ]);

                // Agar sana yoki vaqt o'zgargan bo'lsa, status ni "rescheduled" ga o'zgartiramiz
                if (!empty($changes['old_date']) || !empty($changes['old_start_time'])) {
                    $validated['status'] = 'rescheduled';
                }
            }

            // Darsni yangilash
            $lesson->update($validated);
        });

        return redirect()->route('lessons.index')
            ->with('success', 'Dars muvaffaqiyatli yangilandi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lesson = Lesson::findOrFail($id);

        // Check if lesson has attendances
        if ($lesson->attendances()->exists()) {
            return back()->with('error', 'Bu darsda davomat mavjud. Avval davomatlarni o\'chiring.');
        }

        $lesson->delete();

        return redirect()->route('lessons.index')
            ->with('success', 'Dars muvaffaqiyatli o\'chirildi!');
    }

    /**
     * Mark lesson as completed
     */
    public function complete(string $id)
    {
        $lesson = Lesson::findOrFail($id);

        $lesson->update(['status' => 'completed']);

        return back()->with('success', 'Dars yakunlandi deb belgilandi!');
    }

    /**
     * Cancel lesson
     */
    public function cancel(Request $request, string $id)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $lesson = Lesson::findOrFail($id);

        $lesson->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason']
        ]);

        return back()->with('success', 'Dars bekor qilindi!');
    }
    /**
     * Reschedule lesson (Darsni boshqa vaqtga ko'chirish)
     */
    public function reschedule(Request $request, string $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'new_date' => 'required|date|after_or_equal:today',
            'new_start_time' => 'required|date_format:H:i',
            'new_end_time' => 'required|date_format:H:i|after:new_start_time',
            'change_reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($lesson, $validated) {
            // O'zgarishni saqlash
            LessonChange::create([
                'lesson_id' => $lesson->id,
                'old_date' => $lesson->lesson_date,
                'new_date' => $validated['new_date'],
                'old_start_time' => $lesson->start_time,
                'new_start_time' => $validated['new_start_time'],
                'change_reason' => $validated['change_reason'],
                'changed_by' => Auth::id(),
            ]);

            // Darsni yangilash
            $lesson->update([
                'lesson_date' => $validated['new_date'],
                'start_time' => $validated['new_start_time'],
                'end_time' => $validated['new_end_time'],
                'status' => 'rescheduled',
            ]);
        });

        return back()->with('success', 'Dars muvaffaqiyatli ko\'chirildi!');
    }
}
