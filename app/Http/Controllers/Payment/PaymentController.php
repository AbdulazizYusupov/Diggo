<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
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
            'payments',
            'totalPayments',
            'totalCommissions',
            'pendingPayments',
            'monthlyPayments'
        ));
    }

    public function create(Request $request)
    {
        $students        = Student::with('user', 'director')->whereHas('user', fn($q) => $q->where('is_active', true))->get();
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
     *  1. Duplicate payment for same student + group + month is rejected.
     *  2. Amount must not exceed the group's course price.
     */
    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();
        $month     = Carbon::parse($validated['payment_month']);

        $payment = Payment::where('payable_type', Student::class)
            ->where('payable_id', $validated['student_id'])
            ->where('group_id', $validated['group_id'])
            ->whereYear('payment_month', $month->year)
            ->whereMonth('payment_month', $month->month)
            ->where('status', 'paid')
            ->first();

        if ($payment) {
            return back()->withInput()->with(
                'error',
                "Bu student uchun ushbu oyga to'lov allaqachon qabul qilingan. " .
                    "Sana: " . $payment->created_at->format('d.m.Y') .
                    " | Summa: " . number_format($payment->amount, 0, '.', ' ') . " so'm"
            );
        }

        $student = Student::with('director')->findOrFail($validated['student_id']);
        $group   = Group::with('course')->findOrFail($validated['group_id']);
        $course  = $group->course;

        if (!$course) {
            return back()->withInput()->with('error', "Ushbu guruhga biriktirilgan kurs topilmadi.");
        }

        $coursePrice = (float) $course->price;

        if ((float) $validated['amount'] > $coursePrice) {
            return back()->withInput()->with(
                'error',
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

            if ($student->user) {
                $student->user->notify(new PaymentApprovedNotification($payment));
            }

            return $this->successResponse($amounts, $directorCommission);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data'  => $validated,
            ]);
            return back()->withInput()->with('error', "To'lovni saqlashda xatolik: " . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $payment = Payment::with([
            'payable.user',
            'user',
            'transactions' => fn($q) => $q->whereIn('type', ['payment', 'director_commission'])->with('transactionable'),
        ])->findOrFail($id);

        return view('Payment.show', compact('payment'));
    }

    public function edit(string $id)
    {
        $payment = Payment::with('payable.user')->findOrFail($id);
        $user    = Auth::user();

        // Admin — har qanday to'lovni tahrirlaydi.
        // Boshqalar — faqat 48 soat ichida tahrirlaydi.
        if (!$user->hasRole('admin')) {
            if ($payment->paid_at && $payment->paid_at->diffInHours(Carbon::now()) > 48) {
                return back()->with('error', "48 soatdan oshgan to'lovni tahrirlash mumkin emas!");
            }
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
     */
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

        $data['message'] = number_format($payment->net_amount, 0, '.', ' ') . " so'mlik to'lovingiz qabul qilindi.";
        $data['amount'] = $payment->amount;
        $data['net_amount'] = $payment->net_amount;
        $data['discount'] = $payment->discount;

        $notification->update([
            'data' => $data,
            'read_at' => null
        ]);
    }
    public function update(UpdatePaymentRequest $request, string $id)
    {
        $payment   = Payment::with(['payable', 'transactions'])->findOrFail($id);
        $validated = $request->validated();
        $user      = Auth::user();

        // Admin — har qanday to'lovni yangilaydi; boshqalar — faqat 48 soat ichida
        if (!$user->hasRole('admin')) {
            if ($payment->paid_at && $payment->paid_at->diffInHours(Carbon::now()) > 48) {
                return back()->with('error', "48 soatdan oshgan to'lovni tahrirlash mumkin emas!");
            }
        }

        if ($payment->status === 'cancelled') {
            return back()->with('error', "Bekor qilingan to'lovni tahrirlash mumkin emas!");
        }

        // Kurs narxi tekshiruvi (store bilan bir xil)
        $group  = Group::with('course')->findOrFail($validated['group_id']);
        $course = $group->course;

        if (!$course) {
            return back()->withInput()->with('error', "Ushbu guruhga biriktirilgan kurs topilmadi.");
        }

        $coursePrice = (float) $course->price;

        if ((float) $validated['amount'] > $coursePrice) {
            return back()->withInput()->with(
                'error',
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
                // Faqat meta ma'lumotlar o'zgardi — tranzaksiyalarga ham, notificationga ham tegmaydi
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

            // Moliyaviy qiymatlar o'zgardi — eskisini qaytarib, yangisini yoz
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

            // Eski tranzaksiyalarni o'chiradi, balanslarni qaytaradi
            $this->reversePaymentTransactions($payment, $student);

            // Yangi tranzaksiyalar yaratadi, balanslarni yangilaydi
            $this->createStudentTransaction($student, $payment, $newAmounts['amount_to_balance']);
            $directorCommission = $this->processDirectorCommission($student, $payment, $newAmounts['net_amount']);

            DB::commit();

            // Yangi notification YARATILMAYDI — notifications jadvalidagi
            // mavjud yozuv yangi summa bilan update qilinadi.
            // Shunda student eskirgan "100 000 so'm" o'rniga "120 000 so'm" ko'radi.
            if ($student->user) {
                $this->updateExistingNotification($student->user, $payment->fresh());
            }

            return redirect()->route('payments.show', $payment->id)
                ->with('success', $this->buildUpdateSuccessMessage($oldAmounts, $newAmounts, $directorCommission));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment update failed', [
                'payment_id' => $id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
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
            Log::error('Payment cancellation failed', [
                'payment_id' => $id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
            return back()->with('error', "To'lovni bekor qilishda xatolik: " . $e->getMessage());
        }
    }

    /**
     * AJAX: Student balansi, komissiya % va kurs narxini qaytaradi.
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
