<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorCommissionsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $director = $user->director;

        if (!$director) {
            abort(403, 'Director profili topilmadi');
        }

        // Filters
        $status = $request->get('status', 'all');
        $month = $request->get('month');
        $year = $request->get('year', Carbon::now()->year);

        // Query - Director commission lari payments orqali
        $query = Payment::with(['group.course', 'user'])
            ->whereHas('group.students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
            ->where('status', 'paid')
            ->whereNotNull('paid_at');

        // Month filter
        if ($month) {
            $query->whereMonth('payment_month', $month);
        }

        // Year filter
        $query->whereYear('payment_month', $year);

        $payments = $query->orderBy('paid_at', 'desc')
            ->paginate(20);

        // Calculate commissions for each payment
        $payments->getCollection()->transform(function ($payment) use ($director) {
            $payment->commission_amount = ($payment->net_amount * $director->commission_percentage) / 100;
            return $payment;
        });

        // Statistics
        $stats = $this->getCommissionStats($director, $year);

        // Months
        $months = [
            1 => 'Yanvar',
            2 => 'Fevral',
            3 => 'Mart',
            4 => 'Aprel',
            5 => 'May',
            6 => 'Iyun',
            7 => 'Iyul',
            8 => 'Avgust',
            9 => 'Sentabr',
            10 => 'Oktabr',
            11 => 'Noyabr',
            12 => 'Dekabr'
        ];

        // Years
        $currentYear = Carbon::now()->year;
        $years = range($currentYear, $currentYear - 4);

        return view('Panel.Director.commissions', compact(
            'director',
            'payments',
            'stats',
            'status',
            'month',
            'year',
            'months',
            'years'
        ));
    }

    private function getCommissionStats($director, $year)
    {
        $allPayments = Payment::whereHas('group.students', function ($q) use ($director) {
            $q->where('director_id', $director->id);
        })
            ->where('status', 'paid')
            ->whereYear('payment_month', $year)
            ->get();

        $totalCommission = $allPayments->sum(function ($payment) use ($director) {
            return ($payment->net_amount * $director->commission_percentage) / 100;
        });

        $thisMonth = Carbon::now();
        $thisMonthPayments = Payment::whereHas('group.students', function ($q) use ($director) {
            $q->where('director_id', $director->id);
        })
            ->where('status', 'paid')
            ->whereMonth('payment_month', $thisMonth->month)
            ->whereYear('payment_month', $thisMonth->year)
            ->get();

        $thisMonthCommission = $thisMonthPayments->sum(function ($payment) use ($director) {
            return ($payment->net_amount * $director->commission_percentage) / 100;
        });

        return [
            'current_balance' => $director->balance,
            'commission_percentage' => $director->commission_percentage,
            'total_commission' => $totalCommission,
            'this_month_commission' => $thisMonthCommission,
            'total_payments' => $allPayments->count(),
            'this_month_payments' => $thisMonthPayments->count(),
            'monthly_breakdown' => $this->getMonthlyBreakdown($director, $year),
        ];
    }

    private function getMonthlyBreakdown($director, $year)
    {
        $result = [];

        for ($month = 1; $month <= 12; $month++) {
            $payments = Payment::whereHas('group.students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
                ->where('status', 'paid')
                ->whereMonth('payment_month', $month)
                ->whereYear('payment_month', $year)
                ->get();

            $commission = $payments->sum(function ($payment) use ($director) {
                return ($payment->net_amount * $director->commission_percentage) / 100;
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
        $user = Auth::user();
        $director = $user->director;

        if (!$director) {
            abort(403, 'Director profili topilmadi');
        }

        // Yearly statistics
        $currentYear = Carbon::now()->year;
        $years = range($currentYear, max($currentYear - 5, 2020));

        $yearlyStats = [];
        foreach ($years as $year) {
            $payments = Payment::whereHas('group.students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
                ->where('status', 'paid')
                ->whereYear('payment_month', $year)
                ->get();

            $commission = $payments->sum(function ($payment) use ($director) {
                return ($payment->net_amount * $director->commission_percentage) / 100;
            });

            $yearlyStats[$year] = [
                'total_commission' => $commission,
                'total_payments' => $payments->count(),
                'avg_per_month' => $commission / 12,
            ];
        }

        return view('Panel.Director.commission-history', compact(
            'director',
            'yearlyStats',
            'years'
        ));
    }
}
