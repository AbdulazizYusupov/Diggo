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
use Illuminate\Support\Facades\DB;

class StudentLeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Filters with defaults
        $groupId = $request->get('group_id');
        $type = $request->get('type', 'coins'); // coins, attendance, homework, activity
        $perPage = in_array((int) $request->get('per_page', 20), [20, 50, 100])
            ? (int) $request->get('per_page', 20)
            : 20;

        // Date filters - default to current month
        $fromDate = $request->get('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', Carbon::now()->format('Y-m-d'));

        // Convert to Carbon instances
        $startDate = Carbon::parse($fromDate)->startOfDay();
        $endDate = Carbon::parse($toDate)->endOfDay();

        // Get student's groups for filter
        $myGroups = GroupStudent::with('group.course')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->get()
            ->pluck('group')
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name . ' - ' . ($group->cours->title ?? 'N/A')
                ];
            });

        // Get ALL rankings (for position calculation and top 3)
        $allRankings = $this->getRankings($student, $type, $startDate, $endDate, $groupId);

        // Get my position from full rankings
        $myPosition = $this->getMyPosition($student, $allRankings);

        // Get my stats
        $myStats = $this->getMyStats($student, $startDate, $endDate, $groupId);

        // Get top 3 for podium
        $topThree = $allRankings->take(3);

        // Paginate with LengthAwarePaginator so ->links() works in view
        $currentPage  = LengthAwarePaginator::resolveCurrentPage();
        $totalCount   = $allRankings->count();
        $sliced       = $allRankings->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $rankings = new LengthAwarePaginator(
            $sliced,
            $totalCount,
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

    private function getRankings($currentStudent, $type, $startDate, $endDate, $groupId = null)
    {
        // Base query - students in same groups as current student
        $query = Student::with(['user', 'groups'])
            ->whereHas('groups', function ($q) use ($currentStudent, $groupId) {
                $q->whereIn('group_id', function ($subQuery) use ($currentStudent) {
                    $subQuery->select('group_id')
                        ->from('group_students')
                        ->where('student_id', $currentStudent->id)
                        ->where('status', 'active');
                })->where('status', 'active');

                if ($groupId) {
                    $q->where('group_id', $groupId);
                }
            });

        $students = $query->get()->map(function ($student) use ($type, $startDate, $endDate, $groupId) {
            $score = 0;

            switch ($type) {
                case 'coins':
                    $score = CoinTransaction::where('student_id', $student->id)
                        ->where('coins', '>', 0)
                        ->whereBetween('transaction_date', [$startDate, $endDate])
                        ->sum('coins');
                    break;

                case 'attendance':
                    $score = Attendance::where('student_id', $student->id)
                        ->where('status', 'present')
                        ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                            $q->whereBetween('lesson_date', [$startDate, $endDate]);
                            if ($groupId) {
                                $q->where('group_id', $groupId);
                            }
                        })
                        ->count();
                    break;

                case 'homework':
                    $score = Attendance::where('student_id', $student->id)
                        ->whereNotNull('homework_score')
                        ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                            $q->whereBetween('lesson_date', [$startDate, $endDate]);
                            if ($groupId) {
                                $q->where('group_id', $groupId);
                            }
                        })
                        ->avg('homework_score') ?? 0;
                    break;

                case 'activity':
                    $score = Attendance::where('student_id', $student->id)
                        ->whereNotNull('activity_score')
                        ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                            $q->whereBetween('lesson_date', [$startDate, $endDate]);
                            if ($groupId) {
                                $q->where('group_id', $groupId);
                            }
                        })
                        ->avg('activity_score') ?? 0;
                    break;
            }

            $student->score = round($score, 1);
            return $student;
        })
        ->sortByDesc('score')
        ->values();

        // Add rank
        $students->each(function ($student, $index) {
            $student->rank = $index + 1;
        });

        return $students;
    }

    private function getMyPosition($student, $rankings)
    {
        $position = $rankings->firstWhere('id', $student->id);
        return $position ? $position->rank : null;
    }

    private function getMyStats($student, $startDate, $endDate, $groupId = null)
    {
        // Coins earned
        $coinsQuery = CoinTransaction::where('student_id', $student->id)
            ->where('coins', '>', 0)
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        $totalCoins = $coinsQuery->sum('coins');
        $coinTransactions = $coinsQuery->count();

        // Attendance
        $attendanceQuery = Attendance::where('student_id', $student->id)
            ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                $q->whereBetween('lesson_date', [$startDate, $endDate]);
                if ($groupId) {
                    $q->where('group_id', $groupId);
                }
            });

        $totalLessons = $attendanceQuery->count();
        $presentLessons = (clone $attendanceQuery)->where('status', 'present')->count();
        $attendanceRate = $totalLessons > 0 ? round(($presentLessons / $totalLessons) * 100, 1) : 0;

        // Homework
        $homeworkQuery = Attendance::where('student_id', $student->id)
            ->whereNotNull('homework_score')
            ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                $q->whereBetween('lesson_date', [$startDate, $endDate]);
                if ($groupId) {
                    $q->where('group_id', $groupId);
                }
            });

        $avgHomework = round($homeworkQuery->avg('homework_score') ?? 0, 1);
        $homeworkCount = $homeworkQuery->count();

        // Activity
        $activityQuery = Attendance::where('student_id', $student->id)
            ->whereNotNull('activity_score')
            ->whereHas('lesson', function ($q) use ($startDate, $endDate, $groupId) {
                $q->whereBetween('lesson_date', [$startDate, $endDate]);
                if ($groupId) {
                    $q->where('group_id', $groupId);
                }
            });

        $avgActivity = round($activityQuery->avg('activity_score') ?? 0, 1);
        $activityCount = $activityQuery->count();

        return [
            'total_coins'      => $totalCoins,
            'coin_transactions' => $coinTransactions,
            'total_lessons'    => $totalLessons,
            'present_lessons'  => $presentLessons,
            'attendance_rate'  => $attendanceRate,
            'avg_homework'     => $avgHomework,
            'homework_count'   => $homeworkCount,
            'avg_activity'     => $avgActivity,
            'activity_count'   => $activityCount,
        ];
    }
}