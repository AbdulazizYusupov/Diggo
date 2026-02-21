<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\GroupStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentGroupsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Barcha guruhlar (active, completed, transferred)
        $allGroups = GroupStudent::with(['group.course', 'group.teachers.user'])
            ->where('student_id', $student->id)
            ->orderBy('status')
            ->orderBy('joined_at', 'desc')
            ->get()
            ->map(function ($groupStudent) use ($student) {
                $group = $groupStudent->group;

                // Guruh uchun davomat
                $totalLessons = Attendance::where('student_id', $student->id)
                    ->whereHas('lesson', function ($query) use ($group) {
                        $query->where('group_id', $group->id);
                    })
                    ->count();

                $presentLessons = Attendance::where('student_id', $student->id)
                    ->where('status', 'present')
                    ->whereHas('lesson', function ($query) use ($group) {
                        $query->where('group_id', $group->id);
                    })
                    ->count();

                // days_of_week
                $daysOfWeek = is_array($group->days_of_week)
                    ? implode(', ', $group->days_of_week)
                    : (is_string($group->days_of_week) ? implode(', ', json_decode($group->days_of_week, true) ?? []) : '');

                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'course_name' => $group->cours->title ?? 'N/A',
                    'course_price' => $group->cours->price ?? 0,
                    'start_date' => $group->start_date,
                    'end_date' => $group->end_date,
                    'lesson_days' => $daysOfWeek,
                    'lesson_time' => $group->start_time . ($group->end_time ? ' - ' . $group->end_time : ''),
                    'capacity' => $group->capacity,
                    'is_active' => $group->is_active,
                    'status' => $groupStudent->status,
                    'joined_at' => $groupStudent->joined_at,
                    'left_at' => $groupStudent->left_at,
                    'attendance_rate' => $totalLessons > 0 ? round(($presentLessons / $totalLessons) * 100, 1) : 0,
                    'total_lessons' => $totalLessons,
                    'present_lessons' => $presentLessons,
                    'teachers' => $group->teachers,
                ];
            });

        // Statusga qarab ajratish
        $activeGroups = $allGroups->where('status', 'active');
        $completedGroups = $allGroups->where('status', 'completed');
        $transferredGroups = $allGroups->where('status', 'transferred');
        $droppedGroups = $allGroups->where('status', 'dropped');

        return view('Panel.Student.groups', compact(
            'student',
            'activeGroups',
            'completedGroups',
            'transferredGroups',
            'droppedGroups'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Guruhni tekshirish
        $groupStudent = GroupStudent::with(['group.course', 'group.teachers.user'])
            ->where('student_id', $student->id)
            ->where('group_id', $id)
            ->firstOrFail();

        $group = $groupStudent->group;

        // Guruh statistikasi
        $stats = $this->getGroupStats($student, $group);

        // Darslar tarixi
        $lessons = $this->getLessons($student, $group);

        // Guruh o'quvchilari
        $groupmates = $this->getGroupmates($group, $student->id);

        // days_of_week
        $daysOfWeek = is_array($group->days_of_week)
            ? $group->days_of_week
            : json_decode($group->days_of_week, true) ?? [];

        return view('Panel.Student.group-detail', compact(
            'student',
            'group',
            'groupStudent',
            'stats',
            'lessons',
            'groupmates',
            'daysOfWeek'
        ));
    }

    private function getGroupStats($student, $group)
    {
        $attendances = Attendance::where('student_id', $student->id)
            ->whereHas('lesson', function ($query) use ($group) {
                $query->where('group_id', $group->id);
            })
            ->get();

        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();

        return [
            'total_lessons' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
            'avg_homework' => round($attendances->whereNotNull('homework_score')->avg('homework_score') ?? 0, 1),
            'avg_activity' => round($attendances->whereNotNull('activity_score')->avg('activity_score') ?? 0, 1),
        ];
    }

    private function getLessons($student, $group)
    {
        return DB::table('lessons')
            ->leftJoin('attendances', function ($join) use ($student) {
                $join->on('lessons.id', '=', 'attendances.lesson_id')
                    ->where('attendances.student_id', '=', $student->id)
                    ->whereNull('attendances.deleted_at');
            })
            ->where('lessons.group_id', $group->id)
            ->whereNull('lessons.deleted_at')
            ->select(
                'lessons.*',
                'attendances.status as attendance_status',
                'attendances.homework_score',
                'attendances.activity_score',
                'attendances.notes as attendance_notes',
                'attendances.late_minutes'
            )
            ->orderBy('lessons.lesson_date', 'desc')
            ->paginate(20);
    }

    private function getGroupmates($group, $currentStudentId)
    {
        return DB::table('group_students')
            ->join('students', 'group_students.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('group_students.group_id', $group->id)
            ->where('group_students.status', 'active')
            ->where('students.id', '!=', $currentStudentId)
            ->select(
                'students.id',
                'users.name',
                'users.surname',
                'users.phone'
            )
            ->get();
    }
}
