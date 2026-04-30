<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use App\Models\CoinTransaction;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\User;
use App\Notifications\AttendanceMarked\AttendanceMarkedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceService
{
    private const COINS_PRESENT         = 2;
    private const COINS_LATE_UNDER_15   = 2;
    private const COINS_LATE_UNDER_30   = 1;
    private const COINS_ABSENT          = 0;
    private const MAX_HOMEWORK_SCORE    = 3;
    private const MAX_ACTIVITY_SCORE    = 3;
    private const LATE_ABSENT_THRESHOLD = 30;

    /**
     * Store attendance and manage coins/notifications.
     */
    public function processAttendance(int $groupId, array $attendanceData): bool
    {
        DB::beginTransaction();
        try {
            $entries = $this->collectEntries($attendanceData);

            if (empty($entries)) {
                DB::commit();
                return true;
            }

            $lessonIds = array_unique(array_column($entries, 'lesson_id'));
            $studentIds = array_unique(array_column($entries, 'student_id'));

            $lessonsMap = $this->getLessonsMap($lessonIds, $groupId);
            $validStudentIds = $this->getValidStudentIds($studentIds, $groupId);
            $existingAttendances = $this->getExistingAttendances($lessonIds, $studentIds);

            $studentCoinDeltas = [];
            $coinTransactions = [];
            $toDeleteIds = [];
            $toUpsert = [];

            foreach ($entries as $entry) {
                $studentId = (int) $entry['student_id'];
                $lessonId  = (int) $entry['lesson_id'];

                $lesson = $lessonsMap->get($lessonId);
                if (!$lesson || !array_key_exists($studentId, $validStudentIds)) {
                    continue;
                }

                $attKey = "{$studentId}_{$lessonId}";
                $oldAttendance = $existingAttendances->get($attKey);
                $oldCoins = $oldAttendance ? $this->calcDailyCoins($oldAttendance) : 0;

                // Handle deletion
                if (empty($entry['status'])) {
                    if ($oldAttendance) {
                        $toDeleteIds[] = $oldAttendance->id;
                        $studentCoinDeltas[$studentId] = ($studentCoinDeltas[$studentId] ?? 0) - $oldCoins;
                        if ($oldCoins > 0) {
                            $coinTransactions[$studentId][] = $this->prepareCoinTxn(-$oldCoins, 'refund', $this->buildDeleteDescription($lesson), $lesson->lesson_date);
                        }
                    }
                    continue;
                }

                $data = $this->buildAttendanceData($entry);
                $newCoins = $this->calcDailyCoinsFromData($data);
                $coinDelta = $newCoins - $oldCoins;

                $toUpsert[] = [
                    'lesson_id'     => $lessonId,
                    'student_id'    => $studentId,
                    'data'          => $data,
                    'oldAttendance' => $oldAttendance,
                ];

                $studentCoinDeltas[$studentId] = ($studentCoinDeltas[$studentId] ?? 0) + $coinDelta;

                if ($coinDelta !== 0) {
                    $coinTransactions[$studentId][] = $this->prepareCoinTxn(
                        $coinDelta,
                        $coinDelta > 0 ? 'participation' : 'penalty',
                        $this->buildCoinDescription($data, $oldAttendance, $lesson),
                        $lesson->lesson_date
                    );
                }
            }

            // Lock student records to prevent race conditions
            if (!empty($studentCoinDeltas)) {
                $lockedStudents = DB::table('students')
                    ->whereIn('id', array_keys($studentCoinDeltas))
                    ->lockForUpdate()
                    ->pluck('coins', 'id')
                    ->toArray();
            }

            $this->performDatabaseOperations($toDeleteIds, $toUpsert, $coinTransactions, $studentCoinDeltas);
            $this->sendNotifications($toUpsert, $lessonsMap);
            $this->completeLessons(array_unique(array_column($toUpsert, 'lesson_id')));

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Attendance Service Error: " . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    private function collectEntries(array $attendanceData): array
    {
        $entries = [];
        foreach ($attendanceData as $studentRows) {
            foreach ($studentRows as $entry) {
                if (!empty($entry['student_id']) && !empty($entry['lesson_id'])) {
                    $entries[] = $entry;
                }
            }
        }
        return $entries;
    }

    private function getLessonsMap(array $lessonIds, int $groupId)
    {
        return Lesson::with('group')
            ->whereIn('id', $lessonIds)
            ->where('group_id', $groupId)
            ->get()
            ->keyBy('id');
    }

    private function getValidStudentIds(array $studentIds, int $groupId): array
    {
        return DB::table('group_students')
            ->where('group_id', $groupId)
            ->where('status', 'active')
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->flip()
            ->toArray();
    }

    private function getExistingAttendances(array $lessonIds, array $studentIds)
    {
        return Attendance::whereIn('lesson_id', $lessonIds)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->keyBy(fn($att) => "{$att->student_id}_{$att->lesson_id}");
    }

    private function buildAttendanceData(array $entry): array
    {
        $status      = $entry['status'];
        $lateMinutes = (int) ($entry['late_minutes'] ?? 0);
        $notes       = trim($entry['notes'] ?? '');

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

    public function calcDailyCoins(Attendance $attendance): int
    {
        $coins = $this->calcAttendanceCoins($attendance->status, $attendance->late_minutes ?? 0);
        if (in_array($attendance->status, ['present', 'late'])) {
            $coins += min(self::MAX_HOMEWORK_SCORE, max(0, $attendance->homework_score ?? 0));
            $coins += min(self::MAX_ACTIVITY_SCORE, max(0, $attendance->activity_score ?? 0));
        }
        return $coins;
    }

    private function calcDailyCoinsFromData(array $data): int
    {
        $coins = $this->calcAttendanceCoins($data['status'], $data['late_minutes'] ?? 0);
        if (in_array($data['status'], ['present', 'late'])) {
            $coins += $data['homework_score'] ?? 0;
            $coins += $data['activity_score'] ?? 0;
        }
        return $coins;
    }

    private function calcAttendanceCoins(string $status, int $lateMinutes): int
    {
        return match (true) {
            $status === 'present'                                             => self::COINS_PRESENT,
            $status === 'late' && $lateMinutes < 15                          => self::COINS_LATE_UNDER_15,
            $status === 'late' && $lateMinutes < self::LATE_ABSENT_THRESHOLD => self::COINS_LATE_UNDER_30,
            default                                                           => self::COINS_ABSENT,
        };
    }

    private function prepareCoinTxn(int $coins, string $type, string $description, $date): array
    {
        return [
            'coins'       => $coins,
            'type'        => $type,
            'description' => $description,
            'date'        => $date instanceof Carbon ? $date->format('Y-m-d') : $date,
        ];
    }

    private function performDatabaseOperations(array $toDeleteIds, array $toUpsert, array $coinTransactions, array $studentCoinDeltas): void
    {
        if (!empty($toDeleteIds)) {
            Attendance::whereIn('id', $toDeleteIds)->delete();
        }

        foreach ($toUpsert as $item) {
            Attendance::updateOrCreate(
                ['lesson_id' => $item['lesson_id'], 'student_id' => $item['student_id']],
                $item['data']
            );
        }

        if (!empty($coinTransactions)) {
            $balances = DB::table('students')
                ->whereIn('id', array_keys($coinTransactions))
                ->pluck('coins', 'id');

            $coinRows = [];
            $now = now();

            foreach ($coinTransactions as $studentId => $txns) {
                $runningBalance = (int) ($balances[$studentId] ?? 0);
                foreach ($txns as $txn) {
                    $runningBalance += $txn['coins'];
                    $coinRows[] = [
                        'student_id'       => $studentId,
                        'user_id'          => Auth::id(),
                        'coins'            => $txn['coins'],
                        'type'             => $txn['type'],
                        'description'      => $txn['description'],
                        'balance_after'    => $runningBalance,
                        'transaction_date' => $txn['date'],
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ];
                }
            }
            CoinTransaction::insert($coinRows);
        }

        foreach ($studentCoinDeltas as $studentId => $delta) {
            if ($delta !== 0) {
                DB::table('students')->where('id', $studentId)->increment('coins', $delta);
            }
        }
    }

    private function sendNotifications(array $toUpsert, $lessonsMap): void
    {
        $allNotifyStudentIds = array_unique(array_column($toUpsert, 'student_id'));
        if (empty($allNotifyStudentIds)) return;

        $studentUserMap = DB::table('students')
            ->whereIn('id', $allNotifyStudentIds)
            ->pluck('user_id', 'id');

        $usersById = User::whereIn('id', $studentUserMap->values()->filter()->unique())
            ->get()
            ->keyBy('id');

        $groupName = $lessonsMap->first()?->group?->name ?? '';

        foreach ($toUpsert as $item) {
            $studentId = $item['student_id'];
            $data      = $item['data'];
            $lesson    = $lessonsMap->get($item['lesson_id']);
            $coins     = $this->calcDailyCoinsFromData($data);

            if (!$lesson || ($coins <= 0 && $data['status'] === 'absent')) {
                continue;
            }

            $userId = $studentUserMap->get($studentId);
            $user   = $userId ? $usersById->get($userId) : null;

            if ($user) {
                $user->notify(new AttendanceMarkedNotification(
                    $data['status'],
                    $coins,
                    $data['homework_score'],
                    $data['activity_score'],
                    $lesson->lesson_date->format('d.m.Y'),
                    $lesson->start_time,
                    $groupName
                ));
            }
        }
    }

    private function completeLessons(array $lessonIds): void
    {
        if (empty($lessonIds)) return;

        // Mark lessons as completed if all students have attendance marked
        foreach ($lessonIds as $lessonId) {
            $lesson = Lesson::with('group.activeStudents')->find($lessonId);
            if (!$lesson) continue;

            $totalStudents = $lesson->group->activeStudents->count();
            $markedAttendances = Attendance::where('lesson_id', $lessonId)->count();

            if ($totalStudents > 0 && $markedAttendances >= $totalStudents) {
                $lesson->update(['status' => 'completed']);
            }
        }
    }

    private function buildDeleteDescription(Lesson $lesson): string
    {
        return "Davomat bekor qilindi: {$lesson->lesson_date->format('d.m.Y')} {$lesson->start_time}";
    }

    private function buildCoinDescription(array $data, ?Attendance $oldAttendance, Lesson $lesson): string
    {
        $statusText = match ($data['status']) {
            'present' => 'Keldi',
            'late'    => 'Kechikdi',
            'absent'  => 'Kelmadi',
            default   => $data['status'],
        };

        $description = "Davomat: {$statusText} - {$lesson->lesson_date->format('d.m.Y')}";

        if ($data['homework_score'] > 0 || $data['activity_score'] > 0) {
            $scores = [];
            if ($data['homework_score'] > 0) $scores[] = "UY:{$data['homework_score']}";
            if ($data['activity_score'] > 0) $scores[] = "FA:{$data['activity_score']}";
            $description .= ' (' . implode(', ', $scores) . ')';
        }

        if ($oldAttendance) {
            $description .= ' [o\'zgartirildi]';
        }

        return $description;
    }
}