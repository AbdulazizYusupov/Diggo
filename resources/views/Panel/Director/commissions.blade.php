@extends('Layout.app')

@section('title', __('director/commissions.title'))

@section('content')
    <div class="space-y-6">


        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('director/commissions.title') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('director/commissions.subtitle') }}
                </p>
            </div>
            <a href="{{ route('director.commissions.history') }}"
                class="inline-flex items-center gap-2 px-4 py-2
                   bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                   text-white text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                {{ __('director/commissions.history') }}
            </a>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Joriy Balans --}}
            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200/60
                    dark:from-purple-900/30 dark:to-purple-800/30
                    rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wide">
                            {{ __('director/commissions.current_balance') }}
                        </p>
                        <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50 mt-1 leading-none">
                            {{ number_format($stats['current_balance'], 0, '.', ' ') }}
                            <span class="text-base font-semibold">{{ __('common.currency') }}</span>
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            {{ __('director/commissions.balance_desc') }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Shu Oy --}}
            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60
                    dark:from-emerald-900/30 dark:to-emerald-800/30
                    rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">
                            {{ __('director/commissions.this_month') }}
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1 leading-none">
                            {{ number_format($stats['this_month_commission'], 0, '.', ' ') }}
                            <span class="text-base font-semibold">{{ __('common.currency') }}</span>
                        </p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ now()->translatedFormat('F Y') }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Yillik Jami --}}
            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200/60
                    dark:from-blue-900/30 dark:to-blue-800/30
                    rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wide">
                            {{ __('director/commissions.year_total', ['year' => $year]) }}
                        </p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1 leading-none">
                            {{ number_format($stats['year_total'], 0, '.', ' ') }}
                            <span class="text-base font-semibold">{{__('common.currency')}}</span>
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            {{ __('director/commissions.transactions_count', ['count' =>$stats['year_count']]) }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Komissiya % --}}
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60
                    dark:from-indigo-900/30 dark:to-indigo-800/30
                    rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wide">
                           {{ __('director/commissions.commission_rate') }}
                        </p>
                        <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-1 leading-none">
                            {{ $stats['commission_percentage'] }}<span class="text-base font-semibold">%</span>
                        </p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                            {{ __('director/commissions.current_rate') }}
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════════════════════
         FILTERS
    ════════════════════════════════════════════════ --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm
                border border-gray-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('director.commissions') }}"
                class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Oy --}}
                <div>
                    <label for="monthSelect" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('director/commissions.filter_month') }}
                    </label>
                    <select id="monthSelect" name="month"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600
                           rounded-xl bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        <option value="">{{ __('director/commissions.all_months') }}</option>
                        @foreach ($months as $key => $monthName)
                            <option value="{{ $key }}" @selected($month == $key)>
                                {{ $monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Yil --}}
                <div>
                    <label for="yearSelect" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('director/commissions.filter_year') }}
                    </label>
                    <select id="yearSelect" name="year"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600
                           rounded-xl bg-white dark:bg-gray-700
                           text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                           text-white font-semibold rounded-xl transition-colors
                           flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        {{ __('director/commissions.filter_btn') }}
                    </button>
                    <a href="{{ route('director.commissions') }}"
                        class="group p-2 bg-gray-100 dark:bg-gray-700
                           hover:bg-gray-200 dark:hover:bg-gray-600
                           text-gray-600 dark:text-gray-300
                           rounded-xl transition-all duration-300 flex items-center justify-center"
                        title="Filterni tozalash">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 transition-transform duration-500 group-hover:rotate-180">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </a>
                </div>

            </form>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm
                border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Table header --}}
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700
                    flex items-center justify-between gap-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                    {{ __('director/commissions.table_title') }}
                </h3>
                @if ($transactions->total() > 0)
                    <span
                        class="text-xs font-semibold
                             bg-indigo-100 dark:bg-indigo-900/30
                             text-indigo-700 dark:text-indigo-400
                             px-2.5 py-0.5 rounded-full">
                        {{ __('director/commissions.total_items', ['count' => $transactions->total()]) }}
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-bold
                                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                               {{ __('director/commissions.col_student') }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-bold
                                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('director/commissions.col_group') }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-bold
                                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('director/commissions.col_balance_after') }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-bold
                                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('director/commissions.col_my_commission') }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-bold
                                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('director/commissions.col_rate') }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-bold
                                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('director/commissions.col_date') }}
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-bold
                                   text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('director/commissions.col_cashier') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($transactions as $tx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">

                                {{-- O'quvchi --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="font-semibold text-gray-900 dark:text-white leading-tight">
                                        {{ $tx->source?->payable?->user?->surname }}
                                        {{ $tx->source?->payable?->user?->name }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                        ID: #{{ str_pad($tx->source?->payable_id ?? 0, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                </td>

                                {{-- Guruh / Kurs --}}
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white leading-tight">
                                        {{ $tx->source?->group?->name ?? '—' }}
                                    </p>
                                    @if ($tx->source?->group?->course?->title)
                                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-0.5">
                                            {{ $tx->source->group->course->title }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Running balance after this commission was credited --}}
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ number_format((float) $tx->balance_after, 0, '.', ' ') }}
                                        <span class="text-xs font-normal text-gray-400 dark:text-gray-500">{{__('common.currency')}}</span>
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                        Oldin: {{ number_format((float) $tx->balance_before, 0, '.', ' ') }} {{__('common.currency')}}
                                    </p>
                                </td>

                                {{-- Director's commission amount --}}
                                <td class="px-6 py-4">
                                    <span class="font-extrabold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format((float) $tx->amount, 0, '.', ' ') }}
                                        <span class="text-xs font-medium">{{__('common.currency')}}</span>
                                    </span>
                                </td>

                                {{-- Percentage applied --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                             text-xs font-semibold
                                             bg-indigo-100 dark:bg-indigo-900/30
                                             text-indigo-700 dark:text-indigo-400">
                                        {{ number_format((float) $tx->percentage, 0) }}%
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td class="px-6 py-4">
                                    <p class="text-gray-900 dark:text-white font-medium">
                                        {{ $tx->transaction_date?->format('d.m.Y') ?? '—' }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $tx->transaction_date?->format('H:i') ?? '' }}
                                    </p>
                                </td>

                                {{-- Created by --}}
                                <td class="px-6 py-4">
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $tx->creator?->name ?? '—' }}
                                    </p>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="inline-flex flex-col items-center gap-3">
                                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-full">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-semibold">
                                           {{ __('director/commissions.no_data') }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ __('director/commissions.no_data_desc') }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
            @endif

        </div>

        {{-- ════════════════════════════════════════════════
         MONTHLY BREAKDOWN
         Bar-style visual for the selected year.
         Each month shows commission earned from
         Transaction.amount (not raw payment sums).
    ════════════════════════════════════════════════ --}}
        @if ($stats['monthly_breakdown']->sum('total') > 0)
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm
                    border border-gray-200 dark:border-gray-700 p-6">

                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-6">
                    {{ __('director/commissions.monthly_stat', ['year' => $year]) }}
                    <span
                        class="ml-2 text-xs font-semibold
                             bg-gray-100 dark:bg-gray-700
                             text-gray-500 dark:text-gray-400
                             px-2 py-0.5 rounded-full">
                        {{ __('director/commissions.col_my_commission') }}
                    </span>
                </h3>

                @php
                    $maxMonthlyTotal = $stats['monthly_breakdown']->max('total') ?: 1;
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach ($stats['monthly_breakdown'] as $num => $data)
                        @if ($data['total'] > 0)
                            <div
                                class="p-4 rounded-xl border
                                    {{ (int) $num === now()->month && (int) $year === now()->year
                                        ? 'bg-emerald-50 dark:bg-emerald-900/10 border-emerald-200 dark:border-emerald-800/40'
                                        : 'bg-gray-50 dark:bg-gray-700/40 border-gray-100 dark:border-gray-700' }}">

                                <p
                                    class="text-xs font-semibold
                                      {{ (int) $num === now()->month && (int) $year === now()->year
                                          ? 'text-emerald-700 dark:text-emerald-400'
                                          : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $data['name'] }}
                                </p>

                                <p
                                    class="text-xl font-extrabold mt-1 leading-none
                                      {{ (int) $num === now()->month && (int) $year === now()->year
                                          ? 'text-emerald-800 dark:text-emerald-100'
                                          : 'text-gray-900 dark:text-white' }}">
                                    {{ number_format($data['total'], 0, '.', ' ') }}
                                    <span class="text-xs font-normal text-gray-400 dark:text-gray-500">{{__('common.currency')}}</span>
                                </p>

                                {{-- Mini progress bar --}}
                                <div class="mt-2 h-1 w-full bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div class="h-1 rounded-full transition-all duration-500
                                            {{ (int) $num === now()->month && (int) $year === now()->year
                                                ? 'bg-emerald-500'
                                                : 'bg-indigo-400 dark:bg-indigo-500' }}"
                                        style="width: {{ round(($data['total'] / $maxMonthlyTotal) * 100) }}%">
                                    </div>
                                </div>

                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5">
                                    {{ __('director/commissions.transactions_count', ['count' => $data['count']]) }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        @endif

    </div>
@endsection
