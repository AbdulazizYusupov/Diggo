@extends('Layout.app')

@section('title', $group->name . ' — Guruh tafsilotlari')

@section('content')
    @push('styles')
        <style>
            .ld-scroll::-webkit-scrollbar {
                width: 5px
            }

            .ld-scroll::-webkit-scrollbar-track {
                background: transparent
            }

            .ld-scroll::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 99px
            }

            .dark .ld-scroll::-webkit-scrollbar-thumb {
                background: #4b5563
            }

            .ldesc pre {
                position: relative;
                background: #eefbf0;
                border: 1.5px solid #c8e6c9;
                border-radius: 12px;
                margin: 0;
                overflow: hidden
            }

            .dark .ldesc pre {
                background: #1a2b1e;
                border-color: #2d4a35
            }

            .ldesc .cb-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 6px 12px;
                background: #d7f0da;
                border-bottom: 1px solid #c8e6c9;
                border-radius: 12px 12px 0 0
            }

            .dark .ldesc .cb-head {
                background: #1f3624;
                border-color: #2d4a35
            }

            .ldesc .cb-lang {
                font-family: sans-serif;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .07em;
                text-transform: uppercase;
                color: #2e7d32
            }

            .dark .ldesc .cb-lang {
                color: #66bb6a
            }

            .ldesc .cb-copy {
                display: flex;
                align-items: center;
                gap: 4px;
                padding: 3px 8px;
                border: none;
                border-radius: 6px;
                background: transparent;
                cursor: pointer;
                font-family: sans-serif;
                font-size: 11px;
                color: #388e3c;
                transition: background .15s
            }

            .dark .ldesc .cb-copy {
                color: #66bb6a
            }

            .ldesc .cb-copy:hover {
                background: rgba(0, 0, 0, .07)
            }

            .dark .ldesc .cb-copy:hover {
                background: rgba(255, 255, 255, .07)
            }

            .ldesc .cb-copy svg {
                width: 14px;
                height: 14px
            }

            .ldesc pre code {
                display: block;
                padding: 12px 14px;
                overflow-x: auto;
                font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
                font-size: 12.5px;
                line-height: 1.75;
                color: #202124;
                background: transparent
            }

            .dark .ldesc pre code {
                color: #e0e0e0
            }

            .ldesc pre code::-webkit-scrollbar {
                height: 4px
            }

            .ldesc pre code::-webkit-scrollbar-thumb {
                background: #a5d6a7;
                border-radius: 4px
            }

            .dark .ldesc pre code::-webkit-scrollbar-thumb {
                background: #2d4a35
            }

            /* Telegram syntax colors */
            .tk-dt {
                color: #9c27b0
            }

            .dark .tk-dt {
                color: #ce93d8
            }

            .tk-tg {
                color: #e67c00
            }

            .dark .tk-tg {
                color: #ffb74d
            }

            .tk-at {
                color: #0e8a16
            }

            .dark .tk-at {
                color: #81c784
            }

            .tk-st {
                color: #1565c0
            }

            .dark .tk-st {
                color: #64b5f6
            }

            .tk-cm {
                color: #9aa0a6;
                font-style: italic
            }

            .dark .tk-cm {
                color: #607d8b;
                font-style: italic
            }

            .tk-pu {
                color: #78909c
            }

            .dark .tk-pu {
                color: #78909c
            }

            .tk-sl {
                color: #e67c00
            }

            .dark .tk-sl {
                color: #ffb74d
            }

            .tk-pr {
                color: #0e8a16
            }

            .dark .tk-pr {
                color: #81c784
            }

            .tk-vl {
                color: #1565c0
            }

            .dark .tk-vl {
                color: #64b5f6
            }

            .tk-un {
                color: #e67c00
            }

            .dark .tk-un {
                color: #ffb74d
            }

            .tk-kw {
                color: #9c27b0
            }

            .dark .tk-kw {
                color: #ce93d8
            }

            .tk-va {
                color: #e67c00
            }

            .dark .tk-va {
                color: #ffb74d
            }

            .tk-fn {
                color: #1565c0
            }

            .dark .tk-fn {
                color: #64b5f6
            }

            .tk-nu {
                color: #0e8a16
            }

            .dark .tk-nu {
                color: #81c784
            }

            .ldesc p {
                margin: 0;
                color: #374151
            }

            .dark .ldesc p {
                color: #d1d5db
            }

            .ldesc p code {
                background: #e8f5e9;
                color: #c62828;
                padding: 1px 5px;
                border-radius: 4px;
                font-family: 'JetBrains Mono', monospace;
                font-size: 12px
            }

            .dark .ldesc p code {
                background: #1a2b1e;
                color: #ffab91
            }
        </style>
    @endpush
    <div class="space-y-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('student.groups') }}"
                class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                Guruhlarim
            </a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-900 dark:text-white font-medium truncate">{{ $group->name }}</span>
        </nav>

        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                            {{ $group->name }}
                        </h1>
                        @php
                            $statusMap = [
                                'active' => [
                                    'label' => 'Faol',
                                    'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                ],
                                'completed' => [
                                    'label' => 'Tugallangan',
                                    'class' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                ],
                                'transferred' => [
                                    'label' => "O'tkazilgan",
                                    'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                ],
                                'dropped' => [
                                    'label' => 'Tark etilgan',
                                    'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                ],
                            ];
                            $statusInfo = $statusMap[$groupStudent->status] ?? [
                                'label' => $groupStudent->status,
                                'class' => 'bg-gray-100 text-gray-600',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $statusInfo['class'] }}">
                            {{ $statusInfo['label'] }}
                        </span>
                    </div>
                    <p class="text-indigo-600 dark:text-indigo-400 font-semibold text-base">
                        {{ $group->course->title ?? 'N/A' }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-4 sm:text-right text-sm text-gray-600 dark:text-gray-400">
                    @if ($group->start_date)
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Boshlangan</p>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($group->start_date)->format('d.m.Y') }}</p>
                        </div>
                    @endif
                    @if ($group->end_date)
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Tugash</p>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($group->end_date)->format('d.m.Y') }}</p>
                        </div>
                    @endif
                    @if ($groupStudent->joined_at)
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Qo'shilgan</p>
                            <p class="font-semibold text-gray-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($groupStudent->joined_at)->format('d.m.Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Group Meta Info --}}
            <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-6">
                @if (!empty($daysOfWeek))
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ implode(', ', $daysOfWeek) }}</span>
                    </div>
                @endif

                @if ($group->start_time)
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $group->start_time }}{{ $group->end_time ? ' — ' . $group->end_time : '' }}</span>
                    </div>
                @endif

                @if ($group->teachers && $group->teachers->count())
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ $group->teachers->map(fn($t) => ($t->user->name ?? '') . ' ' . ($t->user->surname ?? ''))->map('trim')->join(', ') }}</span>
                    </div>
                @endif

                @if ($group->capacity)
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $group->capacity }} o'rin</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Attendance Rate --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm text-center">
                @php
                    $rate = $stats['attendance_rate'];
                    $rateColor =
                        $rate >= 80
                            ? 'text-green-600 dark:text-green-400'
                            : ($rate >= 60
                                ? 'text-amber-600 dark:text-amber-400'
                                : 'text-red-600 dark:text-red-400');
                @endphp
                <div class="relative inline-flex items-center justify-center w-16 h-16 mb-3">
                    <svg class="w-16 h-16 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor"
                            stroke-width="2.5" class="text-gray-200 dark:text-gray-700" />
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-dasharray="{{ $rate }} {{ 100 - $rate }}"
                            stroke-linecap="round"
                            class="{{ $rate >= 80 ? 'text-green-500' : ($rate >= 60 ? 'text-amber-500' : 'text-red-500') }}" />
                    </svg>
                    <span class="absolute text-sm font-bold {{ $rateColor }}">{{ $rate }}%</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Davomat</p>
            </div>

            {{-- Total Lessons --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm text-center flex flex-col items-center justify-center">
                <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['total_lessons'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Jami darslar</p>
                <div class="mt-2 flex gap-2 justify-center text-xs">
                    <span class="text-green-600 dark:text-green-400 font-semibold">{{ $stats['present'] }} keldi</span>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <span class="text-red-500 font-semibold">{{ $stats['absent'] }} kelmadi</span>
                </div>
            </div>

            {{-- Avg Homework --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm text-center flex flex-col items-center justify-center">
                <p class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $stats['avg_homework'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">O'rtacha uy ishi</p>
                <div class="mt-2 flex gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-3 h-3 {{ $i <= round($stats['avg_homework'] / 2) ? 'text-indigo-400' : 'text-gray-300 dark:text-gray-600' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
            </div>

            {{-- Avg Activity --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm text-center flex flex-col items-center justify-center">
                <p class="text-3xl font-extrabold text-purple-600 dark:text-purple-400">{{ $stats['avg_activity'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">O'rtacha faollik</p>
                @if ($stats['late'] > 0)
                    <p class="mt-2 text-xs text-amber-500 font-semibold">{{ $stats['late'] }} marta kechikdi</p>
                @endif
            </div>
        </div>

        {{-- Main Content: Lessons + Groupmates --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Lessons Table --}}
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 dark:text-white text-lg">Darslar tarixi</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $lessons->total() }} ta dars</span>
                </div>

                @if ($lessons->isEmpty())
                    <div class="py-16 text-center">
                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Hali darslar yo'q</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Sana</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Mavzu</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Davomat</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Uy ishi</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Faollik</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        lesson description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($lessons as $lesson)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">
                                                {{ \Carbon\Carbon::parse($lesson->lesson_date)->format('d.m.Y') }}
                                            </span>
                                            @if ($lesson->attendance_status === 'late' && $lesson->late_minutes)
                                                <span class="block text-xs text-amber-500">+{{ $lesson->late_minutes }}
                                                    min</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-gray-800 dark:text-gray-200 font-medium line-clamp-1">
                                                {{ $lesson->topic ?? 'Dars #' . $loop->iteration }}
                                            </p>
                                            @if ($lesson->attendance_notes)
                                                <p class="text-xs text-gray-400 dark:text-gray-500 line-clamp-1 mt-0.5">
                                                    {{ $lesson->attendance_notes }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if (is_null($lesson->attendance_status))
                                                <span
                                                    class="inline-block w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600"
                                                    title="Belgilanmagan"></span>
                                            @elseif($lesson->attendance_status === 'present')
                                                <span
                                                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 dark:bg-green-900/40"
                                                    title="Keldi">
                                                    <svg class="w-3.5 h-3.5 text-green-600 dark:text-green-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            @elseif($lesson->attendance_status === 'absent')
                                                <span
                                                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 dark:bg-red-900/40"
                                                    title="Kelmadi">
                                                    <svg class="w-3.5 h-3.5 text-red-600 dark:text-red-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </span>
                                            @elseif($lesson->attendance_status === 'late')
                                                <span
                                                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 dark:bg-amber-900/40"
                                                    title="Kechikdi">
                                                    <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if (!is_null($lesson->homework_score))
                                                @php
                                                    $hw = $lesson->homework_score;
                                                    $hwColor =
                                                        $hw >= 8
                                                            ? 'text-green-600 dark:text-green-400'
                                                            : ($hw >= 5
                                                                ? 'text-amber-600 dark:text-amber-400'
                                                                : 'text-red-500');
                                                @endphp
                                                <span class="font-bold {{ $hwColor }}">{{ $hw }}</span>
                                            @else
                                                <span class="text-gray-300 dark:text-gray-600">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if (!is_null($lesson->activity_score))
                                                @php
                                                    $act = $lesson->activity_score;
                                                    $actColor =
                                                        $act >= 8
                                                            ? 'text-purple-600 dark:text-purple-400'
                                                            : ($act >= 5
                                                                ? 'text-blue-500'
                                                                : 'text-gray-400');
                                                @endphp
                                                <span class="font-bold {{ $actColor }}">{{ $act }}</span>
                                            @else
                                                <span class="text-gray-300 dark:text-gray-600">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-start gap-2">
                                                <div class="min-w-0">
                                                    <p class="text-gray-800 dark:text-gray-200 font-medium line-clamp-1">
                                                        {{ $lesson->topic ?? 'Dars #' . $loop->iteration }}
                                                    </p>
                                                    @if ($lesson->attendance_notes)
                                                        <p
                                                            class="text-xs text-gray-400 dark:text-gray-500 line-clamp-1 mt-0.5">
                                                            {{ $lesson->attendance_notes }}</p>
                                                    @endif
                                                </div>
                                                {{-- Description tugmasi --}}
                                                @if ($lesson->description)
                                                    <button onclick="openLessonModal(this)"
                                                        data-topic="{{ e($lesson->topic ?? 'Dars #' . $loop->iteration) }}"
                                                        data-date="{{ \Carbon\Carbon::parse($lesson->lesson_date)->format('d.m.Y') }}"
                                                        data-desc="{{ base64_encode($lesson->description) }}"
                                                        class="flex-shrink-0 w-6 h-6 rounded-md bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-colors flex items-center justify-center"
                                                        title="Batafsil ko'rish">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($lessons->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                            {{ $lessons->links() }}
                        </div>
                    @endif
                @endif
            </div>

            {{-- Right Column --}}
            <div class="space-y-5">

                {{-- Score Summary --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4">Davomat taqsimoti</h3>

                    @php
                        $total = $stats['total_lessons'];
                        $presentPct = $total > 0 ? round(($stats['present'] / $total) * 100) : 0;
                        $absentPct = $total > 0 ? round(($stats['absent'] / $total) * 100) : 0;
                        $latePct = $total > 0 ? round(($stats['late'] / $total) * 100) : 0;
                    @endphp

                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Keldi</span>
                                <span class="font-bold text-green-600 dark:text-green-400">{{ $stats['present'] }}
                                    ({{ $presentPct }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all"
                                    style="width: {{ $presentPct }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Kelmadi</span>
                                <span class="font-bold text-red-500">{{ $stats['absent'] }} ({{ $absentPct }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full transition-all"
                                    style="width: {{ $absentPct }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Kechikdi</span>
                                <span class="font-bold text-amber-500">{{ $stats['late'] }} ({{ $latePct }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-amber-400 h-2 rounded-full transition-all"
                                    style="width: {{ $latePct }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Groupmates --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-bold text-gray-900 dark:text-white">Guruh a'zolari</h3>
                        <span
                            class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full">
                            {{ $groupmates->count() + 1 }} kishi
                        </span>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-80 overflow-y-auto">
                        {{-- Current student (me) --}}
                        <div class="flex items-center gap-3 px-5 py-3 bg-indigo-50 dark:bg-indigo-900/20">
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(mb_substr(auth()->user()->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ auth()->user()->name ?? '' }} {{ auth()->user()->surname ?? '' }}
                                    <span class="text-indigo-500 text-xs font-normal">(Siz)</span>
                                </p>
                            </div>
                        </div>

                        @forelse($groupmates as $mate)
                            <div class="flex items-center gap-3 px-5 py-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(mb_substr($mate->name ?? 'S', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                        {{ $mate->name }} {{ $mate->surname ?? '' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center">
                                <p class="text-sm text-gray-400 dark:text-gray-500">Boshqa a'zolar yo'q</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Course Price Card --}}
                @if (isset($group->cours) && $group->cours->price)
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-5 text-white shadow-sm">
                        <p class="text-indigo-100 text-sm font-medium mb-1">Kurs narxi</p>
                        <p class="text-3xl font-extrabold">
                            {{ number_format($group->cours->price, 0, '.', ' ') }}
                            <span class="text-lg font-normal text-indigo-200">so'm</span>
                        </p>
                        <p class="text-indigo-200 text-sm mt-2">{{ $group->cours->title ?? '' }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
    {{-- Lesson Description Modal --}}
    {{-- Lesson Description Modal --}}
    {{-- Lesson Modal --}}
    <div id="lessonModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6"
        onclick="if(event.target===this)closeLessonModal()">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl border border-gray-200 dark:border-gray-700 flex flex-col"
            style="max-height:min(88vh,740px)">
            <div
                class="flex items-start justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                <div class="min-w-0 pr-3">
                    <p class="text-xs text-gray-400 mb-0.5" id="modalDate"></p>
                    <h3 class="font-bold text-gray-900 dark:text-white text-base truncate" id="modalTopic"></h3>
                </div>
                <button onclick="closeLessonModal()"
                    class="w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-6 py-5 overflow-y-auto flex-1 min-h-0 ld-scroll">
                <div id="modalDescription" class="ldesc space-y-3 text-sm leading-relaxed"></div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            (function() {
                const E = s => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

                function hlHTML(code) {
                    let s = E(code);
                    s = s.replace(/(&lt;!--[\s\S]*?--&gt;)/g, '<span class="tk-cm">$1</span>');
                    s = s.replace(/(&lt;!DOCTYPE[^&gt;]*&gt;)/gi, '<span class="tk-dt">$1</span>');
                    s = s.replace(/(&lt;\/?)([\w-]+)([^&]*?)(\/?&gt;)/g, function(_, o, tag, rest, c) {
                        let r = rest
                            .replace(/([\w:-]+)(=)(&quot;[^&]*?&quot;|&#039;[^&]*?&#039;)/g,
                                '<span class="tk-at">$1</span><span class="tk-pu">$2</span><span class="tk-st">$3</span>'
                            )
                            .replace(/\b([\w:-]+)\b(?!=)(?!<)/g, '<span class="tk-at">$1</span>');
                        return `<span class="tk-pu">${o}</span><span class="tk-tg">${tag}</span>${r}<span class="tk-pu">${c}</span>`;
                    });
                    return s;
                }

                function hlCSS(code) {
                    let s = E(code);
                    s = s.replace(/(\/\*[\s\S]*?\*\/)/g, '<span class="tk-cm">$1</span>');
                    s = s.replace(/(@[\w-]+[^{;]*)/g, '<span class="tk-kw">$1</span>');
                    s = s.replace(/([^{};@\n][^{};]*?)\{/g, (_, sel) => `<span class="tk-sl">${sel.trim()}</span> {`);
                    s = s.replace(/([\w-]+)(\s*:\s*)([^;{}]+)(;)/g, function(_, p, col, val, semi) {
                        let v = val
                            .replace(/!important/g, '<span class="tk-kw">!important</span>')
                            .replace(/(["'][^"']*["'])/g, '<span class="tk-st">$1</span>')
                            .replace(/#[0-9a-fA-F]{3,8}/g, m => `<span class="tk-st">${m}</span>`)
                            .replace(/\b(\d+\.?\d*)(px|em|rem|%|vh|vw|s|ms|deg)/g,
                                '<span class="tk-nu">$1</span><span class="tk-un">$2</span>');
                        return `<span class="tk-pr">${p}</span>${col}<span class="tk-vl">${v}</span>${semi}`;
                    });
                    return s;
                }

                function hlPHP(code) {
                    let s = E(code);
                    s = s.replace(/(\/\/[^\n]*|#[^\n]*)/g, '<span class="tk-cm">$1</span>');
                    s = s.replace(/(\/\*[\s\S]*?\*\/)/g, '<span class="tk-cm">$1</span>');
                    s = s.replace(/(["'](?:[^"'\\]|\\.)*["'])/g, '<span class="tk-st">$1</span>');
                    s = s.replace(/(&lt;\?php|&lt;\?=|\?&gt;)/g, '<span class="tk-dt">$1</span>');
                    const kw =
                        'echo|print|return|if|else|elseif|foreach|for|while|do|switch|case|break|continue|function|class|new|public|private|protected|static|namespace|use|extends|implements|abstract|try|catch|finally|throw|true|false|null|array|string|int|bool|void';
                    s = s.replace(new RegExp(`\\b(${kw})\\b`, 'g'), '<span class="tk-kw">$1</span>');
                    s = s.replace(/(\$[\w]+)/g, '<span class="tk-va">$1</span>');
                    s = s.replace(/\b([\w_]+)(?=\s*\()/g, '<span class="tk-fn">$1</span>');
                    s = s.replace(/\b(\d+\.?\d*)\b/g, '<span class="tk-nu">$1</span>');
                    return s;
                }

                function detectLang(code) {
                    if (/\<\?php/i.test(code)) return 'php';
                    if (/<(!DOCTYPE|html|head|body|div|span|p|a|ul|li|h[1-6])/i.test(code)) return 'html';
                    if (/[\w-]+\s*:\s*[\w#"']+.*;/.test(code) && /\{[\s\S]*\}/.test(code)) return 'css';
                    return '';
                }

                function highlight(lang, code) {
                    const l = (lang || '').toLowerCase();
                    if (['html', 'xml', 'blade'].includes(l)) return hlHTML(code);
                    if (['css', 'scss'].includes(l)) return hlCSS(code);
                    if (['php'].includes(l)) return hlPHP(code);
                    return E(code);
                }

                const copyIcon =
                    `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>`;
                const checkIcon =
                    `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;

                /* ── COPY: HTTP va HTTPS ikkalasida ishlaydi ── */
                window._ldCopy = function(btn, id) {
                    const el = document.getElementById(id);
                    let text = el.innerText;

                    // original kodni base64 dan olamiz (highlight spanlari ta'sir qilmaydi)
                    if (el.dataset.raw64) {
                        try {
                            text = decodeURIComponent(escape(atob(el.dataset.raw64)));
                        } catch (e) {}
                    }

                    function fallback(t) {
                        const ta = document.createElement('textarea');
                        ta.value = t;
                        ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
                        document.body.appendChild(ta);
                        ta.focus();
                        ta.select();
                        try {
                            document.execCommand('copy');
                        } catch (e) {}
                        document.body.removeChild(ta);
                        btn.innerHTML = checkIcon;
                        setTimeout(() => {
                            btn.innerHTML = copyIcon;
                        }, 2000);
                    }

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text)
                            .then(() => {
                                btn.innerHTML = checkIcon;
                                setTimeout(() => {
                                    btn.innerHTML = copyIcon;
                                }, 2000);
                            })
                            .catch(() => fallback(text));
                    } else {
                        fallback(text);
                    }
                };

                /* ── CODE BLOCK yasash: rawCode ni base64 da saqlaydi ── */
                function makeBlock(lang, rawCode) {
                    const id = 'cb' + Math.random().toString(36).slice(2, 8);
                    const lbl = lang || 'code';
                    const hl = highlight(lang, rawCode);
                    const safe = btoa(unescape(encodeURIComponent(rawCode)));
                    return `<pre><div class="cb-head"><span class="cb-lang">${lbl}</span><button class="cb-copy" onclick="window._ldCopy(this,'${id}')">${copyIcon}</button></div><code id="${id}" data-raw64="${safe}">${hl}</code></pre>`;
                }

                function parseDesc(raw) {
                    const fenceRx = /```([\w]*)\r?\n([\s\S]*?)```/g;
                    let html = '',
                        last = 0,
                        m, hasFence = false;
                    while ((m = fenceRx.exec(raw)) !== null) {
                        hasFence = true;
                        const before = raw.slice(last, m.index);
                        if (before.trim()) before.split('\n').forEach(l => {
                            if (l.trim()) html += `<p>${E(l.trim())}</p>`;
                        });
                        html += makeBlock(m[1], m[2]);
                        last = m.index + m[0].length;
                    }
                    if (hasFence) {
                        const tail = raw.slice(last);
                        if (tail.trim()) tail.split('\n').forEach(l => {
                            if (l.trim()) html += `<p>${E(l.trim())}</p>`;
                        });
                        return html;
                    }

                    const autoLang = detectLang(raw);
                    if (autoLang) return makeBlock(autoLang, raw);

                    raw.split('\n').forEach(line => {
                        const t = line.trim();
                        if (t) {
                            const p = t.replace(/`([^`]+)`/g, (_, c) => `<code>${E(c)}</code>`);
                    html += `<p>${p}</p>`;
                        }
                    });
                    return html;
                }

                window.openLessonModal = function(btn) {
                    let raw = '';
                    try {
                        raw = decodeURIComponent(escape(atob(btn.dataset.desc)));
                    } catch (e) {
                        raw = btn.dataset.desc || '';
                    }
                    document.getElementById('modalDate').textContent = btn.dataset.date || '';
                    document.getElementById('modalTopic').textContent = btn.dataset.topic || '';
                    document.getElementById('modalDescription').innerHTML = parseDesc(raw);
                    const modal = document.getElementById('lessonModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                };

                window.closeLessonModal = function() {
                    const modal = document.getElementById('lessonModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = '';
                };

                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') closeLessonModal();
                });
            })();
        </script>
    @endpush
@endsection
