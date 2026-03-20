@extends('Layout.app')

@section('title', 'Komissiya Tarixi')

@section('content')
    <div class="space-y-6">

        {{-- ════════════════════════════════════════════════
         HEADER
    ════════════════════════════════════════════════ --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('director.commissions') }}"
                class="flex items-center justify-center w-9 h-9 rounded-xl
                   bg-white dark:bg-gray-800
                   border border-gray-200 dark:border-gray-700
                   text-gray-500 dark:text-gray-400
                   hover:text-indigo-600 dark:hover:text-indigo-400
                   hover:border-indigo-300 dark:hover:border-indigo-600
                   transition-all duration-200 shadow-sm"
                title="Ortga">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Komissiya Tarixi
                </h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                    Yillik statistika va tahlil — faqat komissiya tranzaksiyalari
                </p>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
         BALANCE + RATE HERO ROW
    ════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Current balance --}}
            <div
                class="relative overflow-hidden rounded-2xl
                    bg-gradient-to-br from-purple-100 to-indigo-100
                    dark:from-purple-900/30 dark:to-indigo-900/30
                    border border-purple-200 dark:border-purple-700/50 p-6">
                {{-- Decorative ring --}}
                <svg class="pointer-events-none absolute -right-6 -top-6 w-32 h-32
                        text-purple-300 dark:text-purple-800 opacity-40"
                    viewBox="0 0 128 128" fill="none">
                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="16" />
                </svg>
                <p
                    class="text-xs font-bold uppercase tracking-widest
                      text-purple-700 dark:text-purple-400">
                    Joriy Balans
                </p>
                <p class="text-4xl font-extrabold text-purple-900 dark:text-purple-50 mt-2 leading-none">
                    {{ number_format($director->balance, 0, '.', ' ') }}
                    <span class="text-lg font-semibold text-purple-600 dark:text-purple-400 ml-1">so'm</span>
                </p>
                <p class="text-xs text-purple-600 dark:text-purple-400 mt-2">
                    Hisob raqamidagi joriy mablag'
                </p>
            </div>

            {{-- Commission rate --}}
            <div
                class="relative overflow-hidden rounded-2xl
                    bg-gradient-to-br from-indigo-100 to-blue-100
                    dark:from-indigo-900/30 dark:to-blue-900/30
                    border border-indigo-200 dark:border-indigo-700/50 p-6">
                <svg class="pointer-events-none absolute -right-6 -top-6 w-32 h-32
                        text-indigo-300 dark:text-indigo-800 opacity-40"
                    viewBox="0 0 128 128" fill="none">
                    <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="16" />
                </svg>
                <p
                    class="text-xs font-bold uppercase tracking-widest
                      text-indigo-700 dark:text-indigo-400">
                    Komissiya Stavkasi
                </p>
                <p class="text-4xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-2 leading-none">
                    {{ $director->commission_percentage }}<span
                        class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">%</span>
                </p>
                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-2">
                    Har bir to'lovdan olingan ulush
                </p>
            </div>

        </div>

        {{-- ════════════════════════════════════════════════
         ALL-TIME SUMMARY
    ════════════════════════════════════════════════ --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm
                border border-gray-200 dark:border-gray-700 p-6">
            <h3
                class="text-sm font-bold uppercase tracking-widest
                   text-gray-400 dark:text-gray-500 mb-5">
                Umumiy Xulose
            </h3>
            <div
                class="grid grid-cols-1 sm:grid-cols-3 gap-6
                    divide-y sm:divide-y-0 sm:divide-x
                    divide-gray-100 dark:divide-gray-700">

                <div class="pb-4 sm:pb-0">
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">
                        Barcha vaqt jami
                    </p>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white leading-none">
                        {{ number_format($summary['all_time_total'], 0, '.', ' ') }}
                        <span class="text-sm font-normal text-gray-400 dark:text-gray-500">so'm</span>
                    </p>
                </div>

                <div class="py-4 sm:py-0 sm:pl-6">
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">
                        Jami tranzaksiyalar
                    </p>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white leading-none">
                        {{ number_format($summary['all_time_count'], 0, '.', ' ') }}
                        <span class="text-sm font-normal text-gray-400 dark:text-gray-500">ta</span>
                    </p>
                </div>

                <div class="pt-4 sm:pt-0 sm:pl-6">
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">
                        O'rtacha yillik
                    </p>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white leading-none">
                        {{ number_format($summary['avg_per_year'], 0, '.', ' ') }}
                        <span class="text-sm font-normal text-gray-400 dark:text-gray-500">so'm</span>
                    </p>
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════════════
         YEAR-OVER-YEAR GROWTH
         Only pairs where at least one year has real data
         are shown — zero-vs-zero rows are filtered out
         in the controller.
    ════════════════════════════════════════════════ --}}
        @if ($growthPairs->isNotEmpty())
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm
                    border border-gray-200 dark:border-gray-700 p-6">

                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5">
                    Yillik O'sish
                </h3>

                <div class="space-y-2.5">
                    @foreach ($growthPairs as $pair)
                        <div
                            class="group relative overflow-hidden flex items-center justify-between
                                px-5 py-4 rounded-xl
                                border transition-colors duration-150
                                @if ($pair['is_positive']) bg-green-50/60 dark:bg-green-900/10
                                    border-green-200 dark:border-green-800/40
                                    hover:bg-green-50 dark:hover:bg-green-900/20
                                @else
                                    bg-red-50/60 dark:bg-red-900/10
                                    border-red-200 dark:border-red-800/40
                                    hover:bg-red-50 dark:hover:bg-red-900/20 @endif">

                            {{-- Subtle background percentage watermark --}}
                            <span
                                class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2
                                     text-6xl font-black opacity-[0.04] dark:opacity-[0.06] select-none
                                     @if ($pair['is_positive']) text-green-600 @else text-red-600 @endif">
                                {{ $pair['is_positive'] ? '+' : '' }}{{ $pair['growth_pct'] }}%
                            </span>

                            {{-- Left: year labels + amounts --}}
                            <div class="flex items-center gap-4 min-w-0">
                                {{-- From year pill --}}
                                <div class="flex-shrink-0 text-center">
                                    <span
                                        class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold
                                             bg-white dark:bg-gray-700
                                             border border-gray-200 dark:border-gray-600
                                             text-gray-700 dark:text-gray-300">
                                        {{ $pair['from'] }}
                                    </span>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 tabular-nums">
                                        {{ number_format($pair['from_total'], 0, '.', ' ') }}
                                    </p>
                                </div>

                                {{-- Arrow --}}
                                <svg class="w-4 h-4 flex-shrink-0
                                        @if ($pair['is_positive']) text-green-400 dark:text-green-500
                                        @else text-red-400 dark:text-red-500 @endif"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>

                                {{-- To year pill --}}
                                <div class="flex-shrink-0 text-center">
                                    <span
                                        class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold
                                             bg-white dark:bg-gray-700
                                             border border-gray-200 dark:border-gray-600
                                             text-gray-700 dark:text-gray-300">
                                        {{ $pair['to'] }}
                                    </span>
                                    <p
                                        class="text-xs font-semibold mt-1 tabular-nums
                                          @if ($pair['is_positive']) text-green-700 dark:text-green-400
                                          @else text-red-700 dark:text-red-400 @endif">
                                        {{ number_format($pair['to_total'], 0, '.', ' ') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Right: big growth badge --}}
                            <div class="flex-shrink-0 flex items-center gap-2 z-10">
                                <span
                                    class="text-2xl font-extrabold tabular-nums
                                         @if ($pair['is_positive']) text-green-600 dark:text-green-400
                                         @else text-red-600 dark:text-red-400 @endif">
                                    {{ $pair['is_positive'] ? '+' : '' }}{{ $pair['growth_pct'] }}%
                                </span>
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full
                                         @if ($pair['is_positive']) bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400
                                         @else
                                             bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 @endif">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($pair['is_positive'])
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                </span>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════
         PER-YEAR CARDS
         Each card: 3 KPI chips + 12-month bar grid.
         All data = Transaction.amount sums, never Payment.
    ════════════════════════════════════════════════ --}}
        @foreach ($years as $year)
            @if (($yearlyStats[$year]['total_commission'] ?? 0) > 0)
                @php $data = $yearlyStats[$year]; @endphp
                @php $maxMonthly = $data['monthly']->max('total') ?: 1; @endphp

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm
                        border border-gray-200 dark:border-gray-700 overflow-hidden">

                    {{-- Year header --}}
                    <div
                        class="flex items-center justify-between
                            px-6 py-5
                            bg-gradient-to-r from-indigo-50 to-purple-50
                            dark:from-indigo-900/20 dark:to-purple-900/20
                            border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl font-extrabold text-gray-900 dark:text-white">
                                {{ $year }}
                            </span>
                            @if ($data['best_month'])
                                <span
                                    class="text-xs font-semibold
                                         bg-indigo-100 dark:bg-indigo-900/40
                                         text-indigo-700 dark:text-indigo-300
                                         px-2.5 py-0.5 rounded-full">
                                    Eng yaxshi: {{ $data['best_month']['name'] }}
                                </span>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                                Yil jami komissiya
                            </p>
                            <p class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400 leading-none">
                                {{ number_format($data['total_commission'], 0, '.', ' ') }}
                                <span class="text-sm font-semibold text-gray-400 dark:text-gray-500">so'm</span>
                            </p>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">

                        {{-- 3 KPI chips --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                            <div
                                class="flex items-center gap-3 p-4
                                    bg-emerald-50 dark:bg-emerald-900/10
                                    rounded-xl border border-emerald-200 dark:border-emerald-800/30">
                                <div class="p-2.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Tranzaksiyalar</p>
                                    <p class="text-xl font-extrabold text-gray-900 dark:text-white leading-tight">
                                        {{ $data['total_count'] }}
                                        <span class="text-xs font-normal text-gray-400 dark:text-gray-500">ta</span>
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-3 p-4
                                    bg-blue-50 dark:bg-blue-900/10
                                    rounded-xl border border-blue-200 dark:border-blue-800/30">
                                <div class="p-2.5 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Faol oy o'rtachasi</p>
                                    <p class="text-xl font-extrabold text-gray-900 dark:text-white leading-tight">
                                        {{ number_format($data['avg_per_month'], 0, '.', ' ') }}
                                        <span class="text-xs font-normal text-gray-400 dark:text-gray-500">so'm</span>
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-3 p-4
                                    bg-purple-50 dark:bg-purple-900/10
                                    rounded-xl border border-purple-200 dark:border-purple-800/30">
                                <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Har tranzaksiyada</p>
                                    <p class="text-xl font-extrabold text-gray-900 dark:text-white leading-tight">
                                        {{ number_format($data['avg_per_tx'], 0, '.', ' ') }}
                                        <span class="text-xs font-normal text-gray-400 dark:text-gray-500">so'm</span>
                                    </p>
                                </div>
                            </div>

                        </div>

                        {{-- 12-month breakdown grid with bar indicators --}}
                        <div>
                            <p
                                class="text-xs font-bold uppercase tracking-widest
                                   text-gray-400 dark:text-gray-500 mb-3">
                                Oylik Taqsimot
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                                @foreach ($data['monthly'] as $num => $mData)
                                    <div @class([
                                        'p-3 rounded-xl border transition-colors',
                                        'bg-indigo-50 dark:bg-indigo-900/15 border-indigo-200 dark:border-indigo-800/40' =>
                                            $mData['total'] > 0,
                                        'bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-700' =>
                                            $mData['total'] <= 0,
                                    ])>
                                        <p @class([
                                            'text-xs font-semibold',
                                            'text-indigo-700 dark:text-indigo-400' => $mData['total'] > 0,
                                            'text-gray-400 dark:text-gray-600' => $mData['total'] <= 0,
                                        ])>
                                            {{ $mData['name'] }}
                                        </p>

                                        @if ($mData['total'] > 0)
                                            <p
                                                class="text-base font-extrabold
                                                   text-indigo-900 dark:text-indigo-100
                                                   mt-1 leading-none">
                                                {{ number_format($mData['total'], 0, '.', ' ') }}
                                                <span
                                                    class="text-xs font-normal text-gray-400 dark:text-gray-500">so'm</span>
                                            </p>
                                            {{-- Mini bar --}}
                                            <div
                                                class="mt-2 h-1 w-full bg-indigo-100 dark:bg-indigo-900/40 rounded-full overflow-hidden">
                                                <div class="h-1 bg-indigo-500 dark:bg-indigo-400 rounded-full"
                                                    style="width: {{ round(($mData['total'] / $maxMonthly) * 100) }}%">
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">
                                                {{ $mData['count'] }} ta tranzaksiya
                                            </p>
                                        @else
                                            <p class="text-base font-bold text-gray-300 dark:text-gray-700 mt-1">—</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        @endforeach

        {{-- Empty state if no data at all --}}
        @if (collect($yearlyStats)->sum('total_commission') === 0.0)
            <div
                class="text-center py-16
                    bg-white dark:bg-gray-800 rounded-2xl
                    border border-gray-200 dark:border-gray-700">
                <div
                    class="inline-flex items-center justify-center w-16 h-16
                        bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-semibold text-lg">
                    Komissiya tarixi topilmadi
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    Hali hech qanday komissiya tranzaksiyasi qayd etilmagan
                </p>
            </div>
        @endif

    </div>
@endsection
