<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CoinTransaction;
use App\Models\Group;
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
    public function index(Request $request)
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
        dd(123);
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'student_id' => 'required|exists:students,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,late,absent,excused',
            'homework_score' => 'nullable|integer|min:0|max:2',
            'participation_score' => 'nullable|integer|min:0|max:2',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $student = Student::findOrFail($validated['student_id']);
            $group = Group::findOrFail($validated['group_id']);

            // Davomat yaratish yoki yangilash
            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $validated['student_id'],
                    'group_id' => $validated['group_id'],
                    'attendance_date' => $validated['attendance_date'],
                ],
                [
                    'status' => $validated['status'],
                    'homework_score' => $validated['homework_score'] ?? 0,
                    'participation_score' => $validated['participation_score'] ?? 0,
                    'notes' => $validated['notes'],
                    'user_id' => Auth::id(),
                    'school_id' => Auth::user()->student?->school_id
                        ?? Auth::user()->teacher?->school_id
                        ?? Auth::user()->director?->school_id,
                ]
            );

            // Coinlarni hisoblash
            $coins = 0;

            // Davomat uchun coinlar
            if ($validated['status'] === 'present') {
                $coins += 2; // Keldi
            } elseif ($validated['status'] === 'late') {
                $coins += 1; // Kechikdi
            }

            // Uy vazifa uchun
            $coins += $validated['homework_score'] ?? 0;

            // Faollik uchun
            $coins += $validated['participation_score'] ?? 0;

            // Agar coin bor bo'lsa, saqlash
            if ($coins > 0) {
                CoinTransaction::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'transaction_date' => $validated['attendance_date'],
                        'type' => 'attendance',
                    ],
                    [
                        'coins' => $coins,
                        'balance_after' => $student->coins + $coins,
                        'description' => "Davomat: {$group->name} - " . Carbon::parse($validated['attendance_date'])->format('d.m.Y'),
                        'school_id' => $attendance->school_id,
                        'user_id' => Auth::id(),
                    ]
                );

                // Talaba coinini yangilash
                $student->increment('coins', $coins);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Davomat muvaffaqiyatli saqlandi',
                'coins' => $coins,
                'attendance' => $attendance,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }
    public function show(Group $group, Request $request)
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
    public function bulkStore(Request $request)
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
    public function statistics(Group $group, Request $request)
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
}
