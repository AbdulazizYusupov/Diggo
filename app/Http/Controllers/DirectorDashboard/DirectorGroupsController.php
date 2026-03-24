<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectorGroupsController extends Controller
{
    public function index(Request $request)
    {
        $director = Auth::user()->director;

        if (!$director) {
            abort(403, 'Director profili topilmadi');
        }

        $search = $request->get('search');
        $status = $request->get('status', 'active');

        $query = Group::query()
            ->with(['course', 'teachers.user'])
            // 1. Faqat shu directorning studentlari bor guruhlarni olish
            ->whereHas('students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })
            // 2. Guruhdagi jami aktiv studentlar soni
            ->withCount(['groupStudents as total_students' => function ($q) {
                $q->where('status', 'active');
            }])
            // 3. Shu direktorga tegishli studentlar soni
            ->withCount(['groupStudents as director_students' => function ($q) use ($director) {
                $q->where('status', 'active')
                    ->whereHas('student', function ($sq) use ($director) {
                        $sq->where('director_id', $director->id);
                    });
            }])
            // 4. Direktor studentlaridan tushgan umumiy summa
            ->withSum(['payments as director_revenue' => function ($q) use ($director) {
                $q->where('status', 'paid')
                    ->where('payable_type',Student::class)
                    ->whereHas('payable', function ($sq) use ($director) {
                        $sq->where('director_id', $director->id);
                    });
            }], 'net_amount');

        // Filtrlash
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (in_array($status, ['active', 'inactive'])) {
            $query->where('is_active', $status === 'active');
        }

        $groups = $query->latest()->paginate(12)->withQueryString();

        // Overall stats - bu qismni optimallashtirish uchun alohida query qilsa bo'ladi
        // Lekin pagination natijasidan kelib chiqib ham hisoblasa bo'ladi (agar faqat sahifadagi emas, hammasi kerak bo'lsa)
        $stats = [
            'total_groups' => $groups->total(),
            'active_groups' => Group::whereHas('students', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })->where('is_active', true)->count(),
            'total_students_in_groups' => GroupStudent::whereHas('student', function ($q) use ($director) {
                $q->where('director_id', $director->id);
            })->where('status', 'active')->count(),
        ];

        return view('Panel.Director.groups', compact('director', 'groups', 'stats', 'search', 'status'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $director = $user->director;

        if (!$director) {
            abort(403, 'Director profili topilmadi');
        }

        $group = Group::with(['course', 'teachers.user'])
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
