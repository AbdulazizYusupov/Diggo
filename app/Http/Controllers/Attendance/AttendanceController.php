<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Models\CoinTransaction;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\User;
use App\Notifications\AttendanceMarked\AttendanceMarkedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{

    private const COINS_PRESENT         = 2;
    private const COINS_LATE_UNDER_15   = 2;
    private const COINS_LATE_UNDER_30   = 1;
    private const COINS_ABSENT          = 0;
    private const MAX_HOMEWORK_SCORE    = 3;
    private const MAX_ACTIVITY_SCORE    = 3;
    private const LATE_ABSENT_THRESHOLD = 30;

    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'page'   => 'nullable|integer',
        ]);

        $search       = $validated['search'] ?? null;
        $today        = Carbon::today();
        $todayDate    = $today->toDateString();
        $todayDayName = strtolower($today->format('l'));

        $groups = Group::query()
            ->with(['course', 'activeStudents'])
            ->where('is_active', true)
            ->when($search, fn($q) => $q->search($search))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $groupIds = $groups->pluck('id');

        $lessons = Lesson::whereIn('group_id', $groupIds)
            ->whereDate('lesson_date', $todayDate)
            ->whereIn('status', ['scheduled', 'completed', 'rescheduled'])
            ->withCount('attendances')
            ->get()
            ->groupBy('group_id');

        $groups->getCollection()->transform(function ($group) use ($lessons, $todayDayName) {
            $groupLessons = $lessons->get($group->id, collect());

            $group->has_today_lesson    = $groupLessons->isNotEmpty();
            $group->today_lesson_count  = $groupLessons->count();
            $group->scheduled_for_today = in_array($todayDayName, (array) ($group->days_of_week ?? []));

            $markedLessonsCount = $groupLessons->filter(fn($l) => $l->attendances_count > 0)->count();

            $group->has_attendance_today   = $group->has_today_lesson
                && ($markedLessonsCount === $group->today_lesson_count);
            $group->has_partial_attendance = $group->has_today_lesson
                && ($markedLessonsCount > 0 && $markedLessonsCount < $group->today_lesson_count);

            return $group;
        });

        return view('Attendance.index', compact('groups', 'search', 'todayDayName'));
    }


    public function show(Request $request, int $groupId)
    {
        $group = Group::with(['course', 'activeStudents.user'])->findOrFail($groupId);

        $month = (int) $request->get('month', Carbon::now()->month);
        $year  = (int) $request->get('year',  Carbon::now()->year);

        $lessons = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year)
            ->whereMonth('lesson_date', $month)
            ->whereIn('status', ['scheduled', 'completed', 'rescheduled'])
            ->orderBy('lesson_date')
            ->orderBy('start_time')
            ->get();

        $students  = $group->activeStudents;
        $lessonIds = $lessons->pluck('id');


        $attendanceRecords = Attendance::whereIn('lesson_id', $lessonIds)
            ->get([
                'id',
                'lesson_id',
                'student_id',
                'status',
                'late_minutes',
                'homework_score',
                'activity_score',
                'notes',
            ])
            ->groupBy(fn($att) => "{$att->student_id}_{$att->lesson_id}");

        $attendanceMap     = [];
        $studentTotalCoins = [];

        foreach ($students as $student) {
            $attendanceMap[$student->id]     = [];
            $studentTotalCoins[$student->id] = 0;

            foreach ($lessons as $lesson) {
                $key        = "{$student->id}_{$lesson->id}";
                $attendance = $attendanceRecords->get($key)?->first();

                if ($attendance) {
                    $coins = $this->calcDailyCoins($attendance);
                    $studentTotalCoins[$student->id] += $coins;

                    $attendanceMap[$student->id][$lesson->id] = [
                        'status'         => $attendance->status,
                        'late_minutes'   => $attendance->late_minutes ?? 0,
                        'homework_score' => $attendance->homework_score ?? 0,
                        'activity_score' => $attendance->activity_score ?? 0,
                        'daily_coins'    => $coins,
                        'notes'          => $attendance->notes ?? '',
                    ];
                }
            }
        }

        $lessonsPerDay = $lessons
            ->groupBy(fn($l) => $l->lesson_date->format('Y-m-d'))
            ->map(fn($dayLessons) => $dayLessons->count());
        $statusTypes = [
            'present' => ['color' => 'emerald', 'icon' => 'M5 13l4 4L19 7',                                                 'strokeW' => '2.5'],
            'late'    => ['color' => 'amber',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',                  'strokeW' => '2'],
            'absent'  => ['color' => 'rose',    'icon' => 'M6 18L18 6M6 6l12 12',                                          'strokeW' => '2.5'],
        ];
        $today = Carbon::today();

        return view('Attendance.show', compact(
            'group',
            'students',
            'lessons',
            'lessonsPerDay',
            'attendanceMap',
            'studentTotalCoins',
            'month',
            'year',
            'today',
            'statusTypes'
        ));
    }

    // =========================================================================
    // STORE — Davomatni saqlash
    // =========================================================================
    public function store(StoreAttendanceRequest $request)
    {
        $validated = $request->validated();
        $groupId   = (int) $validated['group_id'];

        Group::findOrFail($groupId);

        DB::beginTransaction();
        try {
            // ─────────────────────────────────────────────────────────────────
            // 1-QADAM: Entry larni yig'amiz
            // ─────────────────────────────────────────────────────────────────
            $allLessonIds  = [];
            $allStudentIds = [];
            $entries       = [];

            foreach ($validated['attendances'] as $studentRows) {
                foreach ($studentRows as $entry) {
                    if (empty($entry['student_id']) || empty($entry['lesson_id'])) {
                        continue;
                    }
                    $allLessonIds[]  = (int) $entry['lesson_id'];
                    $allStudentIds[] = (int) $entry['student_id'];
                    $entries[]       = $entry;
                }
            }

            if (empty($entries)) {
                DB::commit();
                return redirect()
                    ->route('attendance.show', $groupId)
                    ->with('success', 'Davomat saqlandi!');
            }

            $allLessonIds  = array_unique($allLessonIds);
            $allStudentIds = array_unique($allStudentIds);

            // ─────────────────────────────────────────────────────────────────
            // 2-QADAM: Lessons — group_id xavfsizlik tekshiruvi bilan — 1 query
            // ─────────────────────────────────────────────────────────────────
            $lessonsMap = Lesson::with('group')
                ->whereIn('id', $allLessonIds)
                ->where('group_id', $groupId)
                ->get()
                ->keyBy('id');

            // ─────────────────────────────────────────────────────────────────
            // 3-QADAM: Faqat shu guruhning active studentlarini tekshiramiz — 1 query
            // group_students pivot jadvalidan foydalanamiz (GroupStudent modeli)
            // ─────────────────────────────────────────────────────────────────
            $validStudentIds = DB::table('group_students')
                ->where('group_id', $groupId)
                ->where('status', 'active')
                ->whereIn('student_id', $allStudentIds)
                ->pluck('student_id')
                ->flip()    // O(1) lookup uchun
                ->toArray();

            // ─────────────────────────────────────────────────────────────────
            // 4-QADAM: Mavjud attendancelar — 1 query
            // ─────────────────────────────────────────────────────────────────
            $existingAttendances = Attendance::whereIn('lesson_id', $allLessonIds)
                ->whereIn('student_id', $allStudentIds)
                ->get()
                ->keyBy(fn($att) => "{$att->student_id}_{$att->lesson_id}");

            // ─────────────────────────────────────────────────────────────────
            // 5-QADAM: Loop — ichida HECH QANDAY DB query yo'q
            // ─────────────────────────────────────────────────────────────────
            $studentCoinDeltas = [];
            $coinTransactions  = [];
            $toDeleteIds       = [];
            $toUpsert          = [];

            foreach ($entries as $entry) {
                $studentId = (int) $entry['student_id'];
                $lessonId  = (int) $entry['lesson_id'];

                $lesson = $lessonsMap->get($lessonId);
                if (!$lesson) {
                    continue; // Bu guruhga tegishli bo'lmagan lesson
                }

                if (!array_key_exists($studentId, $validStudentIds)) {
                    continue; // Bu guruhga tegishli bo'lmagan student
                }

                $attKey        = "{$studentId}_{$lessonId}";
                $oldAttendance = $existingAttendances->get($attKey);
                $oldCoins      = $oldAttendance ? $this->calcDailyCoins($oldAttendance) : 0;

                // Status bo'sh = o'chirish
                if (empty($entry['status'])) {
                    if ($oldAttendance) {
                        $toDeleteIds[]                 = $oldAttendance->id;
                        $studentCoinDeltas[$studentId] = ($studentCoinDeltas[$studentId] ?? 0) - $oldCoins;

                        if ($oldCoins > 0) {
                            $coinTransactions[$studentId][] = [
                                'coins'       => -$oldCoins,
                                'type'        => 'refund',
                                'description' => $this->buildDeleteDescription($lesson),
                                'date'        => $lesson->lesson_date->format('Y-m-d'),
                            ];
                        }
                    }
                    continue;
                }

                // buildAttendanceData ichida late→absent konversiyasi bajariladi
                $data      = $this->buildAttendanceData($entry, $lesson);
                $newCoins  = $this->calcDailyCoinsFromData($data);
                $coinDelta = $newCoins - $oldCoins;

                $toUpsert[] = [
                    'lesson_id'     => $lessonId,
                    'student_id'    => $studentId,
                    'data'          => $data,
                    'oldAttendance' => $oldAttendance,
                ];

                $studentCoinDeltas[$studentId] = ($studentCoinDeltas[$studentId] ?? 0) + $coinDelta;

                if ($coinDelta !== 0) {
                    $coinTransactions[$studentId][] = [
                        'coins'       => $coinDelta,
                        'type'        => $coinDelta > 0 ? 'participation' : 'penalty',
                        'description' => $this->buildCoinDescription($data, $oldAttendance, $lesson),
                        'date'        => $lesson->lesson_date->format('Y-m-d'),
                    ];
                }
            }

            // ─────────────────────────────────────────────────────────────────
            // 6-QADAM: O'chirishlar — 1 query
            // ─────────────────────────────────────────────────────────────────
            if (!empty($toDeleteIds)) {
                Attendance::whereIn('id', $toDeleteIds)->delete();
            }

            // ─────────────────────────────────────────────────────────────────
            // 7-QADAM: Saqlashlar — updateOrCreate
            // Faqat Attendance::$fillable ustunlari (lesson_id, student_id,
            // marked_by, status, late_minutes, homework_score, activity_score,
            // notes, marked_at) — daily_coins / attendance_coins yo'q
            // ─────────────────────────────────────────────────────────────────
            foreach ($toUpsert as $item) {
                Attendance::updateOrCreate(
                    ['lesson_id' => $item['lesson_id'], 'student_id' => $item['student_id']],
                    $item['data']
                );
            }

            // ─────────────────────────────────────────────────────────────────
            // 8-QADAM: CoinTransaction AVVAL, keyin coins increment
            //          (ledger integriteti: ikkalasi bitta DB transaction ichida)
            // ─────────────────────────────────────────────────────────────────
            if (!empty($coinTransactions)) {
                $balances = DB::table('students')
                    ->whereIn('id', array_keys($coinTransactions))
                    ->pluck('coins', 'id');

                $coinRows = [];
                $now      = now();

                foreach ($coinTransactions as $studentId => $txns) {
                    // Oldinga yurib to'g'ri balance_after hisoblaymiz
                    $runningBalance = (int) ($balances[$studentId] ?? 0);

                    foreach ($txns as $txn) {
                        $runningBalance += $txn['coins'];

                        $coinRows[] = [
                            'student_id'       => $studentId,
                            'user_id'          => Auth::id(),
                            'activity_id'      => null,
                            'coins'            => $txn['coins'],
                            'type'             => $txn['type'],
                            'description'      => $txn['description'],
                            'balance_after'    => $runningBalance,
                            'transaction_date' => $txn['date'],
                            'expiry_date'      => null,
                            'created_at'       => $now,
                            'updated_at'       => $now,
                        ];
                    }
                }

                // Avval transaction yozuvi kiritiladi ...
                CoinTransaction::insert($coinRows);
            }

            // ... keyin coins yangilanadi (har ikkalasi bitta transaction ichida)
            foreach ($studentCoinDeltas as $studentId => $delta) {
                if ($delta !== 0) {
                    DB::table('students')->where('id', $studentId)->increment('coins', $delta);
                }
            }

            // ─────────────────────────────────────────────────────────────────
            // 9-QADAM: Bildirishnomalar — barcha User lar bitta query da
            // ─────────────────────────────────────────────────────────────────
            $allNotifyStudentIds = array_unique(array_column($toUpsert, 'student_id'));

            if (!empty($allNotifyStudentIds)) {
                // [student_id => user_id] — Student modeli: user_id ustuni bor
                $studentUserMap = DB::table('students')
                    ->whereIn('id', $allNotifyStudentIds)
                    ->pluck('user_id', 'id');

                // Barcha User larni bitta query da yuklaymiz
                $usersById = User::whereIn('id', $studentUserMap->values()->filter()->unique())
                    ->get()
                    ->keyBy('id');

                $groupName = $lessonsMap->first()?->group?->name ?? '';

                foreach ($toUpsert as $item) {
                    $studentId = $item['student_id'];
                    $data      = $item['data'];
                    $lesson    = $lessonsMap->get($item['lesson_id']);
                    $coins     = $this->calcDailyCoinsFromData($data);

                    // Absent + 0 coin bo'lsa bildirishnoma yubormaymiz
                    if (!$lesson || ($coins <= 0 && $data['status'] === 'absent')) {
                        continue;
                    }

                    $userId = $studentUserMap->get($studentId);
                    $user   = $userId ? $usersById->get($userId) : null;

                    if (!$user) {
                        continue;
                    }

                    $user->notify(new AttendanceMarkedNotification(
                        status: $data['status'],
                        dailyCoins: $coins,
                        homeworkScore: $data['homework_score'],
                        activityScore: $data['activity_score'],
                        lessonDate: $lesson->lesson_date->format('d.m.Y'),
                        lessonTime: $this->formatTime($lesson->start_time),
                        groupName: $groupName,
                    ));
                }
            }

            // ─────────────────────────────────────────────────────────────────
            // 10-QADAM: Faqat submit qilingan darslarni completed ga o'tkazamiz
            //           (hamma scheduled darslarni emas — faqat shu submissiyada qatnashganlarni)
            // ─────────────────────────────────────────────────────────────────
            $submittedLessonIds = array_unique(array_column($toUpsert, 'lesson_id'));

            if (!empty($submittedLessonIds)) {
                Lesson::whereIn('id', $submittedLessonIds)
                    ->where('status', 'scheduled')
                    ->whereDate('lesson_date', '<=', Carbon::today())
                    ->update(['status' => 'completed']);
            }

            DB::commit();

            return redirect()
                ->route('attendance.show', $groupId)
                ->with('success', 'Davomat muvaffaqiyatli saqlandi!');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())
                ->withInput();
        }
    }

    // =========================================================================
    // STATISTICS — Guruh statistikasi
    // =========================================================================
    public function statistics(int $groupId, Request $request)
    {
        $group = Group::with(['course', 'activeStudents.user'])->findOrFail($groupId);

        $month = $request->get('month') ? (int) $request->get('month') : null;
        $year  = (int) $request->get('year', Carbon::now()->year);

        $students = $group->activeStudents;

        $lessonsQuery = Lesson::where('group_id', $groupId)
            ->whereIn('status', ['scheduled', 'completed', 'rescheduled'])
            ->whereYear('lesson_date', $year);

        if ($month) {
            $lessonsQuery->whereMonth('lesson_date', $month);
        }

        $lessons      = $lessonsQuery->orderBy('lesson_date')->orderBy('start_time')->get();
        $totalLessons = $lessons->count();
        $lessonIds    = $lessons->pluck('id')->toArray();

        $totalDays = $lessons->pluck('lesson_date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->unique()
            ->count();

        // Barcha attendancelarni bitta queryda — N+1 yo'q
        $allAttendances = Attendance::whereIn('lesson_id', $lessonIds)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');

        // lesson_date tez lookup uchun
        $lessonsById = $lessons->keyBy('id');

        $studentStats = [];

        foreach ($students as $student) {
            $attendances = $allAttendances->get($student->id, collect());

            $present = $attendances->where('status', 'present')->count();
            $late    = $attendances->where('status', 'late')->count();
            $absent  = $attendances->where('status', 'absent')->count();
            $excused = $attendances->where('status', 'excused')->count();

            $attended = $attendances->whereIn('status', ['present', 'late']);

            $lessonRate = $totalLessons > 0
                ? round(($present + $late) / $totalLessons * 100, 1)
                : 0;

            $attendedDays = $attended
                ->map(fn($a) => $lessonsById->get($a->lesson_id)?->lesson_date?->format('Y-m-d'))
                ->filter()
                ->unique()
                ->count();

            $dayRate = $totalDays > 0
                ? round($attendedDays / $totalDays * 100, 1)
                : 0;

            // Kechikish tafsilotlari — lessonsById dan olamiz (lesson relation yo'q)
            $lateDetails = $attendances->where('status', 'late')->map(function ($att) use ($lessonsById) {
                $lesson = $lessonsById->get($att->lesson_id);

                return [
                    'date'    => $lesson?->lesson_date?->format('d.m.Y') ?? '—',
                    'time'    => $lesson ? $this->formatTime($lesson->start_time) : '—',
                    'minutes' => $att->late_minutes ?? 0,
                    'reason'  => $att->notes ?? '',
                ];
            })->values();

            // daily_coins ustuni yo'q — PHP da hisoblaymiz
            $totalCoins = $attendances->sum(fn($att) => $this->calcDailyCoins($att));

            $studentStats[] = [
                'student'         => $student,
                'total_lessons'   => $totalLessons,
                'total_days'      => $totalDays,
                'present'         => $present,
                'late'            => $late,
                'absent'          => $absent,
                'excused'         => $excused,
                'total_coins'     => $totalCoins,
                'attendance_rate' => $lessonRate,
                'day_rate'        => $dayRate,
                'avg_homework'    => $attended->count() > 0 ? round($attended->avg('homework_score'), 1) : 0,
                'avg_activity'    => $attended->count() > 0 ? round($attended->avg('activity_score'), 1) : 0,
                'late_details'    => $lateDetails,
            ];
        }

        usort($studentStats, fn($a, $b) => $b['total_coins'] <=> $a['total_coins']);

        return view('Attendance.statistics', compact(
            'group',
            'studentStats',
            'month',
            'year',
            'totalLessons',
            'totalDays'
        ));
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Store uchun attendance data array tayyorlash.
     * late→absent konversiyasi shu yerda hal qilinadi.
     *
     * Qaytarilgan array faqat Attendance::$fillable ustunlarini o'z ichiga oladi:
     * status, late_minutes, notes, homework_score, activity_score, marked_by, marked_at
     */
    private function buildAttendanceData(array $entry, Lesson $lesson): array
    {
        $status      = $entry['status'];
        $lateMinutes = (int) ($entry['late_minutes'] ?? 0);
        $notes       = trim($entry['notes'] ?? '');

        // 30+ daqiqa kechikkan = absent
        if ($status === 'late' && $lateMinutes >= self::LATE_ABSENT_THRESHOLD) {
            $status      = 'absent';
            $lateMinutes = 0;
        }

        $homeworkScore = 0;
        $activityScore = 0;

        if (in_array($status, ['present', 'late'])) {
            $homeworkScore = min(self::MAX_HOMEWORK_SCORE, max(0, (int) ($entry['homework_score'] ?? 0)));
            $activityScore = min(self::MAX_ACTIVITY_SCORE, max(0, (int) ($entry['activity_score'] ?? 0)));
        }

        return [
            'status'         => $status,
            'late_minutes'   => $status === 'late' ? $lateMinutes : null,
            'notes'          => $notes ?: null,
            'homework_score' => $homeworkScore,
            'activity_score' => $activityScore,
            'marked_by'      => Auth::id(),
            'marked_at'      => now(),
        ];
    }

    /**
     * Saqlangan Attendance modeldan kunlik coin hisoblash.
     * daily_coins ustuni yo'q — status + scores dan hisoblanadi.
     */
    private function calcDailyCoins(Attendance $attendance): int
    {
        $coins = $this->calcAttendanceCoins($attendance->status, $attendance->late_minutes ?? 0);

        if (in_array($attendance->status, ['present', 'late'])) {
            $coins += min(self::MAX_HOMEWORK_SCORE, max(0, $attendance->homework_score ?? 0));
            $coins += min(self::MAX_ACTIVITY_SCORE, max(0, $attendance->activity_score ?? 0));
        }

        return $coins;
    }

    /**
     * buildAttendanceData() natijasidan kunlik coin hisoblash.
     */
    private function calcDailyCoinsFromData(array $data): int
    {
        $coins = $this->calcAttendanceCoins($data['status'], $data['late_minutes'] ?? 0);

        if (in_array($data['status'], ['present', 'late'])) {
            $coins += $data['homework_score'] ?? 0;
            $coins += $data['activity_score'] ?? 0;
        }

        return $coins;
    }

    /**
     * Faqat davomat statusi uchun coin (homework/activity siz).
     */
    private function calcAttendanceCoins(string $status, int $lateMinutes): int
    {
        return match (true) {
            $status === 'present'                                             => self::COINS_PRESENT,
            $status === 'late' && $lateMinutes < 15                          => self::COINS_LATE_UNDER_15,
            $status === 'late' && $lateMinutes < self::LATE_ABSENT_THRESHOLD => self::COINS_LATE_UNDER_30,
            default                                                           => self::COINS_ABSENT,
        };
    }

    /**
     * CoinTransaction uchun tavsif matni (saqlash/yangilash).
     * Barcha o'zgarishlar (status, homework, faollik) ko'rsatiladi.
     */
    private function buildCoinDescription(array $data, ?Attendance $old, Lesson $lesson): string
    {
        $parts   = [];
        $dateStr = $lesson->lesson_date->format('d.m.Y');
        $timeStr = $this->formatTime($lesson->start_time);

        $oldAttCoins = $old
            ? $this->calcAttendanceCoins($old->status, $old->late_minutes ?? 0)
            : 0;

        $newAttCoins = $this->calcAttendanceCoins($data['status'], $data['late_minutes'] ?? 0);

        if ($newAttCoins !== $oldAttCoins || $old === null) {
            $parts[] = match ($data['status']) {
                'present' => 'Keldi',
                'late'    => "Kechikdi ({$data['late_minutes']} min)",
                'absent'  => 'Kelmadi',
                'excused' => 'Sababli',
                default   => $data['status'],
            };
        }

        if (($data['homework_score'] ?? 0) > 0) {
            $parts[] = "Uy vazifasi: {$data['homework_score']}";
        } elseif ($old && ($old->homework_score ?? 0) > 0 && ($data['homework_score'] ?? 0) === 0) {
            $parts[] = 'Uy vazifasi olib tashlandi';
        }

        if (($data['activity_score'] ?? 0) > 0) {
            $parts[] = "Faollik: {$data['activity_score']}";
        } elseif ($old && ($old->activity_score ?? 0) > 0 && ($data['activity_score'] ?? 0) === 0) {
            $parts[] = 'Faollik olib tashlandi';
        }

        $desc = implode(', ', $parts) ?: 'Davomat yangilandi';

        return $timeStr
            ? "{$desc} — {$dateStr} {$timeStr}"
            : "{$desc} — {$dateStr}";
    }

    /**
     * CoinTransaction uchun tavsif matni (o'chirish).
     */
    private function buildDeleteDescription(Lesson $lesson): string
    {
        $dateStr = $lesson->lesson_date->format('d.m.Y');
        $timeStr = $this->formatTime($lesson->start_time);

        return $timeStr
            ? "Davomat o'chirildi — {$dateStr} {$timeStr}"
            : "Davomat o'chirildi — {$dateStr}";
    }

    /**
     * start_time ni HH:MM formatiga o'tkazish.
     * "20:53:00" yoki "2026-02-26 20:53:00" formatlarini qabul qiladi.
     * null yoki bo'sh string kelsa — bo'sh qaytaradi.
     */
    private function formatTime(?string $startTime): string
    {
        if (empty($startTime)) {
            return '';
        }

        return Carbon::parse($startTime)->format('H:i');
    }

    // =========================================================================
    // UNUSED — Route::resource() interfeysi uchun
    // =========================================================================
    public function create(): never
    {
        abort(404);
    }
    public function edit(string $id): never
    {
        abort(404);
    }
    public function update(Request $request, string $id): never
    {
        abort(404);
    }
    public function destroy(string $id): never
    {
        abort(404);
    }
}
