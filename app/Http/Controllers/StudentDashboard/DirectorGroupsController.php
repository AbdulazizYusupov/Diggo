<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorGroupsController extends Controller
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
        $status = $request->get('status', 'active');

        // Query - Director studentlari bo'lgan guruhlar
        $query = Group::with(['course', 'teachers.user'])
            ->whereHas('students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            });

        // Search
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Status filter
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $groups = $query->paginate(12);

        // Add statistics for each group
        $groups->getCollection()->transform(function ($group) use ($director) {
            // Total students in group
            $group->total_students = GroupStudent::where('group_id', $group->id)
                ->where('status', 'active')
                ->count();

            // Director's students in this group
            $group->director_students = GroupStudent::where('group_id', $group->id)
                ->where('status', 'active')
                ->whereHas('student', function ($q) use ($director) {
                    $q->where('director_id', $director->id);
                })
                ->count();

            // Total revenue from director's students
            $group->director_revenue = Payment::where('group_id', $group->id)
                ->where('status', 'paid')
                ->where('payable_type', \App\Models\Student::class)
                ->whereHas('payable', function ($q) use ($director) {
                    $q->where('director_id', $director->id);
                })
                ->sum('net_amount');

            return $group;
        });

        // Overall statistics
        $stats = [
            'total_groups' => Group::whereHas('students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
                ->count(),
            'active_groups' => Group::whereHas('students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
                ->where('is_active', true)
                ->count(),
            'total_students_in_groups' => GroupStudent::whereHas('student', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
                ->where('status', 'active')
                ->count(),
        ];

        return view('Panel.Director.groups', compact(
            'director',
            'groups',
            'stats',
            'search',
            'status'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        $director = $user->director;

        if (!$director) {
            abort(403, 'Director profili topilmadi');
        }

        $group = Group::with(['cours', 'teachers.user'])
            ->whereHas('students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
            ->findOrFail($id);

        // All students in group
        $allStudents = GroupStudent::with(['student.user'])
            ->where('group_id', $group->id)
            ->where('status', 'active')
            ->get();

        // Director's students
        $directorStudents = $allStudents->filter(function ($gs) use ($director) {
            return $gs->student->director_id === $director->id;
        });

        // Statistics
        $stats = [
            'total_students' => $allStudents->count(),
            'director_students' => $directorStudents->count(),
            'capacity_percentage' => $group->capacity > 0
                ? round(($allStudents->count() / $group->capacity) * 100, 1)
                : 0,
        ];

        // Revenue from director's students
        $payments = Payment::with(['user'])
            ->where('group_id', $group->id)
            ->where('status', 'paid')
            ->whereIn('payable_id', $directorStudents->pluck('student.id'))
            ->where('payable_type', 'App\Models\Student')
            ->latest('paid_at')
            ->get();

        $revenueStats = [
            'total_revenue' => $payments->sum('net_amount'),
            'commission' => $payments->sum(function ($payment) use ($director) {
                return ($payment->net_amount * $director->commission_percentage) / 100;
            }),
            'payment_count' => $payments->count(),
        ];

        return view('Panel.Director.group-detail', compact(
            'director',
            'group',
            'stats',
            'directorStudents',
            'revenueStats',
            'payments'
        ));
    }
}
