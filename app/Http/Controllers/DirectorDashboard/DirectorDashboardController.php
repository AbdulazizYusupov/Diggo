<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\DirectorDashboard\DirectorBaseController;
use App\Models\Attendance;
use App\Models\GroupStudent;
use App\Models\Payment;
use App\Models\School;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectorDashboardController extends DirectorBaseController
{
    public function index()
    {
        $director = $this->director()->load(['school', 'user']);

        // Asosiy statistika
        $stats = $this->getStatistics($director);

        // Oylik komissiya tarixi (oxirgi 6 oy)
        $monthlyCommissions = $this->getMonthlyCommissions($director);

        // So'nggi to'lovlar
        $recentPayments = $this->getRecentPayments($director);

        // O'quvchilar statistikasi
        $studentStats = $this->getStudentStatistics($director);

        // Eng faol guruhlar
        $topGroups = $this->getTopGroups($director);

        // Davomat statistikasi
        $attendanceStats = $this->getAttendanceStatistics($director);

        return view('Panel.Director.index', compact(
            'director',
            'stats',
            'monthlyCommissions',
            'recentPayments',
            'studentStats',
            'topGroups',
            'attendanceStats'
        ));
    }

    private function getStatistics($director)
    {
        $schoolId = $director->school_id;

        // // Jami o'quvchilar soni
        // $totalStudents = Student::where('school_id', $schoolId)
        //     ->where('director_id', $director->id)
        //     ->count();

        // Faol o'quvchilar
        $totalStudents = Student::where('school_id', $schoolId)
            ->where('director_id', $director->id)
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->count();

        // Shu oylik komissiya (Transaction orqali)
        $thisMonthCommission = Transaction::where('transactionable_type', Director::class)
            ->where('transactionable_id', $director->id)
            ->where('type', 'director_commission')
            ->where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');

        // Kutilayotgan komissiya (Transaction orqali)
        $pendingCommission = Transaction::where('transactionable_type', Director::class)
            ->where('transactionable_id', $director->id)
            ->where('type', 'director_commission')
            ->where('status', 'pending')
            ->sum('amount');

        // Jami to'lovlar (shu oyda)
        $thisMonthPayments = Payment::where('payable_type', Student::class)
            ->whereHasMorph('payable', [Student::class], function ($query) use ($schoolId, $director) {
                $query->where('school_id', $schoolId)
                    ->where('director_id', $director->id);
            })
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->where('status', 'paid')
            ->sum('amount');

        // Jami komissiya (barcha vaqt)
        $totalCommissionEarned = Transaction::where('transactionable_type', Director::class)
            ->where('transactionable_id', $director->id)
            ->where('type', 'director_commission')
            ->where('status', 'paid')
            ->sum('amount');

        // Yangi o'quvchilar (shu oyda)
        $newStudentsThisMonth = Student::where('school_id', $schoolId)
            ->where('director_id', $director->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // O'rtacha davomat foizi (shu oyda)
        $attendanceRate  = Attendance::whereHas('student', function ($query) use ($schoolId, $director) {
            $query->where('school_id', $schoolId)
                ->where('director_id', $director->id);
        })
            ->whereHas('lesson', function ($query) {
                $query->whereMonth('lesson_date', Carbon::now()->month)
                    ->whereYear('lesson_date', Carbon::now()->year);
            })
            ->whereIn('status', ['present', 'late'])
            ->avg(DB::raw('CASE WHEN status = "present" THEN 100 ELSE 0 END'));

        return [
            'total_students' => $totalStudents,
            // 'active_students' => $activeStudents,
            // 'inactive_students' => $totalStudents - $activeStudents,
            'this_month_commission' => $thisMonthCommission,
            'pending_commission' => $pendingCommission,
            'current_balance' => $director->balance,
            'this_month_payments' => $thisMonthPayments,
            'total_commission_earned' => $totalCommissionEarned,
            'new_students_this_month' => $newStudentsThisMonth,
            'attendance_rate' => round($attendanceRate ?? 0, 1),
            'commission_percentage' => $director->commission_percentage,
        ];
    }

    private function getMonthlyCommissions($director)
    {
        return Transaction::where('transactionable_type', Director::class)
            ->where('transactionable_id', $director->id)
            ->where('type', 'director_commission')
            ->where('status', 'paid')
            ->where('paid_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) {
                $item->month_name = Carbon::createFromFormat('Y-m', $item->month)->locale('uz')->format('F Y');
                return $item;
            });
    }

    private function getRecentPayments($director)
    {
        return Payment::with(['payable.user'])
            ->where('payable_type', Student::class)
            ->whereHasMorph('payable', [Student::class], function ($query) use ($director) {
                $query->where('school_id', $director->school_id)
                    ->where('director_id', $director->id);
            })
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($payment) use ($director) {
                $commission = ($payment->amount * $director->commission_percentage) / 100;
                $payment->commission_amount = $commission;
                return $payment;
            });
    }

    private function getStudentStatistics($director)
    {
        $schoolId = $director->school_id;

        // Guruh bo'yicha taqsimlash
        $byGroup = Student::where('school_id', $schoolId)
            ->where('director_id', $director->id)
            ->whereHas('groupStudents', function ($query) {
                $query->where('status', 'active');
            })
            ->with(['groupStudents' => function ($query) {
                $query->where('status', 'active')
                    ->with('group.course');
            }])
            ->get()
            ->flatMap(function ($student) {
                return $student->groupStudents->map(function ($gs) {
                    return $gs->group;
                });
            })
            ->groupBy('name')
            ->map(function ($group) {
                return count($group);
            });

        // Jinsi bo'yicha
        $byGender = Student::where('school_id', $schoolId)
            ->where('director_id', $director->id)
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('users.gender', DB::raw('COUNT(*) as count'))
            ->groupBy('users.gender')
            ->pluck('count', 'gender');

        // Shahar bo'yicha
        $byCity = Student::where('school_id', $schoolId)
            ->where('director_id', $director->id)
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('users.city', DB::raw('COUNT(*) as count'))
            ->whereNotNull('users.city')
            ->groupBy('users.city')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('count', 'city');

        return [
            'by_group' => $byGroup,
            'by_gender' => $byGender,
            'by_city' => $byCity,
        ];
    }

    private function getTopGroups($director)
    {
        return GroupStudent::where('status', 'active')
            ->whereHas('student', function ($query) use ($director) {
                $query->where('school_id', $director->school_id)
                    ->where('director_id', $director->id)
                    // MANA SHU YERDA USERNI TEKSHIRISHNI QO'SHIB KETING:
                    ->whereHas('user', function ($u) {
                        $u->where('is_active', true);
                    });
            })
            // Group va Course'ni birga yuklaymiz (N+1 muammosi bo'lmasligi uchun)
            ->with(['group.course'])
            ->select('group_id', DB::raw('COUNT(*) as student_count'))
            ->groupBy('group_id')
            ->orderBy('student_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                // Agar guruh bazadan o'chirilgan bo'lsa, xato bermasligi uchun tekshiruv:
                if ($item->group) {
                    $item->group->student_count = $item->student_count;
                    return $item->group;
                }
                return null;
            })
            ->filter(); // null bo'lib qolgan guruhlarni olib tashlaydi
    }

    private function getAttendanceStatistics($director)
    {
        $thisMonth = Carbon::now();

        $stats = Attendance::whereHas('student', function ($query) use ($director) {
            $query->where('school_id', $director->school_id)
                ->where('director_id', $director->id);
        })
            ->whereMonth('created_at', $thisMonth->month)
            ->whereYear('created_at', $thisMonth->year)
            ->select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->pluck('count', 'status');

        $total = $stats->sum();
        $presentOrLate = $stats->get('present', 0) + $stats->get('late', 0);

        return [
            'present' => $stats->get('present', 0),
            'absent' => $stats->get('absent', 0),
            'late' => $stats->get('late', 0),
            'excused' => $stats->get('excused', 0),
            'total' => $total,
            'present_percentage' => $total > 0 ? round(($presentOrLate / $total) * 100, 1) : 0,
        ];
    }

    public function show(string $id)
    {
        $director = $this->director();

        if ((int)$director->school_id !== (int)$id) {
            abort(403, 'Sizda bu maktabni ko\'rish huquqi yo\'q!');
        }

        $school = School::with(['director.user'])
            ->withCount('students')
            ->where('id', $id)
            ->firstOrFail();

        // Statistika ma'lumotlari
        $stats = [
            'total_students'  => $school->students_count,

            'active_students' => $school->students()->whereHas(
                'user',
                fn($q) =>
                $q->where('is_active', true)
            )->count(),

            'male_students'   => $school->students()->whereHas(
                'user',
                fn($q) =>
                $q->where('gender', 'male')
            )->count(),

            'female_students' => $school->students()->whereHas(
                'user',
                fn($q) =>
                $q->where('gender', 'female')
            )->count(),
        ]; // Mana bu yerda kvadrat qavs bo'lishi shart!
        // Students with filtering and pagination
        $studentsQuery = $school->students()->with('user');

        // Search filter
        if (request()->filled('search')) {
            $search = request('search');
            $studentsQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (request()->filled('status')) {
            $status = request('status') === 'active';
            $studentsQuery->whereHas('user', function ($q) use ($status) {
                $q->where('is_active', $status);
            });
        }

        // Gender filter
        if (request()->filled('gender')) {
            $studentsQuery->whereHas('user', function ($q) {
                $q->where('gender', request('gender'));
            });
        }

        // Sorting
        $sortBy = request('sort_by', 'latest');
        switch ($sortBy) {
            case 'name':
                $studentsQuery->join('users', 'students.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('students.*');
                break;
            case 'oldest':
                $studentsQuery->oldest();
                break;
            default: // latest
                $studentsQuery->latest();
                break;
        }

        $students = $studentsQuery->paginate(12)->appends(request()->query());

        return view('Panel.Director.school-detail', compact('school', 'stats', 'students'));
    }
}
