@extends('Layout.app')

@section('title', 'Mening Statistikam')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('student.leaderboard') }}" 
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    📊 Mening Statistikam
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    To'liq tahlil va ko'rsatkichlar
                </p>
            </div>
        </div>

        {{-- Overall Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase">Jami Coinlar</p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ number_format($stats['total_coins'], 0, '.', ' ') }}
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
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase">Davomat</p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ $stats['attendance_rate'] }}%
                        </p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ $stats['present_lessons'] }}/{{ $stats['total_lessons'] }} dars
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase">Uy Ishi</p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ $stats['avg_homework'] }}
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">O'rtacha ball</p>
                    </div>
                    <div class="p-3 bg-blue-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase">Faollik</p>
                        <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50 mt-1">
                            {{ $stats['avg_activity'] }}
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">O'rtacha ball</p>
                    </div>
                    <div class="p-3 bg-purple-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Achievements --}}
        @if($achievements->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">🏅 Yutuqlar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($achievements as $achievement)
                        <div class="p-4 bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/10 dark:to-amber-900/10 rounded-xl border-2 border-yellow-300 dark:border-yellow-700/50">
                            <div class="flex items-center gap-3">
                                <span class="text-4xl">{{ $achievement['icon'] }}</span>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $achievement['title'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $achievement['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Monthly Progress --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">📈 Oylik O'sish</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($monthlyProgress as $month)
                    <div class="p-4 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/10 dark:to-blue-900/10 rounded-xl border border-indigo-200 dark:border-indigo-800/30">
                        <p class="text-xs font-semibold text-indigo-700 dark:text-indigo-400 mb-2">{{ $month['month'] }}</p>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Coinlar</p>
                                <p class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ number_format($month['coins'], 0) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Davomat</p>
                                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $month['attendance_rate'] }}%</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Coins by Type --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">💰 Coin Manbалари</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($stats['coins_by_type'] as $coinType)
                    <div class="p-4 bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-amber-900/10 dark:to-yellow-900/10 rounded-xl border border-amber-200 dark:border-amber-800/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    @switch($coinType->type)
                                        @case('participation') 📝 Ishtirok @break
                                        @case('homework') 📚 Uy ishi @break
                                        @case('exam') 📝 Imtihon @break
                                        @case('competition') 🏆 Musobaqa @break
                                        @case('active_involvement') ⚡ Faol ishtirok @break
                                        @case('bonus') 🎁 Bonus @break
                                        @default {{ ucfirst($coinType->type) }}
                                    @endswitch
                                </p>
                                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">
                                    {{ number_format($coinType->total, 0, '.', ' ') }}
                                </p>
                            </div>
                            <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                                <span class="text-2xl">
                                    @switch($coinType->type)
                                        @case('participation') 📝 @break
                                        @case('homework') 📚 @break
                                        @case('exam') 📝 @break
                                        @case('competition') 🏆 @break
                                        @case('active_involvement') ⚡ @break
                                        @case('bonus') 🎁 @break
                                        @default 💰
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Group Statistics --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">📚 Guruhlar Bo'yicha Statistika</h3>
            
            <div class="space-y-4">
                @forelse($groupStats as $stat)
                    <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/10 dark:to-purple-900/10 rounded-xl border border-indigo-200 dark:border-indigo-800/30">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-lg">{{ $stat['group_name'] }}</p>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400">{{ $stat['course_name'] }}</p>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg">
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Darslar</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stat['lessons'] }}</p>
                                </div>
                                <div class="text-center p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg">
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Davomat</p>
                                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $stat['attendance_rate'] }}%</p>
                                </div>
                                <div class="text-center p-3 bg-white/60 dark:bg-gray-800/60 rounded-lg">
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Coinlar</p>
                                    <p class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ number_format($stat['coins'], 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">Faol guruhlar yo'q</p>
                @endforelse
            </div>
        </div>

        {{-- Motivation Card --}}
        <div class="bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 dark:from-indigo-900/30 dark:via-purple-900/30 dark:to-pink-900/30 rounded-2xl p-8 border border-indigo-200 dark:border-indigo-700/50 text-center">
            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2">
                🌟 Ajoyib Ish!
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Davom eting va yanada yuqori natijalarga erishing!
            </p>
            <div class="flex items-center justify-center gap-6 text-sm">
                <div>
                    <span class="font-bold text-amber-600 dark:text-amber-400">{{ number_format($stats['total_coins'], 0) }}</span>
                    <span class="text-gray-600 dark:text-gray-400"> coinlar yig'ildi</span>
                </div>
                <div>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['attendance_rate'] }}%</span>
                    <span class="text-gray-600 dark:text-gray-400"> davomat</span>
                </div>
            </div>
        </div>
    </div>
@endsection