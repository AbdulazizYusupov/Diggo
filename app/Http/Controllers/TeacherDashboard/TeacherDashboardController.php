<?php

namespace App\Http\Controllers\TeacherDashboard;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $today = Carbon::today();

        // O'qituvchining barcha faol guruhlari
        $groups = $teacher->groups()
            ->with('course')
            ->where('is_active', true)
            ->get();

        // Bugungi darslar
        $todayLessons = Lesson::with('group.course')
            ->where('teacher_id', $teacher->id)
            ->where('lesson_date', $today)
            ->whereNull('deleted_at')
            ->orderBy('start_time')
            ->get();

        // Bu oylik statistika
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd   = $today->copy()->endOfMonth();

        $monthLessons = Lesson::where('teacher_id', $teacher->id)
            ->whereBetween('lesson_date', [$monthStart, $monthEnd])
            ->whereNull('deleted_at')
            ->get();

        $completedCount  = $monthLessons->where('status', 'completed')->count();
        $scheduledCount  = $monthLessons->where('status', 'scheduled')->count();
        $totalStudents   = $groups->sum(fn($g) => $g->activeStudents()->count());

        // So'nggi 5 ta bajarilgan dars
        $recentLessons = Lesson::with('group')
            ->where('teacher_id', $teacher->id)
            ->where('status', 'completed')
            ->whereNull('deleted_at')
            ->orderBy('lesson_date', 'desc')
            ->limit(5)
            ->get();

        // Rejalashtirilgan darslar (keyingi 7 kun)
        $upcomingLessons = Lesson::with('group.course')
            ->where('teacher_id', $teacher->id)
            ->where('lesson_date', '>=', $today)
            ->where('status', 'scheduled')
            ->whereNull('deleted_at')
            ->orderBy('lesson_date')
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return view('Panel.Teacher.index', compact(
            'teacher',
            'groups',
            'todayLessons',
            'completedCount',
            'scheduledCount',
            'totalStudents',
            'recentLessons',
            'upcomingLessons',
            'today'
        ));
    }
}
