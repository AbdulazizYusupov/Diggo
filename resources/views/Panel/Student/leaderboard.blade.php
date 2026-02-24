@extends('Layout.app')

@section('title', 'Reyting')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Reyting Jadvali
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    O'quvchilar reytingi va natijalar
                </p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('student.leaderboard') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Reyting Turi
                        </label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                            <option value="coins" {{ $type === 'coins' ? 'selected' : '' }}>Coinlar</option>
                            <option value="attendance" {{ $type === 'attendance' ? 'selected' : '' }}>Davomat</option>
                            <option value="homework" {{ $type === 'homework' ? 'selected' : '' }}>Uy Ishi</option>
                            <option value="activity" {{ $type === 'activity' ? 'selected' : '' }}>Faollik</option>
                        </select>
                    </div>

                    {{-- From Date --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Boshlanish
                        </label>
                        <input type="date" name="from_date" value="{{ $fromDate }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- To Date --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tugash
                        </label>
                        <input type="date" name="to_date" value="{{ $toDate }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- Group --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Guruh
                        </label>
                        <select name="group_id" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                            <option value="">Barcha Guruhlar</option>
                            @foreach($myGroups as $group)
                                <option value="{{ $group['id'] }}" {{ $groupId == $group['id'] ? 'selected' : '' }}>
                                    {{ $group['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Per Page --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Ko'rsatish soni
                        </label>
                        <select name="per_page" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 ta</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 ta</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 ta</option>
                        </select>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Ko'rsatish
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- My Stats Card --}}
        <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-indigo-900/20 dark:via-purple-900/20 dark:to-pink-900/20 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mening Ko'rsatkichlarim</h3>
                @if($myPosition)
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/80 dark:bg-gray-800/80 rounded-xl">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Reyting:</span>
                        <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">#{{ $myPosition }}</span>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-white/60 dark:bg-gray-800/60 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Coinlar</p>
                    </div>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($myStats['total_coins'], 0, '.', ' ') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $myStats['coin_transactions'] }} ta</p>
                </div>

                <div class="p-4 bg-white/60 dark:bg-gray-800/60 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Davomat</p>
                    </div>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $myStats['attendance_rate'] }}%</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $myStats['present_lessons'] }}/{{ $myStats['total_lessons'] }}</p>
                </div>

                <div class="p-4 bg-white/60 dark:bg-gray-800/60 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Uy Ishi</p>
                    </div>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $myStats['avg_homework'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $myStats['homework_count'] }} ta</p>
                </div>

                <div class="p-4 bg-white/60 dark:bg-gray-800/60 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Faollik</p>
                    </div>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $myStats['avg_activity'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $myStats['activity_count'] }} ta</p>
                </div>
            </div>
        </div>

        {{-- Top 3 Podium --}}
        @if($topThree->count() >= 3)
            <div class="bg-gradient-to-b from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl p-4 sm:p-8 border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="flex items-end justify-center gap-2 sm:gap-4 mb-4 sm:mb-8 w-full">

                    {{-- 2nd Place --}}
                    @if($topThree->get(1))
                        <div class="flex flex-col items-center flex-1 max-w-[30%] sm:max-w-[140px] animate-[slideUp_0.5s_ease-out_0.2s_both]">
                            <div class="relative mb-3 sm:mb-4">
                                <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white text-lg sm:text-2xl font-bold ring-4 ring-gray-200 dark:ring-gray-700">
                                    {{ substr($topThree->get(1)->user->name, 0, 1) }}{{ substr($topThree->get(1)->user->surname ?? '', 0, 1) }}
                                </div>
                                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-7 h-7 sm:w-10 sm:h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg text-xs sm:text-base">
                                    2
                                </div>
                            </div>
                            <div class="text-center mb-2 w-full px-1">
                                <p class="font-bold text-gray-900 dark:text-white text-xs sm:text-base truncate">{{ $topThree->get(1)->user->name }}</p>
                                <p class="text-lg sm:text-3xl font-extrabold text-gray-600 dark:text-gray-400 truncate">{{ number_format($topThree->get(1)->score, 1) }}</p>
                            </div>
                            <div class="w-full h-20 sm:h-32 bg-gradient-to-t from-gray-300 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-t-xl flex items-center justify-center">
                                <svg class="w-8 h-8 sm:w-16 sm:h-16 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                    @endif

                    {{-- 1st Place --}}
                    @if($topThree->get(0))
                        <div class="flex flex-col items-center flex-1 max-w-[36%] sm:max-w-[160px] animate-[slideUp_0.5s_ease-out_both]">
                            <div class="relative mb-3 sm:mb-4">
                                <div class="w-16 h-16 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-yellow-300 via-yellow-400 to-yellow-500 flex items-center justify-center text-white text-xl sm:text-3xl font-bold ring-4 ring-yellow-200 dark:ring-yellow-700 shadow-2xl">
                                    {{ substr($topThree->get(0)->user->name, 0, 1) }}{{ substr($topThree->get(0)->user->surname ?? '', 0, 1) }}
                                </div>
                                <div class="absolute -top-5 sm:-top-6 left-1/2 -translate-x-1/2">
                                    <svg class="w-8 h-8 sm:w-12 sm:h-12 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-8 h-8 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-xl shadow-lg">
                                    1
                                </div>
                            </div>
                            <div class="text-center mb-2 w-full px-1">
                                <p class="font-bold sm:text-xl text-gray-900 dark:text-white text-xs truncate">{{ $topThree->get(0)->user->name }}</p>
                                <p class="text-xl sm:text-4xl font-extrabold text-yellow-600 dark:text-yellow-400 truncate">{{ number_format($topThree->get(0)->score, 1) }}</p>
                            </div>
                            <div class="w-full h-28 sm:h-40 bg-gradient-to-t from-yellow-400 via-yellow-300 to-yellow-200 dark:from-yellow-700 dark:to-yellow-500 rounded-t-xl flex items-center justify-center shadow-xl">
                                <svg class="w-12 h-12 sm:w-20 sm:h-20 text-yellow-600 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                    @endif

                    {{-- 3rd Place --}}
                    @if($topThree->get(2))
                        <div class="flex flex-col items-center flex-1 max-w-[30%] sm:max-w-[140px] animate-[slideUp_0.5s_ease-out_0.4s_both]">
                            <div class="relative mb-3 sm:mb-4">
                                <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-orange-300 to-orange-500 flex items-center justify-center text-white text-lg sm:text-2xl font-bold ring-4 ring-orange-200 dark:ring-orange-700">
                                    {{ substr($topThree->get(2)->user->name, 0, 1) }}{{ substr($topThree->get(2)->user->surname ?? '', 0, 1) }}
                                </div>
                                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-7 h-7 sm:w-10 sm:h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg text-xs sm:text-base">
                                    3
                                </div>
                            </div>
                            <div class="text-center mb-2 w-full px-1">
                                <p class="font-bold text-gray-900 dark:text-white text-xs sm:text-base truncate">{{ $topThree->get(2)->user->name }}</p>
                                <p class="text-lg sm:text-3xl font-extrabold text-orange-600 dark:text-orange-400 truncate">{{ number_format($topThree->get(2)->score, 1) }}</p>
                            </div>
                            <div class="w-full h-16 sm:h-24 bg-gradient-to-t from-orange-400 to-orange-300 dark:from-orange-700 dark:to-orange-500 rounded-t-xl flex items-center justify-center">
                                <svg class="w-8 h-8 sm:w-16 sm:h-16 text-orange-600 dark:text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endif

        {{-- Full Rankings --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">To'liq Reyting</h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $rankings->firstItem() }}–{{ $rankings->lastItem() }} / {{ $rankings->total() }} ta
                </span>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($rankings as $ranking)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $ranking->id === $student->id ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                        <div class="flex items-center gap-4">
                            {{-- Rank Badge --}}
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full font-bold text-lg
                                {{ $ranking->rank === 1 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-white' : '' }}
                                {{ $ranking->rank === 2 ? 'bg-gradient-to-br from-gray-300 to-gray-500 text-white' : '' }}
                                {{ $ranking->rank === 3 ? 'bg-gradient-to-br from-orange-400 to-orange-600 text-white' : '' }}
                                {{ $ranking->rank > 3 ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' : '' }}">
                                {{ $ranking->rank }}
                            </div>

                            {{-- Avatar --}}
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ substr($ranking->user->name, 0, 1) }}{{ substr($ranking->user->surname ?? '', 0, 1) }}
                            </div>

                            {{-- Name & Score --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-gray-900 dark:text-white truncate">
                                        {{ $ranking->user->name }} {{ $ranking->user->surname }}
                                    </p>
                                    @if($ranking->id === $student->id)
                                        <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-bold rounded">Siz</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($type === 'coins') {{ number_format($ranking->score, 0) }} coin
                                    @elseif($type === 'attendance') {{ number_format($ranking->score, 0) }} dars
                                    @else {{ number_format($ranking->score, 1) }} ball
                                    @endif
                                </p>
                            </div>

                            {{-- Trophy for top 3 --}}
                            @if($ranking->rank <= 3)
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 {{ $ranking->rank === 1 ? 'text-yellow-500' : ($ranking->rank === 2 ? 'text-gray-400' : 'text-orange-500') }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Ma'lumot topilmadi</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($rankings->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $rankings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection