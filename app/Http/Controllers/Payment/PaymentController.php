<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
use App\Notifications\PaymentApprove\PaymentApprovedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // ==================== PUBLIC RESOURCE METHODS ====================

    public function index(Request $request)
    {
        $query = Payment::with(['payable.user', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('payable_type', Student::class)
                ->whereHasMorph('payable', [Student::class], function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('surname', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
                });
        }

        if ($request->filled('status'))       $query->where('status', $request->status);
        if ($request->filled('payment_type')) $query->where('payment_type', $request->payment_type);
        if ($request->filled('date_from'))    $query->whereDate('paid_at', '>=', $request->date_from);
        if ($request->filled('date_to'))      $query->whereDate('paid_at', '<=', $request->date_to);

        $payments         = $query->latest('paid_at')->paginate(15);
        $totalPayments    = Payment::where('status', 'paid')->sum('amount');
        $totalCommissions = Transaction::where('type', 'director_commission')->where('status', 'paid')->sum('amount');
        $pendingPayments  = Payment::where('status', 'pending')->count();
        $monthlyPayments  = Payment::where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)->sum('amount');

        return view('Payment.index', compact(
            'payments', 'totalPayments', 'totalCommissions', 'pendingPayments', 'monthlyPayments'
        ));
    }

    public function create(Request $request)
    {
        $students        = Student::with('user', 'director')
            ->whereHas('user', fn($q) => $q->where('is_active', true))->get();
        $selectedStudent = null;
        $groups          = collect();

        if ($request->filled('student_id')) {
            $selectedStudent = Student::with('user', 'director')->find($request->student_id);
            if ($selectedStudent) {
                $groups = Group::whereHas('groupStudents', fn($q) => $q->where('student_id', $request->student_id))
                    ->with(['teacher.user', 'course'])->orderBy('name')->get();
            }
        } else {
            $groups = Group::with(['teacher.user', 'course'])->orderBy('name')->get();
        }

        return view('Payment.create', compact('students', 'selectedStudent', 'groups'));
    }

    /**
     * Store a new payment.
     *
     * Business rules enforced:
     *  1. Amount must not exceed the group's course price.
     *  2. Installment N cannot be submitted unless installments 1..N-1 are already paid.
     *  3. Cumulative installment total for the month must not exceed the course price.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'         => 'required|exists:students,id',
            'group_id'           => 'required|exists:groups,id',
            'amount'             => 'required|numeric|min:1',
            'discount'           => 'nullable|numeric|min:0|max:100',
            'payment_type'       => 'required|in:cash,card,transfer,other',
            'payment_month'      => 'required|date',
            'is_installment'     => 'boolean',
            'installment_number' => 'nullable|integer|min:1',
            'total_installments' => 'nullable|integer|min:1',
            'notes'              => 'nullable|string|max:1000',
        ]);

        $student = Student::with('director')->findOrFail($validated['student_id']);
        $group   = Group::with('course')->findOrFail($validated['group_id']);
        $course  = $group->course;

        if (!$course) {
            return back()->withInput()->with('error', "Ushbu guruhga biriktirilgan kurs topilmadi.");
        }

        $coursePrice       = (float) $course->price;
        $isInstallment     = !empty($validated['is_installment']);
        $installmentNumber = $isInstallment ? (int) ($validated['installment_number'] ?? 1) : null;
        $totalInstallments = $isInstallment ? (int) ($validated['total_installments'] ?? 1) : null;

        if ($isInstallment) {
            if ($error = $this->validateInstallmentSequence(
                (int) $validated['student_id'], (int) $validated['group_id'],
                $validated['payment_month'], $installmentNumber
            )) {
                return back()->withInput()->with('error', $error);
            }

            if ($error = $this->validateInstallmentAmount(
                (int) $validated['student_id'], (int) $validated['group_id'],
                $validated['payment_month'], $installmentNumber,
                (float) $validated['amount'], $coursePrice
            )) {
                return back()->withInput()->with('error', $error);
            }
        } else {
            if ((float) $validated['amount'] > $coursePrice) {
                return back()->withInput()->with('error',
                    "To'lov summasi kurs narxidan ({$this->formatMoney($coursePrice)} so'm) oshib ketdi."
                );
            }
        }

        DB::beginTransaction();
        try {
            $amounts            = $this->calculatePaymentAmounts((float) $validated['amount'], (float) ($validated['discount'] ?? 0), $coursePrice);
            $payment            = $this->createPayment($validated, $student, $amounts, $installmentNumber, $totalInstallments);
            $this->createStudentTransaction($student, $payment, $amounts['amount_to_balance']);
            $directorCommission = $this->processDirectorCommission($student, $payment, $amounts['amount_to_balance']);
            DB::commit();

            if ($student->user) {
                $student->user->notify(new PaymentApprovedNotification($payment));
            }

            return $this->successResponse($amounts, $directorCommission);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'data' => $validated]);
            return back()->withInput()->with('error', "To'lovni saqlashda xatolik: " . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $payment = Payment::with([
            'payable.user', 'user',
            'transactions' => fn($q) => $q->whereIn('type', ['payment', 'director_commission'])->with('transactionable'),
        ])->findOrFail($id);

        return view('Payment.show', compact('payment'));
    }

    public function edit(string $id)
    {
        $payment = Payment::with('payable.user')->findOrFail($id);

        if (!Auth::user()->hasRole('admin')) {
            return back()->with('error', "Faqat admin to'lovni tahrirlashi mumkin!");
        }
        if ($payment->status === 'cancelled') {
            return back()->with('error', "Bekor qilingan to'lovni tahrirlash mumkin emas!");
        }

        $students = Student::with('user', 'director')
            ->whereHas('user', fn($q) => $q->where('is_active', true))->get();

        $groups = Group::whereHas('groupStudents', fn($q) => $q->where('student_id', $payment->payable_id))
            ->with(['teacher.user', 'course'])->orderBy('name')->get();

        return view('Payment.edit', compact('payment', 'students', 'groups'));
    }

    public function update(Request $request, string $id)
    {
        $payment = Payment::with(['payable', 'transactions'])->findOrFail($id);

        if (!Auth::user()->hasRole('admin')) {
            return back()->with('error', "Faqat admin to'lovni tahrirlashi mumkin!");
        }
        if ($payment->status === 'cancelled') {
            return back()->with('error', "Bekor qilingan to'lovni tahrirlash mumkin emas!");
        }

        $validated = $request->validate([
            'group_id'      => 'required|exists:groups,id',
            'amount'        => 'required|numeric|min:1',
            'discount'      => 'nullable|numeric|min:0|max:100',
            'payment_type'  => 'required|in:cash,card,transfer,other',
            'payment_month' => 'required|date',
            'notes'         => 'nullable|string|max:1000',
        ]);

        $group  = Group::with('course')->findOrFail($validated['group_id']);
        $course = $group->course;

        if (!$course) {
            return back()->withInput()->with('error', "Ushbu guruhga biriktirilgan kurs topilmadi.");
        }

        $coursePrice = (float) $course->price;

        if ((float) $validated['amount'] > $coursePrice) {
            return back()->withInput()->with('error',
                "To'lov summasi kurs narxidan ({$this->formatMoney($coursePrice)} so'm) oshib ketdi."
            );
        }

        DB::beginTransaction();
        try {
            $student    = $payment->payable;
            $oldAmounts = [
                'gross_amount'     => $payment->amount,
                'net_amount'       => $payment->net_amount,
                'discount_percent' => $payment->discount,
            ];

            $newAmounts = $this->calculatePaymentAmounts(
                (float) $validated['amount'],
                (float) ($validated['discount'] ?? 0),
                $coursePrice
            );

            // Only meta changed — skip financial recalculation
            if ($oldAmounts['gross_amount'] == $newAmounts['gross_amount']
                && $oldAmounts['discount_percent'] == $newAmounts['discount_percent']) {

                $payment->update([
                    'group_id'      => $validated['group_id'],
                    'payment_type'  => $validated['payment_type'],
                    'payment_month' => $validated['payment_month'],
                    'notes'         => $validated['notes'] ?? null,
                ]);
                DB::commit();

                return redirect()->route('payments.show', $payment->id)
                    ->with('success', "To'lov ma'lumotlari yangilandi!");
            }

            // Financial values changed — reverse and recreate
            $payment->update([
                'amount'        => $newAmounts['gross_amount'],
                'discount'      => $newAmounts['discount_percent'],
                'net_amount'    => $newAmounts['net_amount'],
                'group_id'      => $validated['group_id'],
                'payment_type'  => $validated['payment_type'],
                'payment_month' => $validated['payment_month'],
                'notes'         => $this->buildUpdateNote($validated['notes'] ?? '', $oldAmounts['gross_amount']),
            ]);

            $student = Student::with('director')->findOrFail($student->id);
            $this->reversePaymentTransactions($payment, $student);
            $this->createStudentTransaction($student, $payment, $newAmounts['amount_to_balance']);
            $directorCommission = $this->processDirectorCommission($student, $payment, $newAmounts['amount_to_balance']);

            DB::commit();

            return redirect()->route('payments.show', $payment->id)
                ->with('success', $this->buildUpdateSuccessMessage($oldAmounts, $newAmounts, $directorCommission));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment update failed', ['payment_id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', "To'lovni yangilashda xatolik: " . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'paid') {
            return back()->with('error', "To'langan to'lovni o'chirib bo'lmaydi! Bekor qilish tugmasidan foydalaning.");
        }
        if ($payment->status === 'cancelled') {
            return back()->with('error', "Bekor qilingan to'lovni o'chirib bo'lmaydi!");
        }

        $payment->delete();

        return redirect()->route('payments.index')->with('success', "To'lov o'chirildi!");
    }

    public function cancel(string $id)
    {
        $payment = Payment::with(['payable', 'transactions'])->findOrFail($id);

        if ($payment->status === 'cancelled') {
            return back()->with('error', "To'lov allaqachon bekor qilingan!");
        }
        if ($payment->paid_at && $payment->paid_at->diffInHours(Carbon::now()) > 48) {
            return back()->with('error', "48 soatdan oshgan to'lovni bekor qilib bo'lmaydi!");
        }

        DB::beginTransaction();
        try {
            $this->reversePaymentTransactions($payment, $payment->payable, true);

            $payment->update([
                'status' => 'cancelled',
                'notes'  => ($payment->notes ? $payment->notes . "\n" : '') .
                    "[Bekor qilindi: " . Carbon::now()->format('d.m.Y H:i') . " - " . Auth::user()->name . "]",
            ]);

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', "To'lov bekor qilindi! Student balansi va direktor komissiyasi qaytarildi.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment cancellation failed', ['payment_id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', "To'lovni bekor qilishda xatolik: " . $e->getMessage());
        }
    }

    /**
     * AJAX: Return student balance, commission %, and course price for the form.
     */
    public function getStudentInfo(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'group_id'   => 'nullable|exists:groups,id',
        ]);

        $student     = Student::with(['user', 'director', 'activeGroups.course'])->findOrFail($request->student_id);
        $coursePrice = null;

        if ($request->filled('group_id')) {
            $group       = Group::with('course')->find($request->group_id);
            $coursePrice = $group?->course?->price;
        }

        return response()->json([
            'student'             => $student,
            'balance'             => $student->balance,
            'director_commission' => $student->director->commission_percentage ?? 0,
            'course_price'        => $coursePrice,
        ]);
    }

    /**
     * AJAX: Return current installment status so the UI can display
     * which installments are paid and what amount remains.
     */
    public function getInstallmentStatus(Request $request)
    {
        $request->validate([
            'student_id'    => 'required|exists:students,id',
            'group_id'      => 'required|exists:groups,id',
            'payment_month' => 'required|date',
        ]);

        $group       = Group::with('course')->findOrFail($request->group_id);
        $coursePrice = (float) ($group->course?->price ?? 0);
        $month       = Carbon::parse($request->payment_month);

        $existingPayments = Payment::where('payable_type', Student::class)
            ->where('payable_id', $request->student_id)
            ->where('group_id', $request->group_id)
            ->whereYear('payment_month', $month->year)
            ->whereMonth('payment_month', $month->month)
            ->where('is_installment', true)
            ->where('status', 'paid')
            ->orderBy('installment_number')
            ->get(['installment_number', 'amount', 'paid_at']);

        $paidTotal       = $existingPayments->sum('amount');
        $remaining       = max(0, $coursePrice - $paidTotal);
        $nextInstallment = ($existingPayments->max('installment_number') ?? 0) + 1;

        return response()->json([
            'course_price'     => $coursePrice,
            'paid_total'       => $paidTotal,
            'remaining'        => $remaining,
            'next_installment' => $nextInstallment,
            'is_fully_paid'    => $remaining <= 0,
            'installments'     => $existingPayments,
        ]);
    }

    // ==================== PRIVATE VALIDATION HELPERS ====================

    /**
     * Ensure that installment N is only accepted after installments 1..N-1 are paid.
     * Also rejects duplicate installment numbers.
     */
    private function validateInstallmentSequence(
        int    $studentId,
        int    $groupId,
        string $paymentMonth,
        int    $installmentNumber
    ): ?string {
        $month = Carbon::parse($paymentMonth);

        $paidNumbers = Payment::where('payable_type', Student::class)
            ->where('payable_id', $studentId)
            ->where('group_id', $groupId)
            ->whereYear('payment_month', $month->year)
            ->whereMonth('payment_month', $month->month)
            ->where('is_installment', true)
            ->where('status', 'paid')
            ->orderBy('installment_number')
            ->pluck('installment_number')
            ->toArray();

        if (in_array($installmentNumber, $paidNumbers)) {
            return "{$installmentNumber}-qism to'lov allaqachon amalga oshirilgan.";
        }

        for ($i = 1; $i < $installmentNumber; $i++) {
            if (!in_array($i, $paidNumbers)) {
                return "{$i}-qism to'lov hali amalga oshirilmagan. "
                    . "Iltimos avval {$i}-qism to'lovni amalga oshiring.";
            }
        }

        return null;
    }

    /**
     * Ensure adding this installment does not push the cumulative total above the course price.
     */
    private function validateInstallmentAmount(
        int    $studentId,
        int    $groupId,
        string $paymentMonth,
        int    $installmentNumber,
        float  $newAmount,
        float  $coursePrice
    ): ?string {
        $month = Carbon::parse($paymentMonth);

        $alreadyPaidTotal = (float) Payment::where('payable_type', Student::class)
            ->where('payable_id', $studentId)
            ->where('group_id', $groupId)
            ->whereYear('payment_month', $month->year)
            ->whereMonth('payment_month', $month->month)
            ->where('is_installment', true)
            ->where('status', 'paid')
            ->sum('amount');

        if ($alreadyPaidTotal + $newAmount > $coursePrice) {
            $remaining = $coursePrice - $alreadyPaidTotal;
            return "Qism to'lov summasi oshib ketdi. "
                . "Qolgan to'lov miqdori: {$this->formatMoney($remaining)} so'm "
                . "(Kurs narxi: {$this->formatMoney($coursePrice)} so'm, "
                . "Allaqachon to'langan: {$this->formatMoney($alreadyPaidTotal)} so'm).";
        }

        return null;
    }

    // ==================== PRIVATE TRANSACTION HELPERS ====================

    /**
     * Calculate gross, discounted net, and balance amounts.
     * The amount is clamped to coursePrice as a safety net.
     */
    private function calculatePaymentAmounts(float $amount, float $discountPercent, float $coursePrice): array
    {
        $amount         = min($amount, $coursePrice);
        $discountAmount = $discountPercent > 0 ? ($amount * $discountPercent) / 100 : 0;

        return [
            'gross_amount'      => $amount,
            'discount_percent'  => $discountPercent,
            'discount_amount'   => $discountAmount,
            'net_amount'        => $amount - $discountAmount,
            'amount_to_balance' => $amount, // full pre-discount amount credited to student
        ];
    }

    private function createPayment(
        array   $validated,
        Student $student,
        array   $amounts,
        ?int    $installmentNumber,
        ?int    $totalInstallments
    ): Payment {
        return Payment::create([
            'payable_type'       => Student::class,
            'payable_id'         => $student->id,
            'group_id'           => $validated['group_id'],
            'user_id'            => Auth::id(),
            'amount'             => $amounts['gross_amount'],
            'discount'           => $amounts['discount_percent'],
            'net_amount'         => $amounts['net_amount'],
            'payment_type'       => $validated['payment_type'],
            'status'             => 'paid',
            'payment_month'      => $validated['payment_month'],
            'is_installment'     => !empty($validated['is_installment']),
            'installment_number' => $installmentNumber,
            'total_installments' => $totalInstallments,
            'notes'              => $validated['notes'] ?? null,
            'paid_at'            => Carbon::now(),
        ]);
    }

    private function createStudentTransaction(Student $student, Payment $payment, float $amount): Transaction
    {
        $oldBalance  = $student->balance;
        $student->increment('balance', $amount);
        $newBalance  = $student->fresh()->balance;
        $description = "To'lov qabul qilindi ({$payment->payment_type})";

        if ($payment->is_installment) {
            $description .= " — {$payment->installment_number}-qism";
        }

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
            ->where('source_id', $payment->id)->where('type', 'payment')->first();

        if ($studentTx) {
            $student->decrement('balance', $studentTx->amount);
            $markAsCancelled
                ? $studentTx->update(['status' => 'cancelled', 'description' => $studentTx->description . ' [BEKOR QILINDI]'])
                : $studentTx->delete();
        }

        $directorTx = Transaction::where('source_type', Payment::class)
            ->where('source_id', $payment->id)->where('type', 'director_commission')->first();

        if ($directorTx && $directorTx->transactionable) {
            if ($directorTx->status === 'paid') {
                $directorTx->transactionable->decrement('balance', $directorTx->amount);
            }
            $markAsCancelled
                ? $directorTx->update(['status' => 'cancelled', 'description' => $directorTx->description . ' [BEKOR QILINDI]'])
                : $directorTx->delete();
        }
    }

    // ==================== PRIVATE FORMATTING HELPERS ====================

    private function buildUpdateNote(string $existingNote, float $oldAmount): string
    {
        $note = $existingNote ?: '';
        if ($note) $note .= "\n";
        $note .= "[Tahrirlandi: " . Carbon::now()->format('d.m.Y H:i')
            . " — " . Auth::user()->name
            . " | Eski summa: " . $this->formatMoney($oldAmount) . " so'm]";
        return $note;
    }

    private function buildUpdateSuccessMessage(array $oldAmounts, array $newAmounts, ?array $directorCommission): string
    {
        $diff     = $newAmounts['gross_amount'] - $oldAmounts['gross_amount'];
        $message  = "To'lov muvaffaqiyatli yangilandi!\n";
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

    private function successResponse(array $amounts, ?array $directorCommission)
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

        return redirect()->route('payments.index')->with('success', $message);
    }

    private function formatMoney(float $amount): string
    {
        return number_format($amount, 0, '.', ' ');
    }
}