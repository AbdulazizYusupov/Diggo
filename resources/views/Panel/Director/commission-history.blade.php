@extends('Layout.app')

@section('title', 'Komissiya Tarixi')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('director.commissions') }}" 
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Komissiya Tarixi
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Yillik statistika va tahlil
                </p>
            </div>
        </div>

        {{-- Current Balance Card --}}
        <div class="bg-gradient-to-br from-purple-100 via-indigo-100 to-blue-100 dark:from-purple-900/30 dark:via-indigo-900/30 dark:to-blue-900/30 rounded-2xl p-8 border border-purple-200 dark:border-purple-700/50">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <p class="text-lg font-bold text-purple-700 dark:text-purple-400 uppercase mb-2">Joriy Balans</p>
                    <p class="text-5xl font-extrabold text-purple-900 dark:text-purple-50">
                        {{ number_format($director->balance, 0, '.', ' ') }}
                    </p>
                    <p class="text-xl text-purple-600 dark:text-purple-400 mt-2">so'm</p>
                </div>
                <div class="p-6 bg-white/50 dark:bg-gray-800/50 rounded-2xl">
                    <svg class="w-16 h-16 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Commission Percentage --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sizning Komissiya Foizingiz</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $director->commission_percentage }}%</p>
                </div>
            </div>
        </div>

        {{-- Yearly Statistics --}}
        <div class="grid grid-cols-1 gap-6">
            @foreach($years as $year)
                @php $data = $yearlyStats[$year] ?? null; @endphp
                @if($data && $data['total_commission'] > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $year }}</h3>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Jami Komissiya</p>
                                    <p class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($data['total_commission'], 0, '.', ' ') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="p-4 bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/10 dark:to-green-900/10 rounded-xl border border-emerald-200 dark:border-emerald-800/30">
                                    <div class="flex items-center gap-3">
                                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Jami To'lovlar</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ $data['total_payments'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-xl border border-blue-200 dark:border-blue-800/30">
                                    <div class="flex items-center gap-3">
                                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Oylik O'rtacha</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ number_format($data['avg_per_month'], 0, '.', ' ') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/10 dark:to-pink-900/10 rounded-xl border border-purple-200 dark:border-purple-800/30">
                                    <div class="flex items-center gap-3">
                                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Har To'lovda</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                {{ $data['total_payments'] > 0 ? number_format($data['total_commission'] / $data['total_payments'], 0, '.', ' ') : 0 }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Monthly Breakdown Chart --}}
                            <div class="mt-6">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Oylik Taqsimot</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @php
                                        $monthNames = [
                                            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
                                            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
                                            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
                                        ];
                                    @endphp
                                    
                                    @for($month = 1; $month <= 12; $month++)
                                        @php
                                            // Bu yerda oylik breakdown ni olish kerak
                                            // Hozircha mock data
                                            $hasData = rand(0, 1);
                                        @endphp
                                        <div class="p-3 {{ $hasData ? 'bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border-indigo-200 dark:border-indigo-800/30' : 'bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-700' }} rounded-lg border">
                                            <p class="text-xs font-semibold {{ $hasData ? 'text-indigo-700 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                                {{ $monthNames[$month] }}
                                            </p>
                                            <p class="text-lg font-bold {{ $hasData ? 'text-indigo-900 dark:text-indigo-50' : 'text-gray-400 dark:text-gray-600' }} mt-1">
                                                {{ $hasData ? number_format(rand(100000, 500000), 0, '.', ' ') : '-' }}
                                            </p>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Summary Card --}}
        <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-indigo-900/20 dark:via-purple-900/20 dark:to-pink-900/20 rounded-2xl p-8 border border-indigo-200 dark:border-indigo-700/50">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Umumiy Xulose</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Barcha Vaqt Jami</p>
                    <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50">
                        {{ number_format(collect($yearlyStats)->sum('total_commission'), 0, '.', ' ') }}
                    </p>
                    <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1">so'm</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Jami To'lovlar</p>
                    <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50">
                        {{ collect($yearlyStats)->sum('total_payments') }}
                    </p>
                    <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">ta to'lov</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">O'rtacha Yillik</p>
                    <p class="text-3xl font-extrabold text-pink-900 dark:text-pink-50">
                        {{ count($yearlyStats) > 0 ? number_format(collect($yearlyStats)->sum('total_commission') / count($yearlyStats), 0, '.', ' ') : 0 }}
                    </p>
                    <p class="text-sm text-pink-600 dark:text-pink-400 mt-1">so'm</p>
                </div>
            </div>
        </div>

        {{-- Growth Comparison --}}
        @if(count($yearlyStats) > 1)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Yillik O'sish</h3>
                
                <div class="space-y-4">
                    @php
                        $sortedYears = collect($years)->sort()->values();
                    @endphp
                    @foreach($sortedYears as $index => $year)
                        @if($index > 0)
                            @php
                                $currentYear = $year;
                                $previousYear = $sortedYears[$index - 1];
                                $current = $yearlyStats[$currentYear]['total_commission'] ?? 0;
                                $previous = $yearlyStats[$previousYear]['total_commission'] ?? 0;
                                
                                if ($previous > 0) {
                                    $growth = (($current - $previous) / $previous) * 100;
                                    $isPositive = $growth >= 0;
                                } else {
                                    $growth = 0;
                                    $isPositive = true;
                                }
                            @endphp
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ $previousYear }} → {{ $currentYear }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($previous, 0, '.', ' ') }} → {{ number_format($current, 0, '.', ' ') }} so'm
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl font-bold {{ $isPositive ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $isPositive ? '+' : '' }}{{ number_format($growth, 1) }}%
                                    </span>
                                    <svg class="w-6 h-6 {{ $isPositive ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($isPositive)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17H5m0 0V9m0 8l8-8 4 4 6-6" />
                                        @endif
                                    </svg>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection