<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class PaymentMatrixController extends Controller
{
    private const PER_PAGE = 50;
 
    private const MONTHS = [
        1  => 'Yanvar',  2  => 'Fevral',  3  => 'Mart',
        4  => 'Aprel',   5  => 'May',      6  => 'Iyun',
        7  => 'Iyul',    8  => 'Avgust',   9  => 'Sentabr',
        10 => 'Oktabr',  11 => 'Noyabr',  12 => 'Dekabr',
    ];
 
 
    public function matrix(Request $request)
    {
        $year    = (int) $request->get('year', Carbon::now()->year);
        $groupId = $request->get('group_id');
        $perPage = (int) $request->get('per_page',self::PER_PAGE);
        $now     = CarbonImmutable::now();
 
      
        $studentQuery = Student::query()
            ->select([
                'students.id',
                'students.user_id',
                'students.balance',
                'students.parent_phone',
            ])
            ->with([
                'user:id,name,surname,middle_name,phone,is_active',
 
                'payments' => function ($q) use ($year) {
                    $q->select([
                            'id',
                            'payable_id',
                            'payable_type',
                            'net_amount',
                            'discount',
                            'status',
                            'payment_type',
                            'payment_month',
                            'paid_at',
                            'remaining_debt',
                        ])
                        ->whereYear('payment_month', $year)
                        ->where('status', '!=', 'cancelled')
                        ->orderBy('payment_month');
                },
            ])
            ->whereHas('user', fn($q) => $q->where('is_active', true));
 
        // ── 2. FILTERS ───────────────────────────────────────
        if ($groupId) {
            // whereHas o'rniga join ishlatamiz — COUNT(*) subquery'dan tezroq.
            $studentQuery->join('group_students', 'group_students.student_id', '=', 'students.id')
                         ->where('group_students.group_id', $groupId)
                         ->distinct(); // join tufayli takroriy qatorlarni oldini olamiz
        }
 
        // ── 3. PAGINATE ──────────────────────────────────────
        // withQueryString() — sahifalash havolalarida year, group_id, per_page saqlanadi.
        $paginator = $studentQuery
            ->orderBy('students.id')
            ->paginate($perPage)
            ->withQueryString();
 
        // ── 4. TRANSFORM — N+1 YO'Q, YAGONA LOOP ────────────
        // getCollection() → joriy sahifadagi Eloquent collectionni qaytaradi.
        // transform() → uni in-place o'zgartiradi, yangi collection yaratmaydi.
        $currentMonth = $now->month;
        $currentYear  = $now->year;
 
        $paginator->getCollection()->transform(
            fn(Student $student) => $this->attachMonthlyBreakdown($student, $year, $currentMonth, $currentYear)
        );
 
        // ── 5. SIDEBAR DATA ──────────────────────────────────
        // Guruhlar ro'yxati: faqat id va name, tartiblangan.
        $groups         = Group::orderBy('name')->get(['id', 'name']);
        $availableYears = range($currentYear - 2, $currentYear + 1);
 
        return view('Payment.matrix', [
            'paginator'      => $paginator,        // Sahifalash + transform qilingan o'quvchilar
            'months'         => self::MONTHS,
            'year'           => $year,
            'groupId'        => $groupId,
            'groups'         => $groups,
            'currentMonth'   => $currentMonth,
            'availableYears' => $availableYears,
            'perPage'        => $perPage,
        ]);
    }

    /**
     * Export Matrix to Excel
     */
    public function export(Request $request)
    {
        $year    = (int) $request->get('year', Carbon::now()->year);
        $groupId = $request->get('group_id');
        $now     = CarbonImmutable::now();
        $currentMonth = $now->month;
        $currentYear  = $now->year;

        $studentQuery = Student::query()
            ->select([
                'students.id',
                'students.user_id',
                'students.balance',
            ])
            ->with([
                'user:id,name,surname,middle_name',
                'payments' => function ($q) use ($year) {
                    $q->select(['id', 'payable_id', 'payable_type', 'net_amount', 'status', 'payment_month'])
                        ->whereYear('payment_month', $year)
                        ->where('status', '!=', 'cancelled');
                },
            ])
            ->whereHas('user', fn($q) => $q->where('is_active', true));

        if ($groupId) {
            $studentQuery->join('group_students', 'group_students.student_id', '=', 'students.id')
                         ->where('group_students.group_id', $groupId)
                         ->distinct();
        }

        $students = $studentQuery->orderBy('students.id')->get();

        $exportData = $students->map(function ($student) use ($year, $currentMonth, $currentYear) {
            $student = $this->attachMonthlyBreakdown($student, $year, $currentMonth, $currentYear);
            
            $row = [
                'F.I.SH' => $student->user->full_name,
                'Balans' => $student->balance,
            ];

            foreach (self::MONTHS as $m => $monthName) {
                $breakdown = $student->month_breakdown[$m];
                $status = $breakdown['status'];
                
                $label = match($status) {
                    'paid'    => "To'langan (" . number_format($breakdown['amount'], 0, '.', ' ') . ")",
                    'partial' => "Qisman (" . number_format($breakdown['amount'], 0, '.', ' ') . ")",
                    'unpaid'  => "To'lanmagan",
                    'future'  => "-",
                    default   => "-",
                };

                $row[$monthName] = $label;
            }

            $row['Jami to\'langan'] = number_format($student->total_paid_year, 0, '.', ' ');
            $row['Qarzdor oylar'] = $student->total_debt_count;

            return $row;
        });

        $filePath = public_path('export/' . time() . '_payment_matrix.xlsx');
        if (!file_exists(public_path('export'))) {
            mkdir(public_path('export'), 0777, true);
        }

        return (new FastExcel($exportData))->download('payment_matrix_' . $year . '.xlsx');
    }
 
    // =========================================================
    //  STUDENT CARD — Bitta o'quvchining moliyaviy kartasi
    // =========================================================
 
    public function studentCard(Request $request, string $studentId)
    {
        $student = Student::select(['id', 'user_id', 'balance', 'parent_name', 'parent_phone'])
            ->with([
                'user:id,name,surname,middle_name,phone',
                'activeGroups:id,name',
            ])
            ->findOrFail($studentId);
 
        $year = (int) $request->get('year', Carbon::now()->year);
 
        // Bitta o'quvchi uchun to'lovlar — pagination shart emas
        $payments = Payment::select([
                'id', 'payable_id', 'payable_type',
                'net_amount', 'discount', 'status', 'payment_type',
                'payment_month', 'paid_at', 'remaining_debt', 'user_id',
            ])
            ->where('payable_type', Student::class)
            ->where('payable_id', $studentId)
            ->whereYear('payment_month', $year)
            ->where('status', '!=', 'cancelled')
            ->orderBy('payment_month')
            ->get();
 
        // groupBy — filter() loopini olib tashlaydi
        // key = oy raqami (int), value = Collection<Payment>
        $paymentsByMonth = $payments->groupBy(
            fn($p) => (int) Carbon::parse($p->payment_month)->format('n')
        );
 
        $now          = CarbonImmutable::now();
        $currentMonth = $now->month;
        $currentYear  = $now->year;
        $breakdown    = [];
        $totalPaid    = 0;
        $debtMonths   = 0;
 
        foreach (range(1, 12) as $m) {
            // Kelajak oy? (current year + future month OR future year entirely)
            $isFuture = ($year > $currentYear)
                || ($year === $currentYear && $m > $currentMonth);
 
            if ($isFuture) {
                $breakdown[$m] = ['status' => 'future', 'payments' => collect()];
                continue;
            }
 
            $monthPayments = $paymentsByMonth->get($m, collect());
 
            if ($monthPayments->isEmpty()) {
                $breakdown[$m] = ['status' => 'unpaid', 'payments' => collect()];
                $debtMonths++;
                continue;
            }
 
            $hasPaid    = $monthPayments->contains('status', 'paid');
            $hasPartial = $monthPayments->contains('status', 'partial');
            $amount     = $monthPayments->sum('net_amount');
 
            $status = match(true) {
                $hasPaid    => 'paid',
                $hasPartial => 'partial',
                default     => 'unpaid',
            };
 
            if ($status !== 'unpaid') {
                $totalPaid += $amount;
            }
 
            $breakdown[$m] = [
                'status'   => $status,
                'payments' => $monthPayments,
                'amount'   => $amount,
            ];
        }
 
        $lastPayment    = $payments->where('status', 'paid')->sortByDesc('paid_at')->first();
        $availableYears = range($currentYear - 2, $currentYear + 1);
 
        return view('Payment.student-card', compact(
            'student', 'breakdown', 'year',
            'totalPaid', 'debtMonths', 'lastPayment', 'availableYears'
        ) + ['months' => self::MONTHS]);
    }
 
    // =========================================================
    //  PRIVATE HELPERS
    // =========================================================
 
    /**
     * Bitta o'quvchiga 12 oylik payment breakdown ni biriktiradi.
     * matrix() ichidagi transform() callback'i uchun.
     *
     * Nima uchun alohida method?
     *  - transform() closure'ni toza saqlaydi
     *  - Kelajakda Unit test yozish osonlashadi
     *  - Mantiq bir joyda — studentCard() ham foydalanishi mumkin
     */
    private function attachMonthlyBreakdown(
        Student $student,
        int $year,
        int $currentMonth,
        int $currentYear
    ): Student {
        // Eager-load qilingan payments ni oyga guruhlash
        // Carbon::parse bir marta ishlaydi — loop ichida qayta parse yo'q
        $paymentsByMonth = $student->payments->groupBy(
            fn($p) => (int) Carbon::parse($p->payment_month)->format('n')
        );
 
        $monthBreakdown = [];
        $paidMonths     = 0;
        $totalPaid      = 0.0;
        $debtCount      = 0;
 
        foreach (range(1, 12) as $m) {
            $isFuture = ($year > $currentYear)
                || ($year === $currentYear && $m > $currentMonth);
 
            if ($isFuture) {
                $monthBreakdown[$m] = ['status' => 'future'];
                continue;
            }
 
            $monthPayments = $paymentsByMonth->get($m, collect());
 
            if ($monthPayments->isEmpty()) {
                $monthBreakdown[$m] = ['status' => 'unpaid'];
                $debtCount++;
                continue;
            }
 
            $hasPaid    = $monthPayments->contains('status', 'paid');
            $hasPartial = $monthPayments->contains('status', 'partial');
            $amount     = $monthPayments->sum('net_amount');
 
            $status = match(true) {
                $hasPaid    => 'paid',
                $hasPartial => 'partial',
                default     => 'unpaid',
            };
 
            if ($status !== 'unpaid') {
                $totalPaid += $amount;
                $paidMonths++;
            } else {
                $debtCount++;
            }
 
            $monthBreakdown[$m] = [
                'status' => $status,
                'amount' => $amount,
                // Matrix hujayralarida to'lov ID'lari kerak emas — faqat show uchun
                // Agar tooltip uchun kerak bo'lsa quyidagi qatorni yoching:
                // 'payment_ids' => $monthPayments->pluck('id'),
            ];
        }
 
        // Student obyektiga virtual atributlar biriktirish
        // (DB'da ustun yo'q, faqat view uchun)
        $student->month_breakdown   = $monthBreakdown;
        $student->paid_months_count = $paidMonths;
        $student->total_paid_year   = $totalPaid;
        $student->total_debt_count  = $debtCount; // Muhim: surunkali qarzdorlarni aniqlash uchun
        unset($student->payments);                 // RAM bo'shatish — view'da endi shart emas
 
        return $student;
    }
}
