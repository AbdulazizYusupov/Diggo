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

class DirectorCommissionsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $director = Director::where('user_id', $user->id)->firstOrFail();

        // Filters
        $status = $request->get('status', 'all');
        $month  = $request->get('month');
        $year   = $request->get('year', Carbon::now()->year);

        // Query - payable_type = Student, payable orqali director va school filter
        $query = Payment::with(['payable.user'])
            ->where('payable_type', Student::class)
            ->whereHasMorph('payable', [Student::class], function ($q) use ($director) {
                $q->where('school_id', $director->school_id)
                  ->where('director_id', $director->id);
            })
            ->where('status', 'paid')
            ->whereNotNull('paid_at');

        // Month filter
        if ($month) {
            $query->whereMonth('paid_at', $month);
        }

        // Year filter
        $query->whereYear('paid_at', $year);

        // Status filter
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $payments = $query->orderBy('paid_at', 'desc')->paginate(20);

        // Har bir payment uchun komissiya hisoblash
        $payments->getCollection()->transform(function ($payment) use ($director) {
            $payment->commission_amount = ($payment->amount * $director->commission_percentage) / 100;
            return $payment;
        });

        // Statistics
        $stats = $this->getCommissionStats($director, $year);

        $months = [
            1  => 'Yanvar',  2  => 'Fevral', 3  => 'Mart',
            4  => 'Aprel',   5  => 'May',     6  => 'Iyun',
            7  => 'Iyul',    8  => 'Avgust',  9  => 'Sentabr',
            10 => 'Oktabr',  11 => 'Noyabr',  12 => 'Dekabr',
        ];

        $currentYear = Carbon::now()->year;
        $years = range($currentYear, $currentYear - 4);

        return view('Panel.Director.commissions', compact(
            'director', 'payments', 'stats',
            'status', 'month', 'year', 'months', 'years'
        ));
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

    public function history()
    {
        $user     = Auth::user();
        $director = Director::where('user_id', $user->id)->firstOrFail();

        $currentYear = Carbon::now()->year;
        $years       = range($currentYear, max($currentYear - 5, 2020));

        $yearlyStats = [];
        foreach ($years as $year) {
            $payments = Payment::where('payable_type', Student::class)
                ->whereHasMorph('payable', [Student::class], function ($q) use ($director) {
                    $q->where('school_id', $director->school_id)
                      ->where('director_id', $director->id);
                })
                ->where('status', 'paid')
                ->whereYear('paid_at', $year)
                ->get();

            $commission = $payments->sum(function ($payment) use ($director) {
                return ($payment->amount * $director->commission_percentage) / 100;
            });

            $yearlyStats[$year] = [
                'total_commission' => $commission,
                'total_payments'   => $payments->count(),
                'avg_per_month'    => $commission / 12,
            ];
        }

        return view('Panel.Director.commission-history', compact(
            'director', 'yearlyStats', 'years'
        ));
    }
}