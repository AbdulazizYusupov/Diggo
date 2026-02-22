<?php

namespace App\Http\Controllers\TeacherDashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\LessonChange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceController extends Controller
{
    public function show($groupId, $lessonId)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $group = $teacher->groups()
            ->with('course', 'activeStudents.user')
            ->findOrFail($groupId);

        $lesson = Lesson::where('group_id', $group->id)
            ->where('teacher_id', $teacher->id)
            ->whereNull('deleted_at')
            ->findOrFail($lessonId);

        // Mavjud davomatlar (student_id => Attendance)
        $existingMap = Attendance::where('lesson_id', $lesson->id)
            ->whereNull('deleted_at')
            ->get()
            ->keyBy('student_id');

        // Har bir o'quvchi uchun mavjud davomat ma'lumotlarini tayyorlash
        // (blade da @php ishlatmaslik uchun)
        $students = $group->activeStudents->map(function ($student) use ($existingMap) {
            $att = $existingMap->get($student->id);

            $student->existing_status   = $att ? $att->status         : null;
            $student->existing_hw       = $att ? $att->homework_score  : null;
            $student->existing_act      = $att ? $att->activity_score  : null;
            $student->existing_late     = $att ? $att->late_minutes    : null;
            $student->existing_notes    = $att ? $att->notes           : null;
            $student->existing_coins    = $att ? $this->calculateDailyCoins($att) : 0;

            return $student;
        });

        // Dars status badge ma'lumotlari (blade @php siz)
        $lessonStatusBadge = $this->getLessonStatusBadge($lesson->status);

        // Status tanlov opsiyalari
        $statusOptions = [
            ['value' => 'present', 'label' => 'Keldi',    'checked_class' => 'bg-emerald-500 text-white ring-2 ring-emerald-300', 'hover_class' => 'hover:bg-emerald-50 dark:hover:bg-emerald-900/20'],
            ['value' => 'late',    'label' => 'Kechikdi', 'checked_class' => 'bg-amber-500 text-white ring-2 ring-amber-300',    'hover_class' => 'hover:bg-amber-50 dark:hover:bg-amber-900/20'],
            ['value' => 'absent',  'label' => 'Kelmadi',  'checked_class' => 'bg-rose-500 text-white ring-2 ring-rose-300',      'hover_class' => 'hover:bg-rose-50 dark:hover:bg-rose-900/20'],
            ['value' => 'excused', 'label' => 'Uzrli',    'checked_class' => 'bg-blue-500 text-white ring-2 ring-blue-300',      'hover_class' => 'hover:bg-blue-50 dark:hover:bg-blue-900/20'],
        ];

        return view('Panel.Teacher.attendance', compact(
            'teacher',
            'group',
            'lesson',
            'students',
            'lessonStatusBadge',
            'statusOptions'
        ));
    }

    /**
     * Yuqlamani saqlash + coinlarni avtomatik berish
     */
    public function store(Request $request, $groupId, $lessonId)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'O\'qituvchi profili topilmadi');
        }

        $group = $teacher->groups()->findOrFail($groupId);

        $lesson = Lesson::where('group_id', $group->id)
            ->where('teacher_id', $teacher->id)
            ->whereNull('deleted_at')
            ->findOrFail($lessonId);

        $request->validate([
            'attendances'                    => 'required|array',
            'attendances.*.student_id'       => 'required|exists:students,id',
            'attendances.*.status'           => 'nullable|in:present,absent,late,excused',
            'attendances.*.homework_score'   => 'nullable|integer|min:0|max:3',
            'attendances.*.activity_score'   => 'nullable|integer|min:0|max:3',
            'attendances.*.late_minutes'     => 'nullable|integer|min:0|max:120',
            'attendances.*.notes'            => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $lesson, $user, $group) {
            $markedAt = Carbon::now();

            // Coin farqlarini yig'ish uchun
            $coinChanges = [];

            foreach ($request->attendances as $data) {
                $studentId = (int) $data['student_id'];
                $status    = $data['status'] ?? null;

                // Eski davomatni olish (coin farqini hisoblash uchun)
                $oldAttendance = Attendance::where('lesson_id', $lesson->id)
                    ->where('student_id', $studentId)
                    ->whereNull('deleted_at')
                    ->first();

                $oldCoins = $oldAttendance ? $this->calculateDailyCoins($oldAttendance) : 0;

                // Status yo'q — mavjud davomatni o'chirish va coinlarni qaytarish
                if (empty($status)) {
                    if ($oldAttendance) {
                        $oldAttendance->delete();
                        $coinChanges[$studentId] = ($coinChanges[$studentId] ?? 0) - $oldCoins;

                        if ($oldCoins > 0) {
                            CoinTransaction::create([
                                'student_id'       => $studentId,
                                'coins'            => -$oldCoins,
                                'type'             => 'participation',
                                'description'      => 'Davomat o\'chirilganligi sababli coin qaytarildi',
                                'transaction_date' => $lesson->lesson_date,
                                'user_id'          => $user->id,
                            ]);
                        }
                    }
                    continue;
                }

                // Kechikish 30+ daqiqa bo'lsa kelmagan hisoblanadi
                $lateMinutes = (int)($data['late_minutes'] ?? 0);
                if ($status === 'late' && $lateMinutes >= 30) {
                    $status      = 'absent';
                    $lateMinutes = 0;
                }

                // Uy ishi va faollik faqat kelgan/kechikkan bo'lsa
                if (in_array($status, ['present', 'late'])) {
                    $homeworkScore = min(3, max(0, (int)($data['homework_score'] ?? 0)));
                    $activityScore = min(3, max(0, (int)($data['activity_score'] ?? 0)));
                } else {
                    $homeworkScore = null;
                    $activityScore = null;
                    $lateMinutes   = null;
                }

                // Davomat coinlarini hisoblash
                $attendanceCoins = $this->calculateAttendanceCoins($status, (int)($lateMinutes ?? 0));
                $newCoins        = $attendanceCoins + ($homeworkScore ?? 0) + ($activityScore ?? 0);

                Attendance::withoutTrashed()->updateOrCreate(
                    [
                        'lesson_id'  => $lesson->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'marked_by'      => $user->id,
                        'status'         => $status,
                        'late_minutes'   => $lateMinutes,
                        'homework_score' => $homeworkScore,
                        'activity_score' => $activityScore,
                        'notes'          => $data['notes'] ?? null,
                        'marked_at'      => $markedAt,
                        'deleted_at'     => null,
                    ]
                );

                // Coin farqi
                $coinDiff = $newCoins - $oldCoins;
                $coinChanges[$studentId] = ($coinChanges[$studentId] ?? 0) + $coinDiff;

                // Coin transaction yozish (farq bo'lsa)
                if ($coinDiff !== 0) {
                    CoinTransaction::create([
                        'student_id'       => $studentId,
                        'coins'            => $coinDiff,
                        'type'             => 'participation',
                        'description'      => "Dars davomati: {$lesson->topic} ({$lesson->lesson_date->format('d.m.Y')})",
                        'transaction_date' => $lesson->lesson_date,
                        'user_id'          => $user->id,
                    ]);
                }
            }

            // Studentlar coin balansini yangilash
            foreach ($coinChanges as $studentId => $diff) {
                if ($diff !== 0) {
                    DB::table('students')
                        ->where('id', $studentId)
                        ->increment('coins', $diff);
                }
            }

            // Darsni "completed" ga o'tkazish
            if ($lesson->status === 'scheduled') {
                $lesson->update(['status' => 'completed']);
            }
        });

        return redirect()
            ->route('teacher.attendance.show', [$group->id, $lesson->id])
            ->with('success', 'Yuqlama saqlandi va coinlar avtomatik yangilandi!');
    }

    // ─────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────

    private function calculateDailyCoins(Attendance $attendance): int
    {
        $coins = $this->calculateAttendanceCoins(
            $attendance->status,
            (int)($attendance->late_minutes ?? 0)
        );

        if (in_array($attendance->status, ['present', 'late'])) {
            $coins += min(3, max(0, (int)($attendance->homework_score ?? 0)));
            $coins += min(3, max(0, (int)($attendance->activity_score ?? 0)));
        }

        return $coins;
    }

    private function calculateAttendanceCoins(string $status, int $lateMinutes): int
    {
        if ($status === 'present') {
            return 2;
        }
        if ($status === 'late') {
            if ($lateMinutes < 15)  return 2;
            if ($lateMinutes < 30)  return 1;
        }
        return 0;
    }

    private function getLessonStatusBadge(string $status): array
    {
        $map = [
            'scheduled'   => ['label' => 'Rejalashtirilgan', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
            'completed'   => ['label' => 'Bajarildi',        'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
            'cancelled'   => ['label' => 'Bekor qilindi',   'class' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'],
            'rescheduled' => ['label' => 'Siljitildi',       'class' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'],
        ];

        return $map[$status] ?? ['label' => $status, 'class' => 'bg-gray-100 text-gray-600'];
    }
}
