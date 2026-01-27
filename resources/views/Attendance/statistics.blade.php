@extends('Layout.app')

@section('title', 'Statistika - ' . $group->name)

@section('content')
    <div class="space-y-4 pb-24">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.show', $group->id) }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-white">
                        Statistika
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $group->name }} • {{ $group->activeStudents->count() }} o'quvchi
                    </p>
                </div>
            </div>

            <!-- Month Filter -->
            <!-- Month Filter (TUZATILGAN) -->
            <form method="GET" class="flex items-center gap-2">
                <select name="month"
                    class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white">
                    <option value="">Barcha oylar</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->locale('uz')->monthName }}
                        </option>
                    @endfor
                </select>

                <select name="year"
                    class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white">
                    @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>

                <button type="submit"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Student Statistics -->
        <div class="grid gap-4">
            @foreach ($studentStats as $stat)
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <!-- Student Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr(optional($stat['student']->user)->surname ?? 'N', 0, 1) }}{{ substr(optional($stat['student']->user)->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ optional($stat['student']->user)->surname }}
                                    {{ optional($stat['student']->user)->name }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Davomat: {{ $stat['attendance_rate'] }}%
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-2xl font-black text-amber-600 dark:text-amber-400">
                                {{ $stat['total_coins'] }}
                            </div>
                            <div class="text-xs text-gray-500">jami coin</div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                        <!-- Present -->
                        <div
                            class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 border border-emerald-100 dark:border-emerald-800">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs font-medium text-emerald-600">Kelgan</span>
                            </div>
                            <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-400">{{ $stat['present'] }}
                            </div>
                        </div>

                        <!-- Late -->
                        <div
                            class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 border border-amber-100 dark:border-amber-800">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs font-medium text-amber-600">Kechikkan</span>
                            </div>
                            <div class="text-2xl font-bold text-amber-700 dark:text-amber-400">{{ $stat['late'] }}</div>
                        </div>

                        <!-- Absent -->
                        <div
                            class="bg-rose-50 dark:bg-rose-900/20 rounded-xl p-4 border border-rose-100 dark:border-rose-800">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="text-xs font-medium text-rose-600">Kelmagan</span>
                            </div>
                            <div class="text-2xl font-bold text-rose-700 dark:text-rose-400">{{ $stat['absent'] }}</div>
                        </div>

                        <!-- Homework -->
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-xs font-medium text-blue-600">O'rtacha UY</span>
                            </div>
                            <div class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ $stat['avg_homework'] }}/3
                            </div>
                        </div>

                        <!-- Activity -->
                        <div
                            class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-100 dark:border-purple-800">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                <span class="text-xs font-medium text-purple-600">O'rtacha Faollik</span>
                            </div>
                            <div class="text-2xl font-bold text-purple-700 dark:text-purple-400">
                                {{ $stat['avg_activity'] }}/3</div>
                        </div>
                    </div>

                    <!-- Late Details -->
                    @if ($stat['late_details']->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" onclick="toggleLateDetails({{ $stat['student']->id }})"
                                class="text-sm font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                Kechikishlar tafsiloti ({{ $stat['late'] }}ta)
                            </button>
                            <div id="late-details-{{ $stat['student']->id }}" class="hidden mt-3 space-y-2">
                                @foreach ($stat['late_details'] as $late)
                                    <div class="bg-amber-50 dark:bg-amber-900/10 rounded-lg p-3 text-sm">
                                        <div class="flex justify-between items-start">
                                            <span class="font-semibold text-amber-900 dark:text-amber-300">
                                                {{ $late['date'] }}
                                            </span>
                                            <span class="text-amber-700 dark:text-amber-400">
                                                {{ $late['minutes'] }} daqiqa
                                            </span>
                                        </div>
                                        @if ($late['reason'])
                                            <p class="text-amber-600 dark:text-amber-500 mt-1">{{ $late['reason'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleLateDetails(studentId) {
                const details = document.getElementById(`late-details-${studentId}`);
                details.classList.toggle('hidden');
            }
        </script>
    @endpush
@endsection
