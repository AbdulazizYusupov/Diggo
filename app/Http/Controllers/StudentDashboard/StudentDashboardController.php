<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CoinTransaction;
use App\Models\GroupStudent;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Asosiy statistika
        $stats = $this->getStats($student);

        // Guruhlar ma'lumotlari
        $activeGroups = $this->getActiveGroups($student);

        // Coin tranzaksiyalari
        $recentCoinTransactions = $this->getRecentCoinTransactions($student);

        // Oylik coin tarixi
        $monthlyCoinHistory = $this->getMonthlyCoinHistory($student);

        // To'lovlar tarixi
        $recentPayments = $this->getRecentPayments($student);

        // Davomat statistikasi
        $attendanceStats = $this->getAttendanceStats($student);

        // Yaqin darslar
        $upcomingLessons = $this->getUpcomingLessons($student);

        // O'qituvchilar
        $teachers = $this->getTeachers($student);

        return view('Panel.Student.index', compact(
            'student',
            'stats',
            'activeGroups',
            'recentCoinTransactions',
            'monthlyCoinHistory',
            'recentPayments',
            'attendanceStats',
            'upcomingLessons',
            'teachers'
        ));
    }

    private function getStats(Student $student)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        return [
            // Coin ma'lumotlari
            'current_coins' => $student->coins,
            'total_coins_earned' => CoinTransaction::where('student_id', $student->id)
                ->where('coins', '>', 0)
                ->sum('coins'),
            'total_coins_spent' => abs(CoinTransaction::where('student_id', $student->id)
                ->where('coins', '<', 0)
                ->sum('coins')),
            'this_month_coins' => CoinTransaction::where('student_id', $student->id)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->where('coins', '>', 0)
                ->sum('coins'),

            // Moliyaviy ma'lumotlar
            'current_balance' => $student->balance,
            'total_paid' => Payment::where('payable_type', 'App\Models\Student')
                ->where('payable_id', $student->id)
                ->where('status', 'paid')
                ->sum('net_amount'),
            'this_month_payment' => Payment::where('payable_type', 'App\Models\Student')
                ->where('payable_id', $student->id)
                ->where('status', 'paid')
                ->whereBetween('payment_month', [$startOfMonth, $endOfMonth])
                ->sum('net_amount'),
            'pending_payment' => Payment::where('payable_type', 'App\Models\Student')
                ->where('payable_id', $student->id)
                ->where('status', 'pending')
                ->sum('net_amount'),

            // Guruhlar va darslar
            'active_groups_count' => GroupStudent::where('student_id', $student->id)
                ->where('status', 'active')
                ->count(),
            'total_lessons' => Attendance::where('student_id', $student->id)
                ->count(),
            'this_month_lessons' => Attendance::where('student_id', $student->id)
                ->whereHas('lesson', function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('lesson_date', [$startOfMonth, $endOfMonth]);
                })
                ->count(),

            // Davomat
            'attendance_rate' => $this->calculateAttendanceRate($student),
            'present_count' => Attendance::where('student_id', $student->id)
                ->where('status', 'present')
                ->count(),
            'absent_count' => Attendance::where('student_id', $student->id)
                ->where('status', 'absent')
                ->count(),
            'late_count' => Attendance::where('student_id', $student->id)
                ->where('status', 'late')
                ->count(),

            // O'rtacha balllar
            'avg_homework_score' => round(Attendance::where('student_id', $student->id)
                ->whereNotNull('homework_score')
                ->avg('homework_score'), 1),
            'avg_activity_score' => round(Attendance::where('student_id', $student->id)
                ->whereNotNull('activity_score')
                ->avg('activity_score'), 1),
        ];
    }

    private function calculateAttendanceRate(Student $student)
    {
        $total = Attendance::where('student_id', $student->id)->count();
        if ($total == 0) return 0;

        $present = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->count();

        return round(($present / $total) * 100, 1);
    }

    private function getActiveGroups(Student $student)
    {
        return GroupStudent::with(['group.course', 'group.lessons' => function ($query) {
            $query->latest()->take(1);
        }])
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->get()
            ->map(function ($groupStudent) use ($student) {
                $group = $groupStudent->group;

                // Guruh uchun davomat statistikasi
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

                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'course_name' => $group->course->name ?? 'N/A',
                    'level' => $group->level,
                    'start_date' => $group->start_date,
                    'end_date' => $group->end_date,
                    'lesson_days' => $group->lesson_days,
                    'lesson_time' => $group->lesson_time,
                    'joined_at' => $groupStudent->joined_at,
                    'attendance_rate' => $totalLessons > 0 ? round(($presentLessons / $totalLessons) * 100, 1) : 0,
                    'total_lessons' => $totalLessons,
                    'last_lesson' => $group->lessons->first(),
                ];
            });
    }

    private function getRecentCoinTransactions(Student $student)
    {
        return CoinTransaction::with(['activity', 'user'])
            ->where('student_id', $student->id)
            ->latest('transaction_date')
            ->take(10)
            ->get();
    }

    private function getMonthlyCoinHistory(Student $student)
    {
        return CoinTransaction::where('student_id', $student->id)
            ->where('coins', '>', 0) // Faqat olingan coinlar
            ->select(
                DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as month'),
                DB::raw('SUM(coins) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->map(function ($item) {
                $item->month_name = Carbon::createFromFormat('Y-m', $item->month)->locale('uz_Latn')->translatedFormat('F Y');
                return $item;
            });
    }

    private function getRecentPayments(Student $student)
    {
        return Payment::with(['group.course', 'user'])
            ->where('payable_type', 'App\Models\Student')
            ->where('payable_id', $student->id)
            ->where('status', 'paid')
            ->latest('paid_at')
            ->take(10)
            ->get();
    }

    private function getAttendanceStats(Student $student)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $thisMonthAttendances = Attendance::where('student_id', $student->id)
            ->whereHas('lesson', function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('lesson_date', [$startOfMonth, $endOfMonth]);
            })
            ->get();

        $total = $thisMonthAttendances->count();
        $present = $thisMonthAttendances->where('status', 'present')->count();
        $absent = $thisMonthAttendances->where('status', 'absent')->count();
        $late = $thisMonthAttendances->where('status', 'late')->count();
        $excused = $thisMonthAttendances->where('status', 'excused')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
        ];
    }

    private function getUpcomingLessons(Student $student)
    {
        $now = Carbon::now();
        $endDate = $now->copy()->addDays(7);

        // Student guruhlaridagi yaqin darslarni olish
        $groupIds = GroupStudent::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('group_id');

        return DB::table('lessons')
            ->join('groups', 'lessons.group_id', '=', 'groups.id')
            ->join('cours', 'groups.cours_id', '=', 'cours.id')
            ->leftJoin('attendances', function ($join) use ($student) {
                $join->on('lessons.id', '=', 'attendances.lesson_id')
                    ->where('attendances.student_id', '=', $student->id)
                    ->whereNull('attendances.deleted_at');
            })
            ->whereIn('lessons.group_id', $groupIds)
            ->whereBetween('lessons.lesson_date', [$now, $endDate])
            ->whereNull('lessons.deleted_at')
            ->select(
                'lessons.*',
                'groups.name as group_name',
                'groups.start_time',
                'cours.title as course_name',
                'attendances.status as attendance_status'
            )
            ->orderBy('lessons.lesson_date')
            ->orderBy('groups.start_time')
            ->take(5)
            ->get();
    }

    private function getTeachers(Student $student)
    {
        // Student guruhlaridagi o'qituvchilarni olish
        return DB::table('group_teachers')
            ->join('teachers', 'group_teachers.teacher_id', '=', 'teachers.id')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            // ->join('cours', 'teachers.cours_id', '=', 'cours.id')
            ->whereIn('group_teachers.group_id', function ($query) use ($student) {
                $query->select('group_id')
                    ->from('group_students')
                    ->where('student_id', $student->id)
                    ->where('status', 'active');
            })
            // ->whereNull('group_teachers.left_at')
            ->select(
                'teachers.id',
                'users.name',
                'users.surname',
                'users.phone',
                'users.email',
                // 'cours.title as course_name',
                // 'teachers.specialization'
            )
            ->distinct()
            ->get();
    }
}
