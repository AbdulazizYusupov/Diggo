@extends('Layout.app')

@section('title', 'O\'quvchilar')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center gap-4">
                {{-- Ikonka konteyneri --}}
                <div class="hidden sm:flex p-3.5 bg-indigo-500 rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                            O'quvchilar
                        </h1>
                        {{-- Statistik badge --}}
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-500/30 shadow-sm">
                            {{ number_format($totalStudents) }} ta
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        O'quvchini boshqarish va nazorat qilish
                    </p>
                </div>
            </div>

            {{-- Qo'shish tugmasi --}}
            <a href="{{ route('students.create') }}"
                class="group inline-flex items-center justify-center px-8 py-3.5 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none transform hover:scale-[1.02] active:scale-95 transition-all duration-300 gap-2 w-full sm:w-auto">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90 duration-300" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>O'quvchi qo'shish</span>
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            {{-- Total Students Card --}}
            <div
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Jami o'quvchilar</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ number_format($totalStudents) }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Active Students Card --}}
            <div
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Aktiv o'quvchilar</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">
                            {{ number_format($activeStudents) }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Balance Card --}}
            <div
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Umumiy balans</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2 truncate"
                            title="{{ number_format($totalBalance, 0, '.', ' ') }} so'm">
                            {{ number_format($totalBalance, 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">so'm</p>
                    </div>
                    <div
                        class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Coins Card --}}
            <div
                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Jami coinlar</p>
                        <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">
                            {{ number_format($totalCoins) }}
                        </p>
                    </div>
                    <div
                        class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-amber-100 to-yellow-100 dark:from-amber-900/30 dark:to-yellow-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 lg:p-6">
            <form method="GET" action="{{ route('students.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    {{-- Search Input --}}
                    <div class="relative sm:col-span-2">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ism, telefon yoki manzil..."
                            class="w-full pl-10 pr-10 px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all">

                        @if (request('search'))
                            <a href="{{ route('students.index', request()->except('search')) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors"
                                title="Qidiruvni tozalash">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    {{-- Gender Filter --}}
                    <select name="gender"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white text-sm cursor-pointer transition-all">
                        <option value="">Barcha jinslar</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Erkak</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Ayol</option>
                    </select>

                    {{-- Balance Filter --}}
                    <select name="balance_range"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white text-sm cursor-pointer transition-all">
                        <option value="">Balans bo'yicha</option>
                        <option value="positive" {{ request('balance_range') === 'positive' ? 'selected' : '' }}>Ijobiy
                            (&gt;0)</option>
                        <option value="zero" {{ request('balance_range') === 'zero' ? 'selected' : '' }}>Nol (=0)
                        </option>
                        <option value="negative" {{ request('balance_range') === 'negative' ? 'selected' : '' }}>Qarzdor
                            (&lt;0)</option>
                    </select>

                    {{-- Groups Count Filter --}}
                    <select name="groups_count"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white text-sm cursor-pointer transition-all">
                        <option value="">Guruhlar soni</option>
                        <option value="none" {{ request('groups_count') === 'none' ? 'selected' : '' }}>Guruhlarsiz
                        </option>
                        <option value="one" {{ request('groups_count') === 'one' ? 'selected' : '' }}>1 ta guruh
                        </option>
                        <option value="multiple" {{ request('groups_count') === 'multiple' ? 'selected' : '' }}>Ko'p
                            guruhlar</option>
                    </select>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="hidden sm:inline">Qidirish</span>
                        </button>

                        <a href="{{ route('students.index') }}"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all flex items-center justify-center shadow-sm hover:shadow-md"
                            title="Filtrlarni tozalash">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Students Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                O'quvchi
                            </th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Telefon
                            </th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Balans
                            </th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Coinlar
                            </th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Guruhlar
                            </th>
                            <th scope="col"
                                class="px-4 lg:px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Amallar
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($students as $student)
                            <tr
                                class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent dark:hover:from-gray-700/50 dark:hover:to-transparent transition-all duration-200">
                                {{-- Student Info --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if ($student->user->photo)
                                                <img src="{{ asset('storage/' . $student->user->photo) }}"
                                                    alt="{{ $student->user->full_name }}"
                                                    class="h-12 w-12 rounded-xl object-cover ring-2 ring-gray-100 dark:ring-gray-700 shadow-sm">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-xl bg-indigo-500 flex items-center justify-center text-white font-bold text-sm tracking-wider shadow-lg shadow-indigo-100 dark:shadow-none border border-indigo-400">
                                                    {{ strtoupper(substr($student->user->name, 0, 1) . substr($student->user->surname, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 min-w-0">
                                            <div
                                                class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[200px]">
                                                {{ $student->user->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                @if ($student->user->is_active)
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

                                {{-- Phone & City --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $student->user->phone }}
                                    </div>
                                    @if ($student->user->city)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[150px]">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $student->user->city }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Balance --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-bold {{ $student->balance >= 0 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ number_format($student->balance, 0, '.', ' ') }}
                                    </div>
                                </td>

                                {{-- Coins --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-semibold rounded-lg bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-700 dark:from-amber-900/30 dark:to-yellow-900/30 dark:text-amber-400 shadow-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ number_format($student->coins, 0, '.', ' ') }}
                                    </span>
                                </td>

                                {{-- Groups --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-semibold rounded-lg bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8h4m-4 0v4m-2-4a2 2 0 11-4 0 2 2 0 014 0zM9 2a2 2 0 11-4 0 2 2 0 014 0zm7 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        {{ $student->groups_count ?? 0 }} ta
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('students.show', $student->id) }}"
                                            class="group p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200 hover:scale-110"
                                            title="Ko'rish">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}"
                                            class="group p-2 text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200 hover:scale-110"
                                            title="Tahrirlash">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                            onclick="document.getElementById('delete-modal-{{ $student->id }}').classList.remove('hidden')"
                                            class="group p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200 hover:scale-110"
                                            title="O'chirish">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Delete Confirmation Modal --}}
                                    <div id="delete-modal-{{ $student->id }}"
                                        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all"
                                        onclick="if(event.target === this) this.classList.add('hidden')">

                                        <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col transform transition-all"
                                            style="max-height: 90vh;" onclick="event.stopPropagation()">

                                            {{-- Close Button --}}
                                            <button type="button"
                                                onclick="document.getElementById('delete-modal-{{ $student->id }}').classList.add('hidden')"
                                                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors z-10">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>

                                            <div class="overflow-y-auto p-8" style="scrollbar-width: thin;">
                                                <div class="text-center">

                                                    {{-- Modal Content --}}
                                                    <div
                                                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30 mb-6 flex-shrink-0">
                                                        <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.27 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                    </div>

                                                    <h3
                                                        class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                                                        O'chirishni tasdiqlaysizmi?
                                                    </h3>

                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 whitespace-normal break-words leading-relaxed"
                                                        style="word-wrap: break-word;">
                                                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                                                            {{ $student->user->full_name }}
                                                        </span>
                                                        ni o'chirishni xohlaysizmi? Bu amalni qaytarib bo'lmaydi!
                                                        {{-- Agar bu yerga qo'shimcha ogohlantirish matnlari kelsa, modal pastga kengayaveradi --}}
                                                    </p>

                                                    {{-- Action Buttons --}}
                                                    <div class="flex flex-wrap sm:flex-nowrap gap-3 mt-auto">
                                                        <button type="button"
                                                            onclick="document.getElementById('delete-modal-{{ $student->id }}').classList.add('hidden')"
                                                            class="flex-1 min-w-[120px] px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all active:scale-95">
                                                            Bekor qilish
                                                        </button>

                                                        <form action="{{ route('students.destroy', $student->id) }}"
                                                            method="POST" class="flex-1 min-w-[120px] m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium rounded-xl transition-all shadow-lg hover:shadow-xl active:scale-95">
                                                                O'chirish
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
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            O'quvchilar topilmadi
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                                            Hozircha hech qanday o'quvchi mavjud emas. Yangi o'quvchi qo'shish uchun
                                            yuqoridagi tugmani bosing.
                                        </p>
                                        <a href="{{ route('students.create') }}"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            O'quvchi qo'shish
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
        </div>
        @if ($students->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-4 mt-6">
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection
