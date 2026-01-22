@extends('Layout.app')

@section('title', 'Davomat')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Davomat boshqaruvi
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Guruhlar bo'yicha davomat va coin tizimi
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <form action="{{ route('attendance.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input type="text" name="search" value="{{ $search }}" placeholder="Rol nomini qidiring..."
                        class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="group flex-1 sm:flex-none px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-indigo-200 dark:shadow-indigo-900/20 hover:-translate-y-0.5 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor"
                            class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>

                    </button>

                    @if ($search)
                        <a href="{{ route('attendance.index') }}"
                            class="group px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all flex items-center justify-center"
                            title="Tozalash">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 transition-transform duration-500 group-hover:rotate-180">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($groups as $group)
                @php
                    // Card rang logikasi
                    $cardClass = 'bg-white dark:bg-slate-900 border-gray-200 dark:border-blue-500/20';

                    if ($group->has_today_lesson) {
                        if ($group->has_attendance_today) {
                            // Bugun dars bor va davomat yuklangan - yashil
                            $cardClass =
                                'bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-900/20 dark:to-emerald-800/10 border-emerald-300 dark:border-emerald-500/30';
                        } else {
                            // Bugun dars bor lekin davomat yuklanmagan - qizil
                            $cardClass =
                                'bg-gradient-to-br from-rose-50 to-rose-100/50 dark:from-rose-900/20 dark:to-rose-800/10 border-rose-300 dark:border-rose-500/30';
                        }
                    }
                @endphp

                <a href="{{ route('attendance.show', $group->id) }}"
                    class="group {{ $cardClass }} rounded-2xl shadow-sm border p-6 pt-12 hover:shadow-lg hover:scale-[1.02] transition-all duration-200 relative">

                    <!-- STATUS BADGES STACK -->
                    <div class="absolute top-3 right-3 flex flex-col gap-2 items-end">

                        {{-- Faol --}}
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg
                   bg-emerald-100 text-emerald-700
                   dark:bg-emerald-900/30 dark:text-emerald-400 shadow-sm">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Faol
                        </span>

                        {{-- Yuklangan / Yuklanmagan --}}
                        @if ($group->has_today_lesson)
                            @if ($group->has_attendance_today)
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg
                           bg-emerald-500 text-white shadow-sm">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Yuklangan
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg
                           bg-rose-500 text-white shadow-sm animate-pulse">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Yuklanmagan
                                </span>
                            @endif
                        @endif
                    </div>

                    <!-- HEADER -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">
                                    {{ substr($group->name, 0, 2) }}
                                </span>
                            </div>
                            <div>
                                <h3
                                    class="text-lg font-bold text-gray-900 dark:text-white
                           group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $group->name }}
                                </h3>
                                @if ($group->course)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $group->course->title }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- INFO -->
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" class="w-4 h-4 text-purple-500 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031 c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21 c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719 m12 0a5.971 5.971 0 0 0-.941-3.197m0 0 A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772 m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197 a5.971 5.971 0 0 0-.94 3.197M15 6.75 a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3 a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0 a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>

                            <span class="font-semibold">{{ $group->activeStudents->count() }}</span>
                            <span>o'quvchi</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>
                                {{ \Carbon\Carbon::parse($group->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($group->end_time)->format('H:i') }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-1">
                            @php
                                $dayNames = [
                                    'monday' => 'Du',
                                    'tuesday' => 'Se',
                                    'wednesday' => 'Ch',
                                    'thursday' => 'Pa',
                                    'friday' => 'Ju',
                                    'saturday' => 'Sh',
                                    'sunday' => 'Ya',
                                ];
                                $todayDayName = strtolower(now()->locale('en')->dayName);
                            @endphp

                            @foreach ($group->days_of_week ?? [] as $day)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded {{ $day === $todayDayName ? 'bg-blue-500 text-white ring-2 ring-blue-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                    {{ $dayNames[$day] ?? $day }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Davomatni boshqarish</span>
                            <svg class="w-5 h-5 text-blue-500 group-hover:translate-x-1 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>

            @empty
                <div class="col-span-full">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Faol guruhlar topilmadi</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Davomat uchun faol guruhlar mavjud emas.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($groups->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-4">
                {{ $groups->links() }}
            </div>
        @endif
    </div>
@endsection
