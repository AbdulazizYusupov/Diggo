@extends('Layout.app')

@section('title', 'Oylik Charge Boshqaruvi')

@section('content')
    <div class="space-y-6">

        {{-- ═══════════════════════════════════════════════
             HEADER
        ═══════════════════════════════════════════════ --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex p-3 bg-emerald-600 rounded-2xl shadow-lg shadow-emerald-500/30 border-b-2 border-emerald-800/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Oylik Charge Boshqaruvi
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Studentlar balansidan oylik dars haqi yechilishini boshqaring
                    </p>
                </div>
            </div>

            {{-- Manual Run Button --}}
            <button onclick="openChargeModal()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 sm:px-8 sm:py-3.5
                       bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl
                       shadow-lg shadow-emerald-100 dark:shadow-none
                       transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm sm:text-base tracking-wide whitespace-nowrap">Charge Ishlatish</span>
            </button>
        </div>

        {{-- ═══════════════════════════════════════════════
             FLASH MESSAGES
        ═══════════════════════════════════════════════ --}}
        @if (session('success'))
            <div class="flex items-start gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/40 rounded-2xl">
                <div class="p-1 bg-emerald-100 dark:bg-emerald-800/40 rounded-lg flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('warning'))
            <div class="flex items-start gap-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 rounded-2xl">
                <div class="p-1 bg-amber-100 dark:bg-amber-800/40 rounded-lg flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">{{ session('warning') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 rounded-2xl">
                <div class="p-1 bg-red-100 dark:bg-red-800/40 rounded-lg flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-red-800 dark:text-red-300">{{ session('error') }}</p>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════
             PREVIEW CARDS — O'TGAN OY STATISTIKASI
        ═══════════════════════════════════════════════ --}}
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 px-1">
                {{ now()->subMonth()->format('Y — F') }} oyi uchun preview
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Charge qilinadi --}}
                <div class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30
                            rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                                Charge qilinadi
                            </p>
                            <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                                {{ $preview['toCharge'] }}
                            </p>
                            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-1">
                                {{ number_format($preview['totalAmount'], 0, '.', ' ') }} so'm
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-500 rounded-xl shadow-lg shadow-emerald-300/50 dark:shadow-none transition-transform hover:scale-110">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- To'lov yo'q --}}
                <div class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30
                            rounded-2xl p-6 border border-red-200 dark:border-red-700/50 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-red-700 dark:text-red-400 uppercase tracking-wider">
                                To'lov yo'q
                            </p>
                            <p class="text-3xl font-extrabold text-red-900 dark:text-red-50 mt-1">
                                {{ $preview['noPayment'] }}
                            </p>
                            <p class="text-xs font-semibold text-red-500 dark:text-red-400 mt-1">
                                charge qilinmaydi
                            </p>
                        </div>
                        <div class="p-3 bg-red-500 rounded-xl shadow-lg shadow-red-300/50 dark:shadow-none transition-transform hover:scale-110">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Allaqachon qilingan --}}
                <div class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30
                            rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">
                                Allaqachon qilingan
                            </p>
                            <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                                {{ $preview['alreadyDone'] }}
                            </p>
                            <p class="text-xs font-semibold text-blue-500 dark:text-blue-400 mt-1">
                                o'tkazib yuboriladi
                            </p>
                        </div>
                        <div class="p-3 bg-blue-500 rounded-xl shadow-lg shadow-blue-300/50 dark:shadow-none transition-transform hover:scale-110">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Jami summa --}}
                <div class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30
                            rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50 shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">
                                Jami summa
                            </p>
                            <p class="text-2xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                                {{ number_format($preview['totalAmount'] / 1000000, 2) }}M
                            </p>
                            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 mt-1">
                                so'm yechiladi
                            </p>
                        </div>
                        <div class="p-3 bg-amber-500 rounded-xl shadow-lg shadow-amber-300/50 dark:shadow-none transition-transform hover:scale-110">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
             CHARGE TARIXI
        ═══════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Table Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-xl">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Charge Tarixi</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Oxirgi 10 ta charge</p>
                    </div>
                </div>

                {{-- Cron status badge --}}
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20
                            border border-emerald-200 dark:border-emerald-800/40 rounded-xl">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">
                        Cron: Har oy 1-sanasi 06:00
                    </span>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Oy
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Natija
                            </th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Charged
                            </th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Skipped
                            </th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                To'lovsiz
                            </th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Xatolik
                            </th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jami summa
                            </th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kim / Qachon
                            </th>
                            <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Amallar
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">

                                {{-- Oy --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="p-1.5 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white">
                                                {{ $log->charge_month->format('F Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $log->charge_month->format('Y-m-d') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status badge --}}
                                <td class="px-6 py-4">
                                    @if ($log->error_count > 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1
                                                     bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400
                                                     text-xs font-bold rounded-lg border border-red-200/50 dark:border-red-800/50">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 9v2m0 4h.01" />
                                            </svg>
                                            Xatolik bor
                                        </span>
                                    @elseif ($log->no_payment_count > 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1
                                                     bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400
                                                     text-xs font-bold rounded-lg border border-amber-200/50 dark:border-amber-800/50">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Qisman
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1
                                                     bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400
                                                     text-xs font-bold rounded-lg border border-emerald-200/50 dark:border-emerald-800/50">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            Muvaffaqiyatli
                                        </span>
                                    @endif
                                </td>

                                {{-- Charged count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                 bg-emerald-50 dark:bg-emerald-900/20
                                                 text-emerald-700 dark:text-emerald-400 font-extrabold text-sm">
                                        {{ $log->charged_count }}
                                    </span>
                                </td>

                                {{-- Skipped count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                 bg-blue-50 dark:bg-blue-900/20
                                                 text-blue-700 dark:text-blue-400 font-extrabold text-sm">
                                        {{ $log->skipped_count }}
                                    </span>
                                </td>

                                {{-- No payment count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                 bg-amber-50 dark:bg-amber-900/20
                                                 text-amber-700 dark:text-amber-400 font-extrabold text-sm">
                                        {{ $log->no_payment_count }}
                                    </span>
                                </td>

                                {{-- Error count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                                 {{ $log->error_count > 0
                                                    ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400'
                                                    : 'bg-gray-50 dark:bg-gray-700 text-gray-400 dark:text-gray-500' }}
                                                 font-extrabold text-sm">
                                        {{ $log->error_count }}
                                    </span>
                                </td>

                                {{-- Total amount --}}
                                <td class="px-6 py-4 text-right">
                                    <span class="font-extrabold text-gray-900 dark:text-white">
                                        {{ number_format($log->total_amount, 0, '.', ' ') }}
                                    </span>
                                    <span class="text-xs text-gray-400 ml-1">so'm</span>
                                </td>

                                {{-- Who / When --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                                            @if ($log->createdBy)
                                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                                    {{ substr($log->createdBy->name ?? 'S', 0, 1) }}
                                                </span>
                                            @else
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                {{ $log->createdBy?->name ?? 'Cron (Avtomatik)' }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $log->run_at?->format('d.m.Y H:i') ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Amallar --}}
                                <td class="px-6 py-4 text-center">
                                    <button
                                        onclick="openDetailsModal({{ $log->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5
                                               bg-indigo-50 hover:bg-indigo-100
                                               dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20
                                               border border-indigo-200 dark:border-indigo-500/30
                                               text-indigo-700 dark:text-indigo-300
                                               text-xs font-semibold rounded-xl transition-all active:scale-95">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Batafsil
                                    </button>
                                </td>
                            </tr>

                            {{-- Hidden details row (expandable) --}}
                            <tr id="details-{{ $log->id }}" class="hidden bg-gray-50/80 dark:bg-gray-700/20">
                                <td colspan="9" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        @if (!empty($log->details['charged']))
                                            <div>
                                                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider mb-2">
                                                    ✅ Charge qilinganlar ({{ count($log->details['charged']) }})
                                                </p>
                                                <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                                                    @foreach ($log->details['charged'] as $item)
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 bg-emerald-50 dark:bg-emerald-900/10 px-2 py-1 rounded-lg">
                                                            {{ $item }}
                                                        </p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if (!empty($log->details['no_payment']))
                                            <div>
                                                <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider mb-2">
                                                    ⚠️ To'lovsizlar ({{ count($log->details['no_payment']) }})
                                                </p>
                                                <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                                                    @foreach ($log->details['no_payment'] as $item)
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 bg-amber-50 dark:bg-amber-900/10 px-2 py-1 rounded-lg">
                                                            {{ $item }}
                                                        </p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if (!empty($log->details['errors']))
                                            <div>
                                                <p class="text-xs font-bold text-red-700 dark:text-red-400 uppercase tracking-wider mb-2">
                                                    ❌ Xatoliklar ({{ count($log->details['errors']) }})
                                                </p>
                                                <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                                                    @foreach ($log->details['errors'] as $item)
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 bg-red-50 dark:bg-red-900/10 px-2 py-1 rounded-lg">
                                                            {{ $item }}
                                                        </p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="flex flex-col items-center justify-center py-16 px-4">
                                        <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                            Hali charge qilinmagan
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-xs">
                                            Birinchi charge qilish uchun yuqoridagi "Charge Ishlatish" tugmasini bosing
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         CHARGE MODAL — Oy tanlash va ishlatish
    ═══════════════════════════════════════════════════════════════════ --}}
    <div id="chargeModal"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">

            {{-- Modal header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Oylik Charge Ishlatish
                    </h3>
                </div>
                <button onclick="closeChargeModal()"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal body --}}
            <form action="{{ route('admin.monthly-charge.run') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Oy tanlash --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 px-1">
                        Qaysi oy uchun? <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="month"
                        name="month"
                        required
                        max="{{ now()->subMonth()->format('Y-m') }}"
                        value="{{ now()->subMonth()->format('Y-m') }}"
                        class="w-full px-4 py-2.5 bg-white dark:bg-slate-900
                               border border-gray-300 dark:border-indigo-500/30
                               text-gray-900 dark:text-gray-100
                               rounded-xl outline-none transition-all duration-200
                               focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500
                               dark:focus:ring-emerald-500/20 dark:focus:border-emerald-400"
                    />
                    <p class="text-xs text-gray-400 mt-1.5 px-1">
                        Kelajak oy tanlab bo'lmaydi. Standart: o'tgan oy.
                    </p>
                </div>

                {{-- Force mode --}}
                <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center h-5 mt-0.5">
                        <input
                            id="force"
                            name="force"
                            type="checkbox"
                            value="1"
                            class="w-4 h-4 text-emerald-600 bg-white dark:bg-gray-900
                                   border-gray-300 dark:border-gray-600 rounded
                                   focus:ring-emerald-500 focus:ring-2 cursor-pointer"
                        />
                    </div>
                    <div>
                        <label for="force" class="text-sm font-semibold text-gray-700 dark:text-gray-200 cursor-pointer">
                            Force mode (qayta charge qilish)
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Allaqachon charge qilingan bo'lsa ham qaytadan ishlatadi
                        </p>
                    </div>
                </div>

                {{-- Warning --}}
                <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800/30">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-800 dark:text-amber-400">Diqqat!</p>
                            <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                                Bu amal tanlangan oy uchun to'lov qilgan barcha studentlar balansidan dars haqi yechadi.
                                <strong>Qaytarib bo'lmaydi.</strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Preview info --}}
                <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-200 dark:border-indigo-800/30">
                    <p class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wider mb-2">
                        O'tgan oy preview
                    </p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                            <span class="text-xs text-gray-600 dark:text-gray-300">
                                Charge qilinadi: <strong class="text-gray-900 dark:text-white">{{ $preview['toCharge'] }}</strong>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                            <span class="text-xs text-gray-600 dark:text-gray-300">
                                To'lovsiz: <strong class="text-gray-900 dark:text-white">{{ $preview['noPayment'] }}</strong>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                            <span class="text-xs text-gray-600 dark:text-gray-300">
                                Allaqachon: <strong class="text-gray-900 dark:text-white">{{ $preview['alreadyDone'] }}</strong>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></span>
                            <span class="text-xs text-gray-600 dark:text-gray-300">
                                Summa: <strong class="text-gray-900 dark:text-white">{{ number_format($preview['totalAmount'], 0, '.', ' ') }}</strong>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeChargeModal()"
                        class="group flex items-center gap-2 px-6 py-2.5
                               bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700
                               border border-slate-200 dark:border-slate-700
                               text-slate-700 dark:text-slate-300
                               font-semibold rounded-xl transition-all duration-300 active:scale-95">
                        <svg class="w-5 h-5 transition-transform duration-500 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Bekor qilish
                    </button>

                    <button type="submit"
                        class="group flex items-center gap-2 px-6 py-2.5
                               bg-emerald-600 hover:bg-emerald-700
                               dark:bg-emerald-500 dark:hover:bg-emerald-600
                               text-white font-semibold rounded-xl
                               shadow-md shadow-emerald-200 dark:shadow-none
                               active:scale-95 transition-all duration-300">
                        <svg class="w-5 h-5 group-hover:rotate-[360deg] transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Charge Ishlatish
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // ── Charge Modal ────────────────────────────────────────
            function openChargeModal() {
                document.getElementById('chargeModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeChargeModal() {
                document.getElementById('chargeModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // ── Details toggle ──────────────────────────────────────
            function openDetailsModal(logId) {
                const row = document.getElementById('details-' + logId);
                if (row) {
                    row.classList.toggle('hidden');
                }
            }

            // ── Escape key ──────────────────────────────────────────
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeChargeModal();
                }
            });

            // ── Backdrop click ──────────────────────────────────────
            document.getElementById('chargeModal').addEventListener('click', function (e) {
                if (e.target === this) closeChargeModal();
            });
        </script>
    @endpush
@endsection