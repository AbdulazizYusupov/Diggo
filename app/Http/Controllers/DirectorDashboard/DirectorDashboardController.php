<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Director;
use App\Models\DirectorCommission;
use App\Models\GroupStudent;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirectorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Foydalanuvchi director role'iga ega ekanligini tekshirish
        if (!$user->hasRole('director')) {
            abort(403, 'Sizda bu sahifaga kirish huquqi yo\'q');
        }

        // Director ma'lumotlarini olish
        $director = Director::with(['school', 'user'])
            ->where('user_id', $user->id)
            ->firstOrFail();

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

        // Jami o'quvchilar soni
        $totalStudents = Student::where('school_id', $schoolId)
            ->where('director_id', $director->id)
            ->count();

        // Faol o'quvchilar
        $activeStudents = Student::where('school_id', $schoolId)
            ->where('director_id', $director->id)
            // ->whereHas('groupStudents', function ($query) {
            //     $query->where('status', 'active');
            // })
            ->count();

        // Shu oylik komissiya
        $thisMonthCommission = DirectorCommission::where('director_id', $director->id)
            ->where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');

        // Kutilayotgan komissiya
        $pendingCommission = DirectorCommission::where('director_id', $director->id)
            ->where('status', 'pending')
            ->sum('amount');

        // Jami to'lovlar (shu oyda)
        $thisMonthPayments = Payment::whereHas('student', function ($query) use ($schoolId, $director) {
            $query->where('school_id', $schoolId)
                ->where('director_id', $director->id);
        })
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->where('status', 'completed')
            ->sum('amount');

        // Jami komissiya (barcha vaqt)
        $totalCommissionEarned = DirectorCommission::where('director_id', $director->id)
            ->where('status', 'paid')
            ->sum('amount');

        // Yangi o'quvchilar (shu oyda)
        $newStudentsThisMonth = Student::where('school_id', $schoolId)
            ->where('director_id', $director->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // O'rtacha davomat foizi (shu oyda)
        $attendanceRate = Attendance::whereHas('student', function ($query) use ($schoolId, $director) {
            $query->where('school_id', $schoolId)
                ->where('director_id', $director->id);
        })
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->avg(DB::raw('CASE WHEN status = "present" THEN 100 ELSE 0 END'));

        return [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'inactive_students' => $totalStudents - $activeStudents,
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
        return DirectorCommission::where('director_id', $director->id)
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
        return Payment::with(['student.user', 'cours'])
            ->whereHas('student', function ($query) use ($director) {
                $query->where('school_id', $director->school_id)
                    ->where('director_id', $director->id);
            })
            ->where('status', 'completed')
            ->orderBy('payment_date', 'desc')
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
                    ->with('group.cours');
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
                    ->where('director_id', $director->id);
            })
            ->with(['group.cours'])
            ->select('group_id', DB::raw('COUNT(*) as student_count'))
            ->groupBy('group_id')
            ->orderBy('student_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->group->student_count = $item->student_count;
                return $item->group;
            });
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

        return [
            'present' => $stats->get('present', 0),
            'absent' => $stats->get('absent', 0),
            'late' => $stats->get('late', 0),
            'excused' => $stats->get('excused', 0),
            'total' => $total,
            'present_percentage' => $total > 0 ? round(($stats->get('present', 0) / $total) * 100, 1) : 0,
        ];
    }

    public function students(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('director')) {
            abort(403);
        }

        $director = Director::where('user_id', $user->id)->firstOrFail();

        $query = Student::with(['user', 'groupStudents.group.cours'])
            ->where('school_id', $director->school_id)
            ->where('director_id', $director->id);

        // Qidiruv
        if ($search = $request->get('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->whereHas('groupStudents', function ($q) {
                    $q->where('status', 'active');
                });
            } elseif ($status === 'inactive') {
                $query->whereDoesntHave('groupStudents', function ($q) {
                    $q->where('status', 'active');
                });
            }
        }

        $students = $query->paginate(20);

        return view('director.students', compact('students', 'director'));
    }

    public function commissions(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('director')) {
            abort(403);
        }

        $director = Director::where('user_id', $user->id)->firstOrFail();

        $query = DirectorCommission::with(['payment.student.user', 'payment.cours'])
            ->where('director_id', $director->id);

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by date range
        if ($from = $request->get('from_date')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->get('to_date')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $commissions = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total_paid' => DirectorCommission::where('director_id', $director->id)
                ->where('status', 'paid')
                ->sum('amount'),
            'total_pending' => DirectorCommission::where('director_id', $director->id)
                ->where('status', 'pending')
                ->sum('amount'),
            'total_cancelled' => DirectorCommission::where('director_id', $director->id)
                ->where('status', 'cancelled')
                ->sum('amount'),
        ];

        return view('director.commissions', compact('commissions', 'director', 'stats'));
    }
}
