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
        $totalPayments = Payment::where('status', 'paid')->sum('net_amount');
        
        $totalCommissions = Transaction::whereIn('type', ['director_commission', 'teacher_commission'])
            ->where('status', 'paid')
            ->sum('amount');
        
        $pendingPayments = Payment::where('status', 'pending')->count();
        
        $monthlyPayments = Payment::where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->sum('net_amount');

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
            // Load student with all necessary relationships
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

            // Create payment record (polymorphic)
            $payment = $this->createPayment($validated, $student, $amounts);

            // Update student balance
            $oldBalance = $student->balance;
            $newStudentBalance = $this->updateStudentBalance($student, $amounts['net']);

            // Create student transaction
            $this->createStudentTransaction($student, $payment, $amounts['net'], $oldBalance, $newStudentBalance);

            // Process director commission
            $directorCommission = $this->processDirectorCommission($student, $payment, $amounts['net']);

            // Process teacher commissions
            $teacherCommissions = $this->processTeacherCommissions($student, $payment, $amounts['net']);

            DB::commit();

            return $this->successResponse($amounts['net'], $directorCommission, $teacherCommissions);
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
            'gross' => $amount,
            'discount' => $discountAmount,
            'discount_percent' => $discountPercent,
            'net' => $netAmount,
        ];
    }

    /**
     * Create payment record (POLYMORPHIC)
     */
    private function createPayment(array $validated, Student $student, array $amounts): Payment
    {
        return Payment::create([
            'payable_type' => Student::class,
            'payable_id' => $student->id,
            'user_id' => Auth::id(),
            'director_id' => $student->director_id,
            'amount' => $amounts['gross'],
            'discount' => $amounts['discount_percent'],
            'net_amount' => $amounts['net'],
            'director_commission' => 0, // Will be updated later
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
     * Update student balance
     */
    private function updateStudentBalance(Student $student, float $amount): float
    {
        $student->increment('balance', $amount);
        return $student->fresh()->balance;
    }

    /**
     * Create student transaction record (POLYMORPHIC)
     */
    private function createStudentTransaction(
        Student $student, 
        Payment $payment, 
        float $amount, 
        float $balanceBefore,
        float $balanceAfter
    ): Transaction {
        return Transaction::create([
            'transactionable_type' => Student::class,
            'transactionable_id' => $student->id,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'type' => 'payment',
            'description' => "To'lov qabul qilindi ({$payment->payment_type})",
            'status' => 'paid',
            'created_by' => Auth::id(),
            'transaction_date' => Carbon::now(),
            'paid_at' => Carbon::now(),
        ]);
    }

    /**
     * Process director commission (POLYMORPHIC via Transaction)
     */
    private function processDirectorCommission(Student $student, Payment $payment, float $netAmount): ?array
    {
        if (!$student->director) {
            return null;
        }

        $director = $student->director;
        $commissionPercent = $director->commission_percentage ?? 0;

        if ($commissionPercent <= 0) {
            return null;
        }

        // Calculate commission
        $commissionAmount = ($netAmount * $commissionPercent) / 100;

        // Update director balance
        $oldBalance = $director->balance ?? 0;
        $director->increment('balance', $commissionAmount);
        $newDirectorBalance = $director->fresh()->balance;

        // Create director commission transaction (POLYMORPHIC)
        Transaction::create([
            'transactionable_type' => get_class($director), // Director model
            'transactionable_id' => $director->id,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
            'amount' => $commissionAmount,
            'balance_before' => $oldBalance,
            'balance_after' => $newDirectorBalance,
            'type' => 'director_commission',
            'percentage' => $commissionPercent,
            'base_amount' => $netAmount,
            'description' => "Direktor komissiyasi ({$commissionPercent}%) - {$student->user->full_name}",
            'status' => 'paid',
            'created_by' => Auth::id(),
            'transaction_date' => Carbon::now(),
            'paid_at' => Carbon::now(),
        ]);

        // Update payment record
        $payment->update(['director_commission' => $commissionAmount]);

        return [
            'director_id' => $director->id,
            'director_name' => $director->user->full_name,
            'percentage' => $commissionPercent,
            'amount' => $commissionAmount,
        ];
    }

    /**
     * Process teacher commissions for all student's active groups (POLYMORPHIC)
     */
    private function processTeacherCommissions(Student $student, Payment $payment, float $netAmount): array
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

            // Check if teacher uses percent salary type
            if (!$teacher->hasPercentSalary() || !$teacher->percent_per_student) {
                continue;
            }

            // Calculate teacher commission
            $teacherPercent = $teacher->percent_per_student;
            $teacherCommission = ($netAmount * $teacherPercent) / 100;

            if ($teacherCommission <= 0) {
                continue;
            }

            // Create teacher commission transaction (POLYMORPHIC)
            $this->createTeacherCommissionTransaction(
                $teacher,
                $payment,
                $student,
                $group,
                $teacherCommission,
                $teacherPercent,
                $netAmount
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
     * Create teacher commission transaction (POLYMORPHIC)
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
        // Update teacher balance (agar teacher modelda balance bo'lsa)
        $oldBalance = $teacher->balance ?? 0;
        // $teacher->increment('balance', $amount);
        // $newBalance = $teacher->fresh()->balance;

        // Create teacher commission transaction
        return Transaction::create([
            'transactionable_type' => get_class($teacher), // Teacher model
            'transactionable_id' => $teacher->id,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
            'amount' => $amount,
            'balance_before' => $oldBalance,
            'balance_after' => $oldBalance, // Agar teacher balance yangilanmasa
            'type' => 'teacher_commission',
            'percentage' => $percent,
            'base_amount' => $baseAmount,
            'description' => "O'qituvchi komissiyasi - {$group->name} ({$percent}%) - {$student->user->full_name}",
            'status' => 'pending', // yoki 'paid'
            'created_by' => Auth::id(),
            'transaction_date' => Carbon::now(),
            'paid_at' => null,
        ]);
    }

    /**
     * Return success response with commission details
     */
    private function successResponse(float $netAmount, ?array $directorCommission, array $teacherCommissions)
    {
        $message = "To'lov muvaffaqiyatli qabul qilindi! Summa: " . number_format($netAmount, 0, '.', ' ') . " so'm";

        if ($directorCommission) {
            $message .= "\n\nDirektor komissiyasi: " . number_format($directorCommission['amount'], 0, '.', ' ') . " so'm ({$directorCommission['percentage']}%)";
        }

        if (!empty($teacherCommissions)) {
            $totalTeacherCommission = array_sum(array_column($teacherCommissions, 'amount'));
            $message .= "\n\nO'qituvchilar komissiyasi: " . number_format($totalTeacherCommission, 0, '.', ' ') . " so'm";

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
                $query->whereIn('type', ['payment', 'director_commission', 'teacher_commission']);
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

    public function getStudentInfo(Request $request)
    {
        $student = Student::with(['user', 'director', 'groups.course'])
            ->findOrFail($request->student_id);

        return response()->json([
            'student' => $student,
            'balance' => $student->balance,
            'director_commission' => $student->director->commission_percentage ?? 0,
        ]);
    }
}