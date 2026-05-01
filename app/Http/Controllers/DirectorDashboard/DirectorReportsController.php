<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\DirectorDashboard\DirectorBaseController;
use App\Models\Director;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;

class DirectorReportsController extends DirectorBaseController
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

    /**
     * Human-readable column headers used in the exported Excel file.
     * Keys match the array keys returned by exportHelper().
     */
    private const EXPORT_COLUMNS = [
        'transaction_date' => 'Sana',
        'status'           => 'Holat',
        'description'      => 'Izoh',
        'amount'           => 'Komissiya miqdori',
        'balance_before'   => 'Avvalgi balans',
        'balance_after'    => 'Keyingi balans',
        'percentage'       => 'Foiz (%)',

        'course_title'     => 'Kurs',
        'group_name'       => 'Guruh',
        'approved_at'      => 'Tasdiqlangan sana',
        'paid_at'          => 'To\'langan sana',
        'created_by_name'  => 'Yaratdi',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Public actions
    // ──────────────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $director = $this->director();

        $validated = $request->validate([
            'month' => ['nullable', 'integer', 'between:1,12'],
            'year'  => ['nullable', 'integer', 'min:2020', 'max:' . now()->year],
        ]);

        $year  = $validated['year']  ?? now()->year;
        $month = $validated['month'] ?? now()->month;

        // ── Base transaction query (director commissions only) ─────────────────
        $baseQuery = fn () => Transaction::query()
            ->where('transactionable_type', Director::class)
            ->where('transactionable_id', $director->id)
            ->where('type', 'director_commission')
            ->where('status', 'paid');

        // ── Reports ────────────────────────────────────────────────────────────
        $monthlyReport      = $this->getMonthlyReport($baseQuery, $director, $year, $month);
        $yearlyReport       = $this->getYearlyReport($baseQuery, $director, $year);
        $studentGrowth      = $this->getStudentGrowth($director, $year);
        $revenueBreakdown   = $this->getRevenueBreakdown($baseQuery, $director, $year, $month);
        $recentTransactions = $this->getRecentTransactions($baseQuery, $year, $month);

        // ── Filter helpers ─────────────────────────────────────────────────────
        $currentYear   = now()->year;
        $years         = range($currentYear, max($currentYear - 5, 2020));
        $months        = self::MONTHS;
        $exportColumns = self::EXPORT_COLUMNS;

        return view('Panel.Director.reports', compact(
            'director',
            'monthlyReport',
            'yearlyReport',
            'studentGrowth',
            'revenueBreakdown',
            'recentTransactions',
            'year',
            'month',
            'years',
            'months',
            'exportColumns',
        ));
    }

    /**
     * Export director commission transactions to Excel.
     * Scope is always locked to the authenticated director — no cross-director leakage.
     */
    public function export(Request $request)
    {
        $director = $this->director();

        $validated = $request->validate([
            'export_year'  => ['nullable', 'integer', 'min:2020', 'max:' . (date('Y') + 1)],
            'export_month' => ['nullable', 'integer', 'between:1,12'],
            'date_from'    => ['nullable', 'date'],
            'date_to'      => ['nullable', 'date', 'after_or_equal:date_from'],
            'columns'      => ['nullable', 'array'],
            'columns.*'    => ['string', 'in:' . implode(',', array_keys(self::EXPORT_COLUMNS))],
        ]);

        // ── Build scoped query ─────────────────────────────────────────────────
        $query = Transaction::with(['source.group.course', 'creator'])
            ->where('transactionable_type', Director::class)
            ->where('transactionable_id', $director->id)
            ->where('type', 'director_commission')
            ->where('status', 'paid');

        if (! empty($validated['export_year'])) {
            $query->whereYear('transaction_date', $validated['export_year']);
        }

        if (! empty($validated['export_month'])) {
            $query->whereMonth('transaction_date', $validated['export_month']);
        }

        // Date-range filters (can override year/month if supplied together)
        if (! empty($validated['date_from'])) {
            $query->whereDate('transaction_date', '>=', $validated['date_from']);
        }
        if (! empty($validated['date_to'])) {
            $query->whereDate('transaction_date', '<=', $validated['date_to']);
        }

        $transactions = $query->latest('transaction_date')->get();

        // Columns to include — default to all if none selected
        $selectedCols = ! empty($validated['columns'])
            ? $validated['columns']
            : array_keys(self::EXPORT_COLUMNS);

        // ── Ensure export directory exists ─────────────────────────────────────
        $exportDir = public_path('export');
        if (! is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        $filePath = $exportDir . DIRECTORY_SEPARATOR . time() . '_director_commissions.xlsx';

        (new FastExcel($transactions))->export($filePath, function ($transaction) use ($selectedCols) {
            $row    = $this->exportHelper($transaction);
            $output = [];

            foreach ($selectedCols as $key) {
                if (array_key_exists($key, $row)) {
                    $output[self::EXPORT_COLUMNS[$key]] = $row[$key];
                }
            }

            return $output;
        });

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Map a single Transaction model to a flat array for the Excel export.
     * All keys MUST match self::EXPORT_COLUMNS keys exactly.
     */
    private function exportHelper(Transaction $transaction): array
    {
        return [
            'transaction_date' => $transaction->transaction_date?->format('Y-m-d H:i:s'),
            'status'           => $transaction->status,
            'description'      => $transaction->description,
            'amount'           => $transaction->amount,
            'balance_before'   => $transaction->balance_before,
            'balance_after'    => $transaction->balance_after,
            'percentage'       => $transaction->percentage,
            'course_title'     => $transaction->source?->group?->course?->title ?? '—',
            'group_name'       => $transaction->source?->group?->name           ?? '—',
            'approved_at'      => $transaction->approved_at?->format('Y-m-d H:i:s'),
            'paid_at'          => $transaction->paid_at?->format('Y-m-d H:i:s'),
            'created_by_name'  => $transaction->creator?->name                 ?? '—',
        ];
    }

    private function getMonthlyReport(callable $baseQuery, Director $director, int $year, int $month): array
    {
        $rows = $baseQuery()
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->selectRaw('SUM(amount) as total, COUNT(*) as cnt')
            ->first();

        $totalRevenue = (float) ($rows->total ?? 0);
        $txCount      = (int)   ($rows->cnt   ?? 0);

        $newStudents = Student::where('director_id', $director->id)
            ->whereYear('created_at',  $year)
            ->whereMonth('created_at', $month)
            ->count();

        return [
            'total_revenue'  => $totalRevenue,
            'commission'     => $totalRevenue,
            'tx_count'       => $txCount,
            'new_students'   => $newStudents,
            'avg_commission' => $txCount > 0 ? $totalRevenue / $txCount : 0,
        ];
    }

    private function getYearlyReport(callable $baseQuery, Director $director, int $year): array
    {
        $rows = $baseQuery()
            ->whereYear('transaction_date', $year)
            ->selectRaw('SUM(amount) as total, COUNT(*) as cnt')
            ->first();

        $totalRevenue = (float) ($rows->total ?? 0);
        $txCount      = (int)   ($rows->cnt   ?? 0);

        return [
            'total_revenue' => $totalRevenue,
            'commission'    => $totalRevenue,
            'tx_count'      => $txCount,
            'avg_per_month' => round($totalRevenue / 12, 2),
        ];
    }

    private function getStudentGrowth(Director $director, int $year): array
    {
        $rows = Student::where('director_id', $director->id)
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as mo, COUNT(*) as cnt')
            ->groupBy('mo')
            ->get()
            ->keyBy('mo');

        $totalAtYearStart = Student::where('director_id', $director->id)
            ->where('created_at', '<', Carbon::create($year, 1, 1)->startOfYear())
            ->count();

        $result     = [];
        $cumulative = $totalAtYearStart;

        for ($m = 1; $m <= 12; $m++) {
            $newCount    = (int) ($rows->get($m)->cnt ?? 0);
            $cumulative += $newCount;

            $result[$m] = [
                'new'   => $newCount,
                'total' => $cumulative,
            ];
        }

        return $result;
    }

    private function getRevenueBreakdown(callable $baseQuery, Director $director, int $year, int $month): array
    {
        // Fully-qualified columns required to avoid ambiguity after joining payments.
        $byCourse = Transaction::query()
            ->where('transactions.transactionable_type', Director::class)
            ->where('transactions.transactionable_id', $director->id)
            ->where('transactions.type', 'director_commission')
            ->where('transactions.status', 'paid')
            ->whereNull('transactions.deleted_at')
            ->whereYear('transactions.transaction_date', $year)
            ->whereMonth('transactions.transaction_date', $month)
            ->join('payments', function ($join) {
                $join->on('transactions.source_id', '=', 'payments.id')
                     ->where('transactions.source_type', '=', Payment::class);
            })
            ->join('groups', 'payments.group_id', '=', 'groups.id')
            ->join('cours',  'groups.cours_id',   '=', 'cours.id')
            ->selectRaw('cours.title, SUM(transactions.amount) as total, COUNT(*) as cnt')
            ->groupBy('cours.id', 'cours.title')
            ->orderByDesc('total')
            ->get();

        $monthlyRows = $baseQuery()
            ->whereYear('transaction_date', $year)
            ->selectRaw('MONTH(transaction_date) as mo, SUM(amount) as total, COUNT(*) as cnt')
            ->groupBy('mo')
            ->get()
            ->keyBy('mo');

        $monthlyBreakdown = collect(self::MONTHS)->map(fn ($name, $num) => [
            'name'  => $name,
            'total' => (float) ($monthlyRows->get($num)->total ?? 0),
            'count' => (int)   ($monthlyRows->get($num)->cnt   ?? 0),
        ]);

        return [
            'by_course'         => $byCourse,
            'monthly_breakdown' => $monthlyBreakdown,
        ];
    }

    private function getRecentTransactions(callable $baseQuery, int $year, int $month)
    {
        return $baseQuery()
            ->with(['source.group.course', 'creator'])
            ->whereYear('transaction_date',  $year)
            ->whereMonth('transaction_date', $month)
            ->latest('transaction_date')
            ->limit(10)
            ->get();
    }
}