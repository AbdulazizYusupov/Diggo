<?php

namespace App\Http\Controllers\TeacherDashboard;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherGroupsController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $groups = $teacher->groups()
            ->with('course')
            ->withCount(['activeStudents'])
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->get()
            ->map(function ($group) use ($teacher) {
                // Guruh uchun jami darslar (bu o'qituvchi)
                $totalLessons = Lesson::where('group_id', $group->id)
                    ->where('teacher_id', $teacher->id)
                    ->whereNull('deleted_at')
                    ->count();

                $completedLessons = Lesson::where('group_id', $group->id)
                    ->where('teacher_id', $teacher->id)
                    ->where('status', 'completed')
                    ->whereNull('deleted_at')
                    ->count();

                $daysOfWeek = is_array($group->days_of_week)
                    ? implode(', ', $group->days_of_week)
                    : implode(', ', json_decode($group->days_of_week ?? '[]', true));

                $group->total_lessons     = $totalLessons;
                $group->completed_lessons = $completedLessons;
                $group->days_label        = $daysOfWeek;
                $group->is_primary        = (bool) $group->pivot->is_primary;

                return $group;
            });

        return view('Panel.Teacher.groups', compact('teacher', 'groups'));
    }
    public function show($id)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        // Teacher bu guruhga biriktirilganini tekshirish
        $group = $teacher->groups()
            ->with('course', 'activeStudents.user')
            ->findOrFail($id);

        // Darslar statistikasi
        $lessons = Lesson::where('group_id', $group->id)
            ->where('teacher_id', $teacher->id)
            ->whereNull('deleted_at')
            ->orderBy('lesson_date', 'desc')
            ->paginate(15);

        $totalLessons     = Lesson::where('group_id', $group->id)->where('teacher_id', $teacher->id)->whereNull('deleted_at')->count();
        $completedLessons = Lesson::where('group_id', $group->id)->where('teacher_id', $teacher->id)->where('status', 'completed')->whereNull('deleted_at')->count();
        $scheduledLessons = Lesson::where('group_id', $group->id)->where('teacher_id', $teacher->id)->where('status', 'scheduled')->whereNull('deleted_at')->count();

        // Talabalar o'rtacha davomat
        $students = $group->activeStudents;

        $daysOfWeek = is_array($group->days_of_week)
            ? $group->days_of_week
            : json_decode($group->days_of_week ?? '[]', true);

        return view('Panel.Teacher.group-detail', compact(
            'teacher',
            'group',
            'lessons',
            'totalLessons',
            'completedLessons',
            'scheduledLessons',
            'students',
            'daysOfWeek'
        ));
    }
}
