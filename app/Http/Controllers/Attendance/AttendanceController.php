<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CoinTransaction;
use App\Models\Group;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // =========================================================================
    // COIN KONSTANTALARI
    // =========================================================================
    private const COINS_PRESENT         = 2;
    private const COINS_LATE_UNDER_15   = 2;
    private const COINS_LATE_UNDER_30   = 1;
    private const COINS_ABSENT          = 0;
    private const MAX_HOMEWORK_SCORE    = 3;
    private const MAX_ACTIVITY_SCORE    = 3;
    private const LATE_ABSENT_THRESHOLD = 30; // 30+ daqiqa = absent

    // =========================================================================
    // INDEX — Guruhlar ro'yxati (bugungi darslar bilan)
    // =========================================================================
    public function index(Request $request)
    {
        $search       = $request->input('search');
        $today        = Carbon::today()->format('Y-m-d');
        $todayDayName = strtolower(Carbon::today()->format('l'));

        $query = Group::query()
            ->with(['course', 'activeStudents'])
            ->where('is_active', true);

        if ($search) {
            $query->where(fn($q) =>
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('course', fn($c) => $c->where('name', 'like', "%{$search}%"))
            );
        }

        $groups   = $query->orderBy('name')->paginate(12)->withQueryString();
        $groupIds = $groups->pluck('id');

        // ✅ groupBy — bir guruhda bir nechta dars bo'lsa hammasi saqlanadi
        $todayLessonsMap = Lesson::whereDate('lesson_date', $today)
            ->whereIn('group_id', $groupIds)
            ->whereIn('status', ['scheduled', 'completed', 'rescheduled'])
            ->orderBy('start_time')
            ->get()
            ->groupBy('group_id');

        // Bugungi barcha lesson id larni olish
        $todayLessonIds = $todayLessonsMap->flatten()->pluck('id');

        // Davomat belgilangan lesson id lar (O(1) lookup uchun flip)
        $markedLessonIds = Attendance::whereIn('lesson_id', $todayLessonIds)
            ->pluck('lesson_id')
            ->unique()
            ->flip();

        $groups->getCollection()->transform(function ($group) use ($todayDayName, $todayLessonsMap, $markedLessonIds) {
            $groupLessons = $todayLessonsMap->get($group->id);

            $group->has_today_lesson    = $groupLessons !== null && $groupLessons->isNotEmpty();
            $group->today_lesson_count  = $groupLessons ? $groupLessons->count() : 0;
            $group->scheduled_for_today = in_array($todayDayName, $group->days_of_week ?? []);

            // Barcha darslar belgilangandagina to'liq true
            $group->has_attendance_today = $groupLessons
                ? $groupLessons->every(fn($l) => $markedLessonIds->has($l->id))
                : false;

            // Qisman belgilangan (ba'zi darslar bor, hammasi emas)
            $group->has_partial_attendance = $groupLessons
                && !$group->has_attendance_today
                && $groupLessons->some(fn($l) => $markedLessonIds->has($l->id));

            return $group;
        });

        return view('Attendance.index', compact('groups', 'search', 'todayDayName'));
    }

    // =========================================================================
    // SHOW — Guruh davomati (oylik jadval)
    // =========================================================================
    public function show(Request $request, int $groupId)
    {
        $group = Group::with(['course', 'activeStudents.user'])->findOrFail($groupId);

        $month = (int) $request->get('month', Carbon::now()->month);
        $year  = (int) $request->get('year',  Carbon::now()->year);

        // Oy bo'yicha barcha darslar — lesson_date + start_time bo'yicha tartiblangan
        $lessons = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year)
            ->whereMonth('lesson_date', $month)
            ->whereIn('status', ['scheduled', 'completed', 'rescheduled'])
            ->orderBy('lesson_date')
            ->orderBy('start_time')
            ->get();

        $students  = $group->activeStudents;
        $lessonIds = $lessons->pluck('id');

        // Bitta query — barcha davomat yozuvlari
        // groupBy composite key orqali O(1) lookup
        $attendanceRecords = Attendance::whereIn('lesson_id', $lessonIds)
            ->get()
            ->groupBy(fn($att) => "{$att->student_id}_{$att->lesson_id}");

        // attendanceMap[student_id][lesson_id] = array
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

        // View uchun — bir kunda nechta dars bor (header colspan/badge uchun)
        $lessonsPerDay = $lessons
            ->groupBy(fn($l) => $l->lesson_date->format('Y-m-d'))
            ->map(fn($dayLessons) => $dayLessons->count());

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
            'today'
        ));
    }

    // =========================================================================
    // STORE — Davomatni saqlash
    // =========================================================================
   public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id'        => 'required|exists:groups,id',
            'attendances'     => 'required|array',
            'attendances.*.*' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $groupId = (int) $validated['group_id'];

            // ─────────────────────────────────────────────────────────────────
            // 1-QADAM: Barcha entry larni tekshirib, ID larni yig'amiz
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
                return redirect()->route('attendance.show', $groupId)->with('success', 'Davomat saqlandi!');
            }

            $allLessonIds  = array_unique($allLessonIds);
            $allStudentIds = array_unique($allStudentIds);

            // ─────────────────────────────────────────────────────────────────
            // 2-QADAM: Lessons — 1 query (+ group_id xavfsizlik tekshiruvi)
            // ─────────────────────────────────────────────────────────────────
            $lessonsMap = Lesson::whereIn('id', $allLessonIds)
                ->where('group_id', $groupId)
                ->get()
                ->keyBy('id'); // [lesson_id => Lesson]

            // ─────────────────────────────────────────────────────────────────
            // 3-QADAM: Mavjud attendancelar — 1 query
            // ─────────────────────────────────────────────────────────────────
            $existingAttendances = Attendance::whereIn('lesson_id', $allLessonIds)
                ->whereIn('student_id', $allStudentIds)
                ->get()
                ->keyBy(fn($att) => "{$att->student_id}_{$att->lesson_id}"); // O(1) lookup

            // ─────────────────────────────────────────────────────────────────
            // 4-QADAM: Loop — ichida HECH QANDAY DB query yo'q
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
                    continue;
                }

                $attKey        = "{$studentId}_{$lessonId}";
                $oldAttendance = $existingAttendances->get($attKey);
                $oldCoins      = $oldAttendance ? $this->calcDailyCoins($oldAttendance) : 0;

                // Status bo'sh = o'chirish
                if (empty($entry['status'])) {
                    if ($oldAttendance) {
                        $toDeleteIds[] = $oldAttendance->id;
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

                $data      = $this->buildAttendanceData($entry, $lesson);
                $newCoins  = $data['daily_coins'];
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
            // 5-QADAM: O'chirishlar — 1 query
            // ─────────────────────────────────────────────────────────────────
            if (!empty($toDeleteIds)) {
                Attendance::whereIn('id', $toDeleteIds)->delete();
            }

            // ─────────────────────────────────────────────────────────────────
            // 6-QADAM: Saqlashlar — updateOrCreate (timestamps va hooks saqlanadi)
            // ─────────────────────────────────────────────────────────────────
            foreach ($toUpsert as $item) {
                Attendance::updateOrCreate(
                    ['lesson_id' => $item['lesson_id'], 'student_id' => $item['student_id']],
                    $item['data']
                );
            }

            // ─────────────────────────────────────────────────────────────────
            // 7-QADAM: Coin deltalar — har student uchun 1 ta query
            // ─────────────────────────────────────────────────────────────────
            foreach ($studentCoinDeltas as $studentId => $delta) {
                if ($delta !== 0) {
                    DB::table('students')->where('id', $studentId)->increment('coins', $delta);
                }
            }

            // ─────────────────────────────────────────────────────────────────
            // 8-QADAM: CoinTransaction — balanslarni 1 query, insertni 1 query
            // ─────────────────────────────────────────────────────────────────
            if (!empty($coinTransactions)) {
                $balances = DB::table('students')
                    ->whereIn('id', array_keys($coinTransactions))
                    ->pluck('coins', 'id');

                $coinRows = [];
                $now      = now();

                foreach ($coinTransactions as $studentId => $txns) {
                    $balance = $balances[$studentId] ?? 0;

                    foreach (array_reverse($txns) as $i => $txn) {
                        $laterCoins = array_sum(
                            array_column(array_slice($txns, 0, count($txns) - $i - 1), 'coins')
                        );

                        $coinRows[] = [
                            'student_id'       => $studentId,
                            'user_id'          => Auth::id(),
                            'activity_id'      => null,
                            'coins'            => $txn['coins'],
                            'type'             => $txn['type'],
                            'description'      => $txn['description'],
                            'balance_after'    => $balance - $laterCoins,
                            'transaction_date' => $txn['date'],
                            'expiry_date'      => null,
                            'created_at'       => $now,
                            'updated_at'       => $now,
                        ];
                    }
                }

                // Barcha transactionlar — 1 ta bulk insert
                CoinTransaction::insert($coinRows);
            }

            // ─────────────────────────────────────────────────────────────────
            // 9-QADAM: Scheduled → completed — 1 query
            // ─────────────────────────────────────────────────────────────────
            Lesson::where('group_id', $groupId)
                ->where('status', 'scheduled')
                ->whereDate('lesson_date', '<=', Carbon::today())
                ->update(['status' => 'completed']);

            DB::commit();

            return redirect()
                ->route('attendance.show', $groupId)
                ->with('success', 'Davomat muvaffaqiyatli saqlandi!');

        } catch (\Throwable $e) {
            DB::rollBack();
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

        // Unique kunlar soni
        $totalDays = $lessons->pluck('lesson_date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->unique()
            ->count();

        $studentStats = [];

        foreach ($students as $student) {
            $attendances = Attendance::whereIn('lesson_id', $lessonIds)
                ->where('student_id', $student->id)
                ->with('lesson')
                ->get();

            $present = $attendances->where('status', 'present')->count();
            $late    = $attendances->where('status', 'late')->count();
            $absent  = $attendances->where('status', 'absent')->count();
            $excused = $attendances->where('status', 'excused')->count();

            $attended = $attendances->whereIn('status', ['present', 'late']);

            // Lesson bo'yicha rate
            $lessonRate = $totalLessons > 0
                ? round(($present + $late) / $totalLessons * 100, 1) : 0;

            // Kun bo'yicha rate (bir kunda 2 dars bo'lsa ham 1 kun)
            $attendedDays = $attended
                ->map(fn($a) => $a->lesson->lesson_date->format('Y-m-d'))
                ->unique()->count();

            $dayRate = $totalDays > 0
                ? round($attendedDays / $totalDays * 100, 1) : 0;

            $lateDetails = $attendances->where('status', 'late')->map(fn($att) => [
                'date'    => $att->lesson->lesson_date->format('d.m.Y'),
                'time'    => substr($att->lesson->start_time ?? '', 0, 5),
                'minutes' => $att->late_minutes ?? 0,
                'reason'  => $att->notes ?? '',
            ])->values();

            $studentStats[] = [
                'student'         => $student,
                'total_lessons'   => $totalLessons,
                'total_days'      => $totalDays,
                'present'         => $present,
                'late'            => $late,
                'absent'          => $absent,
                'excused'         => $excused,
                'total_coins'     => $attendances->sum('daily_coins'),
                'attendance_rate' => $lessonRate,  // view bilan moslik uchun (dars bo'yicha %)
                'day_rate'        => $dayRate,      // kun bo'yicha % (qo'shimcha)
                'avg_homework'    => $attended->count() > 0 ? round($attended->avg('homework_score'), 1) : 0,
                'avg_activity'    => $attended->count() > 0 ? round($attended->avg('activity_score'), 1) : 0,
                'late_details'    => $lateDetails,
            ];
        }

        // Coin bo'yicha tartiblash
        usort($studentStats, fn($a, $b) => $b['total_coins'] <=> $a['total_coins']);

        return view('Attendance.statistics', compact(
            'group', 'studentStats', 'month', 'year', 'totalLessons', 'totalDays'
        ));
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Store uchun attendance data array tayyorlash
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

        $attendanceCoins = $this->calcAttendanceCoins($status, $lateMinutes);
        $dailyCoins      = $attendanceCoins + $homeworkScore + $activityScore;

        // start_time ba'zan "20:53:00" (time only), ba'zan "2026-02-26 20:53:00" (datetime) kelishi mumkin
        // Har ikki holatda ham faqat HH:MM:SS qismini olamiz
        $startTime = $lesson->start_time
            ? Carbon::parse($lesson->start_time)->format('H:i:s')
            : '00:00:00';

        return [
            'status'           => $status,
            'late_minutes'     => $status === 'late' ? $lateMinutes : null,
            'notes'            => $notes ?: null,
            'homework_score'   => $homeworkScore,
            'activity_score'   => $activityScore,
            'attendance_coins' => $attendanceCoins,
            'daily_coins'      => $dailyCoins,
            'marked_by'        => Auth::id(),
            'marked_at'        => Carbon::parse($lesson->lesson_date->format('Y-m-d') . ' ' . $startTime),
        ];
    }

    /**
     * Saqlangan Attendance modeldan kunlik coin hisoblash
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
     * Faqat davomat statusi uchun coin (homework/activity siz)
     */
    private function calcAttendanceCoins(string $status, int $lateMinutes): int
    {
        return match(true) {
            $status === 'present'                                              => self::COINS_PRESENT,
            $status === 'late' && $lateMinutes < 15                           => self::COINS_LATE_UNDER_15,
            $status === 'late' && $lateMinutes < self::LATE_ABSENT_THRESHOLD  => self::COINS_LATE_UNDER_30,
            default                                                            => self::COINS_ABSENT,
        };
    }

    /**
     * CoinTransaction uchun tavsif matni (saqlash/yangilash)
     */
    private function buildCoinDescription(array $data, ?Attendance $old, Lesson $lesson): string
    {
        $parts   = [];
        $dateStr = $lesson->lesson_date->format('d.m.Y');
        $timeStr = substr($lesson->start_time ?? '', 0, 5);

        $oldAttCoins = $old
            ? $this->calcAttendanceCoins($old->status, $old->late_minutes ?? 0)
            : 0;

        if ($data['attendance_coins'] !== $oldAttCoins) {
            $parts[] = match($data['status']) {
                'present' => 'Keldi',
                'late'    => "Kechikdi ({$data['late_minutes']} min)",
                'absent'  => 'Kelmadi',
                'excused' => 'Sababli',
                default   => $data['status'],
            };
        }

        if ($data['homework_score'] > 0) {
            $parts[] = "Uy vazifasi: {$data['homework_score']}";
        }
        if ($data['activity_score'] > 0) {
            $parts[] = "Faollik: {$data['activity_score']}";
        }

        $desc = implode(', ', $parts) ?: 'Davomat yangilandi';

        return $timeStr
            ? "{$desc} — {$dateStr} {$timeStr}"
            : "{$desc} — {$dateStr}";
    }

    /**
     * CoinTransaction uchun tavsif matni (o'chirish)
     */
    private function buildDeleteDescription(Lesson $lesson): string
    {
        $dateStr = $lesson->lesson_date->format('d.m.Y');
        $timeStr = substr($lesson->start_time ?? '', 0, 5);

        return $timeStr
            ? "Davomat o'chirildi — {$dateStr} {$timeStr}"
            : "Davomat o'chirildi — {$dateStr}";
    }

    // =========================================================================
    // UNUSED (ResourceController interfeysi uchun)
    // =========================================================================
    public function create() {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}