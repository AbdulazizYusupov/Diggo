<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use App\Models\GroupStudent;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirectorStudentsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $director = $user->director;

        if (!$director) {
            abort(403, 'Director profili topilmadi');
        }

        // Filters
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $groupId = $request->get('group_id');

        // Query
        $query = Student::with(['user', 'school'])
            ->where('director_id', $director->id);

        // Search filter
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Group filter
        if ($groupId) {
            $query->whereHas('groups', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            });
        }

        $students = $query->paginate(20);

        // Add statistics for each student
        $students->getCollection()->transform(function ($student) {
            $student->total_paid = Payment::where('payable_type', 'App\Models\Student')
                ->where('payable_id', $student->id)
                ->where('status', 'paid')
                ->sum('net_amount');

            $student->active_groups = GroupStudent::where('student_id', $student->id)
                ->where('status', 'active')
                ->count();

            return $student;
        });

        // Statistics
        $stats = [
            'total_students' => Student::where('director_id', $director->id)->count(),
            'active_students' => GroupStudent::whereHas('student', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
                ->where('status', 'active')
                ->distinct('student_id')
                ->count('student_id'),
            'total_balance' => Student::where('director_id', $director->id)->sum('balance'),
            'total_coins' => Student::where('director_id', $director->id)->sum('coins'),
        ];

        // Groups for filter
        $groups = DB::table('groups')
            ->join('group_students', 'groups.id', '=', 'group_students.group_id')
            ->join('students', 'group_students.student_id', '=', 'students.id')
            ->where('students.director_id', $director->id)
            ->where('groups.is_active', true)
            ->select('groups.id', 'groups.name')
            ->distinct()
            ->get();

        return view('Panel.Director.students', compact(
            'director',
            'students',
            'stats',
            'search',
            'status',
            'groupId',
            'groups'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        $director = $user->director;

        if (!$director) {
            abort(403, 'Director profili topilmadi');
        }

        $student = Student::with(['user', 'school', 'groups.course'])
            ->where('director_id', $director->id)
            ->findOrFail($id);

        // Statistics
        $stats = [
            'total_paid' => Payment::where('payable_type', 'App\Models\Student')
                ->where('payable_id', $student->id)
                ->where('status', 'paid')
                ->sum('net_amount'),
            'pending_payment' => Payment::where('payable_type', 'App\Models\Student')
                ->where('payable_id', $student->id)
                ->where('status', 'pending')
                ->sum('net_amount'),
            'total_coins_earned' => CoinTransaction::where('student_id', $student->id)
                ->where('coins', '>', 0)
                ->sum('coins'),
            'active_groups' => GroupStudent::where('student_id', $student->id)
                ->where('status', 'active')
                ->count(),
        ];

        // Recent payments
        $recentPayments = Payment::with(['group.course', 'user'])
            ->where('payable_type', 'App\Models\Student')
            ->where('payable_id', $student->id)
            ->latest('paid_at')
            ->take(10)
            ->get();

        // Recent coin transactions
        $recentCoins = CoinTransaction::with(['activity', 'user'])
            ->where('student_id', $student->id)
            ->latest('transaction_date')
            ->take(10)
            ->get();

        // Groups
        $activeGroups = GroupStudent::with(['group.course'])
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->get();

        return view('Panel.Director.student-detail', compact(
            'director',
            'student',
            'stats',
            'recentPayments',
            'recentCoins',
            'activeGroups'
        ));
    }
}
