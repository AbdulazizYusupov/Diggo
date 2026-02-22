<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirectorReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $director = $user->director;

        if (!$director) {
            abort(403, 'Director profili topilmadi');
        }

        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);

        // Monthly report
        $monthlyReport = $this->getMonthlyReport($director, $year, $month);

        // Yearly report
        $yearlyReport = $this->getYearlyReport($director, $year);

        // Student growth
        $studentGrowth = $this->getStudentGrowth($director, $year);

        // Revenue breakdown
        $revenueBreakdown = $this->getRevenueBreakdown($director, $year, $month);

        // Years for filter
        $currentYear = Carbon::now()->year;
        $years = range($currentYear, max($currentYear - 5, 2020));

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

        return view('Panel.Director.reports', compact(
            'director',
            'monthlyReport',
            'yearlyReport',
            'studentGrowth',
            'revenueBreakdown',
            'year',
            'month',
            'years',
            'months'
        ));
    }

    private function getMonthlyReport($director, $year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $payments = Payment::whereHas('group.students', function ($q) use ($director) {
            $q->where('director_id', $director->id);
        })
            ->where('status', 'paid')
            ->whereBetween('payment_month', [$startDate, $endDate])
            ->get();

        $totalRevenue = $payments->sum('net_amount');
        $commission = $totalRevenue * ($director->commission_percentage / 100);

        $newStudents = Student::where('director_id', $director->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'total_revenue' => $totalRevenue,
            'commission' => $commission,
            'payment_count' => $payments->count(),
            'new_students' => $newStudents,
            'avg_payment' => $payments->count() > 0 ? $totalRevenue / $payments->count() : 0,
        ];
    }

    private function getYearlyReport($director, $year)
    {
        $payments = Payment::whereHas('group.students', function ($q) use ($director) {
            $q->where('director_id', $director->id);
        })
            ->where('status', 'paid')
            ->whereYear('payment_month', $year)
            ->get();

        $totalRevenue = $payments->sum('net_amount');
        $commission = $totalRevenue * ($director->commission_percentage / 100);

        return [
            'total_revenue' => $totalRevenue,
            'commission' => $commission,
            'payment_count' => $payments->count(),
            'avg_per_month' => $totalRevenue / 12,
        ];
    }

    private function getStudentGrowth($director, $year)
    {
        $result = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $newStudents = Student::where('director_id', $director->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $totalStudents = Student::where('director_id', $director->id)
                ->where('created_at', '<=', $endDate)
                ->count();

            $result[$month] = [
                'new' => $newStudents,
                'total' => $totalStudents,
            ];
        }

        return $result;
    }

    private function getRevenueBreakdown($director, $year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // By payment type
        $byPaymentType = Payment::whereHas('group.students', function ($q) use ($director) {
            $q->where('director_id', $director->id);
        })
            ->where('status', 'paid')
            ->whereBetween('payment_month', [$startDate, $endDate])
            ->select('payment_type', DB::raw('SUM(net_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_type')
            ->get()
            ->keyBy('payment_type');

        // By course
        $byCourse = Payment::whereHas('group.students', function ($q) use ($director) {
            $q->where('director_id', $director->id);
        })
            ->join('groups', 'payments.group_id', '=', 'groups.id')
            ->join('cours', 'groups.cours_id', '=', 'cours.id')
            ->where('payments.status', 'paid')
            ->whereBetween('payments.payment_month', [$startDate, $endDate])
            ->select('cours.title', DB::raw('SUM(payments.net_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('cours.id', 'cours.title')
            ->get();

        return [
            'by_payment_type' => $byPaymentType,
            'by_course' => $byCourse,
        ];
    }

    public function export(Request $request)
    {
        // Export functionality - CSV/Excel/PDF
        // This can be implemented based on requirements
    }
}
