@extends('Layout.app')

@section('title', 'Davomat Tarixi')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Davomat Tarixi
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Barcha darslaringiz va davomat ma'lumotlari
                </p>
            </div>
            <a href="{{ route('student.attendance.calendar') }}" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Calendar Ko'rinishi
            </a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase">Jami Darslar</p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ $stats['total'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-100 to-green-200/60 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl p-6 border border-green-200 dark:border-green-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-green-700 dark:text-green-400 uppercase">Qatnashgan</p>
                        <p class="text-3xl font-extrabold text-green-900 dark:text-green-50 mt-1">
                            {{ $stats['present'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30 rounded-2xl p-6 border border-red-200 dark:border-red-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-red-700 dark:text-red-400 uppercase">Qatnashmagan</p>
                        <p class="text-3xl font-extrabold text-red-900 dark:text-red-50 mt-1">
                            {{ $stats['absent'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-red-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase">Kechikkan</p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ $stats['late'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-cyan-100 to-cyan-200/60 dark:from-cyan-900/30 dark:to-cyan-800/30 rounded-2xl p-6 border border-cyan-200 dark:border-cyan-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-cyan-700 dark:text-cyan-400 uppercase">Davomat</p>
                        <p class="text-3xl font-extrabold text-cyan-900 dark:text-cyan-50 mt-1">
                            {{ $stats['attendance_rate'] }}%
                        </p>
                    </div>
                    <div class="p-3 bg-cyan-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('student.attendance') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Guruh</label>
                    <select name="group_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Barcha guruhlar</option>
                        @foreach($groups as $group)
                            <option value="{{ $group['id'] }}" {{ $groupId == $group['id'] ? 'selected' : '' }}>
                                {{ $group['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Barchasi</option>
                        <option value="present" {{ $status === 'present' ? 'selected' : '' }}>Qatnashgan</option>
                        <option value="absent" {{ $status === 'absent' ? 'selected' : '' }}>Qatnashmagan</option>
                        <option value="late" {{ $status === 'late' ? 'selected' : '' }}>Kechikkan</option>
                        <option value="excused" {{ $status === 'excused' ? 'selected' : '' }}>Uzrli</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Oy</label>
                    <select name="month" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Barcha oylar</option>
                        @foreach($months as $key => $monthName)
                            <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                        Filtrlash
                    </button>
                </div>
            </form>
        </div>

        {{-- Attendance List --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Davomat ro'yxati</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Sana</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Guruh</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Mavzu</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Uy ishi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Faollik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($attendance->lesson->lesson_date)->format('d.m.Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($attendance->lesson->lesson_date)->locale('uz_Latn')->translatedFormat('l') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $attendance->lesson->group->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $attendance->lesson->group->cours->title ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $attendance->lesson->topic ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold
                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                        {{ $attendance->status === 'absent' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                        {{ $attendance->status === 'late' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                        {{ $attendance->status === 'excused' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}">
                                        @if($attendance->status === 'present') Qatnashgan
                                        @elseif($attendance->status === 'absent') Qatnashmagan
                                        @elseif($attendance->status === 'late') 
                                            Kechikkan @if($attendance->late_minutes) ({{ $attendance->late_minutes }} min) @endif
                                        @elseif($attendance->status === 'excused') Uzrli
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($attendance->homework_score !== null)
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-bold rounded-lg">
                                            {{ $attendance->homework_score }} ball
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($attendance->activity_score !== null)
                                        <span class="inline-flex items-center px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-sm font-bold rounded-lg">
                                            {{ $attendance->activity_score }} ball
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Davomat ma'lumotlari topilmadi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection