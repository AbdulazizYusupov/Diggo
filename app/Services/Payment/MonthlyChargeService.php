<?php
// app/Services/Payment/MonthlyChargeService.php

namespace App\Services\Payment;

use App\Models\Group;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonthlyChargeService
{
    /**
     * Preview — charge qilinishi kutilayotgan studentlar
     */
    public function getPreview(Carbon $targetMonth): array
    {
        // Single optimized query instead of nested loops
        $stats = DB::table('group_students')
            ->join('students', 'group_students.student_id', '=', 'students.id')
            ->join('groups', 'group_students.group_id', '=', 'groups.id')
            ->leftJoin('transactions', function ($join) use ($targetMonth) {
                $join->on('transactions.transactionable_id', '=', 'students.id')
                    ->where('transactions.transactionable_type', '=', Student::class)
                    ->where('transactions.type', '=', 'charge')
                    ->where('transactions.source_type', '=', Group::class)
                    ->where('transactions.source_id', '=', DB::raw('groups.id'))
                    ->whereYear('transactions.transaction_date', $targetMonth->year)
                    ->whereMonth('transactions.transaction_date', $targetMonth->month);
            })
            ->leftJoin('payments', function ($join) use ($targetMonth) {
                $join->on('payments.payable_id', '=', 'students.id')
                    ->where('payments.payable_type', '=', Student::class)
                    ->where('payments.group_id', '=', DB::raw('groups.id'))
                    ->where('payments.status', '=', 'paid')
                    ->whereYear('payments.payment_month', $targetMonth->year)
                    ->whereMonth('payments.payment_month', $targetMonth->month);
            })
            ->where('groups.is_active', true)
            ->whereNull('transactions.id') // Not already charged
            ->selectRaw('
                COUNT(CASE WHEN payments.id IS NOT NULL THEN 1 END) as to_charge,
                COUNT(CASE WHEN payments.id IS NULL THEN 1 END) as no_payment,
                SUM(payments.net_amount) as total_amount
            ')
            ->first();

        return [
            'toCharge' => $stats->to_charge ?? 0,
            'noPayment' => $stats->no_payment ?? 0,
            'alreadyDone' => 0, // This would require separate query if needed
            'totalAmount' => $stats->total_amount ?? 0,
        ];
    }

    /**
     * Asosiy charge metodi
     */
    public function runMonthlyCharge(Carbon $targetMonth): array
    {
        $stats = [
            'month'        => $targetMonth->format('Y-m'),
            'charged'      => [],
            'skipped'      => [],
            'no_payment'   => [],
            'errors'       => [],
            'total_amount' => 0,
        ];

        $groups = Group::with(['course', 'groupStudents.student.user'])
            ->where('is_active', true)
            ->get();

        foreach ($groups as $group) {
            foreach ($group->groupStudents as $gs) {
                $student = $gs->student;

                try {
                    $result = $this->processOneStudent($student, $group, $targetMonth);

                    $label = "{$student->user->name} | {$group->name}";

                    match ($result['status']) {
                        'charged'    => [
                            $stats['charged'][]    = "$label → -{$result['amount']}",
                            $stats['total_amount'] += $result['amount'],
                        ],
                        'skipped'    => $stats['skipped'][]    = $label,
                        'no_payment' => $stats['no_payment'][] = $label,
                        default      => null,
                    };
                } catch (\Exception $e) {
                    $stats['errors'][] = "Student #{$student->id}: {$e->getMessage()}";
                    Log::error('Monthly charge error', [
                        'student_id' => $student->id,
                        'group_id'   => $group->id,
                        'error'      => $e->getMessage(),
                    ]);
                }
            }
        }

        return $stats;
    }

    private function processOneStudent(Student $student, Group $group, Carbon $month): array
    {
        // Ikki marta charge bo'lmasligi
        $exists = Transaction::where('transactionable_type', Student::class)
            ->where('transactionable_id', $student->id)
            ->where('type', 'charge')
            ->where('source_type', Group::class)
            ->where('source_id', $group->id)
            ->whereYear('transaction_date', $month->year)
            ->whereMonth('transaction_date', $month->month)
            ->exists();

        if ($exists) return ['status' => 'skipped'];

        $payment = Payment::where('payable_type', Student::class)
            ->where('payable_id', $student->id)
            ->where('group_id', $group->id)
            ->where('status', 'paid')
            ->whereYear('payment_month', $month->year)
            ->whereMonth('payment_month', $month->month)
            ->first();

        if (!$payment) return ['status' => 'no_payment'];

        $chargeAmount  = $payment->net_amount;
        $balanceBefore = $student->balance;

        DB::transaction(function () use ($student, $group, $payment, $chargeAmount, $balanceBefore, $month) {
            $student->decrement('balance', $chargeAmount);

            Transaction::create([
                'transactionable_type' => Student::class,
                'transactionable_id'   => $student->id,
                'source_type'          => Payment::class,
                'source_id'            => $payment->id,
                'type'                 => 'charge',
                'amount'               => $chargeAmount,
                'balance_before'       => $balanceBefore,
                'balance_after'        => $balanceBefore - $chargeAmount,
                'status'               => 'paid',
                'description'          => "{$month->format('Y F')} | {$group->name} dars haqi",
                'transaction_date'     => $month,
                'created_by'=>Auth::id()
            ]);

            if (($balanceBefore - $chargeAmount) < 0) {
                $student->update(['has_debt' => true]);
            }
        });

        return ['status' => 'charged', 'amount' => $chargeAmount];
    }
}
