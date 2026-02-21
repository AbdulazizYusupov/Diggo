@extends('Layout.app')

@section('title', 'Dars Jadvali')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Dars Jadvali
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @if($schedule['type'] === 'week')
                        {{ $schedule['start_date']->locale('uz_Latn')->translatedFormat('d F') }} - {{ $schedule['end_date']->locale('uz_Latn')->translatedFormat('d F, Y') }}
                    @else
                        {{ $schedule['month_name'] }}
                    @endif
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- View Toggle --}}
                <div class="inline-flex rounded-lg border border-gray-300 dark:border-gray-600 p-1 bg-white dark:bg-gray-800">
                    <a href="{{ route('student.schedule', ['view' => 'week', 'date' => $date->format('Y-m-d')]) }}"
                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $view === 'week' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        Hafta
                    </a>
                    <a href="{{ route('student.schedule', ['view' => 'month', 'date' => $date->format('Y-m-d')]) }}"
                        class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $view === 'month' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        Oy
                    </a>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex items-center justify-between">
            @if($view === 'week')
                <a href="{{ route('student.schedule', ['view' => 'week', 'date' => $date->copy()->subWeek()->format('Y-m-d')]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="ml-2 font-semibold text-gray-700 dark:text-gray-300">Oldingi hafta</span>
                </a>
            @else
                <a href="{{ route('student.schedule', ['view' => 'month', 'date' => $date->copy()->subMonth()->format('Y-m-d')]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="ml-2 font-semibold text-gray-700 dark:text-gray-300">Oldingi oy</span>
                </a>
            @endif

            <a href="{{ route('student.schedule', ['view' => $view, 'date' => now()->format('Y-m-d')]) }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                Bugun
            </a>

            @if($view === 'week')
                <a href="{{ route('student.schedule', ['view' => 'week', 'date' => $date->copy()->addWeek()->format('Y-m-d')]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <span class="mr-2 font-semibold text-gray-700 dark:text-gray-300">Keyingi hafta</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <a href="{{ route('student.schedule', ['view' => 'month', 'date' => $date->copy()->addMonth()->format('Y-m-d')]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <span class="mr-2 font-semibold text-gray-700 dark:text-gray-300">Keyingi oy</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endif
        </div>

        @if($view === 'week')
            {{-- Weekly View --}}
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                @foreach($schedule['days'] as $day)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border {{ $day['is_today'] ? 'border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-800' : 'border-gray-200 dark:border-gray-700' }} overflow-hidden">
                        <div class="p-4 {{ $day['is_today'] ? 'bg-indigo-600' : 'bg-gray-50 dark:bg-gray-700/50' }} border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-semibold {{ $day['is_today'] ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}">
                                {{ $day['date']->locale('uz_Latn')->translatedFormat('l') }}
                            </p>
                            <p class="text-2xl font-bold {{ $day['is_today'] ? 'text-white' : 'text-gray-900 dark:text-white' }} mt-1">
                                {{ $day['date']->format('d') }}
                            </p>
                        </div>

                        <div class="p-4 space-y-2">
                            @forelse($day['lessons'] as $lesson)
                                <div class="p-3 rounded-lg {{ $day['is_past'] ? 'bg-gray-100 dark:bg-gray-700/50' : 'bg-indigo-50 dark:bg-indigo-900/20' }} border {{ $day['is_past'] ? 'border-gray-200 dark:border-gray-600' : 'border-indigo-200 dark:border-indigo-800/30' }}">
                                    <p class="text-xs font-semibold {{ $day['is_past'] ? 'text-gray-600 dark:text-gray-400' : 'text-indigo-600 dark:text-indigo-400' }} mb-1">
                                        {{ $lesson->start_time }}
                                    </p>
                                    <p class="font-bold text-sm {{ $day['is_past'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-900 dark:text-white' }}">
                                        {{ $lesson->course_name }}
                                    </p>
                                    <p class="text-xs {{ $day['is_past'] ? 'text-gray-500 dark:text-gray-400' : 'text-gray-600 dark:text-gray-400' }}">
                                        {{ $lesson->group_name }}
                                    </p>
                                    
                                    @if($lesson->attendance_status)
                                        <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded
                                            {{ $lesson->attendance_status === 'present' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                            {{ $lesson->attendance_status === 'absent' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                            {{ $lesson->attendance_status === 'late' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}">
                                            @if($lesson->attendance_status === 'present') ✓ Qatnashgan
                                            @elseif($lesson->attendance_status === 'absent') ✗ Qatnashmagan
                                            @elseif($lesson->attendance_status === 'late') ⏰ Kechikkan
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-center text-sm text-gray-400 dark:text-gray-500 py-4">
                                    Dars yo'q
                                </p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Monthly View --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{-- Calendar Header --}}
                <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    @foreach(['Dushanba', 'Seshanba', 'Chorshanba', 'Payshanba', 'Juma', 'Shanba', 'Yakshanba'] as $dayName)
                        <div class="p-4 text-center">
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $dayName }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Calendar Grid --}}
                @foreach($schedule['weeks'] as $week)
                    <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                        @foreach($week as $day)
                            <div class="min-h-[120px] p-2 border-r border-gray-200 dark:border-gray-700 last:border-r-0 {{ !$day['is_current_month'] ? 'bg-gray-50 dark:bg-gray-900/50' : '' }} {{ $day['is_today'] ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                <p class="text-sm font-semibold mb-2 {{ $day['is_today'] ? 'text-indigo-600 dark:text-indigo-400' : ($day['is_current_month'] ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-600') }}">
                                    {{ $day['date']->format('d') }}
                                </p>

                                <div class="space-y-1">
                                    @foreach($day['lessons'] as $lesson)
                                        <div class="p-1 text-xs rounded {{ $day['is_past'] ? 'bg-gray-100 dark:bg-gray-700' : 'bg-indigo-100 dark:bg-indigo-900/30' }}">
                                            <p class="font-semibold truncate {{ $day['is_past'] ? 'text-gray-600 dark:text-gray-400' : 'text-indigo-700 dark:text-indigo-300' }}">
                                                {{ $lesson->start_time }}
                                            </p>
                                            <p class="truncate {{ $day['is_past'] ? 'text-gray-500 dark:text-gray-500' : 'text-gray-700 dark:text-gray-300' }}">
                                                {{ $lesson->group_name }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection