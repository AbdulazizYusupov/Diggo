<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['transactionable', 'source', 'creator']);

        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        // Filter by transaction type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by transactionable type (Student/Director/Teacher)
        if ($request->filled('transactionable_type')) {
            $typeMap = [
                'student' => Student::class,
                'director' => Director::class,
                'teacher' => Teacher::class,
            ];
            if (isset($typeMap[$request->transactionable_type])) {
                $query->where('transactionable_type', $typeMap[$request->transactionable_type]);
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        $transactions = $query->latest('transaction_date')->paginate(20);

        // Statistics
        $stats = $this->calculateStatistics($request);

        // Get unique transaction types for filter
        $transactionTypes = Transaction::select('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('Transaction.index', compact('transactions', 'stats', 'transactionTypes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with([
            'transactionable.user',
            'source',
            'creator'
        ])->findOrFail($id);

        return view('Transaction.show', compact('transaction'));
    }

    /**
     * Export transactions to Excel/CSV
     */
    public function export(Request $request)
    {
        // TODO: Implement export functionality
        return back()->with('info', 'Export funksiyasi hozircha ishlab chiqilmoqda...');
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Calculate transaction statistics
     */
    private function calculateStatistics(Request $request): array
    {
        $query = Transaction::query();

        // Apply same filters as main query
        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('transactionable_type')) {
            $typeMap = [
                'student' => Student::class,
                'director' => Director::class,
                'teacher' => Teacher::class,
            ];
            if (isset($typeMap[$request->transactionable_type])) {
                $query->where('transactionable_type', $typeMap[$request->transactionable_type]);
            }
        }
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Total transactions count
        $totalTransactions = $query->count();

        // Total amount (paid transactions)
        $totalAmount = (clone $query)->where('status', 'paid')->sum('amount');

        // Pending amount
        $pendingAmount = (clone $query)->where('status', 'pending')->sum('amount');

        // Cancelled amount
        $cancelledAmount = (clone $query)->where('status', 'cancelled')->sum('amount');

        // This month's transactions
        $monthlyAmount = (clone $query)
            ->where('status', 'paid')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->sum('amount');

        // Breakdown by type
        $byType = [
            'payment' => (clone $query)->where('type', 'payment')->where('status', 'paid')->sum('amount'),
            'director_commission' => (clone $query)->where('type', 'director_commission')->where('status', 'paid')->sum('amount'),
            'teacher_commission' => (clone $query)->where('type', 'teacher_commission')->where('status', 'paid')->sum('amount'),
            'expense' => (clone $query)->where('type', 'expense')->where('status', 'paid')->sum('amount'),
            'salary' => (clone $query)->where('type', 'salary')->where('status', 'paid')->sum('amount'),
        ];

        return [
            'total_transactions' => $totalTransactions,
            'total_amount' => $totalAmount,
            'pending_amount' => $pendingAmount,
            'cancelled_amount' => $cancelledAmount,
            'monthly_amount' => $monthlyAmount,
            'by_type' => $byType,
        ];
    }

    /**
     * Get transaction type label in Uzbek
     */
    public static function getTypeLabel(string $type): string
    {
        $labels = [
            'payment' => "To'lov",
            'director_commission' => 'Direktor komissiyasi',
            'teacher_commission' => "O'qituvchi komissiyasi",
            'expense' => 'Xarajat',
            'salary' => 'Oylik',
            'withdrawal' => 'Yechib olish',
            'refund' => 'Qaytarish',
            'adjustment' => 'Tuzatish',
        ];

        return $labels[$type] ?? ucfirst($type);
    }

    /**
     * Get transaction status label in Uzbek
     */
    public static function getStatusLabel(string $status): string
    {
        $labels = [
            'paid' => "To'langan",
            'pending' => 'Kutilmoqda',
            'cancelled' => 'Bekor qilingan',
            'failed' => 'Muvaffaqiyatsiz',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Get transaction status badge classes
     */
    public static function getStatusBadgeClasses(string $status): string
    {
        $classes = [
            'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
            'failed' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
        ];

        return $classes[$status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400';
    }

    /**
     * Get transaction type badge classes
     */
    public static function getTypeBadgeClasses(string $type): string
    {
        $classes = [
            'payment' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'director_commission' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            'teacher_commission' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
            'expense' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
            'salary' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            'withdrawal' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
        ];

        return $classes[$type] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400';
    }
}
