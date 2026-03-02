<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ─── Core KPIs ─────────────────────────────────────────────────────────

        $totalStudents  = Student::count();
        $activeGroups   = Group::where('is_active', true)->count();
        $totalTeachers  = Teacher::count();

        // New students enrolled this calendar month
        $newStudentsThisMonth = Student::whereMonth('enrollment_date', now()->month)
            ->whereYear('enrollment_date', now()->year)
            ->count();

        // ─── Revenue ────────────────────────────────────────────────────────────

        $monthlyRevenue = Payment::paid()
            ->thisMonth()
            ->sum('net_amount');

        $prevMonthRevenue = Payment::paid()
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->whereYear('paid_at', now()->subMonth()->year)
            ->sum('net_amount');

        $revenueGrowth = $prevMonthRevenue > 0 ? round((($monthlyRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 1) : null;

        // Unpaid / pending payments — financial risk indicator
        $pendingPaymentsCount  = Payment::pending()->count();
        $pendingPaymentsAmount = Payment::pending()->sum('net_amount');

        // ─── Attendance (current month) ─────────────────────────────────────────

        $attendanceStats = $this->getMonthlyAttendanceStats();

        // ─── Today's Schedule ───────────────────────────────────────────────────

        $todaysLessons = Lesson::with([
            'group:id,name',
            'group.course:id,title',
            'teacher.user:id,name,surname,photo',
        ])
            ->today()
            ->orderBy('start_time')
            ->get();

        // ─── Recent Payments (activity feed) ──────────────────────────────────

        $recentPayments = Payment::paid()
            ->with([
                'payable' => fn($q) => $q->with('user:id,name,surname'),
            ])
            ->latest('paid_at')
            ->limit(5)
            ->get();

        // ─── Active Groups Table (paginated) ────────────────────────────────────

        $groups = Group::with([
            'course:id,title',
            'teachers.user:id,name,surname,photo',
            // 'activeStudents',
        ]) 
        -> withCount('activeStudents')
            ->where('is_active', true)
            ->latest()
            ->paginate(8);

        // ─── Operational Alerts ─────────────────────────────────────────────────

        // Groups at or over capacity
        $fullGroups = Group::where('is_active', true)
            ->withCount(['activeStudents'])
            ->get()
            ->filter(fn($g) => $g->capacity > 0 && $g->active_students_count >= $g->capacity)
            ->count();

        // Students with zero or negative balance (owe money)
        $debtStudentsCount = Student::where('balance', '<=', 0)->count();

        // Lessons today with no teacher assigned
        $unteachedLessonsToday = Lesson::today()
            ->whereNull('teacher_id')
            ->count();

        return view('Dashboard.index', compact(
            'totalStudents',
            'activeGroups',
            'totalTeachers',
            'newStudentsThisMonth',
            'monthlyRevenue',
            'prevMonthRevenue',
            'revenueGrowth',
            'pendingPaymentsCount',
            'pendingPaymentsAmount',
            'attendanceStats',
            'todaysLessons',
            'groups',
            'recentPayments',
            'fullGroups',
            'debtStudentsCount',
            'unteachedLessonsToday',
        ));
    }

    /**
     * Present/late/absent/excused counts and overall attendance rate for this month.
     */
    private function getMonthlyAttendanceStats(): array
    {
        $row = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->whereNull('attendances.deleted_at')
            ->whereNull('lessons.deleted_at')
            ->whereMonth('lessons.lesson_date', now()->month)
            ->whereYear('lessons.lesson_date', now()->year)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as excused
            ', ['present', 'late', 'absent', 'excused'])
            ->first();

        $total = (int) ($row->total ?? 0);

        return [
            'total'   => $total,
            'present' => (int) ($row->present ?? 0),
            'late'    => (int) ($row->late ?? 0),
            'absent'  => (int) ($row->absent ?? 0),
            'excused' => (int) ($row->excused ?? 0),
            'rate'    => $total > 0
                ? round((((int) ($row->present ?? 0) + (int) ($row->late ?? 0)) / $total) * 100, 1)
                : 0.0,
        ];
    }
}
