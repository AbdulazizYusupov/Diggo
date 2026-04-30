@extends('Layout.app')

@section('title', 'Davomat - ' . $group->name)

@section('content')
    <div class="space-y-4 pb-24 md:pb-32">

        {{-- ================================================================
             HEADER
        ================================================================ --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.index') }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="space-y-0.5">
                    <h1 class="text-lg md:text-2xl lg:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        {{ $group->name }}
                    </h1>
                    <p class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ optional($group->course)->title ?? 'Kurs mavjud emas' }} • {{ $students->count() }} o'quvchi
                        • {{ $lessons->count() }} dars
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <form method="GET" action="{{ route('attendance.show', $group->id) }}" class="flex items-center gap-2">
                    <select name="month"
                        class="px-3 py-2 md:px-4 md:py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs md:text-sm font-medium text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ get_month_name($m) }}
                            </option>
                        @endfor
                    </select>
                    <select name="year"
                        class="px-3 py-2 md:px-4 md:py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs md:text-sm font-medium text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                        @foreach (get_attendance_years() as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="px-3 py-2 md:px-4 md:py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>

                <a href="{{ route('attendance.statistics', $group->id) }}"
                    class="px-3 py-2 md:px-4 md:py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="text-xs md:text-sm">Statistika</span>
                </a>
                <a href="{{ route('attendance.export', ['groupId' => $group->id, 'year' => now()->year]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 
          text-white font-semibold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Excel yuklash
                </a>
            </div>
        </div>

        {{-- ================================================================
             COIN TIZIMI CARD
        ================================================================ --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-xl md:rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-3 md:p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row items-start gap-3">
                <div
                    class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 bg-amber-50 dark:bg-amber-500/10 rounded-lg md:rounded-xl flex items-center justify-center border border-amber-100 dark:border-amber-500/20">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-600 dark:text-amber-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 w-full">
                    <h3 class="text-sm md:text-base lg:text-lg font-bold text-gray-900 dark:text-white mb-2 md:mb-3">
                        Coin tizimi (1 dars uchun max: 8)
                    </h3>
                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-2">
                        @foreach (attendance_coin_tiers() as $tier)
                            <div
                                class="flex items-center gap-2 p-2 md:p-3 {{ $tier['bg'] }} rounded-lg border {{ $tier['border'] }}">
                                <svg class="w-4 h-4 md:w-5 md:h-5 {{ $tier['textColor'] }} flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $tier['icon'] }}" />
                                </svg>
                                <div>
                                    <p class="text-[10px] md:text-xs {{ $tier['textColor'] }} font-medium">
                                        {{ $tier['label'] }}</p>
                                    <p class="text-xs md:text-sm font-bold {{ $tier['valueColor'] }}">{{ $tier['value'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================
             DAVOMAT JADVALI
        ================================================================ --}}
        <form method="POST" action="{{ route('attendance.store') }}" id="attendanceForm">
            @csrf
            <input type="hidden" name="group_id" value="{{ $group->id }}">

            <div class="relative">
                {{-- Scroll tugmalari --}}
                <div class="absolute -top-12 right-0 flex gap-2 z-30">
                    <button type="button" id="scrollLeftBtn"
                        class="p-2 md:p-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button type="button" id="scrollRightBtn"
                        class="p-2 md:p-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <div
                    class="bg-white dark:bg-slate-900 rounded-xl md:rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 overflow-hidden">
                    <div class="overflow-x-auto scroll-smooth" id="attendanceTable">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                            {{-- ---- THEAD ---- --}}
                            <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                                <tr>
                                    <th
                                        class="px-2 md:px-3 py-2 md:py-3 text-left text-[10px] md:text-xs font-bold text-white uppercase tracking-wider sticky left-0 bg-blue-600 z-20 min-w-[90px] md:min-w-[120px]">
                                        O'quvchi
                                    </th>

                                    @foreach ($lessons as $lesson)
                                        <th
                                            class="px-2 md:px-3 py-2 md:py-3 text-center text-[10px] md:text-xs font-bold uppercase min-w-[115px] md:min-w-[140px]
                                            {{ $lesson->lesson_date->isSameDay($today) ? 'bg-yellow-500 text-black ring-2 ring-inset ring-yellow-300' : 'text-white' }}">
                                            <div class="flex flex-col items-center gap-0.5">
                                                <span
                                                    class="{{ $lesson->lesson_date->isSameDay($today) ? 'text-lg md:text-xl' : 'text-sm md:text-base' }} font-black">
                                                    {{ $lesson->lesson_date->format('d') }}
                                                </span>
                                                <span
                                                    class="text-[9px] md:text-[10px] {{ $lesson->lesson_date->isSameDay($today) ? 'text-black font-black' : 'text-blue-200' }}">
                                                    {{ get_day_name($lesson->lesson_date->format('l')) }}
                                                </span>
                                                @if (($lessonsPerDay[$lesson->lesson_date->format('Y-m-d')] ?? 1) > 1 && lesson_time_label($lesson->start_time))
                                                    <span
                                                        class="text-[8px] md:text-[9px] font-bold px-1 py-0.5 rounded
                                                        {{ $lesson->lesson_date->isSameDay($today) ? 'bg-black/20 text-black' : 'bg-white/20 text-white' }}">
                                                        {{ lesson_time_label($lesson->start_time) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </th>
                                    @endforeach

                                    <th
                                        class="px-2 md:px-3 py-2 md:py-3 text-center text-[10px] md:text-xs font-bold text-white uppercase tracking-wider bg-purple-600 sticky right-0 z-20 min-w-[60px] md:min-w-[75px]">
                                        Coin
                                    </th>
                                </tr>
                            </thead>

                            {{-- ---- TBODY ---- --}}
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($students as $studentIndex => $student)
                                    <tr class="group hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">

                                        {{-- O'quvchi nomi --}}
                                        <td
                                            class="px-2 md:px-3 py-2 md:py-3 sticky left-0 bg-white dark:bg-slate-800 z-10 border-r border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-1.5">
                                                <div
                                                    class="w-7 h-7 md:w-9 md:h-9 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-[10px] md:text-xs shadow-lg flex-shrink-0">
                                                    {{ user_initials(optional($student->user)->surname, optional($student->user)->name) }}
                                                </div>
                                                <div class="min-w-0 max-w-[80px] md:max-w-[120px]">
                                                    <div
                                                        class="text-[10px] md:text-xs font-bold text-gray-900 dark:text-white break-words whitespace-normal uppercase">
                                                        {{ optional($student->user)->surname ?? '' }}
                                                    </div>
                                                    <div
                                                        class="text-[9px] md:text-[11px] font-medium text-gray-600 dark:text-gray-400 break-words whitespace-normal">
                                                        {{ optional($student->user)->name ?? '' }}
                                                    </div>
                                                    <div
                                                        class="text-[9px] md:text-[11px] font-medium text-gray-600 dark:text-gray-400 break-words whitespace-normal">
                                                        {{ optional($student->user)->middle_name ?? '' }}
                                                    </div>
                                                    <div
                                                        class="text-[9px] md:text-xs text-amber-600 dark:text-amber-400 font-semibold flex items-center gap-0.5 mt-0.5">
                                                        <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $student->coins ?? 0 }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Har bir lesson uchun davomat cell --}}
                                        @foreach ($lessons as $lessonIndex => $lesson)
                                            <td class="px-1.5 md:px-2 py-2 md:py-3 text-center align-top"
                                                data-student="{{ $student->id }}"
                                                data-day="{{ $lesson->lesson_date->format('Y-m-d') }}"
                                                data-lesson="{{ $lesson->id }}">

                                                <input type="hidden"
                                                    name="attendances[{{ $studentIndex }}][{{ $lessonIndex }}][student_id]"
                                                    value="{{ $student->id }}">
                                                <input type="hidden"
                                                    name="attendances[{{ $studentIndex }}][{{ $lessonIndex }}][lesson_id]"
                                                    value="{{ $lesson->id }}">
                                                <input type="hidden" class="late-minutes-input"
                                                    name="attendances[{{ $studentIndex }}][{{ $lessonIndex }}][late_minutes]"
                                                    value="{{ $attendanceMap[$student->id][$lesson->id]['late_minutes'] ?? 0 }}">
                                                <input type="hidden" class="late-reason-input"
                                                    name="attendances[{{ $studentIndex }}][{{ $lessonIndex }}][notes]"
                                                    value="{{ $attendanceMap[$student->id][$lesson->id]['notes'] ?? '' }}">

                                                <div class="flex flex-col items-center gap-1.5">

                                                    {{-- Status: present / late / absent --}}
                                                    <div class="flex items-center justify-center gap-0.5 md:gap-1">
                                                        @foreach ($statusTypes as $statusType => $statusConfig)
                                                            <label class="relative cursor-pointer">
                                                                <input type="radio"
                                                                    name="attendances[{{ $studentIndex }}][{{ $lessonIndex }}][status]"
                                                                    value="{{ $statusType }}"
                                                                    @checked(($attendanceMap[$student->id][$lesson->id]['status'] ?? '') === $statusType)
                                                                    class="peer sr-only attendance-status"
                                                                    data-type="{{ $statusType }}">
                                                                <div
                                                                    class="w-7 h-7 md:w-8 md:h-8 rounded-md transition-all flex items-center justify-center
                                                                    bg-gray-100 dark:bg-slate-700 text-gray-400
                                                                    peer-checked:bg-{{ $statusConfig['color'] }}-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-{{ $statusConfig['color'] }}-300
                                                                    hover:bg-{{ $statusConfig['color'] }}-50 dark:hover:bg-{{ $statusConfig['color'] }}-900/30">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="{{ $statusConfig['strokeW'] }}"
                                                                            d="{{ $statusConfig['icon'] }}" />
                                                                    </svg>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>

                                                    {{-- Homework & Activity --}}
                                                    <div
                                                        class="homework-activity-container w-full {{ isset($attendanceMap[$student->id][$lesson->id]) && in_array($attendanceMap[$student->id][$lesson->id]['status'], ['present', 'late']) ? '' : 'opacity-30 pointer-events-none' }}">

                                                        {{-- Homework (0-3) --}}
                                                        <div
                                                            class="flex items-center justify-center gap-0.5 pt-1.5 border-t border-gray-100 dark:border-gray-700 w-full">
                                                            @foreach ([0, 1, 2, 3] as $score)
                                                                <label class="relative cursor-pointer">
                                                                    <input type="radio"
                                                                        name="attendances[{{ $studentIndex }}][{{ $lessonIndex }}][homework_score]"
                                                                        value="{{ $score }}"
                                                                        @checked(isset($attendanceMap[$student->id][$lesson->id])
                                                                                ? $attendanceMap[$student->id][$lesson->id]['homework_score'] == $score
                                                                                : $score == 0) class="peer sr-only">
                                                                    <div
                                                                        class="w-5 h-5 md:w-6 md:h-6 rounded text-[9px] md:text-[10px] font-bold transition-all flex items-center justify-center
                                                                        bg-gray-50 dark:bg-slate-800 text-gray-400
                                                                        peer-checked:bg-blue-600 peer-checked:text-white
                                                                        hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                                                        {{ $score }}
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                            <span
                                                                class="text-[8px] md:text-[9px] text-blue-500 font-bold ml-0.5">HW</span>
                                                        </div>

                                                        {{-- Activity (0-3) --}}
                                                        <div class="flex items-center justify-center gap-0.5 mt-1 w-full">
                                                            @foreach ([0, 1, 2, 3] as $score)
                                                                <label class="relative cursor-pointer">
                                                                    <input type="radio"
                                                                        name="attendances[{{ $studentIndex }}][{{ $lessonIndex }}][activity_score]"
                                                                        value="{{ $score }}"
                                                                        @checked(isset($attendanceMap[$student->id][$lesson->id])
                                                                                ? $attendanceMap[$student->id][$lesson->id]['activity_score'] == $score
                                                                                : $score == 0) class="peer sr-only">
                                                                    <div
                                                                        class="w-5 h-5 md:w-6 md:h-6 rounded text-[9px] md:text-[10px] font-bold transition-all flex items-center justify-center
                                                                        bg-gray-50 dark:bg-slate-800 text-gray-400
                                                                        peer-checked:bg-purple-600 peer-checked:text-white
                                                                        hover:bg-purple-50 dark:hover:bg-purple-900/20">
                                                                        {{ $score }}
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                            <span
                                                                class="text-[8px] md:text-[9px] text-purple-500 font-bold ml-0.5">ACT</span>
                                                        </div>
                                                    </div>

                                                    {{-- Kunlik coin ko'rsatish --}}
                                                    @if (isset($attendanceMap[$student->id][$lesson->id]) && $attendanceMap[$student->id][$lesson->id]['daily_coins'] > 0)
                                                        <div
                                                            class="text-[10px] md:text-xs font-bold text-amber-600 dark:text-amber-400">
                                                            +{{ $attendanceMap[$student->id][$lesson->id]['daily_coins'] }}
                                                        </div>
                                                    @endif

                                                </div>
                                            </td>
                                        @endforeach

                                        {{-- Jami coin --}}
                                        <td
                                            class="px-2 md:px-3 py-2 md:py-3 text-center sticky right-0 z-10 border-l border-gray-200 dark:border-gray-700 bg-purple-50 dark:bg-purple-950 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/40">
                                            <div
                                                class="text-base md:text-xl font-black text-purple-600 dark:text-purple-400">
                                                {{ $studentTotalCoins[$student->id] ?? 0 }}
                                            </div>
                                            <div class="text-[8px] md:text-[10px] text-purple-500 font-semibold">coins
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Saqlash tugmasi --}}
            <div class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-50">
                <button type="submit"
                    class="px-4 py-3 md:px-6 md:py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-xl md:rounded-2xl shadow-xl shadow-emerald-500/30 transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm md:text-base">Saqlash</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ================================================================
         LATE MODAL
    ================================================================ --}}
    <div id="lateModal"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Kechikish vaqti</h3>
                <button type="button" onclick="closeLateModal()"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Necha daqiqa
                    kechikdi?</label>
                <input type="number" id="lateMinutesInput" min="1" max="120"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none"
                    placeholder="Daqiqalar" value="5">
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    • 0-15 daqiqa: +2 coin<br>
                    • 15-30 daqiqa: +1 coin<br>
                    • 30+ daqiqa: kelmagan hisoblanadi
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kechikish sababi
                    (ixtiyoriy)</label>
                <textarea id="lateReasonInput" rows="3"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none resize-none"
                    placeholder="Sabab kiriting..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeLateModal()"
                    class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors">
                    Bekor qilish
                </button>
                <button type="button" onclick="confirmLateMinutes()"
                    class="flex-1 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl transition-colors">
                    Tasdiqlash
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentLateCell = null;

            document.addEventListener('DOMContentLoaded', () => {
                initAttendance();
                initScrollControls();
                scrollToToday();
            });

            // ----------------------------------------------------------------
            // Attendance status radio listener
            // ----------------------------------------------------------------
            function initAttendance() {
                document.querySelectorAll('.attendance-status').forEach(radio => {
                    if (radio.checked) radio.dataset.checked = 'true';

                    radio.addEventListener('click', function() {
                        const wasChecked = this.dataset.checked === 'true';
                        const cell = this.closest('td');
                        const allInGroup = document.querySelectorAll(`input[name="${this.name}"]`);

                        if (wasChecked) {
                            this.checked = false;
                            this.dataset.checked = 'false';
                            getOrCreateHiddenStatus(cell, this.name).value = '';
                            updateHwActContainer(cell, null);
                        } else {
                            allInGroup.forEach(r => r.dataset.checked = 'false');
                            this.dataset.checked = 'true';
                            removeHiddenStatus(cell);

                            if (this.dataset.type === 'late') {
                                openLateModal(this);
                            } else {
                                updateHwActContainer(cell, this.value);
                            }
                        }
                    });
                });
            }

            function getOrCreateHiddenStatus(cell, name) {
                let h = cell.querySelector('.hidden-status-input');
                if (!h) {
                    h = document.createElement('input');
                    h.type = 'hidden';
                    h.name = name;
                    h.className = 'hidden-status-input';
                    cell.appendChild(h);
                }
                return h;
            }

            function removeHiddenStatus(cell) {
                cell.querySelector('.hidden-status-input')?.remove();
            }

            function updateHwActContainer(cell, status) {
                const container = cell.querySelector('.homework-activity-container');
                if (!container) return;

                if (!status || status === 'absent') {
                    container.classList.add('opacity-30', 'pointer-events-none');
                    resetScores(cell);
                } else {
                    container.classList.remove('opacity-30', 'pointer-events-none');
                }
            }

            function resetScores(cell) {
                ['homework_score', 'activity_score'].forEach(name => {
                    cell.querySelectorAll(`input[name*="${name}"]`).forEach(r => {
                        r.checked = r.value === '0';
                    });
                });
            }

            // ----------------------------------------------------------------
            // Late modal
            // ----------------------------------------------------------------
            function openLateModal(radio) {
                currentLateCell = radio.closest('td');
                const mins = currentLateCell.querySelector('.late-minutes-input')?.value ?? 5;
                const note = currentLateCell.querySelector('.late-reason-input')?.value ?? '';

                document.getElementById('lateMinutesInput').value = mins || 5;
                document.getElementById('lateReasonInput').value = note;
                document.getElementById('lateModal').classList.remove('hidden');
                document.getElementById('lateMinutesInput').focus();
            }

            function closeLateModal() {
                if (currentLateCell) {
                    const lateRadio = currentLateCell.querySelector('input[data-type="late"]');
                    if (lateRadio) {
                        lateRadio.checked = false;
                        lateRadio.dataset.checked = 'false';
                    }
                }
                document.getElementById('lateModal').classList.add('hidden');
                currentLateCell = null;
            }

            function confirmLateMinutes() {
                const mins = parseInt(document.getElementById('lateMinutesInput').value) || 5;
                const note = document.getElementById('lateReasonInput').value.trim();

                if (currentLateCell) {
                    const mInput = currentLateCell.querySelector('.late-minutes-input');
                    const nInput = currentLateCell.querySelector('.late-reason-input');
                    if (mInput) mInput.value = mins;
                    if (nInput) nInput.value = note;

                    const lateRadio = currentLateCell.querySelector('input[data-type="late"]');
                    if (lateRadio) lateRadio.dataset.checked = 'true';

                    updateHwActContainer(currentLateCell, 'late');
                }

                document.getElementById('lateModal').classList.add('hidden');
                currentLateCell = null;
            }

            // ----------------------------------------------------------------
            // Scroll controls
            // ----------------------------------------------------------------
            function initScrollControls() {
                const table = document.getElementById('attendanceTable');
                const leftBtn = document.getElementById('scrollLeftBtn');
                const rightBtn = document.getElementById('scrollRightBtn');
                if (!table) return;

                leftBtn.addEventListener('click', () => table.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                }));
                rightBtn.addEventListener('click', () => table.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                }));
                table.addEventListener('scroll', () => updateScrollBtns(table, leftBtn, rightBtn));
                updateScrollBtns(table, leftBtn, rightBtn);
            }

            function updateScrollBtns(table, leftBtn, rightBtn) {
                const {
                    scrollLeft,
                    scrollWidth,
                    clientWidth
                } = table;
                leftBtn.disabled = scrollLeft <= 0;
                rightBtn.disabled = scrollLeft + clientWidth >= scrollWidth - 1;
            }

            function scrollToToday() {
                const table = document.getElementById('attendanceTable');
                const todayStr = new Date().toISOString().split('T')[0];
                const todayCell = document.querySelector(`td[data-day="${todayStr}"]`);
                if (!table || !todayCell) return;

                const pos = todayCell.offsetLeft - table.offsetWidth / 2 + todayCell.offsetWidth / 2;
                table.scrollTo({
                    left: Math.max(0, pos),
                    behavior: 'smooth'
                });
            }

            document.getElementById('lateModal').addEventListener('click', e => {
                if (e.target === document.getElementById('lateModal')) closeLateModal();
            });

            document.getElementById('lateMinutesInput').addEventListener('keypress', e => {
                if (e.key === 'Enter') confirmLateMinutes();
            });
        </script>
    @endpush
@endsection
