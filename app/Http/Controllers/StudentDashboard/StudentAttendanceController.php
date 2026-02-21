<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\GroupStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Filter parametrlari
        $groupId = $request->get('group_id');
        $status = $request->get('status', 'all');
        $month = $request->get('month');
        $year = $request->get('year', Carbon::now()->year);

        // Query
        $query = Attendance::with(['lesson.group.course', 'marker'])
            ->where('student_id', $student->id);

        // Group filter
        if ($groupId) {
            $query->whereHas('lesson', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            });
        }

        // Status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Month filter
        if ($month) {
            $query->whereHas('lesson', function ($q) use ($month, $year) {
                $q->whereMonth('lesson_date', $month)
                  ->whereYear('lesson_date', $year);
            });
        } else {
            // Faqat year filter
            $query->whereHas('lesson', function ($q) use ($year) {
                $q->whereYear('lesson_date', $year);
            });
        }

        $attendances = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistika
        $stats = $this->getAttendanceStats($student, $year, $groupId);

        // Guruhlar ro'yxati
        $groups = GroupStudent::with('group.course')
            ->where('student_id', $student->id)
            ->whereIn('status', ['active', 'completed'])
            ->get()
            ->pluck('group')
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name . ' - ' . ($group->cours->title ?? 'N/A')
                ];
            });

        // Oylar
        $months = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
        ];

        // Yillar
        $currentYear = Carbon::now()->year;
        $years = range($currentYear, $currentYear - 4);

        return view('Panel.Student.attendance', compact(
            'student',
            'attendances',
            'stats',
            'groups',
            'groupId',
            'status',
            'month',
            'year',
            'months',
            'years'
        ));
    }

    private function getAttendanceStats($student, $year, $groupId = null)
    {
        $query = Attendance::where('student_id', $student->id)
            ->whereHas('lesson', function ($q) use ($year) {
                $q->whereYear('lesson_date', $year);
            });

        if ($groupId) {
            $query->whereHas('lesson', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            });
        }

        $allAttendances = $query->get();

        $total = $allAttendances->count();
        $present = $allAttendances->where('status', 'present')->count();
        $absent = $allAttendances->where('status', 'absent')->count();
        $late = $allAttendances->where('status', 'late')->count();
        $excused = $allAttendances->where('status', 'excused')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
            
            // Score statistics
            'avg_homework' => round($allAttendances->whereNotNull('homework_score')->avg('homework_score') ?? 0, 1),
            'avg_activity' => round($allAttendances->whereNotNull('activity_score')->avg('activity_score') ?? 0, 1),
            'total_homework_scores' => $allAttendances->whereNotNull('homework_score')->count(),
            'total_activity_scores' => $allAttendances->whereNotNull('activity_score')->count(),
            
            // Monthly breakdown
            'monthly_breakdown' => $this->getMonthlyBreakdown($student, $year, $groupId),
            
            // Total late minutes
            'total_late_minutes' => $allAttendances->where('status', 'late')->sum('late_minutes'),
        ];
    }

    private function getMonthlyBreakdown($student, $year, $groupId = null)
    {
        $query = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->where('attendances.student_id', $student->id)
            ->whereYear('lessons.lesson_date', $year)
            ->whereNull('attendances.deleted_at');

        if ($groupId) {
            $query->where('lessons.group_id', $groupId);
        }

        $data = $query->select(
                DB::raw('MONTH(lessons.lesson_date) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) as present'),
                DB::raw('SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) as absent'),
                DB::raw('SUM(CASE WHEN attendances.status = "late" THEN 1 ELSE 0 END) as late')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $data->get($i);
            $total = $monthData->total ?? 0;
            $present = $monthData->present ?? 0;
            
            $result[$i] = [
                'total' => $total,
                'present' => $present,
                'absent' => $monthData->absent ?? 0,
                'late' => $monthData->late ?? 0,
                'rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0
            ];
        }

        return $result;
    }

    public function calendar(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Calendar uchun ma'lumotlar
        $attendances = Attendance::with(['lesson.group.course'])
            ->where('student_id', $student->id)
            ->whereHas('lesson', function ($q) use ($month, $year) {
                $q->whereMonth('lesson_date', $month)
                  ->whereYear('lesson_date', $year);
            })
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->lesson->lesson_date)->format('Y-m-d');
            });

        return view('Panel.Student.attendance-calendar', compact(
            'student',
            'attendances',
            'month',
            'year'
        ));
    }
}
