@extends('Layout.app')

@section('title', $activity->name)

@section('content')
    <div class="space-y-6">

        {{-- Back Button --}}
        <div>
            <a href="{{ route('student.activities.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                E'lonlarga qaytish
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Top accent line --}}
            @if($activity->is_today)
                <div class="h-1.5 bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-400"></div>
            @elseif($activity->is_upcoming)
                <div class="h-1.5 bg-gradient-to-r from-indigo-400 via-purple-500 to-indigo-400"></div>
            @else
                <div class="h-1.5 bg-gradient-to-r from-gray-300 via-gray-400 to-gray-300 dark:from-gray-700 dark:to-gray-600"></div>
            @endif

            {{-- Hero section --}}
            <div class="bg-gradient-to-br {{ $activity->type_light_bg }} p-6 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-start gap-5">

                    {{-- Type Icon --}}
                    <div class="flex-shrink-0 p-4 {{ $activity->type_icon_bg }} rounded-2xl shadow-lg self-start">
                        @if($activity->type === 'homework')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        @elseif($activity->type === 'exam')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        @elseif($activity->type === 'competition')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        @elseif($activity->type === 'project')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        {{-- Badges --}}
                        <div class="flex flex-wrap items-center gap-2 mb-3">

                            {{-- Type badge --}}
                            <span class="inline-flex items-center px-2.5 py-1 {{ $activity->type_bg }} {{ $activity->type_text }} text-xs font-bold rounded-lg border {{ $activity->type_border }}">
                                {{ $activity->type_label }}
                            </span>

                            {{-- Status badge --}}
                            @if($activity->is_today)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-lg border border-emerald-200 dark:border-emerald-700/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Bugun
                                </span>
                            @elseif($activity->is_upcoming)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-bold rounded-lg border border-indigo-200 dark:border-indigo-700/50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Kelgusi
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-bold rounded-lg border border-gray-200 dark:border-gray-600">
                                    O'tgan
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight">
                            {{ $activity->name }}
                        </h1>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="p-6 sm:p-8 space-y-6">

                {{-- Meta grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    {{-- Date --}}
                    <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="p-2.5 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg border border-indigo-100 dark:border-indigo-700/50">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Sana</p>
                            <p class="text-sm font-extrabold text-gray-900 dark:text-white">{{ $activity->date->format('d.m.Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->date->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Group --}}
                    <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="p-2.5 bg-purple-50 dark:bg-purple-900/30 rounded-lg border border-purple-100 dark:border-purple-700/50">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Guruh</p>
                            <p class="text-sm font-extrabold text-gray-900 dark:text-white">
                                {{ $activity->group?->name ?? 'Umumiy' }}
                            </p>
                            @unless($activity->group)
                                <p class="text-xs text-gray-500 dark:text-gray-400">Barcha guruhlar</p>
                            @endunless
                        </div>
                    </div>

                    {{-- Max Coins --}}
                    <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="p-2.5 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg border border-yellow-100 dark:border-yellow-700/50">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.93V18h-2v-1.07C9.39 16.59 8 15.42 8 14h2c0 .55.63 1 2 1s2-.45 2-1c0-.64-.39-1-2-1-2.39 0-4-1.03-4-3 0-1.42 1.39-2.59 3-2.93V6h2v1.07C14.61 7.41 16 8.58 16 10h-2c0-.55-.63-1-2-1s-2 .45-2 1c0 .64.39 1 2 1 2.39 0 4 1.03 4 3 0 1.42-1.39 2.59-3 2.93z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Max coin</p>
                            <p class="text-sm font-extrabold text-yellow-600 dark:text-yellow-400">
                                {{ $activity->max_coins > 0 ? $activity->max_coins . ' coin' : 'Belgilanmagan' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if($activity->description)
                    <div>
                        <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Tavsif
                        </h2>
                        <div class="p-5 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                            {{ $activity->description }}
                        </div>
                    </div>
                @else
                    <div class="p-5 bg-gray-50 dark:bg-gray-700/40 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-600 text-center">
                        <p class="text-sm text-gray-400 dark:text-gray-500 italic">Tavsif qo'shilmagan</p>
                    </div>
                @endif

                {{-- Countdown banner --}}
                @if($activity->days_left !== null)
                    <div class="p-5 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-200 dark:border-indigo-800/30 flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-300">
                            Bu faoliyatga <span class="font-extrabold">{{ $activity->days_left }} kun</span> qoldi
                            ({{ $activity->date->isoFormat('D MMMM, Y') }})
                        </p>
                    </div>
                @elseif($activity->is_today)
                    <div class="p-5 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800/30 flex items-center gap-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-300">
                            Bu faoliyat bugun! Omad tilaymiz 🎯
                        </p>
                    </div>
                @endif

            </div>
        </div>

        {{-- Bottom navigation --}}
        <div>
            <a href="{{ route('student.activities.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Barcha e'lonlar
            </a>
        </div>

    </div>
@endsection