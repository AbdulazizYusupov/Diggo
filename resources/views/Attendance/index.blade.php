@extends('Layout.app')

@section('title', 'Davomat')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Davomat boshqaruvi
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Guruhlar bo'yicha davomat va coin tizimi
                    </p>
                </div>
            </div>
        </div>

        <!-- Groups Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($groups as $group)
                <a href="{{ route('attendance.show', $group->id) }}" 
                   class="group bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6 hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                    
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">
                                    {{ substr($group->name, 0, 2) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $group->name }}
                                </h3>
                                @if($group->course)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $group->course->title }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Faol
                        </span>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="font-semibold">{{ $group->activeStudents->count() }}</span>
                            <span>o'quvchi</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($group->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($group->end_time)->format('H:i') }}</span>
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
                            @endphp
                            @foreach($group->days_of_week ?? [] as $day)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $dayNames[$day] ?? $day }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Davomatni boshqarish</span>
                            <svg class="w-5 h-5 text-blue-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Faol guruhlar topilmadi</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Davomat uchun faol guruhlar mavjud emas.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($groups->hasPages())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-4">
                {{ $groups->links() }}
            </div>
        @endif
    </div>
@endsection