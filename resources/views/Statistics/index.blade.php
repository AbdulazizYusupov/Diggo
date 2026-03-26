@extends('Layout.app')
@section('title', 'Statistika')

@push('styles')
    <style>
        .stat-card {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .chart-card {
            transition: box-shadow .18s ease;
        }

        .chart-card:hover {
            box-shadow: 0 20px 60px -12px rgba(0, 0, 0, .15);
        }

        .type-btn {
            transition: all .15s ease;
        }

        .type-btn.active {
            background: #6366f1;
            color: #fff;
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, .4);
        }

        .dark .type-btn.active {
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, .3);
        }

        .chart-wrap {
            position: relative;
        }

        .chart-wrap canvas {
            display: block;
        }

        /* Fade-in for chart panels */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp .4s ease both;
        }
    </style>
@endpush

@section('content')

    {{-- ════════════════════════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <span class="text-4xl">📊</span>
                Statistika va Tahlil
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm flex items-center gap-1.5">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $kpis['period_label'] }} davri tahlili
            </p>
        </div>

        {{-- Period badge --}}
        <div
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/40 text-indigo-700 dark:text-indigo-300 text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            So'nggi 6 oy
        </div>
        <form method="POST" action="{{ route('statistics.refresh') }}" 
      onsubmit="const btn = this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerHTML='⏳ Yangilanmoqda...';">
    @csrf

    <button type="submit" 
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-150">
        
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        
        <span>Yangilash</span>
    </button>
</form>

    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
     KPI STRIP  — 6 summary cards
════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">

        {{-- Total revenue --}}
        <div
            class="stat-card col-span-2 md:col-span-1 xl:col-span-2 bg-gradient-to-br from-indigo-600 to-indigo-700 p-5 rounded-2xl shadow-lg text-white">
            <div class="flex items-center justify-between mb-3">
                <span class="text-indigo-200 text-xs font-semibold uppercase tracking-wider">Daromad (6 oy)</span>
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold leading-tight">
                {{ number_format($kpis['total_revenue'], 0, '.', ' ') }}
                <span class="text-base font-normal text-indigo-200">so'm</span>
            </div>
        </div>

        {{-- Total students --}}
        <div
            class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-5 rounded-2xl shadow-md">
            <div class="flex items-center justify-between mb-3">
                <span
                    class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">O'quvchilar</span>
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900 dark:text-white">
                {{ number_format($kpis['total_students']) }}
            </div>
        </div>

        {{-- Active groups --}}
        <div
            class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-5 rounded-2xl shadow-md">
            <div class="flex items-center justify-between mb-3">
                <span
                    class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Guruhlar</span>
                <div class="w-8 h-8 bg-violet-100 dark:bg-violet-900/40 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900 dark:text-white">
                {{ $kpis['active_groups'] }}
            </div>
        </div>

        {{-- Teachers --}}
        <div
            class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-5 rounded-2xl shadow-md">
            <div class="flex items-center justify-between mb-3">
                <span
                    class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">O'qituvchi</span>
                <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900 dark:text-white">
                {{ $kpis['total_teachers'] }}
            </div>
        </div>

        {{-- Lesson completion rate --}}
        <div
            class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-5 rounded-2xl shadow-md">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Darslar</span>
                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/40 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900 dark:text-white">
                {{ $kpis['completion_rate'] }}<span class="text-base font-normal text-gray-400">%</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">bajarilish</p>
        </div>

        {{-- Attendance rate --}}
        <div
            class="stat-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-5 rounded-2xl shadow-md">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Davomat</span>
                <div class="w-8 h-8 bg-rose-100 dark:bg-rose-900/40 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div
                class="text-2xl font-extrabold
            {{ $kpis['attendance_rate'] >= 75 ? 'text-emerald-600 dark:text-emerald-400' : ($kpis['attendance_rate'] >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                {{ $kpis['attendance_rate'] }}<span class="text-base font-normal text-gray-400">%</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">qatnashish</p>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
     ROW 1 — Revenue chart  (full width)
════════════════════════════════════════════════════════════════════════ --}}
    <div
        class="chart-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6 fade-up">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Oylik Daromad Dinamikasi
                </h2>
                <p class="text-sm text-gray-400 mt-0.5">So'nggi 6 oy bo'yicha to'langan summalar</p>
            </div>
            <div class="flex items-center gap-1.5 flex-shrink-0" id="revenue-type-btns">
                <button onclick="switchType('revenue','bar')"
                    class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Bar</button>
                <button onclick="switchType('revenue','line')"
                    class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Line</button>
            </div>
        </div>
        <div class="chart-wrap" style="height:280px">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
     ROW 2 — Student growth (left) + Course popularity (right)
════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

        {{-- Student Growth — 3/5 --}}
        <div
            class="chart-card lg:col-span-3 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        O'quvchilar O'sishi
                    </h2>
                    <p class="text-sm text-gray-400 mt-0.5">Oylik yangi ro'yxatdan o'tish</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0" id="growth-type-btns">
                    <button onclick="switchType('growth','line')"
                        class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Line</button>
                    <button onclick="switchType('growth','bar')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Bar</button>
                </div>
            </div>
            <div class="chart-wrap" style="height:240px">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        {{-- Course Popularity — 2/5 --}}
        <div
            class="chart-card lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Kurs Mashhurligi
                    </h2>
                    <p class="text-sm text-gray-400 mt-0.5">Faol o'quvchilar soni</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0" id="course-type-btns">
                    <button onclick="switchType('course','doughnut')"
                        class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Donut</button>
                    <button onclick="switchType('course','bar')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Bar</button>
                </div>
            </div>
            <div class="chart-wrap" style="height:240px">
                <canvas id="courseChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
     ROW 3 — Group occupancy (full width horizontal bar)
════════════════════════════════════════════════════════════════════════ --}}
    <div
        class="chart-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6 fade-up">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Guruh Bandligi
                </h2>
                <p class="text-sm text-gray-400 mt-0.5">Faol o'quvchilar vs sig'im (top 12 guruh)</p>
            </div>
            <div class="flex items-center gap-1.5 flex-shrink-0" id="occupancy-type-btns">
                <button onclick="switchType('occupancy','bar')"
                    class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Bar</button>
                <button onclick="switchType('occupancy','line')"
                    class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Line</button>
            </div>
        </div>
        <div class="chart-wrap" style="height:300px">
            <canvas id="occupancyChart"></canvas>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
     ROW 4 — Attendance donut (left) + Attendance trend (right)
════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

        {{-- Donut — 2/5 --}}
        <div
            class="chart-card lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Davomat Taqsimoti
                    </h2>
                    <p class="text-sm text-gray-400 mt-0.5">6 oy jami</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0" id="attDonut-type-btns">
                    <button onclick="switchType('attDonut','doughnut')"
                        class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Donut</button>
                    <button onclick="switchType('attDonut','pie')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Pie</button>
                </div>
            </div>
            <div class="chart-wrap" style="height:240px">
                <canvas id="attDonutChart"></canvas>
            </div>
        </div>

        {{-- Trend line — 3/5 --}}
        <div
            class="chart-card lg:col-span-3 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Davomat Trendi
                    </h2>
                    <p class="text-sm text-gray-400 mt-0.5">Keldi vs Kelmadi oylik dinamika</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0" id="attTrend-type-btns">
                    <button onclick="switchType('attTrend','line')"
                        class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Line</button>
                    <button onclick="switchType('attTrend','bar')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Bar</button>
                </div>
            </div>
            <div class="chart-wrap" style="height:240px">
                <canvas id="attTrendChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
     ROW 5 — Payment status (left) + Payment type (center) + Teacher activity (right)
════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">

        {{-- Payment status --}}
        <div
            class="chart-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        To'lov Holatlari
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">Soni bo'yicha taqsimot</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0" id="payStatus-type-btns">
                    <button onclick="switchType('payStatus','doughnut')"
                        class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Donut</button>
                    <button onclick="switchType('payStatus','pie')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Pie</button>
                    <button onclick="switchType('payStatus','bar')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Bar</button>
                </div>
            </div>
            <div class="chart-wrap" style="height:220px">
                <canvas id="payStatusChart"></canvas>
            </div>
        </div>

        {{-- Payment type --}}
        <div
            class="chart-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        To'lov Usullari
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">To'langan to'lovlar bo'yicha</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0" id="payType-type-btns">
                    <button onclick="switchType('payType','bar')"
                        class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Bar</button>
                    <button onclick="switchType('payType','doughnut')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Donut</button>
                    <button onclick="switchType('payType','pie')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Pie</button>
                </div>
            </div>
            <div class="chart-wrap" style="height:220px">
                <canvas id="payTypeChart"></canvas>
            </div>
        </div>

        {{-- Teacher activity --}}
        <div
            class="chart-card md:col-span-2 xl:col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 fade-up">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        O'qituvchi Faolligi
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">Bajarilgan darslar soni (top 10)</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0" id="teacher-type-btns">
                    <button onclick="switchType('teacher','bar')"
                        class="type-btn active text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Bar</button>
                    <button onclick="switchType('teacher','doughnut')"
                        class="type-btn text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300">Donut</button>
                </div>
            </div>
            <div class="chart-wrap" style="height:220px">
                <canvas id="teacherChart"></canvas>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
     JAVASCRIPT  — Chart.js initialisation + dynamic type switching
════════════════════════════════════════════════════════════════════════ --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            // ─── Raw data from PHP ─────────────────────────────────────────────────────
            const DATA = {
                revenue: {
                    labels: @json($revenueChart['labels']),
                    data: @json($revenueChart['data']),
                },
                growth: {
                    labels: @json($studentGrowthChart['labels']),
                    data: @json($studentGrowthChart['data']),
                },
                occupancy: {
                    labels: @json($occupancyChart['labels']),
                    enrolled: @json($occupancyChart['enrolled']),
                    capacity: @json($occupancyChart['capacity']),
                },
                attDonut: {
                    labels: @json($attendanceChart['donut']['labels']),
                    data: @json($attendanceChart['donut']['data']),
                },
                attTrend: {
                    labels: @json($attendanceChart['trend']['labels']),
                    attended: @json($attendanceChart['trend']['attended']),
                    absent: @json($attendanceChart['trend']['absent']),
                },
                payStatus: {
                    labels: @json($paymentStatusChart['labels']),
                    counts: @json($paymentStatusChart['counts']),
                },
                payType: {
                    labels: @json($paymentTypeChart['labels']),
                    amounts: @json($paymentTypeChart['amounts']),
                },
                teacher: {
                    labels: @json($teacherActivityChart['labels']),
                    data: @json($teacherActivityChart['data']),
                },
                course: {
                    labels: @json($coursePopularityChart['labels']),
                    data: @json($coursePopularityChart['data']),
                },
            };

            // ─── Palette ───────────────────────────────────────────────────────────────
            const PALETTE = {
                indigo: {
                    solid: 'rgba(99,102,241,1)',
                    ghost: 'rgba(99,102,241,.15)'
                },
                blue: {
                    solid: 'rgba(59,130,246,1)',
                    ghost: 'rgba(59,130,246,.15)'
                },
                emerald: {
                    solid: 'rgba(16,185,129,1)',
                    ghost: 'rgba(16,185,129,.15)'
                },
                amber: {
                    solid: 'rgba(245,158,11,1)',
                    ghost: 'rgba(245,158,11,.15)'
                },
                rose: {
                    solid: 'rgba(244,63,94,1)',
                    ghost: 'rgba(244,63,94,.15)'
                },
                violet: {
                    solid: 'rgba(139,92,246,1)',
                    ghost: 'rgba(139,92,246,.15)'
                },
                orange: {
                    solid: 'rgba(249,115,22,1)',
                    ghost: 'rgba(249,115,22,.15)'
                },
                cyan: {
                    solid: 'rgba(6,182,212,1)',
                    ghost: 'rgba(6,182,212,.15)'
                },
                pink: {
                    solid: 'rgba(236,72,153,1)',
                    ghost: 'rgba(236,72,153,.15)'
                },
                teal: {
                    solid: 'rgba(20,184,166,1)',
                    ghost: 'rgba(20,184,166,.15)'
                },
            };

            const MULTI = Object.values(PALETTE).map(p => p.solid);
            const MULTI_GHOST = Object.values(PALETTE).map(p => p.ghost);

            // Detect dark mode for grid/axis colours
            function isDark() {
                return document.documentElement.classList.contains('dark');
            }

            function gridColor() {
                return isDark() ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.06)';
            }

            function labelColor() {
                return isDark() ? '#9ca3af' : '#6b7280';
            }

            // ─── Base plugin defaults ──────────────────────────────────────────────────
            Chart.defaults.font.family = "'system-ui', sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.plugins.legend.labels.boxWidth = 12;
            Chart.defaults.plugins.legend.labels.padding = 14;

            // ─── Shared options factories ──────────────────────────────────────────────
            function cartesianOpts(yFormatter) {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: labelColor()
                            },
                        },
                        tooltip: {
                            backgroundColor: isDark() ? '#1f2937' : '#fff',
                            titleColor: isDark() ? '#f3f4f6' : '#111827',
                            bodyColor: isDark() ? '#d1d5db' : '#374151',
                            borderColor: isDark() ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            callbacks: yFormatter ? {
                                label: yFormatter
                            } : {},
                        },
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: labelColor()
                            },
                            grid: {
                                color: gridColor()
                            }
                        },
                        y: {
                            ticks: {
                                color: labelColor()
                            },
                            grid: {
                                color: gridColor()
                            }
                        },
                    },
                };
            }

            function polarOpts() {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: labelColor(),
                                boxWidth: 12,
                                padding: 10
                            },
                        },
                        tooltip: {
                            backgroundColor: isDark() ? '#1f2937' : '#fff',
                            titleColor: isDark() ? '#f3f4f6' : '#111827',
                            bodyColor: isDark() ? '#d1d5db' : '#374151',
                            borderColor: isDark() ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                        },
                    },
                };
            }

            // Money formatter for tooltips
            function moneyTooltip(ctx) {
                const val = ctx.parsed.y ?? ctx.parsed;
                return ' ' + Number(val).toLocaleString('uz-UZ') + ' so\'m';
            }

            // ─── Chart registry ────────────────────────────────────────────────────────
            const CHARTS = {};

            // ─── 1. Revenue chart ──────────────────────────────────────────────────────
            CHARTS.revenue = new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: DATA.revenue.labels,
                    datasets: [{
                        label: 'Daromad (so\'m)',
                        data: DATA.revenue.data,
                        backgroundColor: PALETTE.indigo.ghost,
                        borderColor: PALETTE.indigo.solid,
                        borderWidth: 2,
                        borderRadius: 6,
                        fill: true,
                    }],
                },
                options: cartesianOpts(moneyTooltip),
            });

            // ─── 2. Student growth chart ──────────────────────────────────────────────
            CHARTS.growth = new Chart(document.getElementById('growthChart'), {
                type: 'line',
                data: {
                    labels: DATA.growth.labels,
                    datasets: [{
                        label: 'Yangi o\'quvchilar',
                        data: DATA.growth.data,
                        borderColor: PALETTE.blue.solid,
                        backgroundColor: PALETTE.blue.ghost,
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: PALETTE.blue.solid,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }],
                },
                options: cartesianOpts(),
            });

            // ─── 3. Group occupancy chart ─────────────────────────────────────────────
            CHARTS.occupancy = new Chart(document.getElementById('occupancyChart'), {
                type: 'bar',
                data: {
                    labels: DATA.occupancy.labels,
                    datasets: [{
                            label: 'Faol o\'quvchilar',
                            data: DATA.occupancy.enrolled,
                            backgroundColor: PALETTE.emerald.ghost,
                            borderColor: PALETTE.emerald.solid,
                            borderWidth: 2,
                            borderRadius: 4,
                        },
                        {
                            label: 'Sig\'im',
                            data: DATA.occupancy.capacity,
                            backgroundColor: PALETTE.rose.ghost,
                            borderColor: PALETTE.rose.solid,
                            borderWidth: 2,
                            borderRadius: 4,
                        },
                    ],
                },
                options: cartesianOpts(),
            });

            // ─── 4. Attendance donut ──────────────────────────────────────────────────
            CHARTS.attDonut = new Chart(document.getElementById('attDonutChart'), {
                type: 'doughnut',
                data: {
                    labels: DATA.attDonut.labels,
                    datasets: [{
                        data: DATA.attDonut.data,
                        backgroundColor: [PALETTE.emerald.solid, PALETTE.amber.solid, PALETTE.rose.solid,
                            PALETTE.cyan.solid
                        ],
                        borderWidth: 2,
                        hoverOffset: 8,
                    }],
                },
                options: {
                    ...polarOpts(),
                    cutout: '68%',
                },
            });

            // ─── 5. Attendance trend ──────────────────────────────────────────────────
            CHARTS.attTrend = new Chart(document.getElementById('attTrendChart'), {
                type: 'line',
                data: {
                    labels: DATA.attTrend.labels,
                    datasets: [{
                            label: 'Keldi',
                            data: DATA.attTrend.attended,
                            borderColor: PALETTE.emerald.solid,
                            backgroundColor: PALETTE.emerald.ghost,
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: PALETTE.emerald.solid,
                            pointRadius: 4,
                        },
                        {
                            label: 'Kelmadi',
                            data: DATA.attTrend.absent,
                            borderColor: PALETTE.rose.solid,
                            backgroundColor: PALETTE.rose.ghost,
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: PALETTE.rose.solid,
                            pointRadius: 4,
                        },
                    ],
                },
                options: cartesianOpts(),
            });

            // ─── 6. Payment status chart ──────────────────────────────────────────────
            CHARTS.payStatus = new Chart(document.getElementById('payStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: DATA.payStatus.labels,
                    datasets: [{
                        data: DATA.payStatus.counts,
                        backgroundColor: [PALETTE.emerald.solid, PALETTE.amber.solid, PALETTE.rose.solid,
                            PALETTE.violet.solid
                        ],
                        borderWidth: 2,
                        hoverOffset: 8,
                    }],
                },
                options: {
                    ...polarOpts(),
                    cutout: '68%',
                },
            });

            // ─── 7. Payment type chart ────────────────────────────────────────────────
            CHARTS.payType = new Chart(document.getElementById('payTypeChart'), {
                type: 'bar',
                data: {
                    labels: DATA.payType.labels,
                    datasets: [{
                        label: 'Summa (so\'m)',
                        data: DATA.payType.amounts,
                        backgroundColor: [PALETTE.blue.ghost, PALETTE.violet.ghost, PALETTE.teal.ghost, PALETTE
                            .orange.ghost
                        ],
                        borderColor: [PALETTE.blue.solid, PALETTE.violet.solid, PALETTE.teal.solid, PALETTE
                            .orange.solid
                        ],
                        borderWidth: 2,
                        borderRadius: 6,
                    }],
                },
                options: cartesianOpts(moneyTooltip),
            });

            // ─── 8. Teacher activity chart ────────────────────────────────────────────
            CHARTS.teacher = new Chart(document.getElementById('teacherChart'), {
                type: 'bar',
                data: {
                    labels: DATA.teacher.labels,
                    datasets: [{
                        label: 'Bajarilgan darslar',
                        data: DATA.teacher.data,
                        backgroundColor: DATA.teacher.data.map((_, i) => Object.values(PALETTE)[i % 10].ghost),
                        borderColor: DATA.teacher.data.map((_, i) => Object.values(PALETTE)[i % 10].solid),
                        borderWidth: 2,
                        borderRadius: 5,
                    }],
                },
                options: {
                    ...cartesianOpts(),
                    indexAxis: 'y', // horizontal bar
                },
            });

            // ─── 9. Course popularity chart ───────────────────────────────────────────
            CHARTS.course = new Chart(document.getElementById('courseChart'), {
                type: 'doughnut',
                data: {
                    labels: DATA.course.labels,
                    datasets: [{
                        data: DATA.course.data,
                        backgroundColor: MULTI.slice(0, DATA.course.labels.length),
                        borderWidth: 2,
                        hoverOffset: 8,
                    }],
                },
                options: {
                    ...polarOpts(),
                    cutout: '62%',
                },
            });

            // ═══════════════════════════════════════════════════════════════════════════
            // DYNAMIC TYPE SWITCHER
            // Each chart has a config blueprint. When the user clicks a type button we
            // destroy the old Chart instance and rebuild it with the new type, reusing
            // the same canvas element.
            // ═══════════════════════════════════════════════════════════════════════════

            const CHART_CONFIGS = {
                revenue: {
                    canvasId: 'revenueChart',
                    btnGroup: 'revenue-type-btns',
                    buildData: (type) => ({
                        labels: DATA.revenue.labels,
                        datasets: [{
                            label: 'Daromad (so\'m)',
                            data: DATA.revenue.data,
                            backgroundColor: isPolar(type) ? MULTI : PALETTE.indigo.ghost,
                            borderColor: isPolar(type) ? MULTI : PALETTE.indigo.solid,
                            borderWidth: 2,
                            borderRadius: isBar(type) ? 6 : 0,
                            tension: 0.4,
                            fill: type === 'line',
                            pointRadius: type === 'line' ? 4 : 0,
                            pointHoverRadius: type === 'line' ? 6 : 0,
                            pointBackgroundColor: PALETTE.indigo.solid,
                            hoverOffset: 8,
                        }],
                    }),
                    buildOpts: (type) => isPolar(type) ? {
                        ...polarOpts()
                    } : cartesianOpts(moneyTooltip),
                },
                growth: {
                    canvasId: 'growthChart',
                    btnGroup: 'growth-type-btns',
                    buildData: (type) => ({
                        labels: DATA.growth.labels,
                        datasets: [{
                            label: 'Yangi o\'quvchilar',
                            data: DATA.growth.data,
                            backgroundColor: isPolar(type) ? MULTI : PALETTE.blue.ghost,
                            borderColor: isPolar(type) ? MULTI : PALETTE.blue.solid,
                            borderWidth: 2.5,
                            tension: 0.4,
                            fill: type === 'line',
                            borderRadius: isBar(type) ? 6 : 0,
                            pointRadius: type === 'line' ? 4 : 0,
                            pointBackgroundColor: PALETTE.blue.solid,
                            hoverOffset: 8,
                        }],
                    }),
                    buildOpts: (type) => isPolar(type) ? {
                        ...polarOpts()
                    } : cartesianOpts(),
                },
                occupancy: {
                    canvasId: 'occupancyChart',
                    btnGroup: 'occupancy-type-btns',
                    buildData: (type) => ({
                        labels: DATA.occupancy.labels,
                        datasets: [{
                                label: 'Faol o\'quvchilar',
                                data: DATA.occupancy.enrolled,
                                backgroundColor: PALETTE.emerald.ghost,
                                borderColor: PALETTE.emerald.solid,
                                borderWidth: 2,
                                borderRadius: isBar(type) ? 4 : 0,
                                tension: 0.4,
                                fill: type === 'line',
                                pointRadius: type === 'line' ? 4 : 0,
                                pointBackgroundColor: PALETTE.emerald.solid,
                            },
                            {
                                label: 'Sig\'im',
                                data: DATA.occupancy.capacity,
                                backgroundColor: PALETTE.rose.ghost,
                                borderColor: PALETTE.rose.solid,
                                borderWidth: 2,
                                borderRadius: isBar(type) ? 4 : 0,
                                tension: 0.4,
                                fill: type === 'line',
                                pointRadius: type === 'line' ? 4 : 0,
                                pointBackgroundColor: PALETTE.rose.solid,
                            },
                        ],
                    }),
                    buildOpts: (type) => cartesianOpts(),
                },
                attDonut: {
                    canvasId: 'attDonutChart',
                    btnGroup: 'attDonut-type-btns',
                    buildData: (_) => ({
                        labels: DATA.attDonut.labels,
                        datasets: [{
                            data: DATA.attDonut.data,
                            backgroundColor: [PALETTE.emerald.solid, PALETTE.amber.solid, PALETTE.rose
                                .solid, PALETTE.cyan.solid
                            ],
                            borderWidth: 2,
                            hoverOffset: 8,
                        }],
                    }),
                    buildOpts: (type) => ({
                        ...polarOpts(),
                        cutout: type === 'doughnut' ? '68%' : 0
                    }),
                },
                attTrend: {
                    canvasId: 'attTrendChart',
                    btnGroup: 'attTrend-type-btns',
                    buildData: (type) => ({
                        labels: DATA.attTrend.labels,
                        datasets: [{
                                label: 'Keldi',
                                data: DATA.attTrend.attended,
                                borderColor: PALETTE.emerald.solid,
                                backgroundColor: PALETTE.emerald.ghost,
                                borderWidth: 2.5,
                                tension: 0.4,
                                fill: type === 'line',
                                borderRadius: isBar(type) ? 4 : 0,
                                pointRadius: type === 'line' ? 4 : 0,
                                pointBackgroundColor: PALETTE.emerald.solid,
                            },
                            {
                                label: 'Kelmadi',
                                data: DATA.attTrend.absent,
                                borderColor: PALETTE.rose.solid,
                                backgroundColor: PALETTE.rose.ghost,
                                borderWidth: 2.5,
                                tension: 0.4,
                                fill: type === 'line',
                                borderRadius: isBar(type) ? 4 : 0,
                                pointRadius: type === 'line' ? 4 : 0,
                                pointBackgroundColor: PALETTE.rose.solid,
                            },
                        ],
                    }),
                    buildOpts: (type) => cartesianOpts(),
                },
                payStatus: {
                    canvasId: 'payStatusChart',
                    btnGroup: 'payStatus-type-btns',
                    buildData: (type) => ({
                        labels: DATA.payStatus.labels,
                        datasets: [{
                            label: 'Soni',
                            data: DATA.payStatus.counts,
                            backgroundColor: isPolar(type) ? [PALETTE.emerald.solid, PALETTE.amber.solid,
                                PALETTE.rose.solid, PALETTE
                                .violet.solid
                            ] : [PALETTE.emerald.ghost, PALETTE.amber.ghost, PALETTE.rose.ghost, PALETTE
                                .violet.ghost
                            ],
                            borderColor: [PALETTE.emerald.solid, PALETTE.amber.solid, PALETTE.rose.solid,
                                PALETTE.violet.solid
                            ],
                            borderWidth: 2,
                            borderRadius: isBar(type) ? 6 : 0,
                            hoverOffset: 8,
                        }],
                    }),
                    buildOpts: (type) => isPolar(type) ? {
                        ...polarOpts(),
                        cutout: type === 'doughnut' ? '68%' : 0
                    } : cartesianOpts(),
                },
                payType: {
                    canvasId: 'payTypeChart',
                    btnGroup: 'payType-type-btns',
                    buildData: (type) => ({
                        labels: DATA.payType.labels,
                        datasets: [{
                            label: 'Summa (so\'m)',
                            data: DATA.payType.amounts,
                            backgroundColor: isPolar(type) ? [PALETTE.blue.solid, PALETTE.violet.solid,
                                PALETTE.teal.solid, PALETTE
                                .orange.solid
                            ] : [PALETTE.blue.ghost, PALETTE.violet.ghost, PALETTE.teal.ghost, PALETTE
                                .orange.ghost
                            ],
                            borderColor: [PALETTE.blue.solid, PALETTE.violet.solid, PALETTE.teal.solid,
                                PALETTE.orange.solid
                            ],
                            borderWidth: 2,
                            borderRadius: isBar(type) ? 6 : 0,
                            hoverOffset: 8,
                        }],
                    }),
                    buildOpts: (type) => isPolar(type) ? {
                        ...polarOpts(),
                        cutout: type === 'doughnut' ? '68%' : 0
                    } : cartesianOpts(moneyTooltip),
                },
                teacher: {
                    canvasId: 'teacherChart',
                    btnGroup: 'teacher-type-btns',
                    buildData: (type) => ({
                        labels: DATA.teacher.labels,
                        datasets: [{
                            label: 'Darslar',
                            data: DATA.teacher.data,
                            backgroundColor: isPolar(type) ?
                                MULTI.slice(0, DATA.teacher.labels.length) : DATA.teacher.data.map((_, i) =>
                                    Object.values(PALETTE)[i % 10].ghost),
                            borderColor: DATA.teacher.data.map((_, i) => Object.values(PALETTE)[i % 10]
                                .solid),
                            borderWidth: 2,
                            borderRadius: isBar(type) ? 5 : 0,
                            hoverOffset: 8,
                        }],
                    }),
                    buildOpts: (type) => {
                        if (isPolar(type)) return {
                            ...polarOpts(),
                            cutout: type === 'doughnut' ? '62%' : 0
                        };
                        const opts = cartesianOpts();
                        opts.indexAxis = 'y';
                        return opts;
                    },
                },
                course: {
                    canvasId: 'courseChart',
                    btnGroup: 'course-type-btns',
                    buildData: (type) => ({
                        labels: DATA.course.labels,
                        datasets: [{
                            label: 'O\'quvchilar',
                            data: DATA.course.data,
                            backgroundColor: isPolar(type) ?
                                MULTI.slice(0, DATA.course.labels.length) : MULTI_GHOST.slice(0, DATA.course
                                    .labels.length),
                            borderColor: MULTI.slice(0, DATA.course.labels.length),
                            borderWidth: 2,
                            borderRadius: isBar(type) ? 6 : 0,
                            hoverOffset: 8,
                        }],
                    }),
                    buildOpts: (type) => isPolar(type) ? {
                        ...polarOpts(),
                        cutout: type === 'doughnut' ? '62%' : 0
                    } : cartesianOpts(),
                },
            };

            // Helpers
            function isPolar(t) {
                return t === 'doughnut' || t === 'pie';
            }

            function isBar(t) {
                return t === 'bar';
            }

            /**
             * Destroy the existing Chart instance and rebuild it with the new type.
             * Updates the active state on the type-switcher buttons.
             */
            function switchType(chartKey, newType) {
                const cfg = CHART_CONFIGS[chartKey];
                const canvas = document.getElementById(cfg.canvasId);

                // Destroy old instance
                if (CHARTS[chartKey]) {
                    CHARTS[chartKey].destroy();
                    delete CHARTS[chartKey];
                }

                // Rebuild
                CHARTS[chartKey] = new Chart(canvas, {
                    type: newType,
                    data: cfg.buildData(newType),
                    options: cfg.buildOpts(newType),
                });

                // Update button active states
                const btnGroup = document.getElementById(cfg.btnGroup);
                if (btnGroup) {
                    btnGroup.querySelectorAll('.type-btn').forEach(btn => {
                        const btnType = btn.getAttribute('onclick').match(/'([^']+)'\)$/)[1];
                        btn.classList.toggle('active', btnType === newType);
                    });
                }
            }
        </script>
    @endpush

@endsection
