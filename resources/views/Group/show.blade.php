@extends('Layout.app')

@section('title', $group->name)

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <!-- Left: Group Info -->
            <div class="flex items-start gap-4 lg:gap-6">
                {{-- Group Icon --}}
                <div
                    class="flex-shrink-0 w-16 h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-3xl shadow-xl shadow-blue-200 dark:shadow-none flex items-center justify-center transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    {{-- Group Name & Status --}}
                    <div class="flex items-start gap-3 mb-2">
                        <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white">
                            {{ $group->name }}
                        </h1>
                        @if ($group->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 shadow-sm">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Faol
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Nofaol
                            </span>
                        @endif
                    </div>

                    {{-- Course Info --}}
                    @if ($group->course)
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-bold rounded-xl bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ $group->course->title }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-mono">
                                {{ $group->course->code }}
                            </span>
                        </div>
                    @endif

                    {{-- Schedule Info --}}
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-bold">
                                {{ \Carbon\Carbon::parse($group->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($group->end_time)->format('H:i') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-bold">
                                {{ $group->start_date->format('d.m.Y') }} - {{ $group->end_date->format('d.m.Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex items-center gap-2 lg:gap-3">
                <a href="{{ route('groups.index') }}"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Orqaga</span>
                </a>
                <a href="{{ route('groups.students.index', ['group' => $group->id]) }}"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-purple-200 dark:shadow-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="hidden sm:inline">Talabalar</span>
                </a>
                <a href="{{ route('groups.teachers.index', ['group' => $group->id]) }}"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-blue-200 dark:shadow-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="hidden sm:inline">O'qituvchilar</span>
                </a>

                <a href="{{ route('groups.edit', $group->id) }}"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-amber-500 hover:bg-amber-600 dark:bg-amber-600 dark:hover:bg-amber-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-amber-200 dark:shadow-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="hidden sm:inline">Tahrirlash</span>
                </a>

                <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-rose-500 hover:bg-rose-600 dark:bg-rose-600 dark:hover:bg-rose-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-rose-200 dark:shadow-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="hidden sm:inline">O'chirish</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            {{-- Total Students --}}
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100/50 dark:from-purple-500/10 dark:to-purple-500/5 rounded-2xl shadow-sm border border-purple-200 dark:border-purple-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-purple-500 dark:bg-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400 mb-1">Talabalar
                </p>
                <p class="text-3xl lg:text-4xl font-black text-purple-600 dark:text-purple-400">
                    {{ $stats['total_students'] }}
                </p>
            </div>

            {{-- Total Teachers --}}
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-500/5 rounded-2xl shadow-sm border border-blue-200 dark:border-blue-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400 mb-1">O'qituvchilar
                </p>
                <p class="text-3xl lg:text-4xl font-black text-blue-600 dark:text-blue-400">
                    {{ $stats['total_teachers'] }}
                </p>
            </div>

            {{-- Capacity Usage --}}
            <div
                class="bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-500/10 dark:to-amber-500/5 rounded-2xl shadow-sm border border-amber-200 dark:border-amber-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-amber-500 dark:bg-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400 mb-1">Sig'im
                </p>
                <p class="text-2xl lg:text-3xl font-black text-amber-600 dark:text-amber-400">
                    {{ $stats['capacity_used'] }}%
                </p>
            </div>

            {{-- Days Count --}}
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-500/5 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-emerald-500 dark:bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1">Dars
                    kunlari</p>
                <p class="text-3xl lg:text-4xl font-black text-emerald-600 dark:text-emerald-400">
                    {{ $stats['days_count'] }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Schedule & Details -->
            <div class="lg:col-span-2 space-y-6">
                {{-- Schedule Card --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Dars jadvali</h2>
                    </div>

                    <div class="space-y-4">
                        {{-- Days of Week --}}
                        <div class="flex flex-wrap gap-2">
                            @php
                                $dayNames = [
                                    'monday' => 'Dushanba',
                                    'tuesday' => 'Seshanba',
                                    'wednesday' => 'Chorshanba',
                                    'thursday' => 'Payshanba',
                                    'friday' => 'Juma',
                                    'saturday' => 'Shanba',
                                    'sunday' => 'Yakshanba',
                                ];
                            @endphp
                            @foreach ($group->days_of_week ?? [] as $day)
                                <span
                                    class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30">
                                    {{ $dayNames[$day] ?? $day }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Time & Duration --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                            <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <p
                                    class="text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400 mb-2">
                                    Dars vaqti</p>
                                <p class="text-lg font-black text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($group->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($group->end_time)->format('H:i') }}
                                </p>
                            </div>

                            <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <p
                                    class="text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400 mb-2">
                                    Sig'im</p>
                                <p class="text-lg font-black text-gray-900 dark:text-white">
                                    {{ $stats['total_students'] }} / {{ $group->capacity }} ta talaba
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Students List --}}
                @if ($group->activeStudents->count() > 0)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">Talabalar</h2>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400">
                                {{ $stats['total_students'] }} ta talaba
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach ($group->activeStudents->take(5) as $student)
                                <div
                                    class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                                            <span class="text-white font-bold text-sm">
                                                {{ substr($student->user->name, 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p
                                                class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                {{ $student->user->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $student->user->phone ?? 'Telefon yo\'q' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($group->activeStudents->count() > 5)
                            <div class="mt-4 text-center">
                                <span class="text-sm font-bold text-gray-500 dark:text-gray-400">
                                    va yana {{ $group->activeStudents->count() - 5 }} ta talaba...
                                </span>
                            </div>
                        @endif
                    </div>
                @else
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6 lg:p-8">
                        <div class="text-center py-8">
                            <div
                                class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Talabalar yo'q</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Bu guruhda hali talabalar yo'q</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Group Info -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6 lg:p-8 sticky top-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4">Guruh ma'lumotlari</h3>
                        <div class="space-y-3">
                            {{-- Capacity --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Sig'im:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $group->capacity }} ta
                                </span>
                            </div>

                            {{-- Students Count --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Talabalar:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $stats['total_students'] }} ta
                                </span>
                            </div>

                            {{-- Status --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Holat:</span>
                                @if ($group->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Faol
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Nofaol
                                    </span>
                                @endif
                            </div>

                            {{-- Created At --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Yaratilgan:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $group->created_at->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Tezkor amallar</h4>
                        <div class="space-y-2">
                            <a href="{{ route('groups.edit', $group->id) }}"
                                class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-xl transition-all group">
                                <div
                                    class="w-10 h-10 bg-amber-100 dark:bg-amber-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Tahrirlash</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Guruh ma'lumotlarini o'zgartirish
                                    </p>
                                </div>
                            </a>

                            @if ($group->course)
                                <a href="{{ route('courses.show', $group->course->id) }}"
                                    class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-purple-500/10 rounded-xl transition-all group">
                                    <div
                                        class="w-10 h-10 bg-purple-100 dark:bg-purple-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">Kursga o'tish</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $group->course->title }}
                                        </p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
        <div
            class="relative w-full max-w-sm p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5 transform transition-all">
            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 dark:bg-rose-500/10 mb-6 rotate-3">
                    <svg class="h-10 w-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                    O'chirishni tasdiqlang</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Rostdan ham <span
                        class="font-bold text-slate-900 dark:text-white underline decoration-rose-500/30">{{ $group->name }}</span>
                    guruhini tizimdan butunlay o'chirmoqchimisiz?
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor qilish
                    </button>
                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="m-0">
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
@endsection
