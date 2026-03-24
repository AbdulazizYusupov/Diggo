@extends('Layout.app')

@section('title', 'Hisobotlar')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Hisobotlar va Analitika
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Komissiya tranzaksiyalari va statistika
                </p>
            </div>

            {{-- Export Button --}}
            <div class="flex items-center gap-3 shrink-0">
                <button type="button" onclick="openExportModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Excel yuklab olish
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('director.reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Oy --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Oy</label>
                    <select name="month"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @foreach($months as $key => $monthName)
                            <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Yil --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Yil</label>
                    <select name="year"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Ko'rsatish
                    </button>

                    <a href="{{ route('director.reports') }}"
                        class="group p-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg transition-all duration-300 flex items-center justify-center border border-gray-300 dark:border-gray-600"
                        style="height: 42px; width: 42px;" title="Asl holatga qaytarish">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 transition-transform duration-500 group-hover:rotate-180">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Oylik Komissiya --}}
            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase">Oylik Komissiya</p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($monthlyReport['total_revenue'], 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ $monthlyReport['tx_count'] }} ta tranzaksiya
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- O'rtacha Tranzaksiya --}}
            <div class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase">O'rtacha Tranzaksiya</p>
                        <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50 mt-1">
                            {{ number_format($monthlyReport['avg_commission'], 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            Har bir tranzaksiya bo'yicha
                        </p>
                    </div>
                    <div class="p-3 bg-purple-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Yangi O'quvchilar --}}
            <div class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase">Yangi O'quvchilar</p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ $monthlyReport['new_students'] }}
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Shu oyda</p>
                    </div>
                    <div class="p-3 bg-blue-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Yillik Jami --}}
            <div class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase">Yillik Jami</p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ number_format($yearlyReport['total_revenue'], 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                            {{ $yearlyReport['tx_count'] }} ta tranzaksiya
                        </p>
                    </div>
                    <div class="p-3 bg-amber-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Student Growth --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">O'quvchilar O'sishi</h3>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($studentGrowth as $monthNum => $data)
                    <div class="p-4 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/10 dark:to-blue-900/10 rounded-xl border border-indigo-200 dark:border-indigo-800/30">
                        <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-400">
                            {{ $months[$monthNum] }}
                        </p>
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600 dark:text-gray-400">Yangi:</span>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">+{{ $data['new'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600 dark:text-gray-400">Jami:</span>
                                <span class="text-sm font-bold text-indigo-900 dark:text-indigo-50">{{ $data['total'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Revenue Breakdown --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- By Course --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Kurslar Bo'yicha Komissiya</h3>

                @if($revenueBreakdown['by_course']->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400 dark:text-gray-500">
                        <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm">Tranzaksiyalar topilmadi</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($revenueBreakdown['by_course'] as $course)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/10 dark:to-indigo-900/10 rounded-xl">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $course->title }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $course->cnt }} ta tranzaksiya</p>
                                </div>
                                <p class="text-xl font-bold text-purple-600 dark:text-purple-400">
                                    {{ number_format($course->total, 0, '.', ' ') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Monthly Breakdown --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">
                    Oylar Bo'yicha Komissiya ({{ $year }})
                </h3>

                <div class="space-y-3">
                    @foreach($revenueBreakdown['monthly_breakdown'] as $item)
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/10 dark:to-teal-900/10 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['count'] }} ta tranzaksiya</p>
                            </div>
                            <p class="text-base font-bold {{ $item['total'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500' }}">
                                {{ $item['total'] > 0 ? number_format($item['total'], 0, '.', ' ') : '—' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">
                So'nggi Tranzaksiyalar —
                <span class="font-normal text-gray-500 dark:text-gray-400 text-base">
                    {{ $months[$month] }} {{ $year }}
                </span>
            </h3>

            @if($recentTransactions->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm">Ushbu oy uchun tranzaksiyalar mavjud emas</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left pb-3 font-semibold text-gray-500 dark:text-gray-400 pr-4">Sana</th>
                                <th class="text-left pb-3 font-semibold text-gray-500 dark:text-gray-400 pr-4">Kurs</th>
                                <th class="text-left pb-3 font-semibold text-gray-500 dark:text-gray-400 pr-4">Guruh</th>
                                <th class="text-right pb-3 font-semibold text-gray-500 dark:text-gray-400">Komissiya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @foreach($recentTransactions as $tx)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="py-3 pr-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d.m.Y') }}
                                    </td>
                                    <td class="py-3 pr-4 text-gray-900 dark:text-white font-medium">
                                        {{ $tx->source?->group?->course?->title ?? '—' }}
                                    </td>
                                    <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                        {{ $tx->source?->group?->name ?? '—' }}
                                    </td>
                                    <td class="py-3 text-right font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                        {{ number_format($tx->amount, 0, '.', ' ') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- Export Modal                                                           --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div id="exportModal"
        class="fixed inset-0 z-50 hidden items-center justify-center p-4"
        role="dialog" aria-modal="true" aria-labelledby="exportModalTitle">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeExportModal()"></div>

        {{-- Panel --}}
        <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 id="exportModalTitle" class="text-lg font-bold text-gray-900 dark:text-white">
                        Excel yuklab olish
                    </h2>
                </div>
                <button type="button" onclick="closeExportModal()"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Export Form --}}
            <form method="GET" action="{{ route('director.reports.export') }}" id="exportForm">

                <div class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto">

                    {{-- Date Filters --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Sana filtri</p>
                        <div class="grid grid-cols-2 gap-3">

                            {{-- Year --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Yil</label>
                                <select name="export_year"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                                    <option value="">Barcha yillar</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Month --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Oy</label>
                                <select name="export_month"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                                    <option value="">Barcha oylar</option>
                                    @foreach($months as $key => $monthName)
                                        <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $monthName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date From --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Boshlanish sanasi</label>
                                <input type="date" name="date_from"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" />
                            </div>

                            {{-- Date To --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Tugash sanasi</label>
                                <input type="date" name="date_to"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" />
                            </div>
                        </div>
                    </div>

                    {{-- Column Selector --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Ustunlar</p>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="toggleAllColumns(true)"
                                    class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-medium">
                                    Barchasini tanlash
                                </button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button type="button" onclick="toggleAllColumns(false)"
                                    class="text-xs text-gray-500 dark:text-gray-400 hover:underline font-medium">
                                    Bekor qilish
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($exportColumns as $key => $label)
                                <label class="export-col-label flex items-center gap-2.5 p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 cursor-pointer transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-900/20">
                                    <input type="checkbox" name="columns[]" value="{{ $key }}"
                                        checked
                                        class="export-col-checkbox w-4 h-4 rounded text-emerald-600 border-gray-300 dark:border-gray-500 focus:ring-emerald-500 bg-white dark:bg-gray-700 cursor-pointer" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300 leading-tight">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button type="button" onclick="closeExportModal()"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        Bekor qilish
                    </button>
                    <button type="submit" id="exportSubmitBtn"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span id="exportBtnText">Yuklab olish</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- Modal JS (no extra dependencies needed)                                --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <script>
        const exportModal = document.getElementById('exportModal');
        const exportForm  = document.getElementById('exportForm');

        function openExportModal() {
            exportModal.classList.remove('hidden');
            exportModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            // Reset submit button state on open
            resetExportBtn();
        }

        function closeExportModal() {
            exportModal.classList.add('hidden');
            exportModal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeExportModal();
        });

        // Prevent closing on modal panel click (only backdrop closes it)
        exportModal.querySelector('.relative.w-full').addEventListener('click', function (e) {
            e.stopPropagation();
        });

        function toggleAllColumns(check) {
            document.querySelectorAll('.export-col-checkbox').forEach(cb => {
                cb.checked = check;
            });
        }

        // Show loading state while downloading
        exportForm.addEventListener('submit', function (e) {
            const checked = document.querySelectorAll('.export-col-checkbox:checked');
            if (checked.length === 0) {
                e.preventDefault();
                alert('Kamida bitta ustunni tanlang.');
                return;
            }

            const btn  = document.getElementById('exportSubmitBtn');
            const text = document.getElementById('exportBtnText');
            btn.disabled = true;
            text.textContent = 'Yuklanmoqda...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            // Re-enable after download starts (browser triggers download, no redirect)
            setTimeout(function () {
                resetExportBtn();
                closeExportModal();
            }, 3500);
        });

        function resetExportBtn() {
            const btn  = document.getElementById('exportSubmitBtn');
            const text = document.getElementById('exportBtnText');
            btn.disabled = false;
            text.textContent = 'Yuklab olish';
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    </script>
@endsection