@extends('Layout.app')

@section('title', 'Faoliyat natijalari')

@section('content')
    <div class="space-y-6 mb-4">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Icon Box --}}
                <div
                    class="hidden sm:flex p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Faoliyat natijalari
                        </h1>
                        {{-- Count Badge --}}
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20 shadow-sm">
                            {{ $totalResults }} ta natija
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Talabalar natijalari va coinlarni boshqarish
                    </p>
                </div>
            </div>

            {{-- Create Button --}}
            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 
               bg-emerald-500 hover:bg-emerald-600 text-white 
               dark:bg-emerald-600 dark:hover:bg-emerald-700
               border border-emerald-400/50 shadow-sm shadow-emerald-100 dark:shadow-none
               font-semibold rounded-xl transition-all duration-200 
               transform hover:scale-[1.02] active:scale-[0.98] group">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5 text-white group-hover:rotate-12 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="font-bold">Natija qo'shish</span>
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            {{-- Jami natijalar --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400">Jami
                            natijalar</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white mt-2">
                            {{ $totalResults }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Jami berilgan coinlar --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-amber-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400">Jami
                            coinlar</p>
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-2">
                            {{ number_format($totalCoinsAwarded) }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- O'rtacha coin --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400">O'rtacha
                            coin</p>
                        <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-2">
                            {{ round($avgCoinsEarned) }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Top talaba --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400">Top
                            talaba</p>
                        <p class="text-xl font-black text-purple-600 dark:text-purple-400 mt-2 truncate">
                            @if ($topPerformers->isNotEmpty())
                                {{ $topPerformers->first()->student->user->name ?? 'N/A' }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-purple-50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-4 lg:p-6 transition-all">
            <form method="GET" action="{{ route('activityresults.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

                    {{-- 1. Search (Yuqori qator, 1-ustun) --}}
                    <div class="relative lg:col-span-4 sm:col-span-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Talaba ismi..."
                            class="w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none text-sm">

                        @if (request('search'))
                            <a href="{{ route('activityresults.index', request()->except('search')) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    {{-- 2. Activity Filter (Yuqori qator, 2-ustun) --}}
                    <div class="lg:col-span-4">
                        <select name="activity_id"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">Faoliyat (Barchasi)</option>
                            @foreach ($activities as $activity)
                                <option value="{{ $activity->id }}"
                                    {{ request('activity_id') == $activity->id ? 'selected' : '' }}>
                                    {{ $activity->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Student Filter (Yuqori qator, 3-ustun) --}}
                    <div class="lg:col-span-4">
                        <select name="student_id"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">Talaba (Barchasi)</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Rank Filter (Pastki qator, 1-ustun) --}}
                    <div class="lg:col-span-3">
                        <input type="number" name="rank" value="{{ request('rank') }}" placeholder="Rang..."
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    {{-- 5. Coins From (Pastki qator, 2-ustun) --}}
                    <div class="lg:col-span-3">
                        <input type="number" name="coins_from" value="{{ request('coins_from') }}"
                            placeholder="Coindan..."
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    {{-- 6. Coins To (Pastki qator, 3-ustun) --}}
                    <div class="lg:col-span-3">
                        <input type="number" name="coins_to" value="{{ request('coins_to') }}"
                            placeholder="Coingacha..."
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    {{-- 7. Action Buttons (Pastki qator, 4-ustun) --}}
                    <div class="flex gap-2 lg:col-span-3 sm:col-span-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        <a href="{{ route('activityresults.index') }}"
                            class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-500 dark:text-gray-400 rounded-xl transition-all flex items-center justify-center active:scale-95"
                            title="Tozalash">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modern Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Talaba</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Faoliyat</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Olgan coin</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Jami coin</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Rang</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Sana</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Amallar</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($results as $result)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                {{-- Talaba --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if ($result->student->user->photo)
                                                <img src="{{ asset('storage/' . $result->student->user->photo) }}"
                                                    alt="{{ $result->student->user->full_name }}"
                                                    class="h-12 w-12 rounded-xl object-cover ring-2 ring-gray-100 dark:ring-gray-700 shadow-sm">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 flex items-center justify-center text-white font-bold text-sm tracking-wider shadow-lg shadow-purple-100 dark:shadow-none border border-purple-400">
                                                    {{ strtoupper(substr($result->student->user->name, 0, 1) . substr($result->student->user->surname, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 min-w-0">
                                            <div
                                                class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[200px]">
                                                {{ $result->student->user->full_name ?? 'Noma\'lum' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                @if ($result->student->user->is_active)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-green-600 dark:text-green-400">
                                                        <span
                                                            class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                        Faol
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 text-gray-500">
                                                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                        Nofaol
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Faoliyat --}}
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $result->activity->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ ucfirst($result->activity->type) }}
                                    </div>
                                </td>

                                {{-- Coinlar --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    @php
                                        $percentage =
                                            $result->activity->max_coins > 0
                                                ? ($result->earned_coins / $result->activity->max_coins) * 100
                                                : 0;
                                        $badgeColor =
                                            $percentage >= 90 ? 'emerald' : ($percentage >= 70 ? 'amber' : 'rose');
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-700 dark:bg-{{ $badgeColor }}-900/30 dark:text-{{ $badgeColor }}-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $result->earned_coins }}/{{ $result->activity->max_coins }}
                                    </span>
                                </td>

                                {{-- Jami Coinlar (Student) --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-semibold rounded-lg bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-700 dark:from-amber-900/30 dark:to-yellow-900/30 dark:text-amber-400 shadow-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ number_format($result->student->coins, 0, '.', ' ') }}
                                    </span>
                                </td>

                                {{-- Rang --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    @if ($result->rank)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg
                                        @if ($result->rank == 1) bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($result->rank == 2) bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400
                                        @elseif($result->rank == 3) bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                        @else bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 @endif">
                                            @if ($result->rank <= 3)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endif
                                            {{ $result->rank }}-o'rin
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">-</span>
                                    @endif
                                </td>

                                {{-- Sana --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $result->created_at->format('d.m.Y') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- View --}}
                                        <a href="{{ route('activityresults.show', $result->id) }}"
                                            class="p-2 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl transition-all duration-200 group/btn"
                                            title="Ko'rish">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('activityresults.edit', $result->id) }}"
                                            class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all duration-200 group/btn"
                                            title="Tahrirlash">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <button type="button"
                                            onclick="document.getElementById('delete-modal-{{ $result->id }}').classList.remove('hidden')"
                                            class="p-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all duration-200 group/btn"
                                            title="O'chirish">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        {{-- Delete Modal --}}
                                        <div id="delete-modal-{{ $result->id }}"
                                            class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
                                            <div
                                                class="relative w-full max-w-sm p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5 transform transition-all">
                                                <div class="text-center">
                                                    <div
                                                        class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 dark:bg-rose-500/10 mb-6 rotate-3">
                                                        <svg class="h-10 w-10 text-rose-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                    </div>

                                                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                                                        O'chirishni tasdiqlang</h3>
                                                    <p
                                                        class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                                                        Rostdan ham <span
                                                            class="font-bold text-slate-900 dark:text-white underline decoration-rose-500/30">{{ $result->student->user->name ?? 'N/A' }}</span>
                                                        natijasini o'chirmoqchimisiz?
                                                    </p>

                                                    <div class="grid grid-cols-2 gap-3">
                                                        <button type="button"
                                                            onclick="document.getElementById('delete-modal-{{ $result->id }}').classList.add('hidden')"
                                                            class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                                                            Bekor qilish
                                                        </button>
                                                        <form action="{{ route('activityresults.destroy', $result->id) }}"
                                                            method="POST" class="m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full px-5 py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 dark:shadow-none transition-all transform active:scale-95">
                                                                O'chirilsin
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Hech qanday
                                            natija topilmadi</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Qidiruv shartlarini
                                            o'zgartirib ko'ring yoki yangi natija qo'shing.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($results->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-4">
                {{ $results->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    <div id="createModal" onclick="if(event.target === this) this.classList.add('hidden')"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Yangi natija qo'shish</h2>
                </div>
                <button type="button" onclick="closeCreateModal()"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('activityresults.store') }}" method="POST" id="bulkResultForm" class="space-y-5">
                @csrf

                <!-- Activity Selection with Search -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Faoliyat <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="activity_id" id="activity_id_input" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-700 dark:text-gray-200 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all appearance-none">
                            <option value="" disabled selected>Faoliyatni tanlang</option>
                            @foreach ($activities as $activity)
                                <option value="{{ $activity->id }}">
                                    {{-- Barcha ma'lumotlar bitta qatorda --}}
                                    {{ $activity->name }} |
                                    Turi: {{ ucfirst($activity->type) }} |
                                    Sana: {{ $activity->date ? $activity->date->format('d.m.Y') : 'Sana yo\'q' }} |
                                    Max: {{ $activity->max_coins }}
                                    @if ($activity->group)
                                        | Guruh: {{ $activity->group->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Group Filter -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Guruh bo'yicha filtrlash
                    </label>
                    <select id="groupFilter" onchange="filterStudentsByGroup()"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all cursor-pointer">
                        <option value="">Barcha guruhlar</option>

                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">
                                {{ $group->name }}
                                @if ($group->course)
                                    — {{ $group->course->name }}
                                @endif
                                ({{ $group->active_students_count ?? $group->activeStudents()->count() }} talaba)
                                [{{ $group->start_time }}]
                                @if ($group->days_of_week)
                                    —
                                    {{ is_array($group->days_of_week) ? implode(',', $group->days_of_week) : $group->days_of_week }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Students Selection with Search -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Talabalar <span class="text-rose-500">*</span>
                        <span class="text-xs font-normal text-gray-500">(Ko'plab tanlash mumkin)</span>
                    </label>

                    <!-- Search Input -->
                    <div class="mb-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="studentSearchInput" oninput="filterStudentsInModal()"
                                placeholder="Talabalarni qidirish..."
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none text-sm">
                        </div>
                    </div>

                    <!-- Select All / Deselect All -->
                    <div class="flex items-center justify-between mb-3 p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span id="selectedStudentsCount">0</span> ta talaba tanlandi
                        </span>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectAllStudents()"
                                class="px-3 py-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-lg transition-all">
                                Barchasini tanlash
                            </button>
                            <button type="button" onclick="deselectAllStudents()"
                                class="px-3 py-1.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                                Tanlovni bekor qilish
                            </button>
                        </div>
                    </div>

                    <!-- Students List -->
                    <div
                        class="border border-gray-200 dark:border-gray-700 rounded-xl max-h-64 overflow-y-auto custom-scrollbar">
                        @foreach ($students as $student)
                            <label
                                class="student-checkbox-item flex items-center gap-3 p-3 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 cursor-pointer border-b border-gray-100 dark:border-gray-700/50 last:border-none transition-all"
                                {{-- Qidiruv uchun to'liq ism va guruhlarni qo'shamiz --}}
                                data-student-name="{{ strtolower(($student->user->surname ?? '') . ' ' . ($student->user->name ?? '') . ' ' . ($student->user->middle_name ?? '')) }}"
                                {{-- Many-to-many bo'lgani uchun guruh ID larini massiv ko'rinishida saqlaymiz --}}
                                data-student-groups="{{ json_encode($student->groups->pluck('id')) }}">

                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                    onchange="updateSelectedCount()"
                                    class="w-5 h-5 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3">
                                        {{-- Avatar --}}
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm text-white font-black text-xs uppercase">
                                            {{ Str::limit($student->user->surname ?? 'S', 1, '') }}{{ Str::limit($student->user->name ?? 'N', 1, '') }}
                                        </div>

                                        {{-- Ma'lumotlar --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                                {{ $student->user->surname ?? '' }} {{ $student->user->name ?? '' }}
                                                {{ $student->user->middle_name ?? '' }}
                                            </p>

                                            <div class="flex flex-wrap items-center gap-1 mt-0.5">
                                                @forelse ($student->groups as $group)
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                                        {{ $group->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-[10px] text-amber-500 italic">Guruhsiz</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div id="noStudentsFound" class="hidden p-4 text-center text-sm text-gray-500">
                        Talabalar topilmadi...
                    </div>
                </div>

                <!-- Earned Coins (same for all) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Olgan coinlar (hammasi uchun bir xil) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="earned_coins" id="earned_coins_input" required min="0"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none"
                            placeholder="Masalan: 85">
                        <p id="maxCoinsHint" class="mt-1 text-xs text-gray-500 dark:text-gray-400 hidden">
                            Maksimal: <span id="maxCoinsValue">0</span> coin
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Natija darajasi (Rank)
                        </label>
                        <div class="relative">
                            <select name="start_rank"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all appearance-none cursor-pointer">

                                {{-- Default holat: O'rin berilmaydi --}}
                                <option value="" selected>O'rin berilmasin (Oddiy ishtirokchi)</option>

                                {{-- Faqat asosiy g'oliblar --}}
                                <option value="1">🥇 1-o'rin (G'olib)</option>
                                <option value="2">🥈 2-o'rin (Sovrindor)</option>
                                <option value="3">🥉 3-o'rin (Sovrindor)</option>
                            </select>

                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <div
                            class="mt-2 flex items-start gap-2 p-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-100 dark:border-amber-800">
                            <svg class="w-4 h-4 text-amber-600 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-[11px] text-amber-700 dark:text-amber-400 leading-relaxed">
                                Agar yuqoridan o'rin tanlasangiz, tanlangan talabalarga <strong>ketma-ket</strong> o'rin
                                beriladi.
                                Tanlamasangiz, ular shunchaki ball (coin) olishadi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Note -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Izoh (hammasi uchun)
                    </label>
                    <textarea name="note" rows="2"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none resize-none"
                        placeholder="Qo'shimcha izoh..."></textarea>
                </div>

                <!-- Info Box -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-500/5 border border-blue-200 dark:border-blue-500/20 rounded-xl p-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-blue-900 dark:text-blue-300 mb-1">Eslatma</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-400 leading-relaxed">
                                Bir nechta talaba uchun bir xilda natija kiritiladi. Agar rang kiritilsa, talabalar
                                ketma-ketlikda rang oladi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeCreateModal()"
                        class="flex-1 px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor qilish
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all transform active:scale-95">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let maxCoins = 0;



            // Student functions
            function filterStudentsInModal() {
                const input = document.getElementById('studentSearchInput');
                const filter = input.value.toLowerCase();
                const items = document.querySelectorAll('.student-checkbox-item');
                const noResult = document.getElementById('noStudentsFound');
                let hasMatch = false;

                items.forEach(item => {
                    const name = item.dataset.studentName.toLowerCase();

                    if (name.includes(filter)) {
                        item.style.display = '';
                        hasMatch = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                noResult.classList.toggle('hidden', hasMatch);
            }

            function filterStudentsByGroup() {
                const groupId = document.getElementById('groupFilter').value;
                const items = document.querySelectorAll('.student-checkbox-item');
                const noResult = document.getElementById('noStudentsFound');
                let hasVisible = false;

                items.forEach(item => {
                    // data-student-groups ichidagi JSON ni o'qiymiz (massiv holatida)
                    let studentGroups = [];
                    try {
                        studentGroups = JSON.parse(item.dataset.studentGroups || '[]');
                    } catch (e) {
                        studentGroups = [];
                    }

                    // Agar guruh tanlanmagan bo'lsa hamma chiqadi
                    // Agar tanlangan bo'lsa, u massiv ichida bor-yo'qligi tekshiriladi
                    if (!groupId || studentGroups.includes(parseInt(groupId))) {
                        item.style.display = '';
                        hasVisible = true;
                    } else {
                        item.style.display = 'none';
                        // Agar yashirilayotgan talaba belgilangan bo'lsa, uni tanlovdan olib tashlash
                        // ixtiyoriy: item.querySelector('input').checked = false; 
                    }
                });

                if (noResult) {
                    noResult.classList.toggle('hidden', hasVisible);
                }

                updateSelectedCount();
            }

            function selectAllStudents() {
                // Faqat display:none bo'lmagan checkboxlarni topamiz
                const visibleCheckboxes = document.querySelectorAll(
                    '.student-checkbox-item:not([style*="display: none"]) input[type="checkbox"]');
                visibleCheckboxes.forEach(cb => cb.checked = true);
                updateSelectedCount();
            }

            function deselectAllStudents() {
                const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
                checkboxes.forEach(cb => cb.checked = false);
                updateSelectedCount();
            }

            // JavaScript qismiga qo'shish uchun:
            function updateSelectedCount() {
                const items = document.querySelectorAll('.student-checkbox-item');
                let count = 0;

                items.forEach(item => {
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    if (checkbox.checked) {
                        // Tanlangan bo'lsa stilni o'zgartiramiz
                        item.classList.add('bg-emerald-50', 'border-emerald-200', 'dark:bg-emerald-500/10');
                        count++;
                    } else {
                        item.classList.remove('bg-emerald-50', 'border-emerald-200', 'dark:bg-emerald-500/10');
                    }
                });

                document.getElementById('selectedStudentsCount').innerText = count;
            }

            function closeCreateModal() {
                document.getElementById('createModal').classList.add('hidden');
                document.getElementById('bulkResultForm').reset();
                deselectAllStudents();
                document.getElementById('selectedActivityLabel').innerText = 'Faoliyatni tanlang';
                document.getElementById('selectedActivityLabel').classList.add('text-gray-500');
                document.getElementById('selectedActivityLabel').classList.remove('text-gray-900', 'dark:text-white',
                    'font-medium');
                document.getElementById('maxCoinsHint').classList.add('hidden');
                document.getElementById('groupFilter').value = '';
                document.getElementById('studentSearchInput').value = '';
                filterStudentsByGroup();
                filterStudentsInModal();
            }


            // Form validation
            document.getElementById('bulkResultForm').addEventListener('submit', function(e) {
                const selectedStudents = document.querySelectorAll('input[name="student_ids[]"]:checked');
                if (selectedStudents.length === 0) {
                    e.preventDefault();
                    alert('Iltimos, kamida bitta talaba tanlang!');
                    return false;
                }

                const earnedCoins = parseInt(document.getElementById('earned_coins_input').value);
                if (maxCoins > 0 && earnedCoins > maxCoins) {
                    e.preventDefault();
                    alert(`Kiritilgan coinlar maksimal qiymatdan oshib ketdi! Maksimal: ${maxCoins}`);
                    return false;
                }
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Guruh filtri uchun
                const groupSelect = new TomSelect("#groupFilter", {
                    plugins: ['dropdown_input'],
                    create: false,
                    allowEmptyOption: true,
                    placeholder: "Guruhni tanlang...",
                    onChange: function(value) {
                        filterStudentsByGroup(); // Guruh o'zgarganda qidirish
                    }
                });

                // Faoliyat tanlash uchun
                const activitySelect = new TomSelect("#activity_id_input", {
                    plugins: ['dropdown_input'],
                    create: false,
                    allowEmptyOption: true,
                    placeholder: "Faoliyatni tanlang...",
                    onChange: function(value) {
                        // Faoliyat o'zgarganda talabalarni emas, max_coins ni yangilash kerak bo'lishi mumkin
                        // filterStudentsByGroup(); // Buni o'chirib tursangiz ham bo'ladi
                    }
                });
            });
        </script>
    @endpush
@endsection
