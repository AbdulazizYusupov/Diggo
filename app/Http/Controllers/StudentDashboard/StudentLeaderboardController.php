<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CoinTransaction;
use App\Models\GroupStudent;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class StudentLeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }


        $groupId = $request->get('group_id');
        $type    = $request->get('type', 'coins');
        $perPage = in_array((int) $request->get('per_page', 20), [20, 50, 100])
            ? (int) $request->get('per_page', 20)
            : 20;

        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate   = $request->get('to_date', Carbon::now()->format('Y-m-d'));

        $startDate = Carbon::parse($fromDate)->startOfDay()->utc();;
        $endDate   = Carbon::parse($toDate)->endOfDay()->utc();;
//    
        $myGroups = GroupStudent::with('group.course')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->get()
            ->pluck('group')
            ->map(fn($group) => [
                'id'   => $group->id,
                'name' => $group->name . ' - ' . ($group->course->title ?? 'N/A'),  // typo tuzatildi: cours → course
            ]);

        $allRankings = $this->getRankings($student, $type, $startDate, $endDate, $groupId);

        $myPosition = $this->getMyPosition($student, $allRankings);
        $topThree   = $allRankings->take(3);
        $myStats    = $this->getMyStats($student, $startDate, $endDate, $groupId);

        // Pagination (o'zgarishsiz)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $rankings = new LengthAwarePaginator(
            $allRankings->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $allRankings->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('Panel.Student.leaderboard', compact(
            'student',
            'rankings',
            'myPosition',
            'myStats',
            'topThree',
            'myGroups',
            'groupId',
            'type',
            'fromDate',
            'toDate',
            'perPage'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // N+1 FIX: barcha score hisoblash BITTA query ichida (withSum/withAvg/withCount)
    // Oldin: 100 student → 100 qo'shimcha query. Endi: 100 student → +1 query.
    // ─────────────────────────────────────────────────────────────────────────
    private function getRankings($currentStudent, $type, $startDate, $endDate, $groupId = null)
    {
        $myGroupIds = GroupStudent::where('student_id', $currentStudent->id)
            ->where('status', 'active')
            ->pluck('group_id');

        $query = Student::with('user')
            ->whereHas('groups', function ($q) use ($myGroupIds, $groupId) {
                $q->whereIn('group_id', $myGroupIds)
                    ->where('status', 'active');

                if ($groupId) {
                    $q->where('group_id', $groupId);
                }
            });

        // Har bir type uchun bitta qo'shimcha query (N+1 yo'q)
        switch ($type) {

            case 'coins':
                $query->withSum(
                    [
                        'coinTransactions as score' => fn($q) => $q
                            ->where('coins', '>', 0)
                            ->whereBetween('transaction_date', [$startDate, $endDate])
                    ],
                    'coins'
                );
                break;

            case 'attendance':
                $query->withCount(
                    [
                        'attendances as score' => fn($q) => $q
                            ->where('status', 'present')
                            ->whereHas(
                                'lesson',
                                fn($l) => $l
                                    ->whereBetween('lesson_date', [$startDate, $endDate])
                                    ->when($groupId, fn($l) => $l->where('group_id', $groupId))
                            )
                    ]
                );
                break;

            case 'homework':
                $query->withAvg(
                    [
                        'attendances as score' => fn($q) => $q
                            ->whereNotNull('homework_score')
                            ->whereHas(
                                'lesson',
                                fn($l) => $l
                                    ->whereBetween('lesson_date', [$startDate, $endDate])
                                    ->when($groupId, fn($l) => $l->where('group_id', $groupId))
                            )
                    ],
                    'homework_score'
                );
                break;

            case 'activity':
                $query->withAvg(
                    [
                        'attendances as score' => fn($q) => $q
                            ->whereNotNull('activity_score')
                            ->whereHas(
                                'lesson',
                                fn($l) => $l
                                    ->whereBetween('lesson_date', [$startDate, $endDate])
                                    ->when($groupId, fn($l) => $l->where('group_id', $groupId))
                            )
                    ],
                    'activity_score'
                );
                break;
        }

        $students = $query->get()
            ->map(function ($student) {
                $student->score = round((float) ($student->score ?? 0), 1);
                return $student;
            })
            ->sortByDesc('score')
            ->values();

        $students->each(fn($student, $index) => $student->rank = $index + 1);

        return $students;
    }

    private function getMyPosition($student, $rankings)
    {
        $position = $rankings->firstWhere('id', $student->id);
        return $position ? $position->rank : null;
    }

    private function getMyStats($student, $startDate, $endDate, $groupId = null)
    {
        $coinsQuery = CoinTransaction::where('student_id', $student->id)
            ->where('coins', '>', 0)
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        $totalCoins       = $coinsQuery->sum('coins');
        $coinTransactions = $coinsQuery->count();

        $attendanceQuery = Attendance::where('student_id', $student->id)
            ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                $q->whereBetween('lesson_date', [$startDate, $endDate]);
                if ($groupId) $q->where('group_id', $groupId);
            });

        $totalLessons   = $attendanceQuery->count();
        $presentLessons = (clone $attendanceQuery)->where('status', 'present')->count();
        $attendanceRate = $totalLessons > 0
            ? round(($presentLessons / $totalLessons) * 100, 1)
            : 0;

        $homeworkQuery = Attendance::where('student_id', $student->id)
            ->whereNotNull('homework_score')
            ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                $q->whereBetween('lesson_date', [$startDate, $endDate]);
                if ($groupId) $q->where('group_id', $groupId);
            });

        $avgHomework   = round($homeworkQuery->avg('homework_score') ?? 0, 1);
        $homeworkCount = $homeworkQuery->count();

        $activityQuery = Attendance::where('student_id', $student->id)
            ->whereNotNull('activity_score')
            ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                $q->whereBetween('lesson_date', [$startDate, $endDate]);
                if ($groupId) $q->where('group_id', $groupId);
            });

        $avgActivity   = round($activityQuery->avg('activity_score') ?? 0, 1);
        $activityCount = $activityQuery->count();

        return [
            'total_coins'       => $totalCoins,
            'coin_transactions' => $coinTransactions,
            'total_lessons'     => $totalLessons,
            'present_lessons'   => $presentLessons,
            'attendance_rate'   => $attendanceRate,
            'avg_homework'      => $avgHomework,
            'homework_count'    => $homeworkCount,
            'avg_activity'      => $avgActivity,
            'activity_count'    => $activityCount,
        ];
    }
}
