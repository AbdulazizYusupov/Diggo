@extends('Layout.app')

@section('title', 'Dars tafsilotlari')

@section('content')
    <div class="space-y-6">
        <!-- Header Section with Gradient -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-3xl shadow-2xl p-8">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('lessons.index') }}"
                            class="p-3 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-xl transition-all">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-4xl font-black text-white">
                                    {{ $lesson->topic ?? 'Dars tafsilotlari' }}
                                </h1>
                                <span
                                    class="px-3 py-1 text-xs font-bold rounded-full
                                    {{ $lesson->status === 'scheduled' ? 'bg-yellow-400 text-yellow-900' : '' }}
                                    {{ $lesson->status === 'completed' ? 'bg-green-400 text-green-900' : '' }}
                                    {{ $lesson->status === 'cancelled' ? 'bg-red-400 text-red-900' : '' }}
                                    {{ $lesson->status === 'rescheduled' ? 'bg-blue-400 text-blue-900' : '' }}">
                                    @if ($lesson->status === 'scheduled')
                                        REJALASHTIRILGAN
                                    @elseif($lesson->status === 'completed')
                                        YAKUNLANGAN
                                    @elseif($lesson->status === 'cancelled')
                                        BEKOR QILINGAN
                                    @else
                                        KO'CHIRILGAN
                                    @endif
                                </span>
                            </div>
                            <p class="text-white/90 text-lg font-medium">
                                {{ $lesson->group->name }} • {{ $lesson->group->course->name }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- @if ($lesson->status === 'scheduled') --}}
                        <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-5 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                ✓ Yakunlash
                            </button>
                        </form>
                        {{-- @endif --}}
                        <a href="{{ route('lessons.edit', $lesson->id) }}"
                            class="px-5 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                            ✏️ Tahrirlash
                        </a>
                    </div>
                </div>

                <!-- Info Cards in Header -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-white/20 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm font-medium">Sana</p>
                                <p class="text-white text-lg font-bold">{{ $lesson->lesson_date->format('d.m.Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-white/20 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm font-medium">Vaqt</p>
                                <p class="text-white text-lg font-bold">
                                    {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-white/20 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm font-medium">O'qituvchi</p>
                                <p class="text-white text-lg font-bold truncate">
                                    {{ $lesson->teacher->full_name ?? 'Biriktirilmagan' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-white/20 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm font-medium">Talabalar</p>
                                <p class="text-white text-lg font-bold">{{ $totalStudents }} ta</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/20 rounded-full -ml-32 -mb-32 blur-3xl"></div>
        </div>

        <!-- Attendance Statistics -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="group bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 p-6 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-white/20 rounded-xl group-hover:bg-white/30 transition-all group-hover:rotate-12">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-black text-white">{{ $presentCount }}</span>
                </div>
                <p class="text-white/90 font-semibold text-sm">Kelgan talabalar</p>
                <div class="mt-2 h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-1000"
                        style="width: {{ $totalStudents > 0 ? ($presentCount / $totalStudents) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div
                class="group bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 p-6 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-white/20 rounded-xl group-hover:bg-white/30 transition-all group-hover:rotate-12">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="text-3xl font-black text-white">{{ $absentCount }}</span>
                </div>
                <p class="text-white/90 font-semibold text-sm">Kelmagan talabalar</p>
                <div class="mt-2 h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-1000"
                        style="width: {{ $totalStudents > 0 ? ($absentCount / $totalStudents) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div
                class="group bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 p-6 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-white/20 rounded-xl group-hover:bg-white/30 transition-all group-hover:rotate-12">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-black text-white">{{ $lateCount }}</span>
                </div>
                <p class="text-white/90 font-semibold text-sm">Kech qolgan</p>
                <div class="mt-2 h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-1000"
                        style="width: {{ $totalStudents > 0 ? ($lateCount / $totalStudents) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div
                class="group bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 p-6 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-white/20 rounded-xl group-hover:bg-white/30 transition-all group-hover:rotate-12">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-3xl font-black text-white">{{ $excusedCount }}</span>
                </div>
                <p class="text-white/90 font-semibold text-sm">Sababli</p>
                <div class="mt-2 h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-1000"
                        style="width: {{ $totalStudents > 0 ? ($excusedCount / $totalStudents) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Attendance List -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Davomat ro'yxati
                        </h2>
                    </div>

                    <div class="p-6">
                        @if ($lesson->attendances->count() > 0)
                            <div class="space-y-3">
                                @foreach ($lesson->attendances as $attendance)
                                    <div
                                        class="group flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:shadow-md transition-all border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                @if ($attendance->student->user->photo)
                                                    <img src="{{ asset('storage/' . $attendance->student->user->photo) }}"
                                                        alt="{{ $attendance->student->user->full_name }}"
                                                        class="w-12 h-12 rounded-xl object-cover ring-2 ring-white dark:ring-gray-700 group-hover:ring-4 group-hover:ring-indigo-500 transition-all">
                                                @else
                                                    <div
                                                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg ring-2 ring-white dark:ring-gray-700 group-hover:ring-4 group-hover:ring-indigo-500 transition-all">
                                                        {{ substr($attendance->student->user->name, 0, 1) }}{{ substr($attendance->student->user->surname, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $attendance->student->user->full_name }}
                                                </p>
                                                <div class="flex items-center gap-3 mt-1">
                                                    @if ($attendance->homework_score)
                                                        <span
                                                            class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 dark:text-blue-400">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            Uy ishi: {{ $attendance->homework_score }}
                                                        </span>
                                                    @endif
                                                    @if ($attendance->activity_score)
                                                        <span
                                                            class="inline-flex items-center gap-1 text-xs font-medium text-purple-600 dark:text-purple-400">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg>
                                                            Faollik: {{ $attendance->activity_score }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-3 py-1.5 text-xs font-bold rounded-lg
                                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                                {{ $attendance->status === 'absent' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                                {{ $attendance->status === 'late' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                                {{ $attendance->status === 'excused' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}">
                                                @if ($attendance->status === 'present')
                                                    ✓ Kelgan
                                                @elseif($attendance->status === 'absent')
                                                    ✗ Kelmagan
                                                @elseif($attendance->status === 'late')
                                                    ⏰ Kech qolgan
                                                @else
                                                    📋 Sababli
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
                                    Davomat hali belgilanmagan
                                </h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Bu dars uchun hali davomat ma'lumotlari kiritilmagan
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Lesson Details Sidebar -->
            <div class="space-y-6">
                <!-- Description Card -->
                @if ($lesson->description)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tavsif
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            {{ $lesson->description }}
                        </p>
                    </div>
                @endif

                <!-- Cancellation Reason -->
                @if ($lesson->status === 'cancelled' && $lesson->cancellation_reason)
                    <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-red-900 dark:text-red-400 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.27 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Bekor qilish sababi
                        </h3>
                        <p class="text-red-800 dark:text-red-300 text-sm leading-relaxed">
                            {{ $lesson->cancellation_reason }}
                        </p>
                    </div>
                @endif
                <!-- show.blade.php ga qo'shing -->
                @if ($lesson->changes->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            O'zgarishlar tarixi
                        </h3>
                        <div class="space-y-3">
                            @foreach ($lesson->changes as $change)
                                <div
                                    class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800">
                                    <div class="flex items-start justify-between mb-2">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $change->changer->full_name }}
                                        </p>
                                        <span class="text-xs text-gray-500">
                                            {{ $change->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    @if ($change->old_date && $change->new_date)
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Sana: <span
                                                class="line-through text-red-600">{{ $change->old_date->format('d.m.Y') }}</span>
                                            → <span
                                                class="text-green-600 font-bold">{{ $change->new_date->format('d.m.Y') }}</span>
                                        </p>
                                    @endif
                                    @if ($change->old_start_time && $change->new_start_time)
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Vaqt: <span
                                                class="line-through text-red-600">{{ \Carbon\Carbon::parse($change->old_start_time)->format('H:i') }}</span>
                                            → <span
                                                class="text-green-600 font-bold">{{ \Carbon\Carbon::parse($change->new_start_time)->format('H:i') }}</span>
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                                        Sabab: {{ $change->change_reason }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Tezkor ma'lumot
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-white/80 text-sm">Davomat foizi</span>
                            <span class="text-xl font-bold">
                                {{ $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/80 text-sm">Jami talabalar</span>
                            <span class="text-xl font-bold">{{ $totalStudents }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/80 text-sm">Belgilangan</span>
                            <span class="text-xl font-bold">{{ $lesson->attendances->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="{{ route('lessons.edit', $lesson->id) }}"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Tahrirlash
                    </a>

                    <button onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        O'chirish
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="delete-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div
                class="relative w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border-2 border-gray-300 dark:border-gray-600 overflow-hidden">
                <!-- Animated Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-pink-500/10 animate-pulse"></div>

                <div class="relative z-10 text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 dark:bg-red-900/30 mb-4 animate-bounce">
                        <svg class="h-10 w-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.27 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">
                        Darsni o'chirishni xohlaysizmi?
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Ushbu dars va barcha davomat ma'lumotlari butunlay o'chiriladi. <br>
                        <span class="font-bold text-red-600">Bu amalni qaytarib bo'lmaydi!</span>
                    </p>

                    <div class="flex items-center justify-center gap-3">
                        <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                            class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold transition-all transform hover:scale-105">
                            Yo'q, bekor qilish
                        </button>

                        <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                                Ha, o'chirish
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
