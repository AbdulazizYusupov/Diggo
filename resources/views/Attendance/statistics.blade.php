@extends('Layout.app')

@section('title', 'Statistika - ' . $group->name)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('attendance.show', $group->id) }}"
                class="p-2 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div class="space-y-1">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Davomat statistikasi
                </h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    {{ $group->name }} • {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400">Jami
                            darslar</p>
                        <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-2">
                            {{ $stats->first()->total_lessons ?? 0 }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400">O'rtacha
                            davomat</p>
                        <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-2">
                            {{ $stats->avg('present_count') > 0 ? round(($stats->avg('present_count') / ($stats->first()->total_lessons ?? 1)) * 100) : 0 }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400">Jami uy
                            vazifa</p>
                        <p class="text-3xl font-black text-purple-600 dark:text-purple-400 mt-2">
                            {{ $stats->sum('total_homework') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-amber-500/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400">Faollik</p>
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-2">
                            {{ $stats->sum('total_participation') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Statistics Table -->
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">O'quvchilar statistikasi</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                O'quvchi</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Jami</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Keldi</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Kechikdi</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Kelmadi</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Davomat %</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Uy vazifa</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                Faollik</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($stats as $stat)
                            @php
                                $attendancePercent =
                                    $stat->total_lessons > 0
                                        ? round(($stat->present_count / $stat->total_lessons) * 100)
                                        : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                                            {{ substr($stat->student->user->name ?? 'N', 0, 1) }}{{ substr($stat->student->user->surname ?? 'A', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $stat->student->user->surname ?? '' }}
                                                {{ $stat->student->user->name ?? '' }}
                                            </div>
                                            <div class="text-xs text-amber-600 dark:text-amber-400 font-semibold">
                                                🪙 {{ $stat->student->coins ?? 0 }} coins
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-white">{{ $stat->total_lessons }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        {{ $stat->present_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                        {{ $stat->late_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">
                                        {{ $stat->absent_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span
                                            class="text-lg font-black {{ $attendancePercent >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($attendancePercent >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                                            {{ $attendancePercent }}%
                                        </span>
                                        <div class="w-20 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all {{ $attendancePercent >= 80 ? 'bg-emerald-500' : ($attendancePercent >= 60 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                                style="width: {{ $attendancePercent }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                        {{ $stat->total_homework }} / {{ $stat->total_lessons * 2 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-purple-600 dark:text-purple-400">
                                        {{ $stat->total_participation }} / {{ $stat->total_lessons * 2 }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Ma'lumot topilmadi
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hali davomat belgilanmagan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
