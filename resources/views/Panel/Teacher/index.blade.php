@extends('Layout.app')

@section('title', 'O\'qituvchi Paneli')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Xush kelibsiz, {{ $teacher->user->name ?? 'O\'qituvchi' }}!
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $today->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <a href="{{ route('teacher.groups.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all shadow-sm text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Guruhlarim
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Guruhlar</p>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $groups->count() }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">O'tkazilgan (bu oy)</p>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $completedCount }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Rejalashtirilgan</p>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $scheduledCount }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Jami o'quvchilar</p>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $totalStudents }}</p>
        </div>
    </div>

    {{-- Bugungi darslar --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 dark:text-white text-lg flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
                Bugungi darslar
            </h2>
            <span class="text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2.5 py-1 rounded-full">
                {{ $todayLessons->count() }} ta
            </span>
        </div>

        @if($todayLessons->isEmpty())
            <div class="py-12 text-center">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Bugun dars yo'q</p>
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($todayLessons as $lesson)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="text-center min-w-[52px]">
                            <p class="text-base font-bold text-gray-900 dark:text-white">{{ $lesson->start_time }}</p>
                            @if($lesson->end_time)
                            <p class="text-xs text-gray-400">{{ $lesson->end_time }}</p>
                            @endif
                        </div>
                        <div class="w-px h-10 bg-gray-200 dark:bg-gray-700"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $lesson->topic ?? 'Mavzu belgilanmagan' }}</p>
                            <p class="text-sm text-indigo-600 dark:text-indigo-400">{{ $lesson->group->name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusMap = [
                                'scheduled'  => ['label' => 'Rejalashtirilgan', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
                                'completed'  => ['label' => 'Bajarildi',        'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
                                'cancelled'  => ['label' => 'Bekor qilindi',   'class' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'],
                            ];
                            $si = $statusMap[$lesson->status] ?? ['label' => $lesson->status, 'class' => 'bg-gray-100 text-gray-600'];
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $si['class'] }}">{{ $si['label'] }}</span>
                        <a href="{{ route('teacher.attendance.show', [$lesson->group_id, $lesson->id]) }}"
                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-colors">
                            Yuqlama
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Keyingi darslar + Guruhlar --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Upcoming lessons --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-bold text-gray-900 dark:text-white">Keyingi darslar</h2>
            </div>
            @if($upcomingLessons->isEmpty())
                <div class="py-10 text-center">
                    <p class="text-gray-400 dark:text-gray-500 text-sm">Rejalashtirilgan darslar yo'q</p>
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($upcomingLessons as $lesson)
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $lesson->topic ?? 'Mavzu yo\'q' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $lesson->group->name ?? '' }} •
                                {{ \Carbon\Carbon::parse($lesson->lesson_date)->translatedFormat('d M') }},
                                {{ $lesson->start_time }}
                            </p>
                        </div>
                        <a href="{{ route('teacher.attendance.show', [$lesson->group_id, $lesson->id]) }}"
                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- My groups --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-bold text-gray-900 dark:text-white">Guruhlarim</h2>
            </div>
            @if($groups->isEmpty())
                <div class="py-10 text-center">
                    <p class="text-gray-400 dark:text-gray-500 text-sm">Guruh biriktirilmagan</p>
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($groups as $group)
                    <a href="{{ route('teacher.groups.show', $group->id) }}"
                        class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(mb_substr($group->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $group->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $group->course->title ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                {{ $group->active_students_count }} o'q.
                            </span>
                            @if($group->is_active)
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection