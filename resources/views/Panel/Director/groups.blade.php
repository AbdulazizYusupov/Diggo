@extends('Layout.app')

@section('title', 'Guruhlar')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Guruhlar
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    O'quvchilarim qatnashayotgan guruhlar
                </p>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-indigo-700 dark:text-indigo-400 uppercase">Jami Guruhlar</p>
                        <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-1">
                            {{ $stats['total_groups'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-100 to-green-200/60 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl p-6 border border-green-200 dark:border-green-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-green-700 dark:text-green-400 uppercase">Faol Guruhlar</p>
                        <p class="text-3xl font-extrabold text-green-900 dark:text-green-50 mt-1">
                            {{ $stats['active_groups'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase">O'quvchilar</p>
                        <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50 mt-1">
                            {{ $stats['total_students_in_groups'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <form method="GET" action="{{ route('director.groups') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        {{-- Qidiruv --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Qidiruv
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="Guruh nomi..." autocomplete="off"
                    class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                
                @if ($search)
                    <a href="{{ route('director.groups', array_filter(request()->except('search'))) }}"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-red-400 hover:text-red-600 transition-colors"
                        title="Qidiruvni tozalash">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        {{-- Status --}}
        <div class="md:col-span-1">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Status
            </label>
            <select name="status"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Barchasi</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Faol</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Faol emas</option>
            </select>
        </div>

        {{-- Action Buttons --}}
        <div class="md:col-span-1 flex items-end gap-2">
            <button type="submit"
                class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                Filtrlash
            </button>

            <a href="{{ route('director.groups') }}"
                class="group p-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg transition-all duration-300 flex items-center justify-center border border-gray-300 dark:border-gray-600"
                style="height: 42px; width: 42px;" title="Hammasini tozalash">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 transition-transform duration-500 group-hover:rotate-180">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </a>
        </div>
    </form>
</div>

        {{-- Groups Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($groups as $group)
                <a href="{{ route('director.groups.show', $group->id) }}"
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 transition-all">

                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3
                                class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $group->name }}
                            </h3>
                            <p class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold">
                                {{ $group->cours->title ?? 'N/A' }}
                            </p>
                        </div>
                        <span
                            class="px-3 py-1 rounded-lg text-xs font-bold {{ $group->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $group->is_active ? 'Faol' : 'Faol emas' }}
                        </span>
                    </div>

                    <div class="space-y-3 mb-4">
                        @if ($group->start_time)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $group->start_time }}{{ $group->end_time ? ' - ' . $group->end_time : '' }}</span>
                            </div>
                        @endif

                        @if ($group->days_of_week)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>
                                    @php
                                        $days = is_array($group->days_of_week)
                                            ? $group->days_of_week
                                            : json_decode($group->days_of_week, true) ?? [];
                                    @endphp
                                    {{ implode(', ', $days) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/10 rounded-lg">
                            <p class="text-xs text-indigo-700 dark:text-indigo-400 font-semibold">Jami</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $group->total_students }}
                            </p>
                        </div>
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/10 rounded-lg">
                            <p class="text-xs text-purple-700 dark:text-purple-400 font-semibold">Sizniki</p>
                            <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                {{ $group->director_students }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Daromad</span>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($group->director_revenue, 0, '.', ' ') }} so'm
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-12">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Guruhlar topilmadi</p>
                </div>
            @endforelse
        </div>

        @if ($groups->hasPages())
            <div class="mt-6">
                {{ $groups->links() }}
            </div>
        @endif
    </div>
@endsection
