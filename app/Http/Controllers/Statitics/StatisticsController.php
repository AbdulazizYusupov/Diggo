<?php

namespace App\Http\Controllers\Statitics;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    private const MONTHS_BACK = 6;

    public function index()
    {
        return view('Statistics.index', [

            // ── Header KPIs ──────────────────────────────────────────────────
            'kpis' => $this->kpis(),

            // ── Chart datasets (JSON-ready, passed to JS) ─────────────────────
            'revenueChart'      => $this->revenueByMonth(),
            'studentGrowthChart'=> $this->studentGrowthByMonth(),
            'occupancyChart'    => $this->groupOccupancy(),
            'attendanceChart'   => $this->attendanceBreakdown(),
            'paymentStatusChart'=> $this->paymentStatusDistribution(),
            'paymentTypeChart'  => $this->paymentTypeDistribution(),
            'teacherActivityChart' => $this->teacherActivity(),
            'coursePopularityChart' => $this->coursePopularity(),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // HEADER KPIs  — single aggregate numbers shown as stat cards
    // ═══════════════════════════════════════════════════════════════════════════

    private function kpis(): array
    {
        $periodStart = now()->subMonths(self::MONTHS_BACK)->startOfMonth();

        $totalRevenue = Payment::paid()
            ->where('paid_at', '>=', $periodStart)
            ->sum('net_amount');

        $totalLessons = Lesson::where('lesson_date', '>=', $periodStart)->count();

        $completedLessons = Lesson::where('lesson_date', '>=', $periodStart)
            ->where('status', 'completed')
            ->count();

        $attendanceRow = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->whereNull('attendances.deleted_at')
            ->whereNull('lessons.deleted_at')
            ->where('lessons.lesson_date', '>=', $periodStart)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN attendances.status IN (?,?) THEN 1 ELSE 0 END) as attended
            ', ['present', 'late'])
            ->first();

        return [
            'period_label'     => now()->subMonths(self::MONTHS_BACK)->format('M Y') . ' – ' . now()->format('M Y'),
            'total_revenue'    => (float) $totalRevenue,
            'total_students'   => Student::count(),
            'active_groups'    => Group::where('is_active', true)->count(),
            'total_teachers'   => Teacher::count(),
            'completion_rate'  => $totalLessons > 0
                ? round(($completedLessons / $totalLessons) * 100, 1)
                : 0.0,
            'attendance_rate'  => ($attendanceRow->total ?? 0) > 0
                ? round(($attendanceRow->attended / $attendanceRow->total) * 100, 1)
                : 0.0,
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 1. REVENUE BY MONTH  (bar / line)
    //    Source: payments.net_amount  WHERE status = 'paid'
    //    Grouped by: YEAR(paid_at), MONTH(paid_at)
    // ═══════════════════════════════════════════════════════════════════════════

    private function revenueByMonth(): array
    {
        $rows = DB::table('payments')
            ->whereNull('deleted_at')
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subMonths(self::MONTHS_BACK)->startOfMonth())
            ->selectRaw('YEAR(paid_at) as yr, MONTH(paid_at) as mo, SUM(net_amount) as total')
            ->groupByRaw('YEAR(paid_at), MONTH(paid_at)')
            ->orderByRaw('yr, mo')
            ->get()
            ->keyBy(fn($r) => "{$r->yr}-{$r->mo}");

        [$labels, $data] = $this->fillMonthSeries(
            self::MONTHS_BACK,
            fn($yr, $mo) => (float) ($rows["{$yr}-{$mo}"]->total ?? 0)
        );

        return ['labels' => $labels, 'data' => $data];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 2. STUDENT GROWTH BY MONTH  (line)
    //    Source: students.enrollment_date
    //    Cumulative new enrollments per month
    // ═══════════════════════════════════════════════════════════════════════════

    private function studentGrowthByMonth(): array
    {
        $rows = DB::table('students')
            ->whereNull('deleted_at')
            ->whereNotNull('enrollment_date')
            ->where('enrollment_date', '>=', now()->subMonths(self::MONTHS_BACK)->startOfMonth())
            ->selectRaw('YEAR(enrollment_date) as yr, MONTH(enrollment_date) as mo, COUNT(*) as cnt')
            ->groupByRaw('YEAR(enrollment_date), MONTH(enrollment_date)')
            ->orderByRaw('yr, mo')
            ->get()
            ->keyBy(fn($r) => "{$r->yr}-{$r->mo}");

        [$labels, $data] = $this->fillMonthSeries(
            self::MONTHS_BACK,
            fn($yr, $mo) => (int) ($rows["{$yr}-{$mo}"]->cnt ?? 0)
        );

        return ['labels' => $labels, 'data' => $data];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 3. GROUP OCCUPANCY  (bar / doughnut)
    //    Active groups: actual student count vs capacity
    // ═══════════════════════════════════════════════════════════════════════════

    private function groupOccupancy(): array
    {
        $groups = DB::table('groups')
            ->leftJoin('group_students', function ($join) {
                $join->on('groups.id', '=', 'group_students.group_id')
                     ->where('group_students.status', '=', 'active');
            })
            ->leftJoin('cours', 'groups.cours_id', '=', 'cours.id')
            ->whereNull('groups.deleted_at')
            ->where('groups.is_active', true)
            ->selectRaw('
                groups.name,
                groups.capacity,
                cours.title as course_title,
                COUNT(group_students.id) as enrolled
            ')
            ->groupBy('groups.id', 'groups.name', 'groups.capacity', 'cours.title')
            ->orderByRaw('enrolled DESC')
            ->limit(12)
            ->get();

        return [
            'labels'   => $groups->map(fn($g) => $g->name)->values()->toArray(),
            'enrolled' => $groups->map(fn($g) => (int) $g->enrolled)->values()->toArray(),
            'capacity' => $groups->map(fn($g) => (int) $g->capacity)->values()->toArray(),
            'courses'  => $groups->map(fn($g) => $g->course_title ?? '—')->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 4. ATTENDANCE BREAKDOWN  (doughnut / bar — monthly trend)
    //    present / late / absent / excused per month
    // ═══════════════════════════════════════════════════════════════════════════

    private function attendanceBreakdown(): array
    {
        // Donut totals for the whole period
        $totals = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->whereNull('attendances.deleted_at')
            ->whereNull('lessons.deleted_at')
            ->where('lessons.lesson_date', '>=', now()->subMonths(self::MONTHS_BACK)->startOfMonth())
            ->selectRaw('
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as excused
            ', ['present', 'late', 'absent', 'excused'])
            ->first();

        // Monthly trend: present+late vs absent per month (line chart)
        $monthly = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->whereNull('attendances.deleted_at')
            ->whereNull('lessons.deleted_at')
            ->where('lessons.lesson_date', '>=', now()->subMonths(self::MONTHS_BACK)->startOfMonth())
            ->selectRaw('
                YEAR(lessons.lesson_date) as yr,
                MONTH(lessons.lesson_date) as mo,
                SUM(CASE WHEN attendances.status IN (?,?) THEN 1 ELSE 0 END) as attended,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as absent
            ', ['present', 'late', 'absent'])
            ->groupByRaw('YEAR(lessons.lesson_date), MONTH(lessons.lesson_date)')
            ->orderByRaw('yr, mo')
            ->get()
            ->keyBy(fn($r) => "{$r->yr}-{$r->mo}");

        [$labels, $attended, $absent] = $this->fillMonthSeries3(
            self::MONTHS_BACK,
            fn($yr, $mo) => (int) ($monthly["{$yr}-{$mo}"]->attended ?? 0),
            fn($yr, $mo) => (int) ($monthly["{$yr}-{$mo}"]->absent ?? 0),
        );

        return [
            // For doughnut
            'donut' => [
                'labels' => ['Keldi', 'Kech keldi', 'Kelmadi', 'Sababli'],
                'data'   => [
                    (int) ($totals->present ?? 0),
                    (int) ($totals->late ?? 0),
                    (int) ($totals->absent ?? 0),
                    (int) ($totals->excused ?? 0),
                ],
            ],
            // For trend line
            'trend' => [
                'labels'   => $labels,
                'attended' => $attended,
                'absent'   => $absent,
            ],
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 5. PAYMENT STATUS DISTRIBUTION  (doughnut / pie)
    //    Source: payments.status  (pending, paid, failed, cancelled)
    // ═══════════════════════════════════════════════════════════════════════════

    private function paymentStatusDistribution(): array
    {
        $rows = DB::table('payments')
            ->whereNull('deleted_at')
            ->selectRaw('status, COUNT(*) as cnt, SUM(net_amount) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $statuses = ['paid', 'pending', 'failed', 'cancelled'];
        $labels   = ['To\'landi', 'Kutilmoqda', 'Muvaffaqiyatsiz', 'Bekor qilindi'];

        return [
            'labels' => $labels,
            'counts' => collect($statuses)->map(fn($s) => (int) ($rows[$s]->cnt ?? 0))->values()->toArray(),
            'amounts'=> collect($statuses)->map(fn($s) => (float) ($rows[$s]->total ?? 0))->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 6. PAYMENT TYPE DISTRIBUTION  (bar / doughnut)
    //    Source: payments.payment_type  (cash, card, transfer, other)
    // ═══════════════════════════════════════════════════════════════════════════

    private function paymentTypeDistribution(): array
    {
        $rows = DB::table('payments')
            ->whereNull('deleted_at')
            ->where('status', 'paid')
            ->selectRaw('payment_type, COUNT(*) as cnt, SUM(net_amount) as total')
            ->groupBy('payment_type')
            ->get()
            ->keyBy('payment_type');

        $types  = ['cash', 'card', 'transfer', 'other'];
        $labels = ['Naqd', 'Karta', "Bank o'tkazmasi", 'Boshqa'];

        return [
            'labels' => $labels,
            'counts' => collect($types)->map(fn($t) => (int)   ($rows[$t]->cnt   ?? 0))->values()->toArray(),
            'amounts'=> collect($types)->map(fn($t) => (float) ($rows[$t]->total ?? 0))->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 7. TEACHER ACTIVITY  (horizontal bar)
    //    Number of completed lessons per teacher (last N months)
    //    Joins: lessons → teacher_id → teachers → user_id → users
    // ═══════════════════════════════════════════════════════════════════════════

    private function teacherActivity(): array
    {
        $rows = DB::table('lessons')
            ->join('teachers', 'lessons.teacher_id', '=', 'teachers.id')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->whereNull('lessons.deleted_at')
            ->whereNull('teachers.deleted_at')
            ->whereNull('users.deleted_at')
            ->where('lessons.status', 'completed')
            ->where('lessons.lesson_date', '>=', now()->subMonths(self::MONTHS_BACK)->startOfMonth())
            ->selectRaw('
                CONCAT(users.surname, " ", users.name) as teacher_name,
                COUNT(lessons.id) as lesson_count
            ')
            ->groupBy('teachers.id', 'users.surname', 'users.name')
            ->orderByRaw('lesson_count DESC')
            ->limit(10)
            ->get();

        return [
            'labels' => $rows->pluck('teacher_name')->values()->toArray(),
            'data'   => $rows->pluck('lesson_count')->map(fn($v) => (int) $v)->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 8. COURSE POPULARITY  (bar / doughnut)
    //    Number of active students per course (via groups → group_students)
    // ═══════════════════════════════════════════════════════════════════════════

    private function coursePopularity(): array
    {
        $rows = DB::table('cours')
            ->join('groups', 'cours.id', '=', 'groups.cours_id')
            ->join('group_students', function ($join) {
                $join->on('groups.id', '=', 'group_students.group_id')
                     ->where('group_students.status', '=', 'active');
            })
            ->whereNull('cours.deleted_at')
            ->whereNull('groups.deleted_at')
            ->where('cours.is_active', true)
            ->selectRaw('cours.title, COUNT(DISTINCT group_students.student_id) as student_count')
            ->groupBy('cours.id', 'cours.title')
            ->orderByRaw('student_count DESC')
            ->limit(10)
            ->get();

        return [
            'labels' => $rows->pluck('title')->values()->toArray(),
            'data'   => $rows->pluck('student_count')->map(fn($v) => (int) $v)->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Build a complete month-label array + one data series for the last N months.
     * Fills missing months with zero.
     *
     * @param  int      $months   how many months back (including current)
     * @param  callable $resolver fn(int $year, int $month): mixed
     * @return array{0: string[], 1: array}
     */
    private function fillMonthSeries(int $months, callable $resolver): array
    {
        $labels = [];
        $data   = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $yr   = (int) $date->format('Y');
            $mo   = (int) $date->format('n');

            $labels[] = $date->format('M Y');
            $data[]   = $resolver($yr, $mo);
        }

        return [$labels, $data];
    }

    /**
     * Same as fillMonthSeries but returns TWO parallel data series.
     *
     * @return array{0: string[], 1: array, 2: array}
     */
    private function fillMonthSeries3(int $months, callable $resolver1, callable $resolver2): array
    {
        $labels = [];
        $data1  = [];
        $data2  = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $yr   = (int) $date->format('Y');
            $mo   = (int) $date->format('n');

            $labels[] = $date->format('M Y');
            $data1[]  = $resolver1($yr, $mo);
            $data2[]  = $resolver2($yr, $mo);
        }

        return [$labels, $data1, $data2];
    }
}
