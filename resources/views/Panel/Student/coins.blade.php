@extends('Layout.app')

@section('title', 'Coin Tranzaksiyalari')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Coin Tranzaksiyalari
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Coinlaringiz tarixi va statistikasi
                </p>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase">Joriy Balans</p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ number_format($stats['current_balance'], 0, '.', ' ') }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase">Jami Ishlangan</p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($stats['total_earned'], 0, '.', ' ') }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30 rounded-2xl p-6 border border-red-200 dark:border-red-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-red-700 dark:text-red-400 uppercase">Jami Sarflangan</p>
                        <p class="text-3xl font-extrabold text-red-900 dark:text-red-50 mt-1">
                            {{ number_format($stats['total_spent'], 0, '.', ' ') }}
                        </p>
                    </div>
                    <div class="p-3 bg-red-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase">So'nggi 30 kun</p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ number_format($stats['last_30_days'], 0, '.', ' ') }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('student.coins') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Turi</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Barchasi</option>
                        <option value="earned" {{ $type === 'earned' ? 'selected' : '' }}>Ishlangan</option>
                        <option value="spent" {{ $type === 'spent' ? 'selected' : '' }}>Sarflangan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Oy</label>
                    <select name="month" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Barcha oylar</option>
                        @foreach($months as $key => $monthName)
                            <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Yil</label>
                    <select name="year" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                        Filtrlash
                    </button>
                </div>
            </form>
        </div>

        {{-- Transactions List --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Tranzaksiyalar ro'yxati</h3>

            <div class="space-y-3">
                @forelse($transactions as $transaction)
                    <div class="flex items-center justify-between p-4 rounded-xl transition-all {{ $transaction->coins > 0 ? 'bg-green-50 dark:bg-green-900/10 hover:bg-green-100 dark:hover:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/10 hover:bg-red-100 dark:hover:bg-red-900/20' }}">
                        <div class="flex items-center gap-4 flex-1">
                            {{-- Icon --}}
                            <div class="p-3 rounded-xl {{ $transaction->coins > 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                <svg class="w-6 h-6 {{ $transaction->coins > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($transaction->coins > 0)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    @endif
                                </svg>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900 dark:text-white">
                                        {{ $coinTypes[$transaction->type] ?? ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </h4>
                                    @if($transaction->activity)
                                        <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-semibold rounded">
                                            {{ $transaction->activity->name }}
                                        </span>
                                    @endif
                                </div>
                                
                                @if($transaction->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $transaction->description }}</p>
                                @endif
                                
                                <div class="flex items-center gap-4 mt-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d.m.Y H:i') }}
                                    </p>
                                    @if($transaction->user)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $transaction->user->name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="text-right">
                            <p class="text-2xl font-bold {{ $transaction->coins > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->coins > 0 ? '+' : '' }}{{ number_format($transaction->coins, 0, '.', ' ') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Balans: {{ number_format($transaction->balance_after, 0, '.', ' ') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Tranzaksiyalar topilmadi</p>
                    </div>
                @endforelse
            </div>

            @if($transactions->hasPages())
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        {{-- Monthly Breakdown Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Oylik Statistika</h3>
            
            <div class="space-y-4">
                @foreach($stats['monthly_breakdown'] as $month => $data)
                    @if($data['earned'] > 0 || $data['spent'] > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $months[$month] }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $data['count'] }} ta tranzaksiya
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <div class="p-3 bg-green-50 dark:bg-green-900/10 rounded-lg">
                                    <p class="text-xs text-green-700 dark:text-green-400 font-semibold">Ishlangan</p>
                                    <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                        +{{ number_format($data['earned'], 0, '.', ' ') }}
                                    </p>
                                </div>
                                <div class="p-3 bg-red-50 dark:bg-red-900/10 rounded-lg">
                                    <p class="text-xs text-red-700 dark:text-red-400 font-semibold">Sarflangan</p>
                                    <p class="text-lg font-bold text-red-600 dark:text-red-400">
                                        -{{ number_format($data['spent'], 0, '.', ' ') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Type Breakdown --}}
        @if($stats['type_breakdown']->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Tur bo'yicha Statistika</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($stats['type_breakdown'] as $type => $data)
                        <div class="p-4 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/10 dark:to-blue-900/10 rounded-xl border border-indigo-200 dark:border-indigo-800/30">
                            <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-400">
                                {{ $coinTypes[$type] ?? ucfirst(str_replace('_', ' ', $type)) }}
                            </p>
                            <p class="text-2xl font-bold text-indigo-900 dark:text-indigo-50 mt-2">
                                +{{ number_format($data['total'], 0, '.', ' ') }}
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                {{ $data['count'] }} marta
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection