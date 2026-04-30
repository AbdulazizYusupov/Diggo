<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Lesson;
use App\Services\Attendance\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// use Rap2hpoutre\FastExcel\FastExcel;

class AttendanceController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer',
        ]);

        $search = $validated['search'] ?? null;
        $today = Carbon::today();
        $todayDate = $today->toDateString();
        $todayDayName = strtolower($today->format('l'));

        $groups = Group::query()
            ->with(['course', 'activeStudents'])
            ->where('is_active', true)
            ->when($search, fn ($q) => $q->search($search))
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

            $group->has_today_lesson = $groupLessons->isNotEmpty();
            $group->today_lesson_count = $groupLessons->count();
            $group->scheduled_for_today = in_array($todayDayName, (array) ($group->days_of_week ?? []));

            $markedLessonsCount = $groupLessons->filter(fn ($l) => $l->attendances_count > 0)->count();

            $group->has_attendance_today = $group->has_today_lesson
                && ($markedLessonsCount === $group->today_lesson_count);
            $group->has_partial_attendance = $group->has_today_lesson
                && ($markedLessonsCount > 0 && $markedLessonsCount < $group->today_lesson_count);

            return $group;
        });

        return view('Attendance.index', compact('groups', 'search', 'todayDayName'));
    }

    public function show(Request $request, int $groupId)
    {
        // dd(123);
        $group = Group::with(['course', 'activeStudents.user'])->findOrFail($groupId);

        $this->authorize('view', $group);

        $month = (int) $request->get('month', Carbon::now()->month);
        $year = (int) $request->get('year', Carbon::now()->year);

        $lessons = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year)
            ->whereMonth('lesson_date', $month)
            ->whereIn('status', ['scheduled', 'completed', 'rescheduled'])
            ->orderBy('lesson_date')
            ->orderBy('start_time')
            ->get();

        $students = $group->activeStudents;
        $lessonIds = $lessons->pluck('id');

        $attendanceRecords = Attendance::whereIn('lesson_id', $lessonIds)
            ->get([
                'id', 'lesson_id', 'student_id', 'status', 'late_minutes', 'homework_score', 'activity_score', 'notes',
            ])
            ->groupBy(fn ($att) => "{$att->student_id}_{$att->lesson_id}");

        $attendanceMap = [];
        $studentTotalCoins = [];

        foreach ($students as $student) {
            $attendanceMap[$student->id] = [];
            $studentTotalCoins[$student->id] = 0;

            foreach ($lessons as $lesson) {
                $key = "{$student->id}_{$lesson->id}";
                $attendance = $attendanceRecords->get($key)?->first();

                if ($attendance) {
                    $coins = $this->attendanceService->calcDailyCoins($attendance);
                    $studentTotalCoins[$student->id] += $coins;

                    $attendanceMap[$student->id][$lesson->id] = [
                        'status' => $attendance->status,
                        'late_minutes' => $attendance->late_minutes ?? 0,
                        'homework_score' => $attendance->homework_score ?? 0,
                        'activity_score' => $attendance->activity_score ?? 0,
                        'daily_coins' => $coins,
                        'notes' => $attendance->notes ?? '',
                    ];
                }
            }
        }

        $lessonsPerDay = $lessons
            ->groupBy(fn ($l) => $l->lesson_date->format('Y-m-d'))
            ->map(fn ($dayLessons) => $dayLessons->count());

        $statusTypes = [
            'present' => ['color' => 'emerald', 'icon' => 'M5 13l4 4L19 7', 'strokeW' => '2.5'],
            'late' => ['color' => 'amber',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'strokeW' => '2'],
            'absent' => ['color' => 'rose',    'icon' => 'M6 18L18 6M6 6l12 12', 'strokeW' => '2.5'],
        ];
        $today = Carbon::today();

        return view('Attendance.show', compact(
            'group', 'students', 'lessons', 'lessonsPerDay', 'attendanceMap',
            'studentTotalCoins', 'month', 'year', 'today', 'statusTypes'
        ));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $group = Group::findOrFail($request->validated()['group_id']);
        $this->authorize('update', $group);

        try {
            $this->attendanceService->processAttendance(
                (int) $request->validated()['group_id'],
                $request->validated()['attendances']
            );

            return redirect()
                ->route('attendance.show', $request->validated()['group_id'])
                ->with('success', 'Davomat muvaffaqiyatli saqlandi!');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Attendance processing failed', [
                'user_id' => auth()->id(),
                'group_id' => $request->validated()['group_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Davomat saqlashda xatolik yuz berdi. Iltimos qayta urinib ko\'ring yoki administrator bilan bog\'laning.')
                ->withInput();
        }
    }

    public function statistics(int $groupId, Request $request)
    {
        $group = Group::with(['course', 'activeStudents.user'])->findOrFail($groupId);

        $this->authorize('viewStatistics', $group);

        $month = $request->get('month') ? (int) $request->get('month') : null;
        $year = (int) $request->get('year', Carbon::now()->year);

        $students = $group->activeStudents;

        $lessonsQuery = Lesson::where('group_id', $groupId)
            ->whereIn('status', ['scheduled', 'completed', 'rescheduled'])
            ->whereYear('lesson_date', $year);

        if ($month) {
            $lessonsQuery->whereMonth('lesson_date', $month);
        }

        $lessons = $lessonsQuery->orderBy('lesson_date')->orderBy('start_time')->get();
        $totalLessons = $lessons->count();
        $lessonIds = $lessons->pluck('id')->toArray();
        $totalDays = $lessons->pluck('lesson_date')->map(fn ($d) => $d->format('Y-m-d'))->unique()->count();

        $allAttendances = Attendance::whereIn('lesson_id', $lessonIds)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');

        $lessonsById = $lessons->keyBy('id');
        $studentStats = [];

        foreach ($students as $student) {
            $attendances = $allAttendances->get($student->id, collect());
            $present = $attendances->where('status', 'present')->count();
            $late = $attendances->where('status', 'late')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $excused = $attendances->where('status', 'excused')->count();
            $attended = $attendances->whereIn('status', ['present', 'late']);

            $lessonRate = $totalLessons > 0 ? round(($present + $late) / $totalLessons * 100, 1) : 0;
            $attendedDays = $attended->map(fn ($a) => $lessonsById->get($a->lesson_id)?->lesson_date?->format('Y-m-d'))->filter()->unique()->count();
            $dayRate = $totalDays > 0 ? round($attendedDays / $totalDays * 100, 1) : 0;

            $lateDetails = $attendances->where('status', 'late')->map(function ($att) use ($lessonsById) {
                $lesson = $lessonsById->get($att->lesson_id);

                return [
                    'date' => $lesson?->lesson_date?->format('d.m.Y') ?? '—',
                    'time' => $lesson ? $this->formatTime($lesson->start_time) : '—',
                    'minutes' => $att->late_minutes ?? 0,
                    'reason' => $att->notes ?? '',
                ];
            })->values();

            $totalCoins = $attendances->sum(fn ($att) => $this->attendanceService->calcDailyCoins($att));

            $studentStats[] = [
                'student' => $student,
                'total_lessons' => $totalLessons,
                'total_days' => $totalDays,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'excused' => $excused,
                'total_coins' => $totalCoins,
                'attendance_rate' => $lessonRate,
                'day_rate' => $dayRate,
                'avg_homework' => $attended->count() > 0 ? round($attended->avg('homework_score'), 1) : 0,
                'avg_activity' => $attended->count() > 0 ? round($attended->avg('activity_score'), 1) : 0,
                'late_details' => $lateDetails,
            ];
        }

        usort($studentStats, fn ($a, $b) => $b['total_coins'] <=> $a['total_coins']);

        return view('Attendance.statistics', compact('group', 'studentStats', 'month', 'year', 'totalLessons', 'totalDays'));
    }

    public function export(Request $request, int $groupId)
    {
        $group = Group::findOrFail($groupId);
        $this->authorize('export', $group);

        $students = $group->activeStudents;
        $year     = (int) $request->get('year', Carbon::now()->year);
 
        $monthNames = [
            1 => 'YANVAR',  2 => 'FEVRAL',   3 => 'MART',    4 => 'APREL',
            5 => 'MAY',     6 => 'IYUN',     7 => 'IYUL',    8 => 'AVGUST',
            9 => 'SENTABR', 10 => 'OKTABR', 11 => 'NOYABR', 12 => 'DEKABR',
        ];
 
        $lessons = Lesson::where('group_id', $groupId)
            ->whereYear('lesson_date', $year)
            ->whereIn('status', ['scheduled', 'completed', 'rescheduled'])
            ->orderBy('lesson_date')
            ->get();
 
        $attendanceRecords = Attendance::whereIn('lesson_id', $lessons->pluck('id'))
            ->get()
            ->groupBy(fn ($a) => "{$a->student_id}_{$a->lesson_id}");
 
        $lessonsByMonth = $lessons->groupBy(fn ($l) => (int) $l->lesson_date->format('n'));
 
        $rows      = [];
        $styleMap  = [];
        $mergeMap  = [];
        $colWidths = [1 => 24];
 
        foreach ($lessonsByMonth as $month => $monthLessons) {
            $monthLessons = $monthLessons->values();
            $lessonCount  = $monthLessons->count();
 
            // Ustunlar: 1(ism) + lessonCount*2(sana+coin) — musobaqa/jami YO'Q
            $totalCols = 1 + $lessonCount * 2;
 
            // ── 1. Oy sarlavhasi ──────────────────────────────────────────
            $monthRow    = array_fill(0, $totalCols, '');
            $monthRow[0] = $monthNames[$month] ?? $month;
            $rows[]      = $monthRow;
            $rowIdx      = count($rows);
 
            $styleMap[$rowIdx][1] = 'month_title';
            $mergeMap[] = Coordinate::stringFromColumnIndex(1).$rowIdx
                        .':'.Coordinate::stringFromColumnIndex($totalCols).$rowIdx;
 
            // ── 2. Header qatori ──────────────────────────────────────────
            $headerRow    = array_fill(0, $totalCols, '');
            $headerRow[0] = 'Ism-familiya';
            $col          = 2;
 
            foreach ($monthLessons as $lesson) {
                $headerRow[$col - 1] = $lesson->lesson_date->format('d.m.Y');
                $headerRow[$col]     = 'COIN';
                $colWidths[$col]     = 12;
                $colWidths[$col + 1] = 7;
                $col += 2;
            }
 
            $rows[]  = $headerRow;
            $rowIdx  = count($rows);
 
            $styleMap[$rowIdx][1] = 'header_name';
            $col = 2;
            foreach ($monthLessons as $lesson) {
                $styleMap[$rowIdx][$col]     = 'header_date';
                $styleMap[$rowIdx][$col + 1] = 'header_coin';
                $col += 2;
            }
 
            // ── 3. O'quvchilar ────────────────────────────────────────────
            foreach ($students as $si => $student) {
                $dataRow    = array_fill(0, $totalCols, '');
                $dataRow[0] = $student->user->name ?? "Noma'lum";
                $rows[]     = $dataRow;
                $rowIdx     = count($rows);
                $isAlt      = ($si % 2 !== 0);
 
                $styleMap[$rowIdx][1] = $isAlt ? 'name_alt' : 'name';
 
                $col = 2;
                foreach ($monthLessons as $lesson) {
                    $key = "{$student->id}_{$lesson->id}";
                    $att = $attendanceRecords->get($key)?->first();
 
                    if ($att) {
                        $isPresent = in_array($att->status, ['present', 'late']);
                        $coins     = $this->attendanceService->calcDailyCoins($att);
 
                        // Wingdings 2: chr(82)='R' → ☑ checked, chr(84)='T' → ☐ unchecked
                        $rows[$rowIdx - 1][$col - 1] = $isPresent ? chr(82) : chr(84);
                        $rows[$rowIdx - 1][$col]     = ($isPresent && $coins > 0) ? $coins : '';
 
                        $styleMap[$rowIdx][$col]     = $isPresent ? 'checked' : 'unchecked';
                        $styleMap[$rowIdx][$col + 1] = $isAlt ? 'coin_alt' : 'coin';
                    } else {
                        $rows[$rowIdx - 1][$col - 1] = chr(84); // bo'sh checkbox
                        $styleMap[$rowIdx][$col]     = 'unchecked';
                        $styleMap[$rowIdx][$col + 1] = $isAlt ? 'coin_alt' : 'coin';
                    }
 
                    $col += 2;
                }
            }
 
            // ── 4. Ajratuvchi bo'sh qatorlar ─────────────────────────────
            $rows[] = array_fill(0, $totalCols, '');
            $rows[] = array_fill(0, $totalCols, '');
        }
 
        // ── Anonymous Export class ────────────────────────────────────────
        $exportObj = new class($rows, $styleMap, $mergeMap, $colWidths) implements FromArray, WithEvents
        {
            public function __construct(
                private array $rows,
                private array $styleMap,
                private array $mergeMap,
                private array $colWidths,
            ) {}
 
            public function array(): array
            {
                return $this->rows;
            }
 
            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();
 
                        foreach ($this->colWidths as $colIdx => $width) {
                            $sheet->getColumnDimension(
                                Coordinate::stringFromColumnIndex($colIdx)
                            )->setWidth($width);
                        }
 
                        foreach (range(1, count($this->rows)) as $r) {
                            $sheet->getRowDimension($r)->setRowHeight(24);
                        }
 
                        foreach ($this->mergeMap as $range) {
                            $sheet->mergeCells($range);
                        }
 
                        foreach ($this->styleMap as $rowIdx => $cols) {
                            foreach ($cols as $colIdx => $styleType) {
                                $cellRef = Coordinate::stringFromColumnIndex($colIdx).$rowIdx;
                                $this->applyStyle($sheet, $cellRef, $styleType);
                            }
                        }
                    },
                ];
            }
 
            private function applyStyle($sheet, string $cellRef, string $type): void
            {
                $border  = ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFB8CCE4']]];
                $center  = ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER];
                $left    = ['horizontal' => Alignment::HORIZONTAL_LEFT,   'vertical' => Alignment::VERTICAL_CENTER];
                $green   = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF00B050']];
                $white   = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFFFF']];
                $alt     = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF2F2F2']];
                $white_f = ['name' => 'Arial', 'size' => 10, 'bold' => true,  'color' => ['argb' => 'FFFFFFFF']];
                $black_f = ['name' => 'Arial', 'size' => 10, 'bold' => false, 'color' => ['argb' => 'FF000000']];
 
                match ($type) {
                    'month_title'  => $sheet->getStyle($cellRef)->applyFromArray([
                        'font'      => array_merge($white_f, ['size' => 12]),
                        'fill'      => $green,
                        'alignment' => $center,
                        'borders'   => $border,
                    ]),
                    'header_name',
                    'header_date',
                    'header_coin'  => $sheet->getStyle($cellRef)->applyFromArray([
                        'font'      => $white_f,
                        'fill'      => $green,
                        'alignment' => $center,
                        'borders'   => $border,
                    ]),
                    // ☑ Ko'k to'ldirilgan checkbox — Wingdings 2 chr(82)
                    'checked'      => $sheet->getStyle($cellRef)->applyFromArray([
                        'font'      => ['name' => 'Wingdings 2', 'size' => 13, 'color' => ['argb' => 'FF2E75B6']],
                        'fill'      => $white,
                        'alignment' => $center,
                        'borders'   => $border,
                    ]),
                    // ☐ Bo'sh checkbox — Wingdings 2 chr(84)
                    'unchecked'    => $sheet->getStyle($cellRef)->applyFromArray([
                        'font'      => ['name' => 'Wingdings 2', 'size' => 13, 'color' => ['argb' => 'FF2E75B6']],
                        'fill'      => $white,
                        'alignment' => $center,
                        'borders'   => $border,
                    ]),
                    'coin'         => $sheet->getStyle($cellRef)->applyFromArray([
                        'font'      => $black_f,
                        'fill'      => $white,
                        'alignment' => $center,
                        'borders'   => $border,
                    ]),
                    'coin_alt'     => $sheet->getStyle($cellRef)->applyFromArray([
                        'font'      => $black_f,
                        'fill'      => $alt,
                        'alignment' => $center,
                        'borders'   => $border,
                    ]),
                    'name'         => $sheet->getStyle($cellRef)->applyFromArray([
                        'font'      => $black_f,
                        'fill'      => $white,
                        'alignment' => $left,
                        'borders'   => $border,
                    ]),
                    'name_alt'     => $sheet->getStyle($cellRef)->applyFromArray([
                        'font'      => $black_f,
                        'fill'      => $alt,
                        'alignment' => $left,
                        'borders'   => $border,
                    ]),
                    default => null,
                };
            }
        };
 
        $fileName = 'davomat_'.str_replace(' ', '_', $group->name)."_{$year}.xlsx";
 
        return Excel::download($exportObj, $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }

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

    private function formatTime(?string $startTime): string
    {
        return empty($startTime) ? '' : Carbon::parse($startTime)->format('H:i');
    }
}
