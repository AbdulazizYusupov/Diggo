<?php

namespace App\Services\Payment;

use App\Models\Group;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
use App\Notifications\PaymentApprove\PaymentApprovedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    // ==================== PUBLIC BUSINESS METHODS ====================

    /**
     * Store a new payment.
     *
     * Business rules enforced:
     *  1. Duplicate payment for same student + group + month is rejected.
     *  2. Amount must not exceed the group's course price.
     *
     * @throws \RuntimeException
     */
    public function store(array $validated): array
    {
        $month = Carbon::parse($validated['payment_month']);

        $existing = Payment::where('payable_type', Student::class)
            ->where('payable_id', $validated['student_id'])
            ->where('group_id', $validated['group_id'])
            ->whereYear('payment_month', $month->year)
            ->whereMonth('payment_month', $month->month)
            ->where('status', 'paid')
            ->first();

        if ($existing) {
            throw new \RuntimeException(
                "Bu student uchun ushbu oyga to'lov allaqachon qabul qilingan. " .
                    "Sana: " . $existing->created_at->format('d.m.Y') .
                    " | Summa: " . $this->formatMoney($existing->amount) . " so'm"
            );
        }

        $student = Student::with('director')->findOrFail($validated['student_id']);
        $group   = Group::with('course')->findOrFail($validated['group_id']);
        $course  = $group->course;

        if (!$course) {
            throw new \RuntimeException("Ushbu guruhga biriktirilgan kurs topilmadi.");
        }

        $coursePrice = (float) $course->price;

        if ((float) $validated['amount'] > $coursePrice) {
            throw new \RuntimeException(
                "To'lov summasi kurs narxidan ({$this->formatMoney($coursePrice)} so'm) oshib ketdi."
            );
        }

        DB::beginTransaction();
        try {
            $amounts            = $this->calculatePaymentAmounts((float) $validated['amount'], (float) ($validated['discount'] ?? 0));
            $payment            = $this->createPayment($validated, $student, $amounts);
            $this->createStudentTransaction($student, $payment, $amounts['amount_to_balance']);
            $directorCommission = $this->processDirectorCommission($student, $payment, $amounts['net_amount']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data'  => $validated,
            ]);
            throw $e;
        }

        if ($student->user) {
            $student->user->notify(new PaymentApprovedNotification($payment));
        }

        return [
            'amounts'            => $amounts,
            'directorCommission' => $directorCommission,
        ];
    }

    /**
     * Update an existing payment.
     *
     * Business rules:
     *  - Admin — har qanday to'lovni istalgan vaqtda yangilaydi.
     *  - Boshqalar — faqat 48 soat ichida yangilaydi.
     *  - Amount kurs narxidan oshmasligi kerak (store bilan bir xil).
     *  - Faqat meta o'zgarganda — tranzaksiyalarga tegmaydi, notification yuboriladi.
     *  - Moliyaviy qiymatlar o'zgarganda — eski tranzaksiyalar o'chiriladi,
     *    yangilari yaratiladi, notification yuboriladi.
     *
     * @throws \RuntimeException
     */
    public function update(Payment $payment, array $validated): array
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            if ($payment->paid_at && $payment->paid_at->diffInHours(Carbon::now()) > 48) {
                throw new \RuntimeException("48 soatdan oshgan to'lovni tahrirlash mumkin emas!");
            }
        }

        if ($payment->status === 'cancelled') {
            throw new \RuntimeException("Bekor qilingan to'lovni tahrirlash mumkin emas!");
        }

        $group  = Group::with('course')->findOrFail($validated['group_id']);
        $course = $group->course;

        if (!$course) {
            throw new \RuntimeException("Ushbu guruhga biriktirilgan kurs topilmadi.");
        }

        $coursePrice = (float) $course->price;

        if ((float) $validated['amount'] > $coursePrice) {
            throw new \RuntimeException(
                "To'lov summasi kurs narxidan ({$this->formatMoney($coursePrice)} so'm) oshib ketdi."
            );
        }

        DB::beginTransaction();
        try {
            $student    = Student::with('director')->findOrFail($payment->payable_id);
            $newAmounts = $this->calculatePaymentAmounts(
                (float) $validated['amount'],
                (float) $validated['discount']
            );

            $financialChanged = (float) $payment->amount   !== $newAmounts['gross_amount']
                || (float) $payment->discount !== $newAmounts['discount_percent'];

            if (!$financialChanged) {
                $payment->update([
                    'group_id'      => $validated['group_id'],
                    'payment_type'  => $validated['payment_type'],
                    'payment_month' => $validated['payment_month'],
                    'notes'         => $validated['notes'] ?? null,
                ]);

                DB::commit();

                return [
                    'financialChanged'   => false,
                    'payment'            => $payment,
                    'oldAmounts'         => null,
                    'newAmounts'         => $newAmounts,
                    'directorCommission' => null,
                ];
            }

            $oldAmounts = [
                'gross_amount'     => (float) $payment->amount,
                'discount_percent' => (float) $payment->discount,
            ];

            $payment->update([
                'group_id'      => $validated['group_id'],
                'amount'        => $newAmounts['gross_amount'],
                'discount'      => $newAmounts['discount_percent'],
                'net_amount'    => $newAmounts['net_amount'],
                'payment_type'  => $validated['payment_type'],
                'payment_month' => $validated['payment_month'],
                'notes'         => $this->buildUpdateNote($validated['notes'] ?? '', $oldAmounts['gross_amount']),
            ]);

            $this->reversePaymentTransactions($payment, $student);
            $this->createStudentTransaction($student, $payment, $newAmounts['amount_to_balance']);
            $directorCommission = $this->processDirectorCommission($student, $payment, $newAmounts['net_amount']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment update failed', [
                'payment_id' => $payment->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        if ($student->user) {
            $this->updateExistingNotification($student->user, $payment->fresh());
        }

        return [
            'financialChanged'   => true,
            'payment'            => $payment,
            'oldAmounts'         => $oldAmounts,
            'newAmounts'         => $newAmounts,
            'directorCommission' => $directorCommission,
        ];
    }

    /**
     * Cancel a payment and reverse all related transactions.
     *
     * @throws \RuntimeException
     */
    public function cancel(Payment $payment): void
    {
        if ($payment->status === 'cancelled') {
            throw new \RuntimeException("To'lov allaqachon bekor qilingan!");
        }

        if ($payment->paid_at && $payment->paid_at->diffInHours(Carbon::now()) > 48) {
            throw new \RuntimeException("48 soatdan oshgan to'lovni bekor qilib bo'lmaydi!");
        }
        $student = $payment->student;
        if ($student) {
            $notification = $student->user->notifications()
                ->where('type', PaymentApprovedNotification::class)
                ->where('data->payment_id', $payment->id)
                ->first();
            if ($notification) {
                $notification->delete();
            }
        }


        // dd($payment->payable);
        DB::beginTransaction();
        try {
            $this->reversePaymentTransactions($payment, $payment->payable, true);

            $payment->update([
                'status' => 'cancelled',
                'notes'  => ($payment->notes ? $payment->notes . "\n" : '') .
                    "[Bekor qilindi: " . Carbon::now()->format('d.m.Y H:i') . " - " . Auth::user()->name . "]",
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment cancellation failed', [
                'payment_id' => $payment->id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Validate edit access for a payment (non-admin: 48-hour rule).
     *
     * @throws \RuntimeException
     */
    public function assertEditable(Payment $payment): void
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            if ($payment->paid_at && $payment->paid_at->diffInHours(Carbon::now()) > 48) {
                throw new \RuntimeException("48 soatdan oshgan to'lovni tahrirlash mumkin emas!");
            }
        }

        if ($payment->status === 'cancelled') {
            throw new \RuntimeException("Bekor qilingan to'lovni tahrirlash mumkin emas!");
        }
    }

    // ==================== PUBLIC FORMATTING HELPERS ====================

    public function buildStoreSuccessMessage(array $amounts, ?array $directorCommission): string
    {
        $message  = "To'lov muvaffaqiyatli qabul qilindi!\n";
        $message .= "To'langan: {$this->formatMoney($amounts['gross_amount'])} so'm";

        if ($amounts['discount_amount'] > 0) {
            $message .= "\nChegirma ({$amounts['discount_percent']}%): "
                . $this->formatMoney($amounts['discount_amount']) . " so'm";
            $message .= "\nJami (chegirmadan so'ng): "
                . $this->formatMoney($amounts['net_amount']) . " so'm";
        }

        $message .= "\n\nStudent balansi: +" . $this->formatMoney($amounts['amount_to_balance']) . " so'm";

        if ($directorCommission) {
            $message .= "\n\nDirektor komissiyasi: "
                . $this->formatMoney($directorCommission['amount'])
                . " so'm ({$directorCommission['percentage']}%)";
        }

        return $message;
    }

    public function buildUpdateSuccessMessage(array $oldAmounts, array $newAmounts, ?array $directorCommission): string
    {
        $diff    = $newAmounts['gross_amount'] - $oldAmounts['gross_amount'];
        $message = "To'lov muvaffaqiyatli yangilandi!\n";
        $message .= "Eski summa: {$this->formatMoney($oldAmounts['gross_amount'])} so'm\n";
        $message .= "Yangi summa: {$this->formatMoney($newAmounts['gross_amount'])} so'm\n";
        $message .= "Farq: " . ($diff >= 0 ? '+' : '') . $this->formatMoney($diff) . " so'm";

        if ($directorCommission) {
            $message .= "\n\nDirektor komissiyasi yangilandi: "
                . $this->formatMoney($directorCommission['amount'])
                . " so'm ({$directorCommission['percentage']}%)";
        }

        return $message;
    }

    public function formatMoney(float $amount): string
    {
        return number_format($amount, 0, '.', ' ');
    }

    // ==================== PRIVATE HELPERS ====================

    private function calculatePaymentAmounts(float $amount, float $discountPercent): array
    {
        $discountAmount = $discountPercent > 0 ? ($amount * $discountPercent) / 100 : 0;

        return [
            'gross_amount'      => $amount,
            'discount_percent'  => $discountPercent,
            'discount_amount'   => $discountAmount,
            'net_amount'        => $amount - $discountAmount,
            'amount_to_balance' => $amount - $discountAmount,
        ];
    }

    private function createPayment(array $validated, Student $student, array $amounts): Payment
    {
        return Payment::create([
            'payable_type'  => Student::class,
            'payable_id'    => $student->id,
            'group_id'      => $validated['group_id'],
            'user_id'       => Auth::id(),
            'amount'        => $amounts['gross_amount'],
            'discount'      => $amounts['discount_percent'],
            'net_amount'    => $amounts['net_amount'],
            'payment_type'  => $validated['payment_type'],
            'status'        => 'paid',
            'payment_month' => $validated['payment_month'],
            'notes'         => $validated['notes'] ?? null,
            'paid_at'       => Carbon::now(),
        ]);
    }

    private function createStudentTransaction(Student $student, Payment $payment, float $amount): Transaction
    {
        $oldBalance  = $student->balance;
        $student->increment('balance', $amount);
        $newBalance  = $student->fresh()->balance;
        $description = "To'lov qabul qilindi ({$payment->payment_type})";

        return Transaction::create([
            'transactionable_type' => Student::class,
            'transactionable_id'   => $student->id,
            'source_type'          => Payment::class,
            'source_id'            => $payment->id,
            'amount'               => $amount,
            'balance_before'       => $oldBalance,
            'balance_after'        => $newBalance,
            'type'                 => 'payment',
            'description'          => $description,
            'status'               => 'paid',
            'created_by'           => Auth::id(),
            'transaction_date'     => Carbon::now(),
            'paid_at'              => Carbon::now(),
        ]);
    }

    private function processDirectorCommission(Student $student, Payment $payment, float $baseAmount): ?array
    {
        if (!$student->director) return null;

        $director          = $student->director;
        $commissionPercent = (float) ($director->commission_percentage ?? 0);

        if ($commissionPercent <= 0) return null;

        $commissionAmount = ($baseAmount * $commissionPercent) / 100;
        $oldBalance       = $director->balance;
        $director->increment('balance', $commissionAmount);
        $newBalance = $director->fresh()->balance;

        Transaction::create([
            'transactionable_type' => get_class($director),
            'transactionable_id'   => $director->id,
            'source_type'          => Payment::class,
            'source_id'            => $payment->id,
            'amount'               => $commissionAmount,
            'balance_before'       => $oldBalance,
            'balance_after'        => $newBalance,
            'type'                 => 'director_commission',
            'percentage'           => $commissionPercent,
            'base_amount'          => $baseAmount,
            'description'          => "Direktor komissiyasi ({$commissionPercent}%) — {$student->user->full_name}",
            'status'               => 'paid',
            'created_by'           => Auth::id(),
            'transaction_date'     => Carbon::now(),
            'paid_at'              => Carbon::now(),
        ]);

        return [
            'director_id'   => $director->id,
            'director_name' => $director->user->full_name,
            'percentage'    => $commissionPercent,
            'amount'        => $commissionAmount,
        ];
    }

    private function reversePaymentTransactions(Payment $payment, Student $student, bool $markAsCancelled = false): void
    {
        $studentTx = Transaction::where('source_type', Payment::class)
            ->where('source_id', $payment->id)
            ->where('type', 'payment')
            ->first();

        if ($studentTx) {
            $student->decrement('balance', $studentTx->amount);
            $markAsCancelled
                ? $studentTx->update(['status' => 'cancelled', 'description' => $studentTx->description . ' [BEKOR QILINDI]'])
                : $studentTx->delete();
        }

        $directorTx = Transaction::where('source_type', Payment::class)
            ->where('source_id', $payment->id)
            ->where('type', 'director_commission')
            ->first();

        if ($directorTx && $directorTx->transactionable) {
            if ($directorTx->status === 'paid') {
                $directorTx->transactionable->decrement('balance', $directorTx->amount);
            }
            $markAsCancelled
                ? $directorTx->update(['status' => 'cancelled', 'description' => $directorTx->description . ' [BEKOR QILINDI]'])
                : $directorTx->delete();
        }
    }

    private function updateExistingNotification(object $user, Payment $payment): void
    {
        $notification = $user->notifications()
            ->where('type', PaymentApprovedNotification::class)
            ->where('data->payment_id', $payment->id)
            ->first();

        if (!$notification) {
            return;
        }

        $data = $notification->data;

        $data['message']    = number_format($payment->net_amount, 0, '.', ' ') . " so'mlik to'lovingiz qabul qilindi.";
        $data['amount']     = $payment->amount;
        $data['net_amount'] = $payment->net_amount;
        $data['discount']   = $payment->discount;

        $notification->update([
            'data'    => $data,
            'read_at' => null,
        ]);
    }

    private function buildUpdateNote(string $existingNote, float $oldAmount): string
    {
        $note = $existingNote ?: '';
        if ($note) $note .= "\n";
        $note .= "[Tahrirlandi: " . Carbon::now()->format('d.m.Y H:i')
            . " — " . Auth::user()->name
            . " | Eski summa: " . $this->formatMoney($oldAmount) . " so'm]";
        return $note;
    }
}
