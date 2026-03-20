<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DirectorCommissionsController extends Controller
{
    private const MONTHS = [
        1  => 'Yanvar',
        2  => 'Fevral',
        3  => 'Mart',
        4  => 'Aprel',
        5  => 'May',
        6  => 'Iyun',
        7  => 'Iyul',
        8  => 'Avgust',
        9  => 'Sentabr',
        10 => 'Oktabr',
        11 => 'Noyabr',
        12 => 'Dekabr',
    ];

    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $director = Director::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'month' => ['nullable', 'integer', 'between:1,12'],
            'year'  => ['nullable', 'integer', 'min:2020', 'max:' . now()->year],
        ]);

        $month = $validated['month'] ?? null;
        $year  = $validated['year']  ?? now()->year;

        // 1. Base query - Eager loading faqat paginated natija uchun kerak
        $baseQuery = Transaction::query()
            ->where('transactionable_type', Director::class)
            ->where('transactionable_id', $director->id)
            ->where('type', 'director_commission')
            ->where('status', 'paid')
            ->whereHasMorph('source', [Payment::class], function ($q) use ($director) {
                $q->where('payable_type', Student::class)
                    ->whereHas('payable', fn($s) => $s->where('director_id', $director->id));
            });

        // 2. Paginated transactions (Faqat shu yerda 'with' ishlatamiz)
        $transactions = (clone $baseQuery)
            ->with(['source.payable.user', 'source.group.course', 'creator'])
            ->when($month, fn($q) => $q->whereMonth('transaction_date', $month))
            ->whereYear('transaction_date', $year)
            ->latest('transaction_date')
            ->paginate(20)
            ->withQueryString();

        // 3. Stats - Optimization (Bitta query orqali hamma oylik statistika)
        $yearlyStats = (clone $baseQuery)
            ->whereYear('transaction_date', $year)
            ->selectRaw('MONTH(transaction_date) as m, SUM(amount) as total, COUNT(*) as cnt')
            ->groupBy('m')
            ->get()
            ->keyBy('m');

        $yearTotal = $yearlyStats->sum('total');
        $yearCount = $yearlyStats->sum('cnt');

        // Joriy oy komissiyasi (Query yubormasdan, yearlyStats ichidan olamiz)
        $thisMonthTotal = now()->year == $year
            ? ($yearlyStats->get(now()->month)->total ?? 0)
            : (clone $baseQuery)->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $monthlyBreakdown = collect(self::MONTHS)->map(fn($name, $num) => [
            'name'  => $name,
            'total' => (float) ($yearlyStats->get($num)->total ?? 0),
            'count' => (int)   ($yearlyStats->get($num)->cnt ?? 0),
        ]);

        $stats = [
            'current_balance'       => (float) $director->balance,
            'commission_percentage' => (float) $director->commission_percentage,
            'this_month_commission' => (float) $thisMonthTotal,
            'year_total'            => (float) $yearTotal,
            'year_count'            => (int)   $yearCount,
            'monthly_breakdown'     => $monthlyBreakdown,
        ];

        $years = range(now()->year, max(2020, now()->year - 4));

        return view('Panel.Director.commissions', compact(
            'director',
            'transactions',
            'stats',
            'month',
            'year',
            'years'
        ))->with('months', self::MONTHS);
    }

    private function getCommissionStats($director, $year)
    {
        // Yillik barcha to'lovlar
        $baseQuery = Payment::where('payable_type', Student::class)
            ->whereHasMorph('payable', [Student::class], function ($q) use ($director) {
                $q->where('school_id', $director->school_id)
                    ->where('director_id', $director->id);
            })
            ->where('status', 'paid')
            ->whereYear('paid_at', $year);

        $allPayments = $baseQuery->get();

        $totalCommission = $allPayments->sum(function ($payment) use ($director) {
            return ($payment->amount * $director->commission_percentage) / 100;
        });

        // Shu oylik
        $thisMonth = Carbon::now();
        $thisMonthPayments = Payment::where('payable_type', Student::class)
            ->whereHasMorph('payable', [Student::class], function ($q) use ($director) {
                $q->where('school_id', $director->school_id)
                    ->where('director_id', $director->id);
            })
            ->where('status', 'paid')
            ->whereMonth('paid_at', $thisMonth->month)
            ->whereYear('paid_at', $thisMonth->year)
            ->get();

        $thisMonthCommission = $thisMonthPayments->sum(function ($payment) use ($director) {
            return ($payment->amount * $director->commission_percentage) / 100;
        });

        return [
            'current_balance'        => $director->balance,
            'commission_percentage'  => $director->commission_percentage,
            'total_commission'       => $totalCommission,
            'this_month_commission'  => $thisMonthCommission,
            'total_payments'         => $allPayments->count(),
            'this_month_payments'    => $thisMonthPayments->count(),
            'monthly_breakdown'      => $this->getMonthlyBreakdown($director, $year),
        ];
    }

    private function getMonthlyBreakdown($director, $year)
    {
        $result = [];

        for ($month = 1; $month <= 12; $month++) {
            $payments = Payment::where('payable_type', Student::class)
                ->whereHasMorph('payable', [Student::class], function ($q) use ($director) {
                    $q->where('school_id', $director->school_id)
                        ->where('director_id', $director->id);
                })
                ->where('status', 'paid')
                ->whereMonth('paid_at', $month)
                ->whereYear('paid_at', $year)
                ->get();

            $commission = $payments->sum(function ($payment) use ($director) {
                return ($payment->amount * $director->commission_percentage) / 100;
            });

            $result[$month] = [
                'total' => $commission,
                'count' => $payments->count(),
            ];
        }

        return $result;
    }

    public function history(): View
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $director = Director::where('user_id', $user->id)->firstOrFail();

        $currentYear = now()->year;
        $years       = range($currentYear, max($currentYear - 5, 2020));

        // ── Base closure — director_commission transactions for this director ──
        $base = fn() => Transaction::query()
            ->where('transactionable_type', Director::class)
            ->where('transactionable_id', $director->id)
            ->where('type', 'director_commission')
            ->where('status', 'paid');

        // ── All-time summary (single queries, no loops) ───────────────────────
        $allTime = $base();
        $allTimeTotal = (clone $allTime)->sum('amount');
        $allTimeCount = (clone $allTime)->count();

        // ── Per-year stats — ONE query, grouped in PHP ────────────────────────
        // Fetch year + month + amount for all relevant years at once
        $rawRows = $base()
            ->whereYear('transaction_date', '>=', min($years))
            ->selectRaw('
            YEAR(transaction_date)  AS yr,
            MONTH(transaction_date) AS mo,
            SUM(amount)             AS total,
            COUNT(*)                AS cnt
        ')
            ->groupBy('yr', 'mo')
            ->orderBy('yr')
            ->orderBy('mo')
            ->get();

        // Group into [year => [month => [total, count]]]
        $grouped = $rawRows->groupBy('yr');

        $monthNames = self::MONTHS;

        $yearlyStats = [];

        foreach ($years as $year) {
            $yearRows    = $grouped->get($year, collect());
            $yearTotal   = (float) $yearRows->sum('total');
            $yearCount   = (int)   $yearRows->sum('cnt');
            $activeMonths = $yearRows->where('total', '>', 0)->count();

            // Build full 12-month breakdown (zero-filled)
            $monthly = collect($monthNames)->map(function ($name, $num) use ($yearRows) {
                $row = $yearRows->firstWhere('mo', $num);
                return [
                    'name'  => $name,
                    'total' => (float) ($row?->total ?? 0),
                    'count' => (int)   ($row?->cnt   ?? 0),
                ];
            });

            $yearlyStats[$year] = [
                'total_commission' => $yearTotal,
                'total_count'      => $yearCount,
                'avg_per_month'    => $activeMonths > 0
                    ? round($yearTotal / $activeMonths)
                    : 0,
                'avg_per_tx'       => $yearCount > 0
                    ? round($yearTotal / $yearCount)
                    : 0,
                'best_month'       => $monthly->sortByDesc('total')->first(),
                'monthly'          => $monthly,
            ];
        }

        // ── Year-over-year growth pairs ───────────────────────────────────────
        // Sort ascending so we compare prev→next correctly, then reverse for display.
        $sortedYears = collect($years)->sort()->values();  // [2020, 2021, ..., 2026]

        $growthPairs = collect();
        for ($i = 1; $i < $sortedYears->count(); $i++) {
            $prevYear = $sortedYears[$i - 1];
            $currYear = $sortedYears[$i];
            $current  = $yearlyStats[$currYear]['total_commission'] ?? 0;
            $previous = $yearlyStats[$prevYear]['total_commission'] ?? 0;

            // Only include pairs where at least one year has data
            if ($current <= 0 && $previous <= 0) {
                continue;
            }

            $growthPct = $previous > 0
                ? round((($current - $previous) / $previous) * 100, 1)
                : ($current > 0 ? 100.0 : 0.0);

            $growthPairs->push([
                'from'        => $prevYear,
                'to'          => $currYear,
                'from_total'  => $previous,
                'to_total'    => $current,
                'growth_pct'  => $growthPct,
                'is_positive' => $growthPct >= 0,
            ]);
        }

        $growthPairs = $growthPairs->reverse()->values(); // most recent pair first

        $summary = [
            'all_time_total'  => (float) $allTimeTotal,
            'all_time_count'  => (int)   $allTimeCount,
            'avg_per_year'    => count($years) > 0
                ? round($allTimeTotal / count($years))
                : 0,
        ];

        return view('Panel.Director.commission-history', compact(
            'director',
            'yearlyStats',
            'years',
            'growthPairs',
            'summary',
            'monthNames',
        ));
    }
}
