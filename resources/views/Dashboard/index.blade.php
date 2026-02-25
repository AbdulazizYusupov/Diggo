@extends('Layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- ═══════════════════════════════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div class="space-y-1">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    O'quv Markazi Boshqaruvi
                </h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ now()->translatedFormat('d F Y, l') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2.5 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800/40 rounded-xl">
                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-semibold text-indigo-700 dark:text-indigo-300">
                    Bugun {{ $todaysLessons->count() }} ta dars
                </span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         PRIMARY KPI CARDS — 4 columns
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Students --}}
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">Jami o'quvchilar</p>
                <div class="p-2.5 bg-blue-500 rounded-xl shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalStudents) }}</p>
            <div class="flex items-center gap-1.5 mt-2">
                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">+{{ $newStudentsThisMonth }} ta</span>
                <span class="text-xs text-blue-600/70 dark:text-blue-400/70">bu oy qo'shildi</span>
            </div>
        </div>

        {{-- Total Teachers --}}
        <div class="bg-gradient-to-br from-violet-50 to-violet-100 dark:from-violet-900/20 dark:to-violet-800/20 rounded-2xl p-6 border border-violet-200 dark:border-violet-800 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-violet-700 dark:text-violet-300">O'qituvchilar</p>
                <div class="p-2.5 bg-violet-500 rounded-xl shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-violet-900 dark:text-violet-100">{{ $totalTeachers }}</p>
            <div class="flex items-center gap-1.5 mt-2">
                <svg class="w-4 h-4 text-violet-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs text-violet-600/70 dark:text-violet-400/70">ro'yxatdagi barcha o'qituvchilar</span>
            </div>
        </div>

        {{-- Active Groups --}}
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-800 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-300">Faol guruhlar</p>
                <div class="p-2.5 bg-indigo-500 rounded-xl shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-indigo-900 dark:text-indigo-100">
                {{ $activeGroups }} <span class="text-lg font-medium">ta</span>
            </p>
            <div class="flex items-center gap-1.5 mt-2">
                @if ($fullGroups > 0)
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">{{ $fullGroups }} ta guruh to'lgan</span>
                @else
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs text-indigo-600/70 dark:text-indigo-400/70">barcha guruhlar yaxshi</span>
                @endif
            </div>
        </div>

        {{-- Attendance Rate --}}
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-800 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Davomat (bu oy)</p>
                <div class="p-2.5 bg-emerald-500 rounded-xl shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-900 dark:text-emerald-100">
                {{ $attendanceStats['rate'] }}<span class="text-lg font-medium">%</span>
            </p>
            <div class="mt-3">
                <div class="w-full h-1.5 bg-emerald-200 dark:bg-emerald-900 rounded-full overflow-hidden">
                    <div class="h-full rounded-full
                        {{ $attendanceStats['rate'] >= 80 ? 'bg-emerald-500' : ($attendanceStats['rate'] >= 60 ? 'bg-amber-400' : 'bg-red-400') }}"
                        style="width: {{ $attendanceStats['rate'] }}%">
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         REVENUE & PENDING PAYMENTS — 2 wide cards
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Monthly Revenue --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Oylik tushum</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <div class="p-2.5 bg-green-100 dark:bg-green-900/30 rounded-xl">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($monthlyRevenue, 0, '.', ' ') }}
                <span class="text-base font-medium text-gray-400 dark:text-gray-500">so'm</span>
            </p>
            <div class="flex items-center gap-2 mt-3">
                @if ($revenueGrowth !== null)
                    @if ($revenueGrowth >= 0)
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            +{{ $revenueGrowth }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17H5m0 0v-8m0 8l8-8 4 4 6-6" />
                            </svg>
                            {{ $revenueGrowth }}%
                        </span>
                    @endif
                    <span class="text-xs text-gray-400 dark:text-gray-500">o'tgan oyga nisbatan</span>
                @else
                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">O'tgan oy ma'lumoti yo'q</span>
                @endif
            </div>
        </div>

        {{-- Pending Payments --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Kutilayotgan to'lovlar</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Hali qabul qilinmagan</p>
                </div>
                <div class="p-2.5 bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ number_format($pendingPaymentsAmount, 0, '.', ' ') }}
                <span class="text-base font-medium text-gray-400 dark:text-gray-500">so'm</span>
            </p>
            <div class="flex items-center gap-2 mt-3">
                @if ($pendingPaymentsCount > 0)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ $pendingPaymentsCount }} ta to'lov
                    </span>
                    <span class="text-xs text-gray-400 dark:text-gray-500">yechim kutmoqda</span>
                @else
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Barcha to'lovlar yopilgan
                    </span>
                @endif
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         TODAY'S LESSONS  +  RECENT PAYMENTS FEED
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Today's Lessons ─────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Bugungi darslar</h2>
                </div>
                <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-1 rounded-lg">
                    {{ $todaysLessons->count() }} ta
                </span>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                @forelse ($todaysLessons as $lesson)
                    <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="shrink-0 text-center bg-indigo-50 dark:bg-indigo-900/30 rounded-xl px-3 py-2 min-w-[64px]">
                                <p class="text-xs font-bold text-indigo-700 dark:text-indigo-300 tabular-nums">
                                    {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                                </p>
                                <p class="text-xs text-indigo-400 dark:text-indigo-500 tabular-nums">
                                    {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
                                </p>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $lesson->group->name ?? '—' }}
                                </p>
                                @if ($lesson->teacher?->user)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                        {{ $lesson->teacher->user->surname }} {{ $lesson->teacher->user->name }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="shrink-0">
                            @if ($lesson->status === 'completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Tugadi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Rejalashtirilgan
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="inline-flex p-4 bg-gray-50 dark:bg-gray-700/40 rounded-full mb-3">
                            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400 dark:text-gray-500">Bugun dars yo'q</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Payments Feed ────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-xl">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Oxirgi to'lovlar</h2>
                </div>
                <span class="text-xs text-gray-400 dark:text-gray-500">So'nggi 5 ta</span>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                @forelse ($recentPayments as $payment)
                    <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                @if ($payment->payable && $payment->payable->user)
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $payment->payable->user->surname }} {{ $payment->payable->user->name }}
                                    </p>
                                @else
                                    <p class="text-sm font-semibold text-gray-400 dark:text-gray-500 italic truncate">Noma'lum</p>
                                @endif
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ $payment->paid_at?->diffForHumans() ?? '—' }}
                                </p>
                            </div>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">
                                +{{ number_format($payment->net_amount, 0, '.', ' ') }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">so'm</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="inline-flex p-4 bg-gray-50 dark:bg-gray-700/40 rounded-full mb-3">
                            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400 dark:text-gray-500">Hozircha to'lovlar yo'q</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         ACTIVE GROUPS TABLE
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800/50 dark:to-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Faol Guruhlar</h2>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
                {{ $groups->total() }} ta faol guruh &middot; Davomat:
                <span class="font-semibold
                    {{ $attendanceStats['rate'] >= 80 ? 'text-emerald-500' : ($attendanceStats['rate'] >= 60 ? 'text-amber-500' : 'text-red-500') }}">
                    {{ $attendanceStats['rate'] }}%
                </span>
                ({{ now()->format('F') }})
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Guruh / Kurs</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">O'qituvchi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Dars vaqti</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">O'quvchilar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Holat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($groups as $group)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors group/row">

                            {{-- Group / Course --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md group-hover/row:scale-105 transition-transform shrink-0">
                                        {{ mb_strtoupper(mb_substr($group->course->title ?? 'N/A', 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $group->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 truncate mt-0.5">{{ $group->course->title ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Primary Teacher (no @php) --}}
                            <td class="px-6 py-5">
                                @foreach ([$group->teachers->firstWhere('pivot.is_primary', true) ?? $group->teachers->first()] as $primaryTeacher)
                                    @if ($primaryTeacher && $primaryTeacher->user)
                                        <div class="flex items-center gap-2.5">
                                            <img class="w-8 h-8 rounded-lg object-cover ring-2 ring-gray-200 dark:ring-gray-600 shrink-0"
                                                src="{{ $primaryTeacher->user->photo ? asset('storage/' . $primaryTeacher->user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($primaryTeacher->user->surname . '+' . $primaryTeacher->user->name) . '&background=6366f1&color=fff&size=64' }}"
                                                alt="{{ $primaryTeacher->user->name }}">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $primaryTeacher->user->surname }} {{ $primaryTeacher->user->name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm italic text-gray-400 dark:text-gray-500">Belgilanmagan</span>
                                    @endif
                                @endforeach
                            </td>

                            {{-- Schedule --}}
                            <td class="px-6 py-5">
                                @if ($group->start_time && $group->end_time)
                                    <div class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="tabular-nums">
                                            {{ \Carbon\Carbon::parse($group->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($group->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>

                            {{-- Students / Capacity --}}
                            <td class="px-6 py-5">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white tabular-nums">
                                            {{ $group->active_students_count }} / {{ $group->capacity }}
                                        </span>
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 tabular-nums">
                                            {{ $group->capacity > 0 ? min(100, round(($group->active_students_count / $group->capacity) * 100)) : 0 }}%
                                        </span>
                                    </div>
                                    <div class="h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all
                                            {{ ($group->capacity > 0 ? min(100, round(($group->active_students_count / $group->capacity) * 100)) : 0) >= 90 ? 'bg-gradient-to-r from-emerald-500 to-emerald-600' : (($group->capacity > 0 ? min(100, round(($group->active_students_count / $group->capacity) * 100)) : 0) >= 60 ? 'bg-gradient-to-r from-indigo-500 to-indigo-600' : 'bg-gradient-to-r from-amber-400 to-amber-500') }}"
                                            style="width: {{ $group->capacity > 0 ? min(100, round(($group->active_students_count / $group->capacity) * 100)) : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Status badge --}}
                            <td class="px-6 py-5">
                                @if (($group->capacity > 0 ? min(100, round(($group->active_students_count / $group->capacity) * 100)) : 0) >= 100)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        To'lgan
                                    </span>
                                @elseif (($group->capacity > 0 ? min(100, round(($group->active_students_count / $group->capacity) * 100)) : 0) >= 75)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        To'lmoqda
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Faol
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="inline-flex p-4 bg-gray-50 dark:bg-gray-700/40 rounded-full mb-4">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-500 dark:text-gray-400">Faol guruhlar topilmadi</h3>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ───────────────────────────────────── --}}
        @if ($groups->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $groups->firstItem() }}–{{ $groups->lastItem() }}</span>
                    dan
                    <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $groups->total() }}</span>
                    ta guruh
                </span>
                <div class="flex items-center gap-2">
                    {{-- Previous --}}
                    @if ($groups->onFirstPage())
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed select-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Oldingi
                        </span>
                    @else
                        <a href="{{ $groups->previousPageUrl() }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Oldingi
                        </a>
                    @endif

                    {{-- Page numbers (±2 around current) --}}
                    @foreach ($groups->getUrlRange(max(1, $groups->currentPage() - 2), min($groups->lastPage(), $groups->currentPage() + 2)) as $page => $url)
                        @if ($page === $groups->currentPage())
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($groups->hasMorePages())
                        <a href="{{ $groups->nextPageUrl() }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                            Keyingi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed select-none">
                            Keyingi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        @endif

    </div>

</div>
@endsection