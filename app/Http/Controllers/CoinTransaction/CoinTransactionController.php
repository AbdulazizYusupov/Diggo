<?php

namespace App\Http\Controllers\CoinTransaction;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoinTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = CoinTransaction::with(['student.user', 'awardedBy', 'activity']);

        // Search
        if ($search = $request->get('search')) {
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        // Date range
        if ($from = $request->get('from')) {
            $query->whereDate('transaction_date', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('transaction_date', '<=', $to);
        }

        // Coins filter (positive/negative)
        if ($coins = $request->get('coins')) {
            if ($coins === 'positive') $query->where('coins', '>', 0);
            if ($coins === 'negative') $query->where('coins', '<', 0);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Summary stats
        $stats = $this->getStats($request);

        $types = [
            'participation'      => 'Qatnashish',
            'homework'           => 'Uy vazifasi',
            'exam'               => 'Imtihon',
            'competition'        => 'Musobaqa',
            'active_involvement' => 'Faollik',
            'penalty'            => 'Jarima',
            'bonus'              => 'Bonus',
            'purchase'           => 'Xarid',
            'refund'             => 'Qaytarish',
            'other'              => 'Boshqa',
        ];

        return view('CoinTransaction.index', compact('transactions', 'stats', 'types'));
    }

    public function show($id)
    {
        $transaction = CoinTransaction::with(['student.user', 'awardedBy', 'activity'])->findOrFail($id);

        // O'sha student ning barcha transactionlari (oxirgi 10 ta)
        $history = CoinTransaction::where('student_id', $transaction->student_id)
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('CoinTransaction.show', compact('transaction', 'history'));
    }

    private function getStats(Request $request)
    {
        $query = CoinTransaction::query();

        if ($from = $request->get('from')) {
            $query->whereDate('transaction_date', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('transaction_date', '<=', $to);
        }
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        return [
            'total_given'    => (clone $query)->where('coins', '>', 0)->sum('coins'),
            'total_taken'    => (clone $query)->where('coins', '<', 0)->sum('coins'),
            'total_count'    => (clone $query)->count(),
            'unique_students' => (clone $query)->distinct('student_id')->count('student_id'),
            'by_type'        => (clone $query)
                ->select('type', DB::raw('SUM(coins) as total'), DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->get()
                ->keyBy('type'),
        ];
    }
}
