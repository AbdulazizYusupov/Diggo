@extends('Layout.app')

@section('title', 'Faoliyatlar')

@section('content')
    <div class="space-y-6 mb-4">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Icon Box --}}
                <div
                    class="hidden sm:flex p-3 bg-purple-50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Faoliyatlar
                        </h1>
                        {{-- Count Badge --}}
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400 border border-purple-100 dark:border-purple-500/20 shadow-sm">
                            {{ $totalActivities }} ta faoliyat
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Uy ishi, imtihon va boshqa faoliyatlarni boshqarish
                    </p>
                </div>
            </div>

            {{-- Create Button --}}
            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 
               bg-purple-500 hover:bg-purple-600 text-white 
               dark:bg-purple-600 dark:hover:bg-purple-700
               border border-purple-400/50 shadow-sm shadow-purple-100 dark:shadow-none
               font-semibold rounded-xl transition-all duration-200 
               transform hover:scale-[1.02] active:scale-[0.98] group">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5 text-white group-hover:rotate-12 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="font-bold">Faoliyat qo'shish</span>
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            {{-- Jami faoliyatlar --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400">Jami
                            faoliyatlar</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white mt-2">
                            {{ $totalActivities }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-purple-50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Uy ishlari --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400">Uy ishlari
                        </p>
                        <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-2">
                            {{ $homeworkCount }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Imtihonlar --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-red-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-red-600 dark:text-red-400">Imtihonlar</p>
                        <p class="text-3xl font-black text-red-600 dark:text-red-400 mt-2">
                            {{ $examCount }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- O'rtacha coin --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-amber-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400">O'rtacha
                            coin</p>
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-2">
                            {{ round($avgCoins) }}
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
        </div>

        <!-- Filters Section -->
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-4 lg:p-6 transition-all">
            <form method="GET" action="{{ route('activities.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

                    {{-- 1. Search (Yuqori qator, 1-ustun) --}}
                    <div class="relative lg:col-span-4 sm:col-span-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Faoliyat nomi..."
                            class="w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none text-sm">

                        @if (request('search'))
                            <a href="{{ route('activities.index', request()->except('search')) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    {{-- 2. Type Filter (Yuqori qator, 2-ustun) --}}
                    <div class="lg:col-span-4">
                        <select name="type"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">Turi (Barchasi)</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Group Filter (Yuqori qator, 3-ustun) --}}
                    <div class="lg:col-span-4">
                        <select name="group_id"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">Guruh (Barchasi)</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}"
                                    {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} - {{ $group->course->title ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Date From (Pastki qator, 1-ustun) --}}
                    <div class="lg:col-span-4">
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    {{-- 5. Date To (Pastki qator, 2-ustun) --}}
                    <div class="lg:col-span-4">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    {{-- 6. Action Buttons (Pastki qator, 3-ustun) --}}
                    <div class="flex gap-2 lg:col-span-4 sm:col-span-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        <a href="{{ route('activities.index') }}"
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
                                Faoliyat</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Turi</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Guruh</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Sana</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Max Coin</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Amallar</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                {{-- Faoliyat nomi va icon --}}
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="flex items-center group">
                                        <div class="flex-shrink-0 h-10 w-10 lg:h-12 lg:w-12">
                                            <div
                                                class="h-10 w-10 lg:h-12 lg:w-12 rounded-xl 
                                            @if ($activity->type == 'exam') bg-red-100 dark:bg-red-500/20 border border-red-200 dark:border-red-500/30
                                            @elseif($activity->type == 'homework') bg-blue-100 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30
                                            @elseif($activity->type == 'competition') bg-purple-100 dark:bg-purple-500/20 border border-purple-200 dark:border-purple-500/30
                                            @elseif($activity->type == 'project') bg-green-100 dark:bg-green-500/20 border border-green-200 dark:border-green-500/30
                                            @else bg-gray-100 dark:bg-gray-500/20 border border-gray-200 dark:border-gray-500/30 @endif
                                            flex items-center justify-center transition-transform group-hover:scale-105">
                                                <svg class="w-6 h-6 
                                                @if ($activity->type == 'exam') text-red-700 dark:text-red-300
                                                @elseif($activity->type == 'homework') text-blue-700 dark:text-blue-300
                                                @elseif($activity->type == 'competition') text-purple-700 dark:text-purple-300
                                                @elseif($activity->type == 'project') text-green-700 dark:text-green-300
                                                @else text-gray-700 dark:text-gray-300 @endif"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="ml-3 lg:ml-4 min-w-0">
                                            <div
                                                class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                {{ $activity->name }}
                                            </div>
                                            @if ($activity->description)
                                                <div
                                                    class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate max-w-xs">
                                                    {{ Str::limit($activity->description, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Type --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg
                                    @if ($activity->type == 'exam') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                    @elseif($activity->type == 'homework') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($activity->type == 'competition') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($activity->type == 'project') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                    @else bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                        {{ ucfirst($activity->type) }}
                                    </span>
                                </td>

                                {{-- Group --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    @if ($activity->group)
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $activity->group->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $activity->group->course->title ?? '' }}
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Guruh yo'q</span>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($activity->date)->format('d.m.Y') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Max Coin --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $activity->max_coins }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- View --}}
                                        <a href="{{ route('activities.show', $activity->id) }}"
                                            class="p-2 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl transition-all duration-200 group/btn"
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
                                        {{-- <button type="button" onclick="openEditModal({{ json_encode($activity) }})"
                                            class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all duration-200 group/btn"
                                            title="Tahrirlash">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button> --}}
                                        <a href="{{ route('activities.edit', $activity->id) }}"
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
                                            onclick="document.getElementById('delete-modal-{{ $activity->id }}').classList.remove('hidden')"
                                            class="p-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all duration-200 group/btn"
                                            title="O'chirish">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        {{-- Delete Modal --}}
                                        <div id="delete-modal-{{ $activity->id }}"
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
                                                            class="font-bold text-slate-900 dark:text-white underline decoration-rose-500/30">{{ $activity->name }}</span>
                                                        faoliyatini tizimdan butunlay o'chirmoqchimisiz?
                                                    </p>

                                                    <div class="grid grid-cols-2 gap-3">
                                                        <button type="button"
                                                            onclick="document.getElementById('delete-modal-{{ $activity->id }}').classList.add('hidden')"
                                                            class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                                                            Bekor qilish
                                                        </button>
                                                        <form action="{{ route('activities.destroy', $activity->id) }}"
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Hech qanday
                                            faoliyat topilmadi</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Qidiruv shartlarini
                                            o'zgartirib ko'ring yoki yangi faoliyat qo'shing.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Pagination -->
    @if ($activities->hasPages())
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-4">
            {{ $activities->links() }}
        </div>
    @endif
    </div>
    <!-- Create Modal -->
    <div id="createModal" onclick="if(event.target === this) this.classList.add('hidden')"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="p-3 bg-purple-50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 rounded-2xl">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Yangi faoliyat qo'shish</h2>
                </div>
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('activities.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Faoliyat nomi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none"
                        placeholder="Masalan: Python asoslari bo'yicha test">
                </div>

                <!-- Type and Group -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Turi <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white outline-none transition-all cursor-pointer">
                            <option value="">Tanlang</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Guruh
                        </label>
                        <select name="group_id"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white outline-none transition-all cursor-pointer">
                            <option value="">Tanlang</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }} -
                                    {{ $group->course->title ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Date and Max Coins -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Sana <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="date" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Maksimal coin <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="max_coins" required min="0" max="1000"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none"
                            placeholder="Masalan: 100">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Tavsif
                    </label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none resize-none"
                        placeholder="Faoliyat haqida qo'shimcha ma'lumot..."></textarea>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="flex-1 px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor qilish
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-3.5 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-2xl shadow-lg shadow-purple-200 dark:shadow-none transition-all transform active:scale-95">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="p-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Faoliyatni tahrirlash</h2>
                </div>
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Faoliyat nomi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none">
                </div>

                <!-- Type and Group -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Turi <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" id="edit_type" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white outline-none transition-all cursor-pointer">
                            <option value="">Tanlang</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Guruh
                        </label>
                        <select name="group_id" id="edit_group_id"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white outline-none transition-all cursor-pointer">
                            <option value="">Tanlang</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }} -
                                    {{ $group->course->title ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Date and Max Coins -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Sana <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="date" id="edit_date" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Maksimal coin <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="max_coins" id="edit_max_coins" required min="0"
                            max="1000"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Tavsif
                    </label>
                    <textarea name="description" id="edit_description" rows="4"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none resize-none"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="flex-1 px-6 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor qilish
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl shadow-lg shadow-amber-200 dark:shadow-none transition-all transform active:scale-95">
                        Yangilash
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(activity) {
            document.getElementById('editForm').action = `/activities/${activity.id}`;
            document.getElementById('edit_name').value = activity.name;
            document.getElementById('edit_type').value = activity.type;
            document.getElementById('edit_group_id').value = activity.group_id || '';

            if (activity.date) {
                const dateObj = new Date(activity.date);
                const y = dateObj.getFullYear();
                const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                const d = String(dateObj.getDate()).padStart(2, '0');
                document.getElementById('edit_date').value = `${y}-${m}-${d}`;
            } else {
                document.getElementById('edit_date').value = '';
            }

            document.getElementById('edit_max_coins').value = activity.max_coins;
            document.getElementById('edit_description').value = activity.description || '';
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>

@endsection
