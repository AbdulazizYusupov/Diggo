<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\GroupStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        // Haftalik yoki oylik ko'rinish
        $view = $request->get('view', 'week');
        
        // Tanlangan sana
        $date = $request->get('date') 
            ? Carbon::parse($request->get('date')) 
            : Carbon::now();

        // Faol guruhlar
        $groupIds = GroupStudent::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('group_id');

        if ($view === 'week') {
            $schedule = $this->getWeeklySchedule($student, $groupIds, $date);
        } else {
            $schedule = $this->getMonthlySchedule($student, $groupIds, $date);
        }

        return view('Panel.Student.schedule', compact(
            'student',
            'schedule',
            'view',
            'date'
        ));
    }

    private function getWeeklySchedule($student, $groupIds, $date)
    {
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        // Darslar
        $lessons = DB::table('lessons')
            ->join('groups', 'lessons.group_id', '=', 'groups.id')
            ->join('cours', 'groups.cours_id', '=', 'cours.id')
            ->leftJoin('attendances', function ($join) use ($student) {
                $join->on('lessons.id', '=', 'attendances.lesson_id')
                    ->where('attendances.student_id', '=', $student->id)
                    ->whereNull('attendances.deleted_at');
            })
            ->whereIn('lessons.group_id', $groupIds)
            ->whereBetween('lessons.lesson_date', [$startOfWeek, $endOfWeek])
            ->whereNull('lessons.deleted_at')
            ->select(
                'lessons.*',
                'groups.name as group_name',
                'groups.start_time',
                'groups.end_time',
                'cours.title as course_name',
                'attendances.status as attendance_status',
                'attendances.homework_score',
                'attendances.activity_score'
            )
            ->orderBy('lessons.lesson_date')
            ->orderBy('groups.start_time')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->lesson_date)->format('Y-m-d');
            });

        // Hafta kunlari
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $dayKey = $day->format('Y-m-d');
            
            $days[] = [
                'date' => $day,
                'lessons' => $lessons->get($dayKey, collect()),
                'is_today' => $day->isToday(),
                'is_past' => $day->isPast() && !$day->isToday()
            ];
        }

        return [
            'type' => 'week',
            'start_date' => $startOfWeek,
            'end_date' => $endOfWeek,
            'days' => $days
        ];
    }

    private function getMonthlySchedule($student, $groupIds, $date)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Darslar
        $lessons = DB::table('lessons')
            ->join('groups', 'lessons.group_id', '=', 'groups.id')
            ->join('cours', 'groups.cours_id', '=', 'cours.id')
            ->leftJoin('attendances', function ($join) use ($student) {
                $join->on('lessons.id', '=', 'attendances.lesson_id')
                    ->where('attendances.student_id', '=', $student->id)
                    ->whereNull('attendances.deleted_at');
            })
            ->whereIn('lessons.group_id', $groupIds)
            ->whereBetween('lessons.lesson_date', [$startOfMonth, $endOfMonth])
            ->whereNull('lessons.deleted_at')
            ->select(
                'lessons.*',
                'groups.name as group_name',
                'groups.start_time',
                'cours.title as course_name',
                'attendances.status as attendance_status'
            )
            ->orderBy('lessons.lesson_date')
            ->orderBy('groups.start_time')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->lesson_date)->format('Y-m-d');
            });

        // Calendar grid
        $startDate = $startOfMonth->copy()->startOfWeek();
        $endDate = $endOfMonth->copy()->endOfWeek();
        
        $weeks = [];
        $currentWeek = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dayKey = $currentDate->format('Y-m-d');
            
            $currentWeek[] = [
                'date' => $currentDate->copy(),
                'lessons' => $lessons->get($dayKey, collect()),
                'is_today' => $currentDate->isToday(),
                'is_current_month' => $currentDate->month === $date->month,
                'is_past' => $currentDate->isPast() && !$currentDate->isToday()
            ];
            
            if ($currentDate->dayOfWeek === 0 || $currentDate->eq($endDate)) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
            
            $currentDate->addDay();
        }

        return [
            'type' => 'month',
            'month' => $date->month,
            'year' => $date->year,
            'month_name' => $date->locale('uz_Latn')->translatedFormat('F Y'),
            'weeks' => $weeks
        ];
    }

    public function upcoming()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profili topilmadi');
        }

        $now = Carbon::now();
        $endDate = $now->copy()->addDays(14); // Keyingi 2 hafta

        $groupIds = GroupStudent::where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('group_id');

        $lessons = DB::table('lessons')
            ->join('groups', 'lessons.group_id', '=', 'groups.id')
            ->join('cours', 'groups.cours_id', '=', 'cours.id')
            ->leftJoin('attendances', function ($join) use ($student) {
                $join->on('lessons.id', '=', 'attendances.lesson_id')
                    ->where('attendances.student_id', '=', $student->id)
                    ->whereNull('attendances.deleted_at');
            })
            ->whereIn('lessons.group_id', $groupIds)
            ->whereBetween('lessons.lesson_date', [$now, $endDate])
            ->whereNull('lessons.deleted_at')
            ->select(
                'lessons.*',
                'groups.name as group_name',
                'groups.start_time',
                'groups.end_time',
                'cours.title as course_name',
                'attendances.status as attendance_status'
            )
            ->orderBy('lessons.lesson_date')
            ->orderBy('groups.start_time')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->lesson_date)->format('Y-m-d');
            });

        return view('Panel.Student.schedule-upcoming', compact('student', 'lessons'));
    }
}
