<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
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

        $totalCommissions = Transaction::whereIn('type', ['director_commission', 'teacher_commission'])
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

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $students = Student::with('user', 'director')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        $selectedStudent = null;
        if ($request->filled('student_id')) {
            $selectedStudent = Student::with('user', 'director')->find($request->student_id);
        }

        return view('Payment.create', compact('students', 'selectedStudent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
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
            // Load student with relationships
            $student = Student::with([
                'director',
                'activeGroups.teachers' => function ($query) {
                    $query->where('is_primary', true);
                }
            ])->findOrFail($validated['student_id']);

            // Calculate amounts
            $amounts = $this->calculatePaymentAmounts(
                $validated['amount'],
                $validated['discount'] ?? 0
            );

            // 1. Create payment record
            $payment = $this->createPayment($validated, $student, $amounts);

            // 2. Create student transaction (student balance update)
            $studentTransaction = $this->createStudentTransaction($student, $payment, $amounts['amount_to_balance']);

            // 3. Process director commission
            $directorCommission = $this->processDirectorCommission($student, $payment, $amounts['amount_to_balance']);

            // 4. Process teacher commissions
            $teacherCommissions = $this->processTeacherCommissions($student, $payment, $amounts['amount_to_balance']);

            DB::commit();

            return $this->successResponse($amounts, $directorCommission, $teacherCommissions);
            
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
            'gross_amount' => $amount,              // To'langan asl summa
            'discount_percent' => $discountPercent,  // Chegirma foizi
            'discount_amount' => $discountAmount,    // Chegirma summasi
            'net_amount' => $netAmount,              // Chegirmadan keyingi summa
            'amount_to_balance' => $amount,          // Student balansiga tushadigan summa (to'langan summa)
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
        
        // Student balansiga TO'LANGAN SUMMA tushadi (chegirmasiz)
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
     * Process director commission
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

        // Calculate commission from student's PAID amount
        $commissionAmount = ($baseAmount * $commissionPercent) / 100;

        // Update director balance
        $oldBalance = $director->balance;
        $director->increment('balance', $commissionAmount);
        $newBalance = $director->fresh()->balance;

        // Create director commission transaction
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
     * Process teacher commissions for student's active groups
     */
    private function processTeacherCommissions(Student $student, Payment $payment, float $baseAmount): array
    {
        $commissions = [];

        // Get all active groups where student is enrolled
        $activeGroups = $student->activeGroups()
            ->with(['teachers' => function ($query) {
                $query->where('is_primary', true);
            }])
            ->get();

        foreach ($activeGroups as $group) {
            // Get primary teacher
            $primaryTeacher = $group->teachers->first();

            if (!$primaryTeacher || !$primaryTeacher->teacher) {
                continue;
            }

            $teacher = $primaryTeacher->teacher;

            // Only for teachers with PERCENT salary type
            if (!$teacher->hasPercentSalary() || !$teacher->percent_per_student) {
                continue;
            }

            // Calculate teacher commission
            $teacherPercent = $teacher->percent_per_student;
            $teacherCommission = ($baseAmount * $teacherPercent) / 100;

            if ($teacherCommission <= 0) {
                continue;
            }

            // Create teacher commission transaction
            $this->createTeacherCommissionTransaction(
                $teacher,
                $payment,
                $student,
                $group,
                $teacherCommission,
                $teacherPercent,
                $baseAmount
            );

            $commissions[] = [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->user->full_name,
                'group_name' => $group->name,
                'percentage' => $teacherPercent,
                'amount' => $teacherCommission,
            ];
        }

        return $commissions;
    }

    /**
     * Create teacher commission transaction
     */
    private function createTeacherCommissionTransaction(
        $teacher,
        Payment $payment,
        Student $student,
        $group,
        float $amount,
        float $percent,
        float $baseAmount
    ): Transaction {
        // Teacher uchun balance ni hozircha update qilmaymiz
        // Chunki fixed salary teacherlar uchun oylik hisoblab to'laymiz
        $oldBalance = $teacher->balance ?? 0;

        return Transaction::create([
            'transactionable_type' => get_class($teacher),
            'transactionable_id' => $teacher->id,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
            'amount' => $amount,
            'balance_before' => $oldBalance,
            'balance_after' => $oldBalance, // Balance hali yangilanmagan
            'type' => 'teacher_commission',
            'percentage' => $percent,
            'base_amount' => $baseAmount,
            'description' => "O'qituvchi komissiyasi - {$group->name} ({$percent}%) - {$student->user->full_name}",
            'status' => 'pending', // Oylik to'lashda 'paid' qilamiz
            'created_by' => Auth::id(),
            'transaction_date' => Carbon::now(),
            'paid_at' => null,
        ]);
    }

    /**
     * Return success response with commission details
     */
    private function successResponse(array $amounts, ?array $directorCommission, array $teacherCommissions)
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

        if (!empty($teacherCommissions)) {
            $totalTeacherCommission = array_sum(array_column($teacherCommissions, 'amount'));
            $message .= "\n\nO'qituvchilar komissiyasi (pending): " . 
                number_format($totalTeacherCommission, 0, '.', ' ') . " so'm";

            foreach ($teacherCommissions as $tc) {
                $message .= "\n  • {$tc['teacher_name']} ({$tc['group_name']}): " .
                    number_format($tc['amount'], 0, '.', ' ') . " so'm ({$tc['percentage']}%)";
            }
        }

        return redirect()->route('payments.index')->with('success', $message);
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
                $query->whereIn('type', ['payment', 'director_commission', 'teacher_commission'])
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

        if ($payment->status === 'paid') {
            return back()->with('error', "Tasdiqlangan to'lovni tahrirlash mumkin emas!");
        }

        $students = Student::with('user', 'director')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        return view('Payment.edit', compact('payment', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'paid') {
            return back()->with('error', "Tasdiqlangan to'lovni o'zgartirib bo'lmaydi!");
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'payment_type' => 'required|in:cash,card,transfer,other',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Recalculate amounts
        $discountAmount = 0;
        if (!empty($validated['discount'])) {
            $discountAmount = ($validated['amount'] * $validated['discount']) / 100;
        }
        $netAmount = $validated['amount'] - $discountAmount;

        $payment->update([
            'amount' => $validated['amount'],
            'discount' => $validated['discount'] ?? 0,
            'net_amount' => $netAmount,
            'payment_type' => $validated['payment_type'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('payments.show', $payment->id)
            ->with('success', "To'lov ma'lumotlari yangilandi!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'paid') {
            return back()->with('error', "Tasdiqlangan to'lovni o'chirib bo'lmaydi!");
        }

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', "To'lov o'chirildi!");
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
}