<?php

namespace App\Http\Controllers\DirectorDashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CoinTransaction;
use App\Models\Director;
use App\Models\GroupStudent;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DirectorStudentsController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $director = $user->director;

        abort_unless($director, 403, 'Director profili topilmadi');

        // ── Input validation ─────────────────────────────────────────────────
        $validated = $request->validate([
            'search'   => ['nullable', 'string', 'max:100'],
            'group_id' => ['nullable', 'integer', 'exists:groups,id'],
            'status'   => ['nullable', 'string', 'in:all,active,inactive'],
        ]);

        $search  = trim((string) ($validated['search']  ?? ''));
        $groupId = isset($validated['group_id']) ? (int) $validated['group_id'] : null;
        $status  = $validated['status'] ?? 'all';

        $directorId = $director->id;

        // ── Main student query ────────────────────────────────────────────────
        $students = Student::query()
            ->with(['user'])
            ->where('director_id', $directorId)
            ->when($search, function (Builder $q) use ($search): void {
                $like = '%' . $search . '%';

                $q->where(function (Builder $outer) use ($like): void {
                    // Search across user table columns
                    $outer->whereHas('user', function (Builder $u) use ($like): void {
                        $u->where(function (Builder $sub) use ($like): void {
                            $sub->where('phone',       'like', $like)
                                ->orWhere('name',        'like', $like)
                                ->orWhere('surname',     'like', $like)
                                ->orWhere('middle_name', 'like', $like)
                                ->orWhereRaw(
                                    "CONCAT(name, ' ', surname, ' ', COALESCE(middle_name, '')) LIKE ?",
                                    [$like]
                                )
                                ->orWhereRaw(
                                    "CONCAT(surname, ' ', name, ' ', COALESCE(middle_name, '')) LIKE ?",
                                    [$like]
                                );
                        });
                    });

                    // parent_phone & parent_name live on the students table
                    $outer->orWhere('parent_phone', 'like', $like)
                        ->orWhere('parent_name',  'like', $like);
                });
            })
            ->when($groupId, function (Builder $q) use ($groupId): void {
                // Extra safety: confirm this group belongs to this director's school
                $q->whereHas('groups', fn(Builder $g) => $g->where('groups.id', $groupId));
            })
            ->latest('enrollment_date')
            ->paginate(18)
            ->withQueryString();

        // ── Attach active_groups count — one query instead of N ──────────────
        $studentIds = $students->getCollection()->modelKeys();

        $activeGroupCounts = $studentIds
            ? GroupStudent::query()
            ->whereIn('student_id', $studentIds)
            ->where('status', 'active')
            ->selectRaw('student_id, COUNT(*) AS total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id')
            : collect();

        $students->getCollection()->each(function (Student $student) use ($activeGroupCounts): void {
            $student->active_groups = $activeGroupCounts->get($student->id, 0);
        });

        // ── Aggregate statistics (all scoped to this director) ────────────────
        $base = fn() => Student::where('director_id', $directorId);

        $stats = [
            'total_students' => $base()->count(),

            'active_students' => GroupStudent::query()
                ->whereHas('student', fn(Builder $q) => $q->where('director_id', $directorId))
                ->where('status', 'active')
                ->distinct('student_id')
                ->count('student_id'),

            'new_this_month' => $base()
                ->whereMonth('enrollment_date', now()->month)
                ->whereYear('enrollment_date',  now()->year)
                ->count(),

            'male_count' => $base()
                ->whereHas('user', fn(Builder $q) => $q->where('gender', 'male'))
                ->count(),

            'female_count' => $base()
                ->whereHas('user', fn(Builder $q) => $q->where('gender', 'female'))
                ->count(),
        ];

        // ── Groups dropdown — only groups that contain this director's students ─
        $groups = DB::table('groups')
            ->join('group_students', 'groups.id',                '=', 'group_students.group_id')
            ->join('students',       'group_students.student_id', '=', 'students.id')
            ->where('students.director_id', $directorId)
            ->where('groups.is_active', true)
            ->select('groups.id', 'groups.name')
            ->distinct()
            ->orderBy('groups.name')
            ->get();

        return view('Panel.Director.students', compact(
            'director',
            'students',
            'stats',
            'search',
            'status',
            'groupId',
            'groups',
        ));
    }
    public function show(int $id): View
{
    /** @var \App\Models\User $user */
    $user     = Auth::user();
    $director = $user->director;
 
    abort_unless($director, 403, 'Director profili topilmadi');
 
    // ── Load student — must belong to this director ───────────────────────
    $student = Student::query()
        ->with(['user', 'school'])
        ->where('director_id', $director->id)
        ->findOrFail($id);
 
    // ── Active group enrolments ───────────────────────────────────────────
    $activeGroups = GroupStudent::query()
        ->with(['group.course'])
        ->where('student_id', $student->id)
        ->where('status', 'active')
        ->latest('joined_at')
        ->get();
 
    $activeGroupIds = $activeGroups->pluck('group_id');
 
    // ── Attendance statistics ─────────────────────────────────────────────
    $lessonIds = Lesson::query()
        ->whereIn('group_id', $activeGroupIds)
        ->pluck('id');
 
    $attendanceBase = fn () => Attendance::query()
        ->where('student_id', $student->id)
        ->whereIn('lesson_id', $lessonIds);
 
    $totalLessons  = $lessonIds->count();
    $presentCount  = (clone $attendanceBase())->where('status', 'present')->count();
    $lateCount     = (clone $attendanceBase())->where('status', 'late')->count();
    $absentCount   = (clone $attendanceBase())->where('status', 'absent')->count();
    $attendedCount = $presentCount + $lateCount;
 
    $attendanceRate = $totalLessons > 0
        ? round(($attendedCount / $totalLessons) * 100)
        : 0;
 
    $avgLateMinutes = (clone $attendanceBase())
        ->where('status', 'late')
        ->whereNotNull('late_minutes')
        ->avg('late_minutes') ?? 0;
 
    $avgHomework = (clone $attendanceBase())
        ->whereNotNull('homework_score')
        ->avg('homework_score') ?? 0;
 
    $avgActivity = (clone $attendanceBase())
        ->whereNotNull('activity_score')
        ->avg('activity_score') ?? 0;
 
    $commissionBase = fn () => Transaction::query()
        ->where('transactionable_type', Director::class)
        ->where('transactionable_id', $director->id)
        ->where('type', 'director_commission')
        ->where('status', 'paid')
        ->whereHasMorph('source', [Payment::class], function (Builder $q) use ($student): void {
            $q->where('payable_type', Student::class)
              ->where('payable_id', $student->id);
        });
 
    $directorTotalEarned     = (float) (clone $commissionBase())->sum('amount');
    $directorPaymentCount    = (clone $commissionBase())->count();
    $latestCommissionPct     = (float) ((clone $commissionBase())
        ->latest('transaction_date')
        ->value('percentage') ?? $director->commission_percentage ?? 0);
 
    // ── Recent attendance records (last 20) ───────────────────────────────
    $recentAttendance = Attendance::query()
        ->with(['lesson.group'])
        ->where('student_id', $student->id)
        ->whereIn('lesson_id', $lessonIds)
        ->latest('marked_at')
        ->take(20)
        ->get();
 
    // ── Stats ─────────────────────────────────────────────────────────────
    $stats = [
        // Attendance
        'total_lessons'    => $totalLessons,
        'present_count'    => $presentCount,
        'late_count'       => $lateCount,
        'absent_count'     => $absentCount,
        'attendance_rate'  => $attendanceRate,
        'avg_late_minutes' => round($avgLateMinutes),
        'avg_homework'     => round($avgHomework, 1),
        'avg_activity'     => round($avgActivity, 1),
 
        // Groups
        'active_groups' => $activeGroups->count(),
 
        // Coins (student-owned, safe to expose)
        'total_coins_earned' => CoinTransaction::query()
            ->where('student_id', $student->id)
            ->where('coins', '>', 0)
            ->sum('coins'),
 
        // Director-only financials
        // = sum of director_commission transactions sourced from this student's payments
        'director_total_earned'   => $directorTotalEarned,   // actual money director earned
        'director_payment_count'  => $directorPaymentCount,  // how many payments generated commission
        'director_commission_pct' => $latestCommissionPct,   // latest % rate applied
    ];
 
    return view('Panel.Director.student-detail', compact(
        'director',
        'student',
        'stats',
        'activeGroups',
        'recentAttendance',
    ));
}
}
