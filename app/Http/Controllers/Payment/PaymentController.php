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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['payable.user', 'user']);

        // Search filter
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

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment type filter
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('paid_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('paid_at', '<=', $request->date_to);
        }

        $payments = $query->latest('paid_at')->paginate(15);

        // Statistics
        $totalPayments = Payment::where('status', 'paid')->sum('amount');

        $totalCommissions = Transaction::where('type', 'director_commission')
            ->where('status', 'paid')
            ->sum('amount');

        $pendingPayments = Payment::where('status', 'pending')->count();

        $monthlyPayments = Payment::where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->sum('amount');

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
        // Fetch all active students with relationships
        $students = Student::with('user', 'director')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        // Initialize variables
        $selectedStudent = null;
        $groups = collect();

        // If student is selected, fetch their groups
        if ($request->filled('student_id')) {
            $selectedStudent = Student::with('user', 'director')->find($request->student_id);

            if ($selectedStudent) {
                // Get groups through GroupStudent pivot
                $groups = Group::whereHas('groupStudents', function ($q) use ($request) {
                    $q->where('student_id', $request->student_id);
                })
                    ->with(['teacher.user'])
                    ->orderBy('name')
                    ->get();
            }
        } else {
            // If no student selected, show all groups
            $groups = Group::with(['teacher.user'])
                ->orderBy('name')
                ->get();
        }

        return view('Payment.create', compact('students', 'selectedStudent', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'group_id' => 'required|exists:groups,id',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'payment_type' => 'required|in:cash,card,transfer,other',
            'payment_month' => 'required|date',
            'is_installment' => 'boolean',
            'installment_number' => 'nullable|integer|min:1',
            'total_installments' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Load student with director relationship only
            $student = Student::with('director')->findOrFail($validated['student_id']);
            // Calculate amounts
            $amounts = $this->calculatePaymentAmounts(
                $validated['amount'],
                $validated['discount'] ?? 0
            );

            // 1. Create payment record
            $payment = $this->createPayment($validated, $student, $amounts);

            // 2. Create student transaction (student balance update)
            $this->createStudentTransaction($student, $payment, $amounts['amount_to_balance']);

            // 3. Process ONLY director commission
            $directorCommission = $this->processDirectorCommission($student, $payment, $amounts['amount_to_balance']);

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
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->with('error', "To'lovni saqlashda xatolik: " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with([
            'payable.user',
            'user',
            'transactions' => function ($query) {
                $query->whereIn('type', ['payment', 'director_commission'])
                    ->with('transactionable');
            }
        ])->findOrFail($id);

        return view('Payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Payment::with('payable.user')->findOrFail($id);

        // Admin rolini tekshirish
        if (!Auth::user()->hasRole('admin')) {
            return back()->with('error', "Faqat admin to'lovni tahrirlashi mumkin!");
        }

        // Cancelled to'lovni tahrirlash mumkin emas
        if ($payment->status === 'cancelled') {
            return back()->with('error', "Bekor qilingan to'lovni tahrirlash mumkin emas!");
        }

        $students = Student::with('user', 'director')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->get();
        $groups = Group::whereHas('groupStudents', function ($q) use ($payment) {
            $q->where('student_id', $payment->payable_id);
        })
            ->with(['teacher.user'])
            ->orderBy('name')
            ->get();

        return view('Payment.edit', compact('payment', 'students', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::with(['payable', 'transactions'])->findOrFail($id);

        // Admin rolini tekshirish
        if (!Auth::user()->hasRole('admin')) {
            return back()->with('error', "Faqat admin to'lovni tahrirlashi mumkin!");
        }

        // Faqat paid to'lovlarni tahrirlash mumkin (cancelled emas)
        if ($payment->status === 'cancelled') {
            return back()->with('error', "Bekor qilingan to'lovni tahrirlash mumkin emas!");
        }

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'payment_type' => 'required|in:cash,card,transfer,other',
            'payment_month' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $student = $payment->payable;

            // Eski va yangi summalarni hisoblash
            $oldAmounts = [
                'gross_amount' => $payment->amount,
                'net_amount' => $payment->net_amount,
                'discount_percent' => $payment->discount,
            ];

            $newAmounts = $this->calculatePaymentAmounts(
                $validated['amount'],
                $validated['discount'] ?? 0
            );

            // Summa o'zgarmagan bo'lsa, faqat ma'lumotlarni yangilash
            if (
                $oldAmounts['gross_amount'] == $newAmounts['gross_amount']
                && $oldAmounts['discount_percent'] == $newAmounts['discount_percent']
            ) {
                $payment->update([
                    'group_id' => $validated['group_id'],
                    'payment_type' => $validated['payment_type'],
                    'payment_month' => $validated['payment_month'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                DB::commit();

                return redirect()->route('payments.show', $payment->id)
                    ->with('success', "To'lov ma'lumotlari yangilandi!");
            }

            // Summa yoki chegirma o'zgardi - balanslar va komissiyalarni qayta hisoblash
            // 1. Payment ma'lumotlarini yangilash
            $payment->update([
                'amount' => $newAmounts['gross_amount'],
                'discount' => $newAmounts['discount_percent'],
                'net_amount' => $newAmounts['net_amount'],
                'group_id' => $validated['group_id'],
                'payment_type' => $validated['payment_type'],
                'payment_month' => $validated['payment_month'],
                'notes' => $this->buildUpdateNote(
                    $validated['notes'] ?? '',
                    $oldAmounts['gross_amount']
                ),
            ]);

            // 2. Reload student with director
            $student = Student::with('director')->findOrFail($student->id);

            // 3. Barcha eski transactionlarni reverse qilish
            $this->reversePaymentTransactions($payment, $student);

            // 4. Yangi transactionlarni yaratish
            $this->createStudentTransaction($student, $payment, $newAmounts['amount_to_balance']);
            $directorCommission = $this->processDirectorCommission($student, $payment, $newAmounts['amount_to_balance']);

            DB::commit();

            return redirect()->route('payments.show', $payment->id)
                ->with('success', $this->buildUpdateSuccessMessage($oldAmounts, $newAmounts, $directorCommission));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment update failed', [
                'payment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', "To'lovni yangilashda xatolik: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
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

        return redirect()->route('payments.index')
            ->with('success', "To'lov o'chirildi!");
    }

    /**
     * Cancel a payment and reverse all transactions
     */
    public function cancel(string $id)
    {
        $payment = Payment::with(['payable', 'transactions'])->findOrFail($id);

        if ($payment->status === 'cancelled') {
            return back()->with('error', "To'lov allaqachon bekor qilingan!");
        }

        // Faqat bugungi va kechagi to'lovlarni bekor qilish mumkin
        if ($payment->paid_at && $payment->paid_at->diffInHours(Carbon::now()) > 48) {
            return back()->with('error', "48 soatdan oshgan to'lovni bekor qilib bo'lmaydi!");
        }

        DB::beginTransaction();
        try {
            $student = $payment->payable;

            // Barcha transactionlarni reverse qilish va cancel qilish
            $this->reversePaymentTransactions($payment, $student, true);

            // Paymentni bekor qilish
            $payment->update([
                'status' => 'cancelled',
                'notes' => ($payment->notes ? $payment->notes . "\n" : '') .
                    "[Bekor qilindi: " . Carbon::now()->format('d.m.Y H:i') . " - " . Auth::user()->name . "]"
            ]);

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', "To'lov bekor qilindi! Student balansi va direktor komissiyasi qaytarildi.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment cancellation failed', [
                'payment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', "To'lovni bekor qilishda xatolik: " . $e->getMessage());
        }
    }

    /**
     * Get student info for payment form
     */
    public function getStudentInfo(Request $request)
    {
        $student = Student::with(['user', 'director', 'activeGroups.course'])
            ->findOrFail($request->student_id);
        return response()->json([
            'student' => $student,
            'balance' => $student->balance,
            'director_commission' => $student->director->commission_percentage ?? 0,
        ]);
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Calculate payment amounts with discount
     */
    private function calculatePaymentAmounts(float $amount, float $discountPercent): array
    {
        $discountAmount = 0;
        if ($discountPercent > 0) {
            $discountAmount = ($amount * $discountPercent) / 100;
        }
        $netAmount = $amount - $discountAmount;

        return [
            'gross_amount' => $amount,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'net_amount' => $netAmount,
            'amount_to_balance' => $amount, // To'liq summa student balansiga
        ];
    }

    /**
     * Create payment record
     */
    private function createPayment(array $validated, Student $student, array $amounts): Payment
    {
        return Payment::create([
            'payable_type' => Student::class,
            'payable_id' => $student->id,
            'group_id' => $validated['group_id'],
            'user_id' => Auth::id(),
            'amount' => $amounts['gross_amount'],
            'discount' => $amounts['discount_percent'],
            'net_amount' => $amounts['net_amount'],
            'payment_type' => $validated['payment_type'],
            'status' => 'paid',
            'payment_month' => $validated['payment_month'],
            'is_installment' => $validated['is_installment'] ?? false,
            'installment_number' => $validated['installment_number'] ?? null,
            'total_installments' => $validated['total_installments'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'paid_at' => Carbon::now(),
        ]);
    }

    /**
     * Create student transaction (update student balance with FULL AMOUNT)
     */
    private function createStudentTransaction(Student $student, Payment $payment, float $amount): Transaction
    {
        $oldBalance = $student->balance;
        $student->increment('balance', $amount);
        $newBalance = $student->fresh()->balance;

        return Transaction::create([
            'transactionable_type' => Student::class,
            'transactionable_id' => $student->id,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
            'amount' => $amount,
            'balance_before' => $oldBalance,
            'balance_after' => $newBalance,
            'type' => 'payment',
            'description' => "To'lov qabul qilindi ({$payment->payment_type})",
            'status' => 'paid',
            'created_by' => Auth::id(),
            'transaction_date' => Carbon::now(),
            'paid_at' => Carbon::now(),
        ]);
    }

    /**
     * Process ONLY director commission
     */
    private function processDirectorCommission(Student $student, Payment $payment, float $baseAmount): ?array
    {
        if (!$student->director) {
            return null;
        }

        $director = $student->director;
        $commissionPercent = $director->commission_percentage ?? 0;

        if ($commissionPercent <= 0) {
            return null;
        }

        $commissionAmount = ($baseAmount * $commissionPercent) / 100;

        $oldBalance = $director->balance;
        $director->increment('balance', $commissionAmount);
        $newBalance = $director->fresh()->balance;

        Transaction::create([
            'transactionable_type' => get_class($director),
            'transactionable_id' => $director->id,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
            'amount' => $commissionAmount,
            'balance_before' => $oldBalance,
            'balance_after' => $newBalance,
            'type' => 'director_commission',
            'percentage' => $commissionPercent,
            'base_amount' => $baseAmount,
            'description' => "Direktor komissiyasi ({$commissionPercent}%) - {$student->user->full_name}",
            'status' => 'paid',
            'created_by' => Auth::id(),
            'transaction_date' => Carbon::now(),
            'paid_at' => Carbon::now(),
        ]);

        return [
            'director_id' => $director->id,
            'director_name' => $director->user->full_name,
            'percentage' => $commissionPercent,
            'amount' => $commissionAmount,
        ];
    }

    /**
     * Reverse all transactions related to a payment (ONLY student and director)
     */
    private function reversePaymentTransactions(Payment $payment, Student $student, bool $markAsCancelled = false)
    {
        // 1. Student balansini kamaytirish
        $studentTransaction = Transaction::where('source_type', Payment::class)
            ->where('source_id', $payment->id)
            ->where('type', 'payment')
            ->first();

        if ($studentTransaction) {
            $student->decrement('balance', $studentTransaction->amount);

            if ($markAsCancelled) {
                $studentTransaction->update([
                    'status' => 'cancelled',
                    'description' => $studentTransaction->description . ' [BEKOR QILINDI]'
                ]);
            } else {
                $studentTransaction->delete();
            }
        }

        // 2. ONLY Director komissiyasini qaytarish
        $directorTransaction = Transaction::where('source_type', Payment::class)
            ->where('source_id', $payment->id)
            ->where('type', 'director_commission')
            ->first();

        if ($directorTransaction && $directorTransaction->transactionable) {
            if ($directorTransaction->status === 'paid') {
                $directorTransaction->transactionable->decrement('balance', $directorTransaction->amount);
            }

            if ($markAsCancelled) {
                $directorTransaction->update([
                    'status' => 'cancelled',
                    'description' => $directorTransaction->description . ' [BEKOR QILINDI]'
                ]);
            } else {
                $directorTransaction->delete();
            }
        }
    }

    /**
     * Build update note with edit history
     */
    private function buildUpdateNote(string $existingNote, float $oldAmount): string
    {
        $note = $existingNote;
        if ($note) {
            $note .= "\n";
        }
        $note .= "[Tahrirlandi: " . Carbon::now()->format('d.m.Y H:i') . " - " . Auth::user()->name;
        $note .= " | Eski summa: " . number_format($oldAmount, 0) . " so'm]";

        return $note;
    }

    /**
     * Build success message for update (without teacher commissions)
     */
    private function buildUpdateSuccessMessage(array $oldAmounts, array $newAmounts, ?array $directorCommission): string
    {
        $message = "To'lov muvaffaqiyatli yangilandi!\n";
        $message .= "Eski summa: " . number_format($oldAmounts['gross_amount'], 0, '.', ' ') . " so'm\n";
        $message .= "Yangi summa: " . number_format($newAmounts['gross_amount'], 0, '.', ' ') . " so'm\n";

        $difference = $newAmounts['gross_amount'] - $oldAmounts['gross_amount'];
        $message .= "Farq: " . ($difference > 0 ? '+' : '') . number_format($difference, 0, '.', ' ') . " so'm";

        if ($directorCommission) {
            $message .= "\n\nDirektor komissiyasi yangilandi: " .
                number_format($directorCommission['amount'], 0, '.', ' ') .
                " so'm ({$directorCommission['percentage']}%)";
        }

        return $message;
    }

    /**
     * Return success response with commission details (without teacher commissions)
     */
    private function successResponse(array $amounts, ?array $directorCommission)
    {
        $message = "To'lov muvaffaqiyatli qabul qilindi!\n";
        $message .= "To'langan: " . number_format($amounts['gross_amount'], 0, '.', ' ') . " so'm";

        if ($amounts['discount_amount'] > 0) {
            $message .= "\nChegirma ({$amounts['discount_percent']}%): " . number_format($amounts['discount_amount'], 0, '.', ' ') . " so'm";
            $message .= "\nJami: " . number_format($amounts['net_amount'], 0, '.', ' ') . " so'm";
        }

        $message .= "\n\nStudent balansi: +" . number_format($amounts['amount_to_balance'], 0, '.', ' ') . " so'm";

        if ($directorCommission) {
            $message .= "\n\nDirektor komissiyasi: " .
                number_format($directorCommission['amount'], 0, '.', ' ') .
                " so'm ({$directorCommission['percentage']}%)";
        }

        return redirect()->route('payments.index')->with('success', $message);
    }
}
