<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\DirectorCommission;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['student.user', 'director.user', 'recordedBy']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
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
        $totalPayments = Payment::where('status', 'completed')->sum('net_amount');
        $totalCommissions = DirectorCommission::where('status', 'paid')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();
        $monthlyPayments = Payment::where('status', 'completed')
            ->whereMonth('paid_at', now()->month)
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
            $student = Student::with('director')->findOrFail($validated['student_id']);

            // Calculate net amount
            $discountAmount = 0;
            if (!empty($validated['discount'])) {
                $discountAmount = ($validated['amount'] * $validated['discount']) / 100;
            }
            $netAmount = $validated['amount'] - $discountAmount;

            // Calculate director commission
            $commissionPercentage = $student->director->commission_percentage ?? 0;
            $commissionAmount = ($netAmount * $commissionPercentage) / 100;

            // Create payment
            $payment = Payment::create([
                'student_id' => $validated['student_id'],
                'user_id' => Auth::id(),
                'amount' => $validated['amount'],
                'discount' => $validated['discount'] ?? 0,
                'net_amount' => $netAmount,
                'director_commission' => $commissionAmount,
                'director_id' => $student->director_id,
                'payment_type' => $validated['payment_type'],
                'status' => 'paid',
                'payment_month' => $validated['payment_month'],
                'is_installment' => $validated['is_installment'] ?? false,
                'installment_number' => $validated['installment_number'] ?? null,
                'total_installments' => $validated['total_installments'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'paid_at' => now(),
            ]);

            // Update student balance
            $student->increment('balance', $netAmount);
            $newBalance = $student->fresh()->balance;

            // Create transaction for student payment
            Transaction::create([
                'student_id' => $student->id,
                'amount' => $netAmount,
                'type' => 'deposit',
                'description' => "To'lov qabul qilindi ({$validated['payment_type']})",
                'transactionable_type' => Payment::class,
                'transactionable_id' => $payment->id,
                'balance_after' => $newBalance,
                'created_by' => Auth::id(),
                'transaction_date' => now(),
            ]);

            // Update director balance and create commission
            if ($commissionAmount > 0 && $student->director) {
                $director = $student->director;
                $director->increment('balance', $commissionAmount);

                // Create director commission record
                $commission = DirectorCommission::create([
                    'director_id' => $director->id,
                    'payment_id' => $payment->id,
                    'school_id' => $student->school_id,
                    'amount' => $commissionAmount,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Create transaction for director commission
                Transaction::create([
                    'student_id' => $student->id,
                    'amount' => $commissionAmount,
                    'type' => 'commission',
                    'description' => "Direktor komissiyasi ({$commissionPercentage}%)",
                    'transactionable_type' => DirectorCommission::class,
                    'transactionable_id' => $commission->id,
                    'balance_after' => $newBalance - $commissionAmount,
                    'created_by' => Auth::id(),
                    'transaction_date' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', "To'lov muvaffaqiyatli qabul qilindi! Summa: " . number_format($netAmount, 0, '.', ' ') . " so'm");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', "To'lovni saqlashda xatolik: " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with([
            'student.user',
            'director.user',
            'recordedBy',
            'transaction',
            'commission'
        ])->findOrFail($id);

        return view('Payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Payment::with('student.user', 'director')->findOrFail($id);

        if ($payment->status === 'completed') {
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

        if ($payment->status === 'completed') {
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

        if ($payment->status === 'completed') {
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
