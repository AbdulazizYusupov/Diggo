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

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::with(['course', 'activeStudents'])
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(12);

        return view('Attendance.index', compact('groups'));
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
        // 1. Validationni to'g'irlaymiz
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'attendances' => 'required|array',
            'attendances.*.*.student_id' => 'required|exists:students,id',
            'attendances.*.*.date' => 'required|date',
            'attendances.*.*.status' => 'nullable|in:present,late,absent,excused', // nullable qildik
            'attendances.*.*.homework_score' => 'nullable|integer|min:0|max:2',
            'attendances.*.*.activity_score' => 'nullable|integer|min:0|max:2',
        ]);

        DB::beginTransaction();
        try {
            $group = Group::findOrFail($validated['group_id']);
            $schoolId = Auth::user()->teacher?->school_id ?? Auth::user()->school_id;

            // Massivni chuqur aylanib chiqamiz
            foreach ($validated['attendances'] as $studentAttendances) {
                foreach ($studentAttendances as $attendanceData) {

                    // Agar status tanlanmagan bo'lsa, bu o'quvchini tashlab ketamiz
                    if (empty($attendanceData['status'])) {
                        continue;
                    }

                    $student = Student::findOrFail($attendanceData['student_id']);

                    // Eski davomatni tekshirish va coinlarni qaytarish (Sizning kodingiz...)
                    $oldAttendance = Attendance::where([
                        'student_id' => $student->id,
                        'group_id' => $group->id,
                        'date' => $attendanceData['date'],
                    ])->first();

                    if ($oldAttendance) {
                        $oldCoins = ($oldAttendance->status === 'present' ? 2 : ($oldAttendance->status === 'late' ? 1 : 0));
                        $oldCoins += ($oldAttendance->homework_score + $oldAttendance->participation_score);
                        $student->decrement('coins', $oldCoins);
                    }

                    // Yangi davomatni saqlash
                    $attendance = Attendance::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'group_id' => $group->id,
                            'date' => $attendanceData['date'],
                        ],
                        [
                            'status' => $attendanceData['status'],
                            'homework_score' => $attendanceData['homework_score'] ?? 0,
                            'participation_score' => $attendanceData['activity_score'] ?? 0,
                            'user_id' => Auth::id(),
                            'school_id' => $schoolId,
                        ]
                    );

                    // Yangi coinlarni hisoblash
                    $newCoins = ($attendanceData['status'] === 'present' ? 2 : ($attendanceData['status'] === 'late' ? 1 : 0));
                    $newCoins += ($attendanceData['homework_score'] ?? 0) + ($attendanceData['activity_score'] ?? 0);

                    if ($newCoins > 0) {
                        $student->increment('coins', $newCoins);
                        // CoinTransaction logikasini shu yerga yozasiz...
                    }
                }
            }

            DB::commit();

            // Oddiy forma uchun redirect ma'qul, agar AJAX bo'lmasa
            return redirect()->back()->with('success', 'Davomat muvaffaqiyatli saqlandi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Xatolik: ' . $e->getMessage());
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
