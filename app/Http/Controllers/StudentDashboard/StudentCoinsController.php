<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentCoinsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Filter parametrlari
        $type = $request->get('type', 'all');
        $month = $request->get('month');
        $year = $request->get('year', Carbon::now()->year);

        // Query
        $query = CoinTransaction::with(['activity', 'student'])
            ->where('student_id', $student->id);

        // Type filter
        if ($type === 'earned') {
            $query->where('coins', '>', 0);
        } elseif ($type === 'spent') {
            $query->where('coins', '<', 0);
        }

        // Month filter
        if ($month) {
            $query->whereMonth('transaction_date', $month);
        }

        // Year filter
        $query->whereYear('transaction_date', $year);

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistika
        $stats = $this->getCoinStats($student, $year);

        // Coin types
        $coinTypes = [
            'participation' => 'Ishtirok',
            'homework' => 'Uy ishi',
            'exam' => 'Imtihon',
            'competition' => 'Musobaqa',
            'active_involvement' => 'Faol ishtirok',
            'penalty' => 'Jarima',
            'bonus' => 'Bonus',
            'purchase' => 'Xarid',
            'other' => 'Boshqa'
        ];

        // Oylar
        $months = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
        ];

        // Yillar
        $currentYear = Carbon::now()->year;
        $years = range($currentYear, $currentYear - 4);

        return view('Panel.Student.coins', compact(
            'student',
            'transactions',
            'stats',
            'type',
            'month',
            'year',
            'coinTypes',
            'months',
            'years'
        ));
    }

    private function getCoinStats($student, $year)
    {
        $allTransactions = CoinTransaction::where('student_id', $student->id)
            ->whereYear('transaction_date', $year)
            ->get();

        $earned = $allTransactions->where('coins', '>', 0);
        $spent = $allTransactions->where('coins', '<', 0);

        return [
            'current_balance' => $student->coins,
            'total_earned' => $earned->sum('coins'),
            'total_spent' => abs($spent->sum('coins')),
            'total_transactions' => $allTransactions->count(),
            
            // Oylik breakdown
            'monthly_breakdown' => $this->getMonthlyBreakdown($student, $year),
            
            // Type breakdown
            'type_breakdown' => $earned->groupBy('type')->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('coins')
                ];
            })->sortByDesc('total'),

            // So'nggi 30 kun
            'last_30_days' => CoinTransaction::where('student_id', $student->id)
                ->where('transaction_date', '>=', Carbon::now()->subDays(30))
                ->where('coins', '>', 0)
                ->sum('coins'),
        ];
    }

    private function getMonthlyBreakdown($student, $year)
    {
        $earned = CoinTransaction::where('student_id', $student->id)
            ->where('coins', '>', 0)
            ->whereYear('transaction_date', $year)
            ->select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('SUM(coins) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $spent = CoinTransaction::where('student_id', $student->id)
            ->where('coins', '<', 0)
            ->whereYear('transaction_date', $year)
            ->select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('SUM(coins) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = [
                'earned' => $earned->get($i)?->total ?? 0,
                'spent' => abs($spent->get($i)?->total ?? 0),
                'count' => ($earned->get($i)?->count ?? 0) + ($spent->get($i)?->count ?? 0)
            ];
        }

        return $result;
    }

    public function show($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        $transaction = CoinTransaction::with(['activity', 'user'])
            ->where('student_id', $student->id)
            ->findOrFail($id);

        return view('Panel.Student.coin-detail', compact('student', 'transaction'));
    }
}
