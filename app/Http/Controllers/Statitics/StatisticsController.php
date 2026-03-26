<?php

namespace App\Http\Controllers\Statitics;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    private const MONTHS_BACK  = 6;
    private const CACHE_TTL    = 15 * 60;   // 15 daqiqa (soniyada)
    private const CACHE_KEY    = 'statistics.dashboard.v1';
    private const GROUP_LIMIT  = 12;
    private const TEACHER_LIMIT= 10;
    private const COURSE_LIMIT = 10;

    // ═══════════════════════════════════════════════════════════════════════════
    // ENTRY POINT
    // ═══════════════════════════════════════════════════════════════════════════

    public function index()
    {
        // dd(123);
        $data = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->buildDashboardData();
        });

        return view('Statistics.index', $data);
    }

    /**
     * Cache'ni qo'lda tozalash uchun (masalan: to'lov qabul qilinganda trigger qilish)
     * Route::post('/statistics/refresh', ...) kabi qo'shib ishlatsa bo'ladi.
     */
    public function refresh()
    {
        // dd(123);
        Cache::forget(self::CACHE_KEY);
        return redirect()->route('statistics.index')
            ->with('success', 'Statistika yangilandi.');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // DATA BUILDER — barcha metodlarni bitta joyda yig'adi
    // ═══════════════════════════════════════════════════════════════════════════

    private function buildDashboardData(): array
    {
        return [
            'kpis'                  => $this->kpis(),
            'revenueChart'          => $this->revenueByMonth(),
            'studentGrowthChart'    => $this->studentGrowthByMonth(),
            'occupancyChart'        => $this->groupOccupancy(),
            'attendanceChart'       => $this->attendanceBreakdown(),
            'paymentStatusChart'    => $this->paymentStatusDistribution(),
            'paymentTypeChart'      => $this->paymentTypeDistribution(),
            'teacherActivityChart'  => $this->teacherActivity(),
            'coursePopularityChart' => $this->coursePopularity(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // HEADER KPIs
    // Optimizatsiya: 7 ta query → 4 ta query
    // ═══════════════════════════════════════════════════════════════════════════

    private function kpis(): array
    {
        $periodStart = now()->subMonths(self::MONTHS_BACK)->startOfMonth();

        // 1-query: Revenue
        $totalRevenue = (float) DB::table('payments')
            ->whereNull('deleted_at')
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', $periodStart)
            ->sum('net_amount');

        // 2-query: Lessons (total + completed bir queryda)
        $lessonRow = DB::table('lessons')
            ->whereNull('deleted_at')
            ->where('lesson_date', '>=', $periodStart)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed
            ')
            ->first();

        $totalLessons     = (int) ($lessonRow->total     ?? 0);
        $completedLessons = (int) ($lessonRow->completed ?? 0);

        // 3-query: Attendance
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

        // 4-query: Student / Group / Teacher counts — bitta subquery
        $counts = DB::selectOne('
            SELECT
                (SELECT COUNT(*) FROM students  WHERE deleted_at IS NULL) as students,
                (SELECT COUNT(*) FROM `groups`  WHERE deleted_at IS NULL AND is_active = 1) as grps,
                (SELECT COUNT(*) FROM teachers  WHERE deleted_at IS NULL) as teachers
        ');

        $attendedTotal = (int) ($attendanceRow->total    ?? 0);
        $attended      = (int) ($attendanceRow->attended ?? 0);

        return [
            'period_label'    => now()->subMonths(self::MONTHS_BACK)->format('M Y')
                                 . ' – ' . now()->format('M Y'),
            'total_revenue'   => $totalRevenue,
            'total_students'  => (int) ($counts->students ?? 0),
            'active_groups'   => (int) ($counts->grps     ?? 0),
            'total_teachers'  => (int) ($counts->teachers ?? 0),
            'completion_rate' => $totalLessons > 0
                ? round(($completedLessons / $totalLessons) * 100, 1)
                : 0.0,
            'attendance_rate' => $attendedTotal > 0
                ? round(($attended / $attendedTotal) * 100, 1)
                : 0.0,
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 1. REVENUE BY MONTH
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
    // 2. STUDENT GROWTH BY MONTH
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
    // 3. GROUP OCCUPANCY
    // Limit(12) MAJBURIY — chart buzilmasligi uchun
    // Label'lar strip_tags bilan tozalanadi
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
            ->limit(self::GROUP_LIMIT)   // ← qat'iy limit, chart buzilmaydi
            ->get();

        return [
            'labels'   => $groups->map(fn($g) => $this->safeLabel($g->name))->values()->toArray(),
            'enrolled' => $groups->map(fn($g) => max(0, (int) $g->enrolled))->values()->toArray(),
            'capacity' => $groups->map(fn($g) => max(0, (int) $g->capacity))->values()->toArray(),
            'courses'  => $groups->map(fn($g) => $this->safeLabel($g->course_title ?? '—'))->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 4. ATTENDANCE BREAKDOWN
    // ═══════════════════════════════════════════════════════════════════════════

    private function attendanceBreakdown(): array
    {
        $periodStart = now()->subMonths(self::MONTHS_BACK)->startOfMonth();

        // Donut totals
        $totals = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->whereNull('attendances.deleted_at')
            ->whereNull('lessons.deleted_at')
            ->where('lessons.lesson_date', '>=', $periodStart)
            ->selectRaw('
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN attendances.status = ? THEN 1 ELSE 0 END) as excused
            ', ['present', 'late', 'absent', 'excused'])
            ->first();

        // Monthly trend
        $monthly = DB::table('attendances')
            ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
            ->whereNull('attendances.deleted_at')
            ->whereNull('lessons.deleted_at')
            ->where('lessons.lesson_date', '>=', $periodStart)
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
            fn($yr, $mo) => max(0, (int) ($monthly["{$yr}-{$mo}"]->attended ?? 0)),
            fn($yr, $mo) => max(0, (int) ($monthly["{$yr}-{$mo}"]->absent   ?? 0)),
        );

        return [
            'donut' => [
                'labels' => ['Keldi', 'Kech keldi', 'Kelmadi', 'Sababli'],
                'data'   => [
                    max(0, (int) ($totals->present ?? 0)),
                    max(0, (int) ($totals->late    ?? 0)),
                    max(0, (int) ($totals->absent  ?? 0)),
                    max(0, (int) ($totals->excused ?? 0)),
                ],
            ],
            'trend' => [
                'labels'   => $labels,
                'attended' => $attended,
                'absent'   => $absent,
            ],
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 5. PAYMENT STATUS DISTRIBUTION
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
        $labels   = ["To'landi", 'Kutilmoqda', 'Muvaffaqiyatsiz', 'Bekor qilindi'];

        return [
            'labels' => $labels,
            'counts' => collect($statuses)
                ->map(fn($s) => max(0, (int) ($rows[$s]->cnt ?? 0)))
                ->values()->toArray(),
            'amounts' => collect($statuses)
                ->map(fn($s) => max(0.0, (float) ($rows[$s]->total ?? 0)))
                ->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 6. PAYMENT TYPE DISTRIBUTION
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
            'counts' => collect($types)
                ->map(fn($t) => max(0, (int) ($rows[$t]->cnt ?? 0)))
                ->values()->toArray(),
            'amounts' => collect($types)
                ->map(fn($t) => max(0.0, (float) ($rows[$t]->total ?? 0)))
                ->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 7. TEACHER ACTIVITY
    // Limit(10) MAJBURIY — horizontal bar chart uchun
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
            ->limit(self::TEACHER_LIMIT)  // ← qat'iy limit
            ->get();

        return [
            'labels' => $rows->map(fn($r) => $this->safeLabel($r->teacher_name))->values()->toArray(),
            'data'   => $rows->map(fn($r) => max(0, (int) $r->lesson_count))->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // 8. COURSE POPULARITY
    // Limit(10) MAJBURIY — doughnut/bar chart uchun
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
            ->limit(self::COURSE_LIMIT)  // ← qat'iy limit
            ->get();

        return [
            'labels' => $rows->map(fn($r) => $this->safeLabel($r->title))->values()->toArray(),
            'data'   => $rows->map(fn($r) => max(0, (int) $r->student_count))->values()->toArray(),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Chart label'larini xavfsiz qilish:
     * - HTML teglarni olib tashlaydi (XSS)
     * - 40 belgidan uzun bo'lsa qisqartiradi (chart label'lari sig'ib tursin)
     */
    private function safeLabel(string $value, int $maxLength = 40): string
    {
        $clean = strip_tags(trim($value));
        return mb_strlen($clean) > $maxLength
            ? mb_substr($clean, 0, $maxLength - 1) . '…'
            : $clean;
    }

    /**
     * So'nggi N oy uchun label + bitta data seriyasini to'ldiradi.
     * Bo'sh oylar 0 bilan to'ldiriladi.
     *
     * @return array{0: string[], 1: array}
     */
    private function fillMonthSeries(int $months, callable $resolver): array
    {
        $labels = [];
        $data   = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $yr       = (int) $date->format('Y');
            $mo       = (int) $date->format('n');
            $labels[] = $date->format('M Y');
            $data[]   = $resolver($yr, $mo);
        }

        return [$labels, $data];
    }

    /**
     * So'nggi N oy uchun label + IKKI parallel data seriyasini to'ldiradi.
     *
     * @return array{0: string[], 1: array, 2: array}
     */
    private function fillMonthSeries3(int $months, callable $resolver1, callable $resolver2): array
    {
        $labels = [];
        $data1  = [];
        $data2  = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $yr       = (int) $date->format('Y');
            $mo       = (int) $date->format('n');
            $labels[] = $date->format('M Y');
            $data1[]  = $resolver1($yr, $mo);
            $data2[]  = $resolver2($yr, $mo);
        }

        return [$labels, $data1, $data2];
    }
}