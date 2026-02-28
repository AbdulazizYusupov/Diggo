<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentPaymentsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Filter parametrlari
        $status = $request->get('status', 'all');
        $month = $request->get('month');
        $year = $request->get('year', Carbon::now()->year);

        // Query
        $query = Payment::with(['group.course', 'user'])
            ->where('payable_type', 'App\Models\Student')
            ->where('payable_id', $student->id);

        // Status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Month filter
        if ($month) {
            $query->whereMonth('payment_month', $month);
        }

        // Year filter
        $query->whereYear('payment_month', $year);

        $payments = $query->orderBy('paid_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistika
        $stats = $this->getPaymentStats($student, $year);

        // Oylar ro'yxati
        $months = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
        ];

        // Yillar ro'yxati (so'nggi 5 yil)
        $currentYear = Carbon::now()->year;
        $years = range($currentYear, $currentYear - 4);

        return view('Panel.Student.payments', compact(
            'student',
            'payments',
            'stats',
            'status',
            'month',
            'year',
            'months',
            'years'
        ));
    }

    private function getPaymentStats($student, $year)
    {
        $allPayments = Payment::where('payable_type', 'App\Models\Student')
            ->where('payable_id', $student->id)
            ->whereYear('payment_month', $year)
            ->get();

        $paidPayments = $allPayments->where('status', 'paid');

        return [
            'total_paid' => $paidPayments->sum('net_amount'),
            'total_discount' => $paidPayments->sum('discount'),
            'total_gross' => $paidPayments->sum('amount'),
            'pending_amount' => $allPayments->where('status', 'pending')->sum('net_amount'),
            'total_payments' => $paidPayments->count(),
            'pending_payments' => $allPayments->where('status', 'pending')->count(),
            
            // Oylik breakdown
            'monthly_breakdown' => $this->getMonthlyBreakdown($student, $year),
            
            // Payment type breakdown
            'payment_types' => $paidPayments->groupBy('payment_type')->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('net_amount')
                ];
            }),
        ];
    }

    private function getMonthlyBreakdown($student, $year)
    {
        return Payment::where('payable_type', 'App\Models\Student')
            ->where('payable_id', $student->id)
            ->where('status', 'paid')
            ->whereYear('payment_month', $year)
            ->select(
                DB::raw('MONTH(payment_month) as month'),
                DB::raw('SUM(net_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
    }

    public function show($id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        $payment = Payment::with(['group.course', 'user'])
            ->where('payable_type', 'App\Models\Student')
            ->where('payable_id', $student->id)
            ->findOrFail($id);
        // dd($payment);

        return view('Panel.Student.payment-detail', compact('student', 'payment'));
    }
}
