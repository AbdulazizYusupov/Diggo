@extends('Layout.app')

@section('title', 'Davomat Kalendari')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Davomat Kalendari
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Har kunlik dars va davomat holati
            </p>
        </div>
        <a href="{{ route('student.attendance') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            Ro'yxat ko'rinishi
        </a>
    </div>

    {{-- Month Navigation --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
            @php
                $prevMonth = $month == 1 ? 12 : $month - 1;
                $prevYear  = $month == 1 ? $year - 1 : $year;
                $nextMonth = $month == 12 ? 1 : $month + 1;
                $nextYear  = $month == 12 ? $year + 1 : $year;

                $monthNames = [
                    1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
                    5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
                    9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
                ];
            @endphp

            <a href="{{ route('student.attendance.calendar', ['month' => $prevMonth, 'year' => $prevYear]) }}"
                class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="text-center">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white">
                    {{ $monthNames[$month] }} {{ $year }}
                </h2>
            </div>

            <a href="{{ route('student.attendance.calendar', ['month' => $nextMonth, 'year' => $nextYear]) }}"
                class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex flex-wrap items-center gap-4 text-xs font-medium">
        <div class="flex items-center gap-1.5">
            <span class="w-4 h-4 rounded bg-green-500 flex-shrink-0"></span>
            <span class="text-gray-600 dark:text-gray-400">Keldi</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-4 h-4 rounded bg-red-400 flex-shrink-0"></span>
            <span class="text-gray-600 dark:text-gray-400">Kelmadi</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-4 h-4 rounded bg-amber-400 flex-shrink-0"></span>
            <span class="text-gray-600 dark:text-gray-400">Kechikdi</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-4 h-4 rounded bg-blue-400 flex-shrink-0"></span>
            <span class="text-gray-600 dark:text-gray-400">Uzrli</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-4 h-4 rounded bg-gray-200 dark:bg-gray-700 flex-shrink-0"></span>
            <span class="text-gray-600 dark:text-gray-400">Dam olish</span>
        </div>
    </div>

    {{-- Calendar Grid --}}
    @php
        use Carbon\Carbon;
        $firstDay = Carbon::create($year, $month, 1);
        $daysInMonth = $firstDay->daysInMonth;

        // 0=Sunday ... 6=Saturday, we want Monday=0
        $startDow = $firstDay->dayOfWeek; // 0=Sun
        $startOffset = ($startDow === 0) ? 6 : $startDow - 1; // Monday-first offset

        $today = Carbon::today()->format('Y-m-d');
    @endphp

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        {{-- Day headers --}}
        <div class="grid grid-cols-7 border-b border-gray-100 dark:border-gray-700">
            @foreach(['Du', 'Se', 'Ch', 'Pa', 'Ju', 'Sh', 'Ya'] as $dayName)
            <div class="py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider
                {{ $loop->last ? 'text-red-400' : '' }}">
                {{ $dayName }}
            </div>
            @endforeach
        </div>

        {{-- Days grid --}}
        <div class="grid grid-cols-7">
            {{-- Empty cells before month starts --}}
            @for($i = 0; $i < $startOffset; $i++)
            <div class="min-h-[90px] border-b border-r border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/20"></div>
            @endfor

            {{-- Day cells --}}
            @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $attendance = $attendances->get($dateStr);
                $isToday = $dateStr === $today;
                $col = ($startOffset + $day - 1) % 7; // 0=Mon ... 6=Sun
                $isWeekend = $col === 6;

                $cellBg = '';
                $dotColor = '';
                $statusLabel = '';
                if ($attendance) {
                    switch ($attendance->status) {
                        case 'present':
                            $cellBg    = 'bg-green-50 dark:bg-green-900/10';
                            $dotColor  = 'bg-green-500';
                            $statusLabel = 'Keldi';
                            break;
                        case 'absent':
                            $cellBg    = 'bg-red-50 dark:bg-red-900/10';
                            $dotColor  = 'bg-red-400';
                            $statusLabel = 'Kelmadi';
                            break;
                        case 'late':
                            $cellBg    = 'bg-amber-50 dark:bg-amber-900/10';
                            $dotColor  = 'bg-amber-400';
                            $statusLabel = 'Kechikdi';
                            break;
                        case 'excused':
                            $cellBg    = 'bg-blue-50 dark:bg-blue-900/10';
                            $dotColor  = 'bg-blue-400';
                            $statusLabel = 'Uzrli';
                            break;
                    }
                } elseif ($isWeekend) {
                    $cellBg = 'bg-gray-50/70 dark:bg-gray-900/30';
                }
            @endphp

            <div class="min-h-[90px] border-b border-r border-gray-100 dark:border-gray-700/50 p-2 relative flex flex-col {{ $cellBg }}
                {{ $isWeekend ? 'opacity-60' : '' }}">

                {{-- Day number --}}
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-bold
                        {{ $isToday ? 'w-7 h-7 flex items-center justify-center rounded-full bg-indigo-600 text-white text-xs' : ($isWeekend ? 'text-red-400' : 'text-gray-700 dark:text-gray-300') }}">
                        {{ $day }}
                    </span>
                    @if($attendance)
                    <span class="w-2.5 h-2.5 rounded-full {{ $dotColor }} flex-shrink-0"></span>
                    @endif
                </div>

                {{-- Lesson info --}}
                @if($attendance)
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold
                        {{ $attendance->status === 'present' ? 'text-green-700 dark:text-green-400'
                         : ($attendance->status === 'absent' ? 'text-red-600 dark:text-red-400'
                         : ($attendance->status === 'late' ? 'text-amber-600 dark:text-amber-400'
                         : 'text-blue-600 dark:text-blue-400')) }}">
                        {{ $statusLabel }}
                    </p>
                    @if($attendance->lesson->group ?? null)
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate leading-tight mt-0.5">
                        {{ $attendance->lesson->group->name ?? '' }}
                    </p>
                    @endif
                    @if($attendance->status === 'late' && $attendance->late_minutes)
                    <p class="text-xs text-amber-500 font-medium">+{{ $attendance->late_minutes }}min</p>
                    @endif
                    @if(!is_null($attendance->homework_score) || !is_null($attendance->activity_score))
                    <div class="flex items-center gap-1 mt-1">
                        @if(!is_null($attendance->homework_score))
                        <span class="text-xs bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 px-1 rounded font-semibold">
                            UY:{{ $attendance->homework_score }}
                        </span>
                        @endif
                        @if(!is_null($attendance->activity_score))
                        <span class="text-xs bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 px-1 rounded font-semibold">
                            FA:{{ $attendance->activity_score }}
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endfor

            {{-- Fill remaining cells --}}
            @php
                $totalCells = $startOffset + $daysInMonth;
                $remainder  = $totalCells % 7;
                $trailingCells = $remainder > 0 ? 7 - $remainder : 0;
            @endphp
            @for($i = 0; $i < $trailingCells; $i++)
            <div class="min-h-[90px] border-b border-r border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/20"></div>
            @endfor
        </div>
    </div>

    {{-- Monthly Summary --}}
    @php
        $monthPresent = $attendances->where('status', 'present')->count();
        $monthAbsent  = $attendances->where('status', 'absent')->count();
        $monthLate    = $attendances->where('status', 'late')->count();
        $monthExcused = $attendances->where('status', 'excused')->count();
        $monthTotal   = $attendances->count();
        $monthRate    = $monthTotal > 0 ? round($monthPresent / $monthTotal * 100, 1) : 0;
    @endphp

    @if($monthTotal > 0)
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $monthTotal }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Jami dars</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900/10 rounded-xl border border-green-200 dark:border-green-800/30 p-4 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-green-600 dark:text-green-400">{{ $monthPresent }}</p>
            <p class="text-xs text-green-600/70 dark:text-green-500 mt-0.5">Keldi</p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/10 rounded-xl border border-red-200 dark:border-red-800/30 p-4 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-red-500">{{ $monthAbsent }}</p>
            <p class="text-xs text-red-500/70 mt-0.5">Kelmadi</p>
        </div>
        <div class="bg-amber-50 dark:bg-amber-900/10 rounded-xl border border-amber-200 dark:border-amber-800/30 p-4 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-amber-500">{{ $monthLate }}</p>
            <p class="text-xs text-amber-500/70 mt-0.5">Kechikdi</p>
        </div>
        <div class="bg-indigo-50 dark:bg-indigo-900/10 rounded-xl border border-indigo-200 dark:border-indigo-800/30 p-4 text-center shadow-sm sm:col-span-1 col-span-2">
            <p class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $monthRate }}%</p>
            <p class="text-xs text-indigo-600/70 dark:text-indigo-400/70 mt-0.5">Davomat</p>
        </div>
    </div>
    @endif

</div>
@endsection