@extends('Layout.app')

@section('title', $group->name . ' — Guruh')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('teacher.groups.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            Guruhlarim
        </a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 dark:text-white font-medium">{{ $group->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $group->name }}</h1>
                    @if($group->is_active)
                    <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded-lg">Faol</span>
                    @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 text-xs font-bold rounded-lg">Arxiv</span>
                    @endif
                </div>
                <p class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ $group->course->title ?? 'N/A' }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('teacher.groups.lessons.index', $group->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Darslar
                </a>
                @if($group->is_active)
                <a href="{{ route('teacher.groups.lessons.create', $group->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Yangi dars
                </a>
                @endif
            </div>
        </div>

        {{-- Group meta --}}
        <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-6 text-sm text-gray-600 dark:text-gray-400">
            @if(!empty($daysOfWeek))
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ implode(', ', $daysOfWeek) }}</span>
            </div>
            @endif
            @if($group->start_time)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $group->start_time }}{{ $group->end_time ? ' — '.$group->end_time : '' }}</span>
            </div>
            @endif
            @if($group->start_date)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>{{ \Carbon\Carbon::parse($group->start_date)->format('d.m.Y') }}{{ $group->end_date ? ' — '.\Carbon\Carbon::parse($group->end_date)->format('d.m.Y') : '' }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm text-center">
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $totalLessons }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jami darslar</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm text-center">
            <p class="text-3xl font-extrabold text-green-600 dark:text-green-400">{{ $completedLessons }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bajarildi</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm text-center">
            <p class="text-3xl font-extrabold text-amber-500">{{ $scheduledLessons }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rejalashtirilgan</p>
        </div>
    </div>

    {{-- Latest lessons + Students --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Latest 5 lessons --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-bold text-gray-900 dark:text-white">So'nggi darslar</h2>
                <a href="{{ route('teacher.groups.lessons.index', $group->id) }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">
                    Hammasi →
                </a>
            </div>
            @if($lessons->isEmpty())
                <div class="py-12 text-center">
                    <p class="text-gray-400 dark:text-gray-500 text-sm">Darslar mavjud emas</p>
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($lessons->take(5) as $lesson)
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                {{ $lesson->topic ?? 'Mavzu belgilanmagan' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($lesson->lesson_date)->translatedFormat('d M Y') }}
                                • {{ $lesson->start_time }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 ml-3">
                            @php
                                $statusColors = [
                                    'scheduled'  => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'completed'  => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'cancelled'  => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                                    'rescheduled'=> 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                                ];
                                $statusLabels = ['scheduled'=>'Rejalashtirilgan','completed'=>'Bajarildi','cancelled'=>'Bekor','rescheduled'=>'Siljitildi'];
                            @endphp
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $statusColors[$lesson->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $statusLabels[$lesson->status] ?? $lesson->status }}
                            </span>
                            <a href="{{ route('teacher.attendance.show', [$group->id, $lesson->id]) }}"
                                class="p-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition-colors"
                                title="Yuqlama">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Students list --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-bold text-gray-900 dark:text-white">O'quvchilar</h2>
                <span class="text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2.5 py-1 rounded-full">
                    {{ $students->count() }} kishi
                </span>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @forelse($students as $i => $student)
                <div class="flex items-center gap-3 px-5 py-3">
                    <span class="w-6 text-xs text-gray-400 font-bold text-center">{{ $i + 1 }}</span>
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(mb_substr($student->user->surname ?? 'S', 0, 1)) }}{{ strtoupper(mb_substr($student->user->name ?? 'N', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                            {{ $student->user->surname ?? '' }} {{ $student->user->name ?? '' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $student->user->phone ?? '' }}</p>
                    </div>
                    <div class="flex items-center gap-1 text-amber-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-bold">{{ $student->coins ?? 0 }}</span>
                    </div>
                </div>
                @empty
                <div class="py-12 text-center">
                    <p class="text-gray-400 dark:text-gray-500 text-sm">O'quvchilar mavjud emas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection