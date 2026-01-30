<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Davomat guruhlarini ko'rsatish
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        
        // ❌ XATO: use function Symfony\Component\Clock\now;
        // ✅ TO'G'RI: Carbon yoki now() helper
        $today = Carbon::today()->format('Y-m-d');
        $todayDayName = strtolower(Carbon::today()->format('l'));

        /** -----------------------------
         * 1️⃣ BASE QUERY
         * ----------------------------- */
        $query = Group::query()
            ->with(['course', 'activeStudents'])
            ->where('is_active', true);

        /** -----------------------------
         * 2️⃣ ROLE FILTER
         * ----------------------------- */
        if ($user->hasRole('teacher')) {
            $query->whereHas(
                'teachers',
                fn($q) => $q->where('teacher_id', $user->teacher->id)
            );
        } elseif (!$user->hasRole('admin')) {
            // hech kim ko'rmasin
            $query->whereRaw('1 = 0');
        }

        /** -----------------------------
         * 3️⃣ SEARCH
         * ----------------------------- */
        if ($search) {
            $query->where(
                fn($q) => $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas(
                        'course',
                        fn($c) => $c->where('name', 'like', "%{$search}%")
                    )
            );
        }

        /** -----------------------------
         * 4️⃣ PAGINATION
         * ----------------------------- */
        $groups = $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        /** -----------------------------
         * 5️⃣ BUGUNGI LESSONLAR (1 QUERY)
         * ----------------------------- */
        $groupIds = $groups->pluck('id');

        $todayLessons = Lesson::whereDate('lesson_date', $today)
            ->whereIn('group_id', $groupIds)
            ->get()
            ->keyBy('group_id');

        /** -----------------------------
         * 6️⃣ ATTENDANCE BORLIGI (1 QUERY)
         * ----------------------------- */
        $attendanceExists = Attendance::whereIn(
            'lesson_id',
            $todayLessons->pluck('id')
        )
            ->whereDate('marked_at', $today)
            ->pluck('lesson_id')
            ->flip();

        /** -----------------------------
         * 7️⃣ DATA TRANSFORM (QUERY YO'Q)
         * ----------------------------- */
        $groups->getCollection()->transform(function ($group) use ($todayDayName, $todayLessons, $attendanceExists) {
            // Bugun dars mavjudmi bazada? (ASOSIY TEKSHIRUV)
            $lesson = $todayLessons->get($group->id);
            
            // ✅ VARIANT 1: Faqat bazada mavjud darslarni ko'rsatish
            $group->has_today_lesson = $lesson !== null;
            
            // Davomat yuklanganmi?
            $group->has_attendance_today = $lesson ? $attendanceExists->has($lesson->id) : false;
            
            // 🔍 QOSHIMCHA: Guruh jadvalidagi kun (debug/info uchun)
            $group->scheduled_for_today = in_array($todayDayName, $group->days_of_week ?? []);

            return $group;
        });

        return view('Attendance.index', compact(
            'groups',
            'search',
            'todayDayName'
        ));
    }

    /**
     * Guruh davomatini ko'rsatish
     */
    public function show(Request $request, $groupId)
    {
        $group = Group::with(['course', 'activeStudents.user'])->findOrFail($groupId);

        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Oy bo'yicha darslarni olish
        $lessons = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year)
            ->whereMonth('lesson_date', $month)
            ->whereIn('status', ['scheduled', 'completed'])
            ->orderBy('lesson_date')
            ->get();

        $lessonDays = $lessons->pluck('lesson_date')->unique()->values();
        $students = $group->activeStudents;
        $lessonIds = $lessons->pluck('id');

        // Davomat yozuvlarini olish
        $attendanceRecords = Attendance::whereIn('lesson_id', $lessonIds)
            ->with('lesson')
            ->get();

        // Ma'lumotlarni formatlash
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
                    $dailyCoins = $this->calculateDailyCoins($attendance);

                    $attendancesByStudentDay[$student->id][$dateKey] = [
                        'status' => $attendance->status,
                        'late_minutes' => $attendance->late_minutes ?? 0,
                        'homework_score' => $attendance->homework_score ?? 0,
                        'activity_score' => $attendance->activity_score ?? 0,
                        'daily_coins' => $dailyCoins,
                        'notes' => $attendance->notes ?? '',
                    ];

                    $studentTotalCoins[$student->id] += $dailyCoins;
                }
            }
        }
        
        $today = Carbon::today();
        
        return view('Attendance.show', compact(
            'group',
            'students',
            'lessonDays',
            'attendancesByStudentDay',
            'studentTotalCoins',
            'month',
            'year',
            'today'
        ));
    }

    /**
     * Davomatni saqlash va studentlarning coinlarini yangilash
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
            $studentCoinsToAdd = [];

            foreach ($validated['attendances'] as $studentData) {
                foreach ($studentData as $dayData) {
                    if (!isset($dayData['student_id']) || !isset($dayData['date'])) {
                        continue;
                    }

                    // Status mavjud emas yoki bo'sh bo'lsa, skip qilamiz
                    if (!isset($dayData['status']) || empty($dayData['status'])) {
                        continue;
                    }

                    // Darsni topish
                    $lesson = Lesson::where('group_id', $groupId)
                        ->whereDate('lesson_date', $dayData['date'])
                        ->first();

                    if (!$lesson) {
                        continue;
                    }

                    // Ma'lumotlarni tayyorlash
                    $attendanceData = $this->prepareAttendanceData($dayData);

                    // Eski davomatni topish (coin farqini hisoblash uchun)
                    $oldAttendance = Attendance::where('lesson_id', $lesson->id)
                        ->where('student_id', $dayData['student_id'])
                        ->first();

                    $oldCoins = $oldAttendance ? $this->calculateDailyCoins($oldAttendance) : 0;

                    // Davomatni saqlash
                    Attendance::updateOrCreate(
                        [
                            'lesson_id' => $lesson->id,
                            'student_id' => $dayData['student_id'],
                        ],
                        $attendanceData
                    );

                    // Yangi coinlarni hisoblash
                    $newCoins = $attendanceData['daily_coins'];
                    $coinDifference = $newCoins - $oldCoins;

                    // Coin farqini yig'ish
                    if (!isset($studentCoinsToAdd[$dayData['student_id']])) {
                        $studentCoinsToAdd[$dayData['student_id']] = 0;
                    }
                    $studentCoinsToAdd[$dayData['student_id']] += $coinDifference;
                }
            }

            // Studentlarning coinlarini yangilash
            foreach ($studentCoinsToAdd as $studentId => $coinsToAdd) {
                if ($coinsToAdd != 0) {
                    DB::table('students')
                        ->where('id', $studentId)
                        ->increment('coins', $coinsToAdd);
                }
            }

            // O'tgan darslarni completed qilish
            Lesson::where('group_id', $groupId)
                ->where('status', 'scheduled')
                ->whereDate('lesson_date', '<=', Carbon::now())
                ->update(['status' => 'completed']);

            DB::commit();

            return redirect()
                ->route('attendance.show', [$groupId])
                ->with('success', 'Davomat muvaffaqiyatli saqlandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Statistika sahifasi
     */
    public function statistics($groupId, Request $request)
    {
        $group = Group::with(['course', 'activeStudents.user'])->findOrFail($groupId);

        $month = $request->get('month');
        $year = $request->get('year', Carbon::now()->year);

        $students = $group->activeStudents;
        $studentStats = [];

        // Darslar query
        $lessonsQuery = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year);

        if ($month) {
            $lessonsQuery->whereMonth('lesson_date', $month);
        }

        $totalLessons = $lessonsQuery->count();
        $lessonIds = $lessonsQuery->pluck('id')->toArray();

        // Har bir student uchun statistika
        foreach ($students as $student) {
            $attendances = Attendance::whereIn('lesson_id', $lessonIds)
                ->where('student_id', $student->id)
                ->with('lesson')
                ->get();

            $present = $attendances->where('status', 'present')->count();
            $late = $attendances->where('status', 'late')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $excused = $attendances->where('status', 'excused')->count();

            $totalCoins = $attendances->sum('daily_coins');

            $attendedLessons = $attendances->whereIn('status', ['present', 'late']);
            $avgHomework = $attendedLessons->count() > 0
                ? $attendedLessons->avg('homework_score')
                : 0;
            $avgActivity = $attendedLessons->count() > 0
                ? $attendedLessons->avg('activity_score')
                : 0;

            $attendanceRate = $totalLessons > 0
                ? round(($present + $late) / $totalLessons * 100, 1)
                : 0;

            $lateDetails = $attendances->where('status', 'late')->map(function ($att) {
                return [
                    'date' => $att->lesson->lesson_date->format('d.m.Y'),
                    'minutes' => $att->late_minutes ?? 0,
                    'reason' => $att->notes ?? '',
                ];
            })->values();

            $studentStats[] = [
                'student' => $student,
                'total_lessons' => $totalLessons,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'excused' => $excused,
                'total_coins' => $totalCoins,
                'attendance_rate' => $attendanceRate,
                'avg_homework' => round($avgHomework, 1),
                'avg_activity' => round($avgActivity, 1),
                'late_details' => $lateDetails,
            ];
        }

        return view('Attendance.statistics', compact(
            'group',
            'studentStats',
            'month',
            'year'
        ));
    }

    /**
     * Davomat ma'lumotlarini tayyorlash
     */
    private function prepareAttendanceData(array $dayData): array
    {
        $status = $dayData['status'] ?? 'absent';
        $lateMinutes = intval($dayData['late_minutes'] ?? 0);
        $notes = $dayData['notes'] ?? '';
        $homeworkScore = 0;
        $activityScore = 0;

        // Agar kechikish 30+ daqiqa bo'lsa, kelmagan deb hisoblanadi
        if ($status === 'late' && $lateMinutes >= 30) {
            $status = 'absent';
            $lateMinutes = 0;
        }

        // Homework va Activity faqat kelgan/kechikkan bo'lsa
        if ($status === 'present' || $status === 'late') {
            $homeworkScore = min(3, max(0, intval($dayData['homework_score'] ?? 0)));
            $activityScore = min(3, max(0, intval($dayData['activity_score'] ?? 0)));
        }

        // Davomat coinlarini hisoblash
        $attendanceCoins = $this->calculateAttendanceCoins($status, $lateMinutes);

        // Kunlik jami coinlar
        $dailyCoins = $attendanceCoins + $homeworkScore + $activityScore;

        return [
            'status' => $status,
            'late_minutes' => $status === 'late' ? $lateMinutes : null,
            'notes' => $notes,
            'homework_score' => $homeworkScore,
            'activity_score' => $activityScore,
            'attendance_coins' => $attendanceCoins,
            'daily_coins' => $dailyCoins,
            'marked_by' => Auth::id(),
            'marked_at' => Carbon::parse($dayData['date']),
        ];
    }

    /**
     * Davomat coinlarini hisoblash
     */
    private function calculateAttendanceCoins(string $status, int $lateMinutes): int
    {
        if ($status === 'present') {
            return 2;
        }

        if ($status === 'late') {
            if ($lateMinutes < 15) {
                return 2;
            } elseif ($lateMinutes < 30) {
                return 1;
            }
        }

        return 0;
    }

    /**
     * Kunlik jami coinlarni hisoblash
     */
    private function calculateDailyCoins(Attendance $attendance): int
    {
        $coins = 0;

        if ($attendance->status === 'present') {
            $coins += 2;
        } elseif ($attendance->status === 'late') {
            $lateMinutes = $attendance->late_minutes ?? 0;

            if ($lateMinutes < 15) {
                $coins += 2;
            } elseif ($lateMinutes < 30) {
                $coins += 1;
            }
        }

        if (in_array($attendance->status, ['present', 'late'])) {
            $coins += min(3, max(0, $attendance->homework_score ?? 0));
            $coins += min(3, max(0, $attendance->activity_score ?? 0));
        }

        return $coins;
    }

    public function create()
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}