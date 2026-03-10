<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

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

    public function store(StorePaymentRequest $request)
    {
        try {
            $result = $this->paymentService->store($request->validated());
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "To'lovni saqlashda xatolik: " . $e->getMessage());
        }

        $message = $this->paymentService->buildStoreSuccessMessage(
            $result['amounts'],
            $result['directorCommission']
        );

        return redirect()->route('payments.index')->with('success', $message);
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

        try {
            $this->paymentService->assertEditable($payment);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $students = Student::with('user', 'director')
            ->whereHas('user', fn($q) => $q->where('is_active', true))->get();

        $groups = Group::whereHas('groupStudents', fn($q) => $q->where('student_id', $payment->payable_id))
            ->with(['teacher.user', 'course'])->orderBy('name')->get();

        return view('Payment.edit', compact('payment', 'students', 'groups'));
    }

    public function update(UpdatePaymentRequest $request, string $id)
    {
        $payment = Payment::with(['payable', 'transactions'])->findOrFail($id);

        try {
            $result = $this->paymentService->update($payment, $request->validated());
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "To'lovni yangilashda xatolik: " . $e->getMessage());
        }

        if (!$result['financialChanged']) {
            return redirect()->route('payments.show', $payment->id)
                ->with('success', "To'lov ma'lumotlari yangilandi!");
        }

        $message = $this->paymentService->buildUpdateSuccessMessage(
            $result['oldAmounts'],
            $result['newAmounts'],
            $result['directorCommission']
        );

        return redirect()->route('payments.show', $payment->id)->with('success', $message);
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

        try {
            $this->paymentService->cancel($payment);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', "To'lovni bekor qilishda xatolik: " . $e->getMessage());
        }

        return redirect()->route('payments.index')
            ->with('success', "To'lov bekor qilindi! Student balansi va direktor komissiyasi qaytarildi.");
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
}