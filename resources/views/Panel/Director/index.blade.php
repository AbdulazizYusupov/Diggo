@extends('Layout.app')

@section('title', 'Director Kabineti')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl shadow-lg shadow-purple-500/30 border-b-2 border-purple-800/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Director Kabineti
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        {{ $director->school->name }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="px-4 py-2 bg-gradient-to-r from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-xl border border-emerald-300 dark:border-emerald-700/50">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">Komissiya:</span>
                        <span class="text-lg font-extrabold text-emerald-900 dark:text-emerald-50">
                            {{ $stats['commission_percentage'] }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Balance and Commission Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

            {{-- This Month Commission --}}
            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            Shu oy komissiyasi
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($stats['this_month_commission'], 0, '.', ' ') }}
                            <span class="text-lg">so'm</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-emerald-600 rounded-xl shadow-lg shadow-emerald-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pending Commission --}}
            <div
                class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">
                            Kutilayotgan
                        </p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ number_format($stats['pending_commission'], 0, '.', ' ') }}
                            <span class="text-lg">so'm</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-amber-500 rounded-xl shadow-lg shadow-amber-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Commission Earned --}}
            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">
                            Jami ishlab topilgan
                        </p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ number_format($stats['total_commission_earned'], 0, '.', ' ') }}
                            <span class="text-lg">so'm</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-blue-600 rounded-xl shadow-lg shadow-blue-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Students Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wider">
                            Jami o'quvchilar
                        </p>
                        <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-1">
                            {{ $stats['total_students'] }}
                        </p>
                    </div>
                    <div
                        class="p-3 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-100 to-green-200/60 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl p-6 border border-green-200 dark:border-green-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-green-700 dark:text-green-400 uppercase tracking-wider">
                            Faol o'quvchilar
                        </p>
                        <p class="text-3xl font-extrabold text-green-900 dark:text-green-50 mt-1">
                            {{ $stats['active_students'] }}
                        </p>
                    </div>
                    <div
                        class="p-3 bg-green-600 rounded-xl shadow-lg shadow-green-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-rose-100 to-rose-200/60 dark:from-rose-900/30 dark:to-rose-800/30 rounded-2xl p-6 border border-rose-200 dark:border-rose-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider">
                            Yangi o'quvchilar
                        </p>
                        <p class="text-3xl font-extrabold text-rose-900 dark:text-rose-50 mt-1">
                            {{ $stats['new_students_this_month'] }}
                        </p>
                        <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">Shu oyda</p>
                    </div>
                    <div
                        class="p-3 bg-rose-600 rounded-xl shadow-lg shadow-rose-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                </div>
            </div>

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
                        <p class="text-xs text-cyan-600 dark:text-cyan-400 mt-1">Shu oyda</p>
                    </div>
                    <div
                        class="p-3 bg-cyan-600 rounded-xl shadow-lg shadow-cyan-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts and Recent Data --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Monthly Commissions Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Oylik komissiya tarixi
                </h3>
                <div class="space-y-3">
                    @forelse($monthlyCommissions as $month)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $month->month_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $month->count }} ta to'lov</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($month->total, 0, '.', ' ') }} so'm
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Ma'lumot topilmadi</p>
                    @endforelse
                </div>
            </div>

            {{-- Top Groups --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Eng ko'p o'quvchili guruhlar
                </h3>
                <div class="space-y-3">
                    @forelse($topGroups as $group)
                        <div
                            class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl border border-purple-100 dark:border-purple-800/30">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-purple-600 rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $group->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $group->cours->name }}</p>
                                </div>
                            </div>
                            <div class="px-3 py-1.5 bg-purple-600 text-white font-bold rounded-lg shadow-sm">
                                {{ $group->student_count }}
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Guruhlar topilmadi</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Payments --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    So'nggi to'lovlar
                </h3>
                <a href="{{ route('director.commissions') }}"
                    class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-semibold text-sm flex items-center gap-1">
                    Barchasini ko'rish
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                O'quvchi</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Kurs</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                To'lov summasi</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Komissiya</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Sana</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentPayments as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($payment->student->user->name, 0, 1) }}{{ substr($payment->student->user->surname ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ $payment->student->user->name }} {{ $payment->student->user->surname }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $payment->student->user->phone }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-semibold rounded-lg">
                                        {{ $payment->cours->name }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ number_format($payment->amount, 0, '.', ' ') }} so'm
                                    </p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($payment->commission_amount, 0, '.', ' ') }} so'm
                                    </p>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $payment->payment_date->format('d.m.Y') }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">To'lovlar topilmadi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('director.students') }}"
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 transition-all">
                <div class="flex items-center gap-4">
                    <div
                        class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">O'quvchilar</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Barcha o'quvchilarni ko'rish</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('director.commissions') }}"
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-emerald-400 dark:hover:border-emerald-500 transition-all">
                <div class="flex items-center gap-4">
                    <div
                        class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Komissiyalar</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">To'lovlar va komissiyalar</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('schools.show', $director->school_id) }}"
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-purple-400 dark:hover:border-purple-500 transition-all">
                <div class="flex items-center gap-4">
                    <div
                        class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Maktab ma'lumotlari</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">To'liq ma'lumot</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
