@extends('Layout.app')

@section('title', 'O\'quvchi Kabineti')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl shadow-lg shadow-blue-500/30 border-b-2 border-blue-800/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Mening Kabinetim
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ $student->user->name }} {{ $student->user->surname }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="px-4 py-2 bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl border border-blue-300 dark:border-blue-700/50">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-blue-700 dark:text-blue-400">ID:</span>
                        <span class="text-lg font-extrabold text-blue-900 dark:text-blue-50">
                            #{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coins and Balance Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Current Coins --}}
            <div
                class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">
                            Joriy Coinlar
                        </p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ number_format($stats['current_coins'], 0, '.', ' ') }}
                            <span class="text-lg">coin</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-amber-500 rounded-xl shadow-lg shadow-amber-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- This Month Coins --}}
            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            Shu oy coinlar
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($stats['this_month_coins'], 0, '.', ' ') }}
                            <span class="text-lg">coin</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-emerald-600 rounded-xl shadow-lg shadow-emerald-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Current Balance --}}
            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider">
                            Joriy balans
                        </p>
                        <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50 mt-1">
                            {{ number_format($stats['current_balance'], 0, '.', ' ') }}
                            <span class="text-lg">so'm</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-purple-600 rounded-xl shadow-lg shadow-purple-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Attendance Rate --}}
            <div
                class="bg-gradient-to-br from-cyan-100 to-cyan-200/60 dark:from-cyan-900/30 dark:to-cyan-800/30 rounded-2xl p-6 border border-cyan-200 dark:border-cyan-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider">
                            Davomat
                        </p>
                        <p class="text-3xl font-extrabold text-cyan-900 dark:text-cyan-50 mt-1">
                            {{ $stats['attendance_rate'] }}%
                        </p>
                    </div>
                    <div
                        class="p-3 bg-cyan-600 rounded-xl shadow-lg shadow-cyan-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Performance Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wider">
                            Faol guruhlar
                        </p>
                        <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-1">
                            {{ $stats['active_groups_count'] }}
                        </p>
                    </div>
                    <div
                        class="p-3 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">
                            Jami darslar
                        </p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ $stats['total_lessons'] }}
                        </p>
                    </div>
                    <div
                        class="p-3 bg-blue-600 rounded-xl shadow-lg shadow-blue-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-100 to-green-200/60 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl p-6 border border-green-200 dark:border-green-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">
                            O'rtacha uy ishi
                        </p>
                        <p class="text-3xl font-extrabold text-green-900 dark:text-green-50 mt-1">
                            {{ $stats['avg_homework_score'] ?? 0 }}
                            <span class="text-lg">ball</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-green-600 rounded-xl shadow-lg shadow-green-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-rose-100 to-rose-200/60 dark:from-rose-900/30 dark:to-rose-800/30 rounded-2xl p-6 border border-rose-200 dark:border-rose-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider">
                            Faollik bali
                        </p>
                        <p class="text-3xl font-extrabold text-rose-900 dark:text-rose-50 mt-1">
                            {{ $stats['avg_activity_score'] ?? 0 }}
                            <span class="text-lg">ball</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-rose-600 rounded-xl shadow-lg shadow-rose-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Groups --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Mening guruhlarim
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($activeGroups as $group)
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl p-5 border border-indigo-200 dark:border-indigo-800/50">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $group['name'] }}</h4>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold">
                                    {{ $group['course_name'] }}
                                </p>
                            </div>
                            <span
                            {{-- {{ $group['level'] }} --}}
                                class="px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-lg"></span>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $group['lesson_days'] }}</span>
                            </div>

                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $group['lesson_time'] }}</span>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-indigo-200 dark:border-indigo-800">
                                <div class="text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Davomat:</span>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $group['attendance_rate'] }}%</span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Darslar:</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $group['total_lessons'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">Faol guruhlar topilmadi</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Charts and Data --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Monthly Coin History --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Oylik coin tarixi
                </h3>
                <div class="space-y-3">
                    @forelse($monthlyCoinHistory as $month)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $month->month_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $month->count }} ta tranzaksiya
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-amber-600 dark:text-amber-400">
                                    {{ number_format($month->total, 0, '.', ' ') }} coin
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Ma'lumot topilmadi</p>
                    @endforelse
                </div>
            </div>

            {{-- Attendance Statistics This Month --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Shu oylik davomat
                </h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white">Qatnashgan</span>
                        </div>
                        <span
                            class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $attendanceStats['present'] }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white">Qatnashmagan</span>
                        </div>
                        <span
                            class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $attendanceStats['absent'] }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-600 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white">Kechikkan</span>
                        </div>
                        <span
                            class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $attendanceStats['late'] }}</span>
                    </div>

                    @if($attendanceStats['total'] > 0)
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Davomat
                                    foizi:</span>
                                <span class="text-xl font-bold text-cyan-600 dark:text-cyan-400">
                                    {{ $attendanceStats['rate'] }}%
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Upcoming Lessons --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Yaqin darslar
            </h3>

            <div class="space-y-3">
                @forelse($upcomingLessons as $lesson)
                    <div
                        class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800/30">
                        <div class="flex items-center gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ \Carbon\Carbon::parse($lesson->lesson_date)->format('d') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($lesson->lesson_date)->locale('uz_Latn')->translatedFormat('M') }}
                                </p>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $lesson->group_name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $lesson->course_name }}</p>
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mt-1">
                                    {{ $lesson->start_time }}
                                </p>
                            </div>
                        </div>
                        @if($lesson->attendance_status)
                            <span
                                class="px-3 py-1 text-xs font-bold rounded-lg
                                {{ $lesson->attendance_status === 'present' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                {{ $lesson->attendance_status === 'absent' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                {{ $lesson->attendance_status === 'late' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}">
                                @if($lesson->attendance_status === 'present')
                                    Qatnashgan
                                @elseif($lesson->attendance_status === 'absent')
                                    Qatnashmagan
                                @elseif($lesson->attendance_status === 'late')
                                    Kechikkan
                                @endif
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 text-xs font-bold rounded-lg">
                                Kutilmoqda
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">Yaqin darslar topilmadi</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Activities --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Coin Transactions --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    So'nggi coin tranzaksiyalari
                </h3>

                <div class="space-y-2">
                    @forelse($recentCoinTransactions->take(5) as $transaction)
                        <div
                            class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <div
                                    class="p-2 rounded-lg {{ $transaction->coins > 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                    <svg class="w-4 h-4 {{ $transaction->coins > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($transaction->coins > 0)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d.m.Y') }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="font-bold {{ $transaction->coins > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->coins > 0 ? '+' : '' }}{{ number_format($transaction->coins, 0, '.', ' ') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Tranzaksiyalar topilmadi</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Payments --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    So'nggi to'lovlar
                </h3>

                <div class="space-y-2">
                    @forelse($recentPayments->take(5) as $payment)
                        <div
                            class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-colors">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                    {{ $payment->group->course->name ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d.m.Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($payment->net_amount, 0, '.', ' ') }} so'm
                                </p>
                                @if($payment->discount > 0)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        -{{ number_format($payment->discount, 0, '.', ' ') }} chegirma
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">To'lovlar topilmadi</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Teachers --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Mening o'qituvchilarim
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($teachers as $teacher)
                    <div
                        class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($teacher->name, 0, 1) }}{{ substr($teacher->surname ?? '', 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 dark:text-white">
                                    {{ $teacher->name }} {{ $teacher->surname }}
                                </p>
                                <p class="text-sm text-purple-600 dark:text-purple-400">{{ $teacher->course_name ?? '-' }}</p>
                                {{-- @if($teacher?->specialization)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $teacher->specialization }}</p>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">O'qituvchilar topilmadi</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- School Info --}}
        @if($student->school)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Maktab ma'lumotlari
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Maktab nomi</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $student->school->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Telefon</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $student->school->phone }}</p>
                        </div>
                    </div>

                    @if($student->school->email)
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $student->school->email }}</p>
                            </div>
                        </div>
                    @endif

                    @if($student->school->address)
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Manzil</p>
                                <p class="font-bold text-gray-900 dark:text-white">{{ $student->school->address }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection