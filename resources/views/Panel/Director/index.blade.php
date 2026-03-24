@extends('Layout.app')

@section('title', __('director/index.title'))

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
                        {{ __('director/index.title') }}
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
                        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">{{ __('director/index.commission') }}:</span>
                        <span class="text-lg font-extrabold text-emerald-900 dark:text-emerald-50">
                            {{ $stats['commission_percentage'] }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Balance and Commission Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Current Balance --}}
            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider">
                            {{ __('director/index.current_balance') }}
                        </p>
                        <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50 mt-1">
                            {{ number_format($stats['current_balance'], 0, '.', ' ') }}
                            <span class="text-lg">{{ __('common.currency') }}</span>
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
                            {{ __('director/index.this_month_commission') }}
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($stats['this_month_commission'], 0, '.', ' ') }}
                            <span class="text-lg">{{ __('common.currency') }}</span>
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

           
            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">
                            {{ __('director/index.total_earned') }}
                        </p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ number_format($stats['total_commission_earned'], 0, '.', ' ') }}
                            <span class="text-lg">{{ __('common.currency') }}</span>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wider">
                            {{ __('director/index.total_students') }}
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
                class="bg-gradient-to-br from-rose-100 to-rose-200/60 dark:from-rose-900/30 dark:to-rose-800/30 rounded-2xl p-6 border border-rose-200 dark:border-rose-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider">
                           {{ __('director/index.new_students') }}
                        </p>
                        <p class="text-3xl font-extrabold text-rose-900 dark:text-rose-50 mt-1">
                            {{ $stats['new_students_this_month'] }}
                        </p>
                        <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ __('common.this_month') }}</p>
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
                            {{ __('director/index.attendance') }}
                        </p>
                        <p class="text-3xl font-extrabold text-cyan-900 dark:text-cyan-50 mt-1">
                            {{ $stats['attendance_rate'] }}%
                        </p>
                        <p class="text-xs text-cyan-600 dark:text-cyan-400 mt-1">{{ __('common.this_month') }}</p>
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
                   {{ __('director/index.monthly_history') }}
                </h3>
                <div class="space-y-3">
                    @forelse($monthlyCommissions as $month)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $month->month_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $month->count }} {{ __('director/index.payments') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($month->total, 0, '.', ' ') }} {{ __('common.currency') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">{{ __('common.no_data') }}</p>
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
                    {{ __('director/index.top_groups') }}
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
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $group->course->name }}</p>
                                </div>
                            </div>
                            <div class="px-3 py-1.5 bg-purple-600 text-white font-bold rounded-lg shadow-sm">
                                {{ $group->student_count }}
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">{{ __('director/index.no_groups') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

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
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ __('director/index.students') }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('director/index.view_students') }}</p>
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
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ __('director/index.commissions') }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('director/index.payments_commissions') }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('director.school.show', $director->school_id) }}"
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
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ __('director/index.school_info') }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('director/index.full_info') }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
