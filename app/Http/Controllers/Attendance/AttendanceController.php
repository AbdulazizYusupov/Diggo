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
        $today = now()->toDateString();
        $todayDayName = strtolower(now()->locale('en')->dayName);

        // 1. Asosiy so'rovni boshlaymiz
        $query = Group::with(['course', 'activeStudents'])
            // ->withExists(['attendances as has_attendance_today' => function ($query) use ($today) {
            //     $query->whereDate('date', $today);
            // }])
            ->where('is_active', true);

        // 2. Rolga qarab filtrlash
        if ($user->hasRole('admin')) {
            // Admin bo'lsa hech qanday qo'shimcha filtrsiz hamma guruhlar (is_active: true bo'lgan)
        } elseif ($user->hasRole('teacher')) {
            // Agar foydalanuvchida teacher bog'liqligi bo'lsa (yoki teacher bo'lsa)
            // Faqat shu o'qituvchiga biriktirilgan guruhlarni olamiz
            $query->whereHas('teachers', function ($q) use ($user) {
                $q->where('teacher_id', $user->teacher->id); // teacher_id foydalanuvchining teacher modelidagi ID si
            });
        } else {
            // Agar admin ham, teacher ham bo'lmasa, bo'sh natija qaytarish uchun noto'g'ri shart beramiz
            $query->whereKey([]);
        }

        // 3. Qidiruv logikasi
        $query->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($c) use ($search) {
                        $c->where('name', 'like', "%{$search}%");
                    });
            });
        });

        // 4. Ma'lumotlarni olish
        $groups = $query->orderBy('name')->paginate(12)->withQueryString();

        // 5. Bugun dars borligini belgilash (transform)
        $groups->getCollection()->map(function ($group) use ($todayDayName) {
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
    public function show1(Group $group, Request $request)
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
    public function show(Request $request, $groupId)
    {
        $group = Group::with(['course', 'activeStudents.user'])
            ->findOrFail($groupId);

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);


        // Tanlangan oy uchun barcha darslar kunlarini olish
        $lessons = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year)
            ->whereMonth('lesson_date', $month)
            ->whereIn('status', ['scheduled', 'completed'])
            ->orderBy('lesson_date')
            ->get();
        // Dars kunlari
        $lessonDays = $lessons->pluck('lesson_date')->unique()->values();

        // Talabalar
        $students = $group->activeStudents;

        // Mavjud davomatlarni olish
        $lessonIds = $lessons->pluck('id');
        $attendanceRecords = Attendance::whereIn('lesson_id', $lessonIds)
            ->with('lesson')
            ->get();

        // Davomatlarni student_id va date bo'yicha strukturalash
        $attendancesByStudentDay = [];
        $studentTotalCoins = [];

        foreach ($students as $student) {
            $attendancesByStudentDay[$student->id] = [];
            $studentTotalCoins[$student->id] = 0;

            foreach ($lessonDays as $day) {
                $dateKey = $day->format('Y-m-d');

                // Shu kun uchun davomatni topish
                $attendance = $attendanceRecords->first(function ($att) use ($student, $day) {
                    return $att->student_id == $student->id &&
                        $att->lesson->lesson_date->format('Y-m-d') == $day->format('Y-m-d');
                });

                if ($attendance) {
                    // Coin hisoblash
                    $dailyCoins = 0;

                    if ($attendance->status === 'present') {
                        $dailyCoins = 4;
                    } elseif ($attendance->status === 'late') {
                        $dailyCoins = 3;
                    }

                    if ($attendance->status === 'present' || $attendance->status === 'late') {
                        $dailyCoins += ($attendance->homework_score ?? 0) + ($attendance->activity_score ?? 0);
                    }

                    $attendancesByStudentDay[$student->id][$dateKey] = [
                        'status' => $attendance->status,
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

        return view('Attendance.show1', compact(
            'group',
            'students',
            'lessonDays',
            'attendancesByStudentDay',
            'studentTotalCoins',
            'month',
            'year'
        ));
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
    public function bulkStore1(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'attendances' => 'required|array',
            'attendances.*.*.student_id' => 'required|exists:students,id',
            'attendances.*.*.date' => 'required|date',
            'attendances.*.*.status' => 'nullable|in:present,late,absent,excused',
            'attendances.*.*.homework_score' => 'nullable|integer|min:0|max:2',
            'attendances.*.*.activity_score' => 'nullable|integer|min:0|max:2',
        ]);

        DB::beginTransaction();
        try {
            $group = Group::findOrFail($validated['group_id']);
            $userId = Auth::id();

            foreach ($validated['attendances'] as $studentAttendances) {
                foreach ($studentAttendances as $attendanceData) {

                    // Agar status bo'sh bo'lsa, skip qilamiz
                    if (empty($attendanceData['status'])) {
                        continue;
                    }

                    $studentId = $attendanceData['student_id'];
                    $date = $attendanceData['date'];

                    // Studentni topamiz
                    $student = Student::findOrFail($studentId);

                    // Eski davomatni tekshiramiz
                    $oldAttendance = Attendance::where([
                        'student_id' => $studentId,
                        'group_id' => $group->id,
                        'date' => $date,
                    ])->first();

                    // Eski qiymatlarni saqlaymiz
                    $oldStatus = $oldAttendance->status ?? null;
                    $oldHomework = $oldAttendance->homework_score ?? 0;
                    $oldActivity = $oldAttendance->activity_score ?? 0;

                    // Yangi qiymatlar
                    $newStatus = $attendanceData['status'];

                    // MUHIM: Agar talaba kelmagan bo'lsa, homework va activity 0 bo'lishi kerak
                    if ($newStatus === 'absent' || $newStatus === 'excused') {
                        $newHomework = 0;
                        $newActivity = 0;
                    } else {
                        $newHomework = $attendanceData['homework_score'] ?? 0;
                        $newActivity = $attendanceData['activity_score'] ?? 0;
                    }

                    // Agar mavjud bo'lsa - update, yo'q bo'lsa - create
                    if ($oldAttendance) {
                        // Update existing attendance
                        $oldAttendance->update([
                            'status' => $newStatus,
                            'homework_score' => $newHomework,
                            'activity_score' => $newActivity,
                            'user_id' => $userId,
                        ]);
                    } else {
                        // Create new attendance
                        $attendance = Attendance::create([
                            'student_id' => $studentId,
                            'group_id' => $group->id,
                            'date' => $date,
                            'status' => $newStatus,
                            'homework_score' => $newHomework,
                            'activity_score' => $newActivity,
                            'user_id' => $userId,
                        ]);
                    }

                    // Student balansini refresh qilamiz
                    $student->refresh();
                    $currentBalance = $student->coins ?? 0;

                    // 1. PARTICIPATION (Keldi/Kechikdi) coin hisoblash
                    $oldParticipationCoins = 0;
                    if ($oldStatus === 'present') {
                        $oldParticipationCoins = 4;
                    } elseif ($oldStatus === 'late') {
                        $oldParticipationCoins = 3;
                    }

                    $newParticipationCoins = 0;
                    if ($newStatus === 'present') {
                        $newParticipationCoins = 4;
                    } elseif ($newStatus === 'late') {
                        $newParticipationCoins = 3;
                    }

                    $participationDifference = $newParticipationCoins - $oldParticipationCoins;

                    if ($participationDifference !== 0) {
                        if ($participationDifference > 0) {
                            $student->increment('coins', $participationDifference);
                            $currentBalance += $participationDifference;

                            CoinTransaction::create([
                                'student_id' => $studentId,
                                'user_id' => $userId,
                                'coins' => $participationDifference,
                                'type' => 'participation',
                                'description' => $newStatus === 'present' ? 'Darsga keldi' : 'Darsga kechikdi',
                                'balance_after' => $currentBalance,
                                'transaction_date' => $date,
                                'expiry_date' => null,
                            ]);
                        } else {
                            $deductAmount = abs($participationDifference);
                            $student->decrement('coins', $deductAmount);
                            $currentBalance -= $deductAmount;

                            CoinTransaction::create([
                                'student_id' => $studentId,
                                'user_id' => $userId,
                                'coins' => $deductAmount,
                                'type' => 'penalty',
                                'description' => 'Davomat tuzatildi (qatnashish)',
                                'balance_after' => $currentBalance,
                                'transaction_date' => $date,
                                'expiry_date' => null,
                            ]);
                        }
                    }

                    // 2. HOMEWORK coin hisoblash
                    // Faqat kelgan yoki kechikkan talabalar uchun
                    $homeworkDifference = $newHomework - $oldHomework;

                    if ($homeworkDifference !== 0) {
                        if ($homeworkDifference > 0) {
                            $student->increment('coins', $homeworkDifference);
                            $currentBalance += $homeworkDifference;

                            CoinTransaction::create([
                                'student_id' => $studentId,
                                'user_id' => $userId,
                                'coins' => $homeworkDifference,
                                'type' => 'homework',
                                'description' => "Uy vazifa bajarildi ({$newHomework} ball)",
                                'balance_after' => $currentBalance,
                                'transaction_date' => $date,
                                'expiry_date' => null,
                            ]);
                        } else {
                            $deductAmount = abs($homeworkDifference);
                            $student->decrement('coins', $deductAmount);
                            $currentBalance -= $deductAmount;

                            CoinTransaction::create([
                                'student_id' => $studentId,
                                'user_id' => $userId,
                                'coins' => $deductAmount,
                                'type' => 'penalty',
                                'description' => 'Uy vazifa balli tuzatildi',
                                'balance_after' => $currentBalance,
                                'transaction_date' => $date,
                                'expiry_date' => null,
                            ]);
                        }
                    }

                    // 3. ACTIVE INVOLVEMENT coin hisoblash
                    // Faqat kelgan yoki kechikkan talabalar uchun
                    $activityDifference = $newActivity - $oldActivity;

                    if ($activityDifference !== 0) {
                        if ($activityDifference > 0) {
                            $student->increment('coins', $activityDifference);
                            $currentBalance += $activityDifference;

                            CoinTransaction::create([
                                'student_id' => $studentId,
                                'user_id' => $userId,
                                'coins' => $activityDifference,
                                'type' => 'active_involvement',
                                'description' => "Darsda faol ishtirok ({$newActivity} ball)",
                                'balance_after' => $currentBalance,
                                'transaction_date' => $date,
                                'expiry_date' => null,
                            ]);
                        } else {
                            $deductAmount = abs($activityDifference);
                            $student->decrement('coins', $deductAmount);
                            $currentBalance -= $deductAmount;

                            CoinTransaction::create([
                                'student_id' => $studentId,
                                'user_id' => $userId,
                                'coins' => $deductAmount,
                                'type' => 'penalty',
                                'description' => 'Faollik balli tuzatildi',
                                'balance_after' => $currentBalance,
                                'transaction_date' => $date,
                                'expiry_date' => null,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Davomat muvaffaqiyatli saqlandi!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Attendance bulk store error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'group_id' => $request->group_id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function bulkStore(Request $request) {}

    public function statistics1(Group $group, Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $stats = Attendance::where('group_id', $group->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->select(
                'student_id',
                DB::raw('COUNT(*) as total_lessons'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count'),
                DB::raw('SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_count'),
                DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count')
            )
            ->groupBy('student_id')
            ->with('student.user')
            ->get();

        return view('Attendance.statistics', compact('group', 'stats', 'startDate', 'endDate'));
    }
    public function statistics($groupId, Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());
        $group = Group::with(['course', 'activeStudents.user'])
            ->findOrFail($groupId);

        $students = $group->activeStudents;

        // Har bir talaba uchun statistika
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

            $totalCoins = 0;
            foreach ($attendances as $att) {
                $coins = 0;
                if ($att->status === 'present') {
                    $coins = 4;
                } elseif ($att->status === 'late') {
                    $coins = 3;
                }
                if ($att->status === 'present' || $att->status === 'late') {
                    $coins += ($att->homework_score ?? 0) + ($att->activity_score ?? 0);
                }
                $totalCoins += $coins;
            }

            $attendanceRate = $totalLessons > 0 ? round(($present + $late) / $totalLessons * 100, 1) : 0;

            $stats[] = [
                'student' => $student,
                'total_lessons' => $totalLessons,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'excused' => $excused,
                'total_coins' => $totalCoins,
                'attendance_rate' => $attendanceRate,
                'avg_homework' => $attendances->avg('homework_score') ?? 0,
                'avg_activity' => $attendances->avg('activity_score') ?? 0,
            ];
        }

        // Attendance rate bo'yicha tartiblash
        usort($studentStats, function ($a, $b) {
            return $b['attendance_rate'] <=> $a['attendance_rate'];
        });

        return view('Attendance.statistics', compact('group', 'studentStats', 'startDate', 'endDate'));
    }
}
