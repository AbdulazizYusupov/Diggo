@extends('Layout.app')

@section('title', 'Mening Guruhlarim')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Mening Guruhlarim
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Sizga biriktirilgan barcha guruhlar
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 rounded-xl px-4 py-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm font-semibold text-green-700 dark:text-green-400">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Faol guruhlar --}}
    @php
        $activeGroups   = $groups->where('is_active', true);
        $inactiveGroups = $groups->where('is_active', false);
    @endphp

    @if($groups->isEmpty())
        <div class="text-center py-20">
            <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Hech qanday guruh biriktirilmagan</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Admin tomonidan guruh biriktirilishini kuting</p>
        </div>
    @else

    {{-- Active Groups --}}
    @if($activeGroups->count())
    <div>
        <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>
            Faol guruhlar ({{ $activeGroups->count() }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($activeGroups as $group)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-4">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0">
                            <h3 class="font-bold text-white text-lg truncate">{{ $group->name }}</h3>
                            <p class="text-indigo-200 text-sm">{{ $group->course->title ?? 'N/A' }}</p>
                        </div>
                        @if($group->is_primary)
                        <span class="flex-shrink-0 ml-2 px-2 py-0.5 bg-white/20 text-white text-xs font-bold rounded-full">
                            Asosiy
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-5 space-y-3">
                    @if($group->days_label)
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $group->days_label }}</span>
                    </div>
                    @endif
                    @if($group->start_time)
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $group->start_time }}{{ $group->end_time ? ' — '.$group->end_time : '' }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>{{ $group->active_students_count }} o'quvchi</span>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="px-5 pb-2 flex items-center gap-4 text-center border-t border-gray-100 dark:border-gray-700 pt-3">
                    <div>
                        <p class="text-xl font-extrabold text-gray-900 dark:text-white">{{ $group->total_lessons }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jami dars</p>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-green-600 dark:text-green-400">{{ $group->completed_lessons }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Bajarildi</p>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-amber-500">{{ $group->total_lessons - $group->completed_lessons }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Qoldi</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex gap-2">
                    <a href="{{ route('teacher.groups.show', $group->id) }}"
                        class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ko'rish
                    </a>
                    <a href="{{ route('teacher.groups.lessons.index', $group->id) }}"
                        class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Darslar
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Inactive Groups --}}
    @if($inactiveGroups->count())
    <div>
        <h2 class="text-base font-bold text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>
            Arxivlangan guruhlar ({{ $inactiveGroups->count() }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($inactiveGroups as $group)
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 opacity-70">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-bold text-gray-700 dark:text-gray-300">{{ $group->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $group->course->title ?? 'N/A' }}</p>
                    </div>
                    <span class="px-2.5 py-1 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-bold rounded-full">Arxiv</span>
                </div>
                <div class="flex gap-3 text-center">
                    <div>
                        <p class="text-lg font-bold text-gray-600 dark:text-gray-400">{{ $group->completed_lessons }}</p>
                        <p class="text-xs text-gray-400">Bajarildi</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-600 dark:text-gray-400">{{ $group->active_students_count }}</p>
                        <p class="text-xs text-gray-400">O'quvchi</p>
                    </div>
                </div>
                <a href="{{ route('teacher.lessons.index', $group->id) }}"
                    class="mt-3 flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-semibold">
                    Darslar tarixi
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @endif

</div>
@endsection