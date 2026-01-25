<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CoinTransaction;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index1(Request $request)
    {
        $search = $request->input('search');
        $today = now()->toDateString();
        $todayDayName = strtolower(now()->locale('en')->dayName);

        $groups = Group::with(['course', 'activeStudents'])
            ->where('is_active', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('course', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        // Har bir guruh uchun bugungi dars va davomat holatini aniqlash
        $groups->getCollection()->transform(function ($group) use ($today, $todayDayName) {
            // Bugungi kun dars kunlaridan birimi?
            $hasTodayLesson = in_array($todayDayName, $group->days_of_week ?? []);

            // Bugun davomat yuklangan bo'lsa
            $hasAttendanceToday = false;
            if ($hasTodayLesson) {
                $hasAttendanceToday = Attendance::where('group_id', $group->id)
                    ->whereDate('date', $today)
                    ->exists();
            }

            $group->has_today_lesson = $hasTodayLesson;
            $group->has_attendance_today = $hasAttendanceToday;

            return $group;
        });

        return view('Attendance.index', compact('groups', 'search'));
    }
    public function index2(Request $request)
    {
        $search = $request->input('search');
        $today = now()->toDateString();
        $todayDayName = strtolower(now()->locale('en')->dayName);


        $groups = Group::with(['course', 'activeStudents'])
            ->withExists(['attendances as has_attendance_today' => function ($query) use ($today) {
                $query->whereDate('date', $today);
            }])
            ->where('is_active', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('course', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        // Faqat bugun dars borligini tekshirish qoldi
        $groups->getCollection()->transform(function ($group) use ($todayDayName) {
            $group->has_today_lesson = in_array($todayDayName, $group->days_of_week ?? []);
            return $group;
        });

        $dayNames = [
            'monday' => 'Du',
            'tuesday' => 'Se',
            'wednesday' => 'Ch',
            'thursday' => 'Pa',
            'friday' => 'Ju',
            'saturday' => 'Sh',
            'sunday' => 'Ya',
        ];
        $todayDayName = strtolower(now()->locale('en')->dayName);
        return view('Attendance.index', compact('groups', 'search', 'dayNames', 'todayDayName'));
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $todayDayName = strtolower(now()->locale('en')->dayName);

        $query = Group::with(['course', 'activeStudents'])
            ->where('is_active', true);

        if ($user->hasRole('teacher')) {
            $query->whereHas('teachers', function ($q) use ($user) {
                $q->where('teacher_id', $user->teacher->id);
            });
        } elseif (!$user->hasRole('admin')) {
            $query->whereKey([]);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($c) use ($search) {
                        $c->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $groups = $query->orderBy('name')->paginate(12)->withQueryString();

        $groups->getCollection()->transform(function ($group) use ($todayDayName) {
            $group->has_today_lesson = in_array($todayDayName, $group->days_of_week ?? []);
            return $group;
        });

        $dayNames = [
            'monday' => 'Du',
            'tuesday' => 'Se',
            'wednesday' => 'Ch',
            'thursday' => 'Pa',
            'friday' => 'Ju',
            'saturday' => 'Sh',
            'sunday' => 'Ya',
        ];

        return view('Attendance.index', compact('groups', 'search', 'dayNames', 'todayDayName'));
    }
    public function index4(Request $request)
    {
        $groupId = 1;
        $group = Group::with(['course', 'teachers', 'activeStudents.user'])
            ->withCount('activeStudents')
            ->findOrFail($groupId);

        // Filtrlar
        $month = $request->get('month', now()->format('Y-m'));
        $status = $request->get('status');

        // Darslarni olish
        $lessonsQuery = Lesson::where('group_id', $groupId)
            ->with(['teacher', 'attendances.student.user'])
            ->whereYear('lesson_date', substr($month, 0, 4))
            ->whereMonth('lesson_date', substr($month, 5, 2))
            ->orderBy('lesson_date', 'desc')
            ->orderBy('start_time', 'desc');

        if ($status) {
            $lessonsQuery->where('status', $status);
        }

        $lessons = $lessonsQuery->paginate(10);

        // Statistika
        $stats = [
            'total_lessons' => Lesson::where('group_id', $groupId)
                ->whereYear('lesson_date', substr($month, 0, 4))
                ->whereMonth('lesson_date', substr($month, 5, 2))
                ->count(),
            'completed_lessons' => Lesson::where('group_id', $groupId)
                ->where('status', 'completed')
                ->whereYear('lesson_date', substr($month, 0, 4))
                ->whereMonth('lesson_date', substr($month, 5, 2))
                ->count(),
            'cancelled_lessons' => Lesson::where('group_id', $groupId)
                ->where('status', 'cancelled')
                ->whereYear('lesson_date', substr($month, 0, 4))
                ->whereMonth('lesson_date', substr($month, 5, 2))
                ->count(),
            'avg_attendance' => $this->calculateAverageAttendance($groupId, $month),
        ];

        return view('Attendance.index1', compact('group', 'lessons', 'stats', 'month'));
    }
    private function calculateAverageAttendance($groupId, $month)
    {
        $completedLessons = Lesson::where('group_id', $groupId)
            ->where('status', 'completed')
            ->whereYear('lesson_date', substr($month, 0, 4))
            ->whereMonth('lesson_date', substr($month, 5, 2))
            ->pluck('id');

        if ($completedLessons->isEmpty()) {
            return 0;
        }

        $totalAttendances = Attendance::whereIn('lesson_id', $completedLessons)->count();
        $presentAttendances = Attendance::whereIn('lesson_id', $completedLessons)
            ->where('status', 'present')
            ->count();

        return $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100, 1) : 0;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'attendances' => 'required|array',
            'attendances.*.*' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $groupId = $validated['group_id'];

            foreach ($validated['attendances'] as $studentData) {
                foreach ($studentData as $dayData) {
                    if (!isset($dayData['student_id']) || !isset($dayData['date'])) {
                        continue;
                    }

                    // Shu kun uchun darsni topish
                    $lesson = Lesson::where('group_id', $groupId)
                        ->whereDate('lesson_date', $dayData['date'])
                        ->first();

                    if (!$lesson) {
                        continue;
                    }

                    $status = $dayData['status'] ?? 'absent';
                    $homeworkScore = 0;
                    $activityScore = 0;

                    // Faqat kelgan yoki kechikkan bo'lsa homework va activity hisobga olinadi
                    if ($status === 'present' || $status === 'late') {
                        $homeworkScore = $dayData['homework_score'] ?? 0;
                        $activityScore = $dayData['activity_score'] ?? 0;
                    }

                    // Davomat saqlash yoki yangilash
                    Attendance::updateOrCreate(
                        [
                            'lesson_id' => $lesson->id,
                            'student_id' => $dayData['student_id'],
                        ],
                        [
                            'status' => $status,
                            'homework_score' => $homeworkScore,
                            'activity_score' => $activityScore,
                            'marked_by' => auth()->id(),
                            'marked_at' => now(),
                        ]
                    );
                }
            }

            // Barcha darslarni completed qilish
            Lesson::where('group_id', $groupId)
                ->where('status', 'scheduled')
                ->whereDate('lesson_date', '<=', now())
                ->update(['status' => 'completed']);

            DB::commit();

            return redirect()
                ->route('attendance.show', $groupId)
                ->with('success', 'Davomat muvaffaqiyatli saqlandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }
    public function show3(Group $group, Request $request)
    {
        // Tanlangan oy va yil (default: joriy oy)
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Guruh o'quvchilari
        $students = $group->activeStudents()
            ->with(['user', 'coinTransactions' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            }])
            ->orderBy('created_at')
            ->get();

        // Davomat ma'lumotlari
        $attendances = Attendance::where('group_id', $group->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                return $item->student_id . '_' . $item->date->format('Y-m-d');
            });

        // Oyning kunlari (faqat guruh kunlari)
        $daysOfWeek = $group->days_of_week ?? [];
        $daysMapping = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 0,
        ];

        $lessonDays = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dayName = strtolower($current->format('l'));
            if (in_array($dayName, $daysOfWeek)) {
                $lessonDays[] = $current->copy();
            }
            $current->addDay();
        }

        return view('Attendance.show', compact(
            'group',
            'students',
            'attendances',
            'lessonDays',
            'startDate',
            'endDate',
            'year',
            'month'
        ));
    }
    public function show1(Request $request, $groupId)
    {
        $group = Group::with(['course', 'activeStudents.user'])
            ->findOrFail($groupId);

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $lessons = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year)
            ->whereMonth('lesson_date', $month)
            ->whereIn('status', ['scheduled', 'completed'])
            ->orderBy('lesson_date')
            ->get();

        $lessonDays = $lessons->pluck('lesson_date')->unique()->values();
        $students = $group->activeStudents;

        $lessonIds = $lessons->pluck('id');
        $attendanceRecords = Attendance::whereIn('lesson_id', $lessonIds)
            ->with('lesson')
            ->get();

        $attendancesByStudentDay = [];
        $studentTotalCoins = [];

        foreach ($students as $student) {
            $attendancesByStudentDay[$student->id] = [];
            $studentTotalCoins[$student->id] = 0;

            foreach ($lessonDays as $day) {
                $dateKey = $day->format('Y-m-d');

                $attendance = $attendanceRecords->first(function ($att) use ($student, $day) {
                    return $att->student_id == $student->id &&
                        $att->lesson->lesson_date->format('Y-m-d') == $day->format('Y-m-d');
                });

                if ($attendance) {
                    $dailyCoins = $this->calculateCoins($attendance);

                    $attendancesByStudentDay[$student->id][$dateKey] = [
                        'status' => $attendance->status,
                        'late_minutes' => $attendance->late_minutes ?? 0,
                        'homework_score' => $attendance->homework_score ?? 0,
                        'activity_score' => $attendance->activity_score ?? 0,
                        'daily_coins' => $dailyCoins,
                    ];

                    $studentTotalCoins[$student->id] += $dailyCoins;
                } else {
                    $attendancesByStudentDay[$student->id][$dateKey] = null;
                }
            }
        }

        return view('Attendance.show', compact(
            'group',
            'students',
            'lessonDays',
            'attendancesByStudentDay',
            'studentTotalCoins',
            'month',
            'year'
        ));
    }

    private function calculateCoins1($attendance)
    {
        $coins = 0;

        if ($attendance->status === 'present') {
            $coins = 4;
        } elseif ($attendance->status === 'late') {
            $coins = 3;
        }

        if (in_array($attendance->status, ['present', 'late'])) {
            $coins += ($attendance->homework_score ?? 0) + ($attendance->activity_score ?? 0);
        }

        return $coins;
    }

    public function statistics($groupId, Request $request)
    {
        $group = Group::with(['course', 'activeStudents.user'])
            ->findOrFail($groupId);

        $students = $group->activeStudents;
        $studentStats = [];

        foreach ($students as $student) {
            $totalLessons = Lesson::where('group_id', $groupId)
                ->where('status', 'completed')
                ->count();

            $attendances = Attendance::whereHas('lesson', function ($query) use ($groupId) {
                $query->where('group_id', $groupId)
                    ->where('status', 'completed');
            })
                ->where('student_id', $student->id)
                ->get();

            $present = $attendances->where('status', 'present')->count();
            $late = $attendances->where('status', 'late')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $excused = $attendances->where('status', 'excused')->count();

            $totalCoins = $attendances->sum(function ($att) {
                return $this->calculateCoins($att);
            });

            $attendanceRate = $totalLessons > 0
                ? round(($present + $late) / $totalLessons * 100, 1)
                : 0;

            $studentStats[] = [
                'student' => $student,
                'total_lessons' => $totalLessons,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'excused' => $excused,
                'total_coins' => $totalCoins,
                'attendance_rate' => $attendanceRate,
                'avg_homework' => round($attendances->avg('homework_score') ?? 0, 1),
                'avg_activity' => round($attendances->avg('activity_score') ?? 0, 1),
            ];
        }

        usort($studentStats, fn($a, $b) => $b['attendance_rate'] <=> $a['attendance_rate']);

        return view('Attendance.statistics', compact('group', 'studentStats'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    // public function statistics($groupId, Request $request)
    // {
    //     $startDate = $request->input('start_date', now()->startOfMonth());
    //     $endDate = $request->input('end_date', now()->endOfMonth());
    //     $group = Group::with(['course', 'activeStudents.user'])
    //         ->findOrFail($groupId);

    //     $students = $group->activeStudents;

    //     // Har bir talaba uchun statistika
    //     $studentStats = [];

    //     foreach ($students as $student) {
    //         $totalLessons = Lesson::where('group_id', $groupId)
    //             ->where('status', 'completed')
    //             ->count();

    //         $attendances = Attendance::whereHas('lesson', function ($query) use ($groupId) {
    //             $query->where('group_id', $groupId)
    //                 ->where('status', 'completed');
    //         })
    //             ->where('student_id', $student->id)
    //             ->get();

    //         $present = $attendances->where('status', 'present')->count();
    //         $late = $attendances->where('status', 'late')->count();
    //         $absent = $attendances->where('status', 'absent')->count();
    //         $excused = $attendances->where('status', 'excused')->count();

    //         $totalCoins = 0;
    //         foreach ($attendances as $att) {
    //             $coins = 0;
    //             if ($att->status === 'present') {
    //                 $coins = 4;
    //             } elseif ($att->status === 'late') {
    //                 $coins = 3;
    //             }
    //             if ($att->status === 'present' || $att->status === 'late') {
    //                 $coins += ($att->homework_score ?? 0) + ($att->activity_score ?? 0);
    //             }
    //             $totalCoins += $coins;
    //         }

    //         $attendanceRate = $totalLessons > 0 ? round(($present + $late) / $totalLessons * 100, 1) : 0;

    //         $stats[] = [
    //             'student' => $student,
    //             'total_lessons' => $totalLessons,
    //             'present' => $present,
    //             'late' => $late,
    //             'absent' => $absent,
    //             'excused' => $excused,
    //             'total_coins' => $totalCoins,
    //             'attendance_rate' => $attendanceRate,
    //             'avg_homework' => $attendances->avg('homework_score') ?? 0,
    //             'avg_activity' => $attendances->avg('activity_score') ?? 0,
    //         ];
    //     }

    //     // Attendance rate bo'yicha tartiblash
    //     usort($studentStats, function ($a, $b) {
    //         return $b['attendance_rate'] <=> $a['attendance_rate'];
    //     });

    //     return view('Attendance.statistics', compact('group', 'studentStats', 'startDate', 'endDate'));
    // }



    public function show(Request $request, $groupId)
    {
        $group = Group::with(['course', 'activeStudents.user'])->findOrFail($groupId);

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $lessons = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year)
            ->whereMonth('lesson_date', $month)
            ->whereIn('status', ['scheduled', 'completed'])
            ->orderBy('lesson_date')
            ->get();

        $lessonDays = $lessons->pluck('lesson_date')->unique()->values();
        $students = $group->activeStudents;
        $lessonIds = $lessons->pluck('id');

        $attendanceRecords = Attendance::whereIn('lesson_id', $lessonIds)
            ->with('lesson')
            ->get();

        $attendancesByStudentDay = [];
        $studentTotalCoins = [];

        foreach ($students as $student) {
            $attendancesByStudentDay[$student->id] = [];
            $studentTotalCoins[$student->id] = 0;

            foreach ($lessonDays as $day) {
                $dateKey = $day->format('Y-m-d');

                $attendance = $attendanceRecords->first(function ($att) use ($student, $day) {
                    return $att->student_id == $student->id &&
                        $att->lesson->lesson_date->format('Y-m-d') == $day->format('Y-m-d');
                });

                if ($attendance) {
                    $dailyCoins = $this->calculateCoins($attendance);

                    $attendancesByStudentDay[$student->id][$dateKey] = [
                        'status' => $attendance->status,
                        'late_minutes' => $attendance->late_minutes ?? 0,
                        'homework_score' => $attendance->homework_score ?? 0,
                        'activity_score' => $attendance->activity_score ?? 0,
                        'daily_coins' => $dailyCoins,
                    ];

                    $studentTotalCoins[$student->id] += $dailyCoins;
                }
            }
        }

        return view('Attendance.show', compact(
            'group',
            'students',
            'lessonDays',
            'attendancesByStudentDay',
            'studentTotalCoins',
            'month',
            'year'
        ));
    }

    public function store1(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'attendances' => 'required|array',
            'attendances.*.*' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $groupId = $validated['group_id'];

            foreach ($validated['attendances'] as $studentData) {
                foreach ($studentData as $dayData) {
                    if (!isset($dayData['student_id'], $dayData['date'])) {
                        continue;
                    }

                    $lesson = Lesson::where('group_id', $groupId)
                        ->whereDate('lesson_date', $dayData['date'])
                        ->first();

                    if (!$lesson) {
                        continue;
                    }

                    $status = $dayData['status'] ?? null;
                    $lateMinutes = 0;
                    $homeworkScore = 0;
                    $activityScore = 0;

                    // Agar status yo'q bo'lsa (unchecked), attendance o'chiriladi
                    if (!$status) {
                        Attendance::where('lesson_id', $lesson->id)
                            ->where('student_id', $dayData['student_id'])
                            ->delete();
                        continue;
                    }

                    // Faqat kelgan yoki kechikkan bo'lsa homework va activity hisobga olinadi
                    if (in_array($status, ['present', 'late'])) {
                        $homeworkScore = $dayData['homework_score'] ?? 0;
                        $activityScore = $dayData['activity_score'] ?? 0;
                    }

                    // Kechikish vaqti
                    if ($status === 'late') {
                        $lateMinutes = $dayData['late_minutes'] ?? 5;
                    }

                    Attendance::updateOrCreate(
                        [
                            'lesson_id' => $lesson->id,
                            'student_id' => $dayData['student_id'],
                        ],
                        [
                            'status' => $status,
                            'late_minutes' => $lateMinutes,
                            'homework_score' => $homeworkScore,
                            'activity_score' => $activityScore,
                            'marked_by' => auth()->id(),
                            'marked_at' => now(),
                        ]
                    );
                }
            }

            // Barcha o'tgan darslarni completed qilish
            Lesson::where('group_id', $groupId)
                ->where('status', 'scheduled')
                ->whereDate('lesson_date', '<=', now())
                ->update(['status' => 'completed']);

            DB::commit();

            return redirect()
                ->route('attendance.show', $groupId)
                ->with('success', 'Davomat muvaffaqiyatli saqlandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Coin hisoblash
     * - Keldi: +4
     * - Kechikdi (0-15 daqiqa): +3
     * - Kechikdi (15+ daqiqa): +2
     * - Uy vazifa: 0-2
     * - Faollik: 0-2
     */
    private function calculateCoins(Attendance $attendance): int
    {
        $coins = 0;

        // Attendance coin
        if ($attendance->status === 'present') {
            $coins += 4;
        } elseif ($attendance->status === 'late') {
            $lateMinutes = $attendance->late_minutes ?? 0;
            $coins += ($lateMinutes <= 15) ? 3 : 2;
        }

        // Homework va Activity faqat kelgan/kechikkan bo'lsa
        if (in_array($attendance->status, ['present', 'late'])) {
            $coins += $attendance->homework_score ?? 0;
            $coins += $attendance->activity_score ?? 0;
        }

        return $coins;
    }
}
