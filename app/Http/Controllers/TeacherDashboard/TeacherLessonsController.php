<?php

namespace App\Http\Controllers\TeacherDashboard;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherLessonsController extends Controller
{
    public function index(Request $request, $groupId)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        // Guruh teacher ga tegishli ekanligini tekshirish
        $group = $teacher->groups()->with('course')->findOrFail($groupId);

        $status = $request->get('status', 'all');
        $month  = $request->get('month');
        $year   = $request->get('year', Carbon::now()->year);

        $query = Lesson::where('group_id', $group->id)
            ->where('teacher_id', $teacher->id)
            ->whereNull('deleted_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($month) {
            $query->whereMonth('lesson_date', $month);
        }
        $query->whereYear('lesson_date', $year);

        $lessons = $query->orderBy('lesson_date', 'desc')->paginate(20);

        $months = [
            1 => 'Yanvar',
            2 => 'Fevral',
            3 => 'Mart',
            4 => 'Aprel',
            5 => 'May',
            6 => 'Iyun',
            7 => 'Iyul',
            8 => 'Avgust',
            9 => 'Sentabr',
            10 => 'Oktabr',
            11 => 'Noyabr',
            12 => 'Dekabr',
        ];
        $years = range(Carbon::now()->year, Carbon::now()->year - 3);

        return view('Panel.Teacher.lessons', compact(
            'teacher',
            'group',
            'lessons',
            'status',
            'month',
            'year',
            'months',
            'years'
        ));
    }

    /**
     * Yangi dars yaratish formasi
     */
    public function create($groupId)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $group = $teacher->groups()->with('course')->findOrFail($groupId);

        return view('Panel.Teacher.lessons-form', compact('teacher', 'group'));
    }

    /**
     * Yangi darsni saqlash
     */
    public function store(Request $request, $groupId)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $group = $teacher->groups()->findOrFail($groupId);

        $validated = $request->validate([
            'topic'       => 'required|string|max:255',
            'lesson_date' => 'required|date',
            // 'start_time'  => 'required|date_format:H:i',
            // 'end_time'    => 'nullable|date_format:H:i|after:start_time',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:2000',
        ], [
            'topic.required'       => 'Dars mavzusi majburiy',
            'lesson_date.required' => 'Dars sanasi majburiy',
            'start_time.required'  => 'Boshlanish vaqti majburiy',
            'end_time.after'       => 'Tugash vaqti boshlanishdan keyin bo\'lishi kerak',
        ]);

        Lesson::create([
            'group_id'    => $group->id,
            'teacher_id'  => $teacher->id,
            'topic'       => $validated['topic'],
            'lesson_date' => $validated['lesson_date'],
            'start_time'  => $validated['start_time'],
            'end_time'    => $validated['end_time'] ?? null,
            'description' => $validated['description'] ?? null,
            'status'      => 'scheduled',
        ]);

        return redirect()
            ->route('teacher.groups.lessons.index', $group->id)
            ->with('success', 'Dars muvaffaqiyatli qo\'shildi');
    }

    /**
     * Darsni tahrirlash formasi
     */
    public function edit($groupId, $lessonId)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $group  = $teacher->groups()->with('course')->findOrFail($groupId);
        $lesson = Lesson::where('group_id', $group->id)
            ->where('teacher_id', $teacher->id)
            ->whereNull('deleted_at')
            ->findOrFail($lessonId);

        return view('Panel.Teacher.lessons-form', compact('teacher', 'group', 'lesson'));
    }

    /**
     * Darsni yangilash
     */
    public function update(Request $request, $groupId, $lessonId)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $group  = $teacher->groups()->findOrFail($groupId);
        $lesson = Lesson::where('group_id', $group->id)
            ->where('teacher_id', $teacher->id)
            ->whereNull('deleted_at')
            ->findOrFail($lessonId);

        $validated = $request->validate([
            'topic'       => 'required|string|max:255',
            'lesson_date' => 'required|date',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:2000',
            'status'      => 'required|in:scheduled,completed,cancelled',
        ], [
            'topic.required'       => 'Dars mavzusi majburiy',
            'lesson_date.required' => 'Dars sanasi majburiy',
            'start_time.required'  => 'Boshlanish vaqti majburiy',
        ]);

        $lesson->update($validated);

        return redirect()
            ->route('teacher.groups.lessons.index', $group->id)
            ->with('success', 'Dars muvaffaqiyatli yangilandi');
    }

    /**
     * Darsni o'chirish (soft delete)
     */
    public function destroy($groupId, $lessonId)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $group  = $teacher->groups()->findOrFail($groupId);
        $lesson = Lesson::where('group_id', $group->id)
            ->where('teacher_id', $teacher->id)
            ->where('status', 'scheduled') // faqat rejalashtirilganni o'chirish
            ->whereNull('deleted_at')
            ->findOrFail($lessonId);

        $lesson->delete();

        return redirect()
            ->route('teacher.groups.lessons.index', $group->id)
            ->with('success', 'Dars o\'chirildi');
    }
}
