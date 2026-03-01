@extends('Layout.app')

@section('title', 'E\'lonlar')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30 border-b-2 border-indigo-800/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        E'lonlar
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Guruhingiz uchun barcha faoliyat va e'lonlar
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-2xl p-4 border border-indigo-200 dark:border-indigo-700/50 shadow-sm">
                <p class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wider mb-1">Jami</p>
                <p class="text-2xl font-extrabold text-indigo-900 dark:text-indigo-50">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-4 border border-emerald-200 dark:border-emerald-700/50 shadow-sm">
                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider mb-1">Kelgusi</p>
                <p class="text-2xl font-extrabold text-emerald-900 dark:text-emerald-50">{{ $stats['upcoming'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-sky-100 to-sky-200/60 dark:from-sky-900/30 dark:to-sky-800/30 rounded-2xl p-4 border border-sky-200 dark:border-sky-700/50 shadow-sm">
                <p class="text-xs font-bold text-sky-700 dark:text-sky-400 uppercase tracking-wider mb-1">Bu oy</p>
                <p class="text-2xl font-extrabold text-sky-900 dark:text-sky-50">{{ $stats['this_month'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-4 border border-blue-200 dark:border-blue-700/50 shadow-sm">
                <p class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider mb-1">Uy vazifa</p>
                <p class="text-2xl font-extrabold text-blue-900 dark:text-blue-50">{{ $stats['homework'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30 rounded-2xl p-4 border border-red-200 dark:border-red-700/50 shadow-sm">
                <p class="text-xs font-bold text-red-700 dark:text-red-400 uppercase tracking-wider mb-1">Imtihon</p>
                <p class="text-2xl font-extrabold text-red-900 dark:text-red-50">{{ $stats['exam'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-100 to-yellow-200/60 dark:from-yellow-900/30 dark:to-yellow-800/30 rounded-2xl p-4 border border-yellow-200 dark:border-yellow-700/50 shadow-sm">
                <p class="text-xs font-bold text-yellow-700 dark:text-yellow-400 uppercase tracking-wider mb-1">Musobaqa</p>
                <p class="text-2xl font-extrabold text-yellow-900 dark:text-yellow-50">{{ $stats['competition'] }}</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <form action="{{ route('student.activities.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                    {{-- Search --}}
                    <div class="relative group lg:col-span-4">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="E'lon nomini qidiring..."
                            class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    {{-- Type Filter --}}
                    <div class="lg:col-span-2">
                        <select name="type"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                            <option value="">Barcha turlar</option>
                            <option value="homework"    {{ request('type') == 'homework'    ? 'selected' : '' }}>Uy vazifa</option>
                            <option value="exam"        {{ request('type') == 'exam'        ? 'selected' : '' }}>Imtihon</option>
                            <option value="competition" {{ request('type') == 'competition' ? 'selected' : '' }}>Musobaqa</option>
                            <option value="project"     {{ request('type') == 'project'     ? 'selected' : '' }}>Loyiha</option>
                            <option value="other"       {{ request('type') == 'other'       ? 'selected' : '' }}>Boshqa</option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div class="lg:col-span-2">
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    {{-- Date To --}}
                    <div class="lg:col-span-2">
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    {{-- Buttons --}}
                    <div class="lg:col-span-2 flex gap-2">
                        <button type="submit"
                            class="group flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all shadow-lg shadow-indigo-200 dark:shadow-none flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <span class="hidden xl:inline">Qidirish</span>
                        </button>

                        @if(request('search') || request('type') || request('from') || request('to'))
                            <a href="{{ route('student.activities.index') }}"
                                class="group px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all flex items-center justify-center"
                                title="Tozalash">
                                <svg class="w-5 h-5 transition-transform duration-500 group-hover:rotate-180"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>

        {{-- Activities Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($activities as $activity)
                <a href="{{ route('student.activities.show', $activity) }}"
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-300 flex flex-col gap-3 relative overflow-hidden">

                    {{-- Upcoming top accent --}}
                    @if($activity->is_today)
                        <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-400 rounded-t-2xl"></div>
                    @elseif($activity->is_upcoming)
                        <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-indigo-400 via-purple-500 to-indigo-400 rounded-t-2xl"></div>
                    @endif

                    {{-- Type Badge + Status --}}
                    <div class="flex items-center justify-between">

                        {{-- Type badge --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $activity->type_bg }} {{ $activity->type_text }} text-xs font-bold rounded-lg border {{ $activity->type_border }}">
                            @if($activity->type === 'homework')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            @elseif($activity->type === 'exam')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            @elseif($activity->type === 'competition')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            @elseif($activity->type === 'project')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            @endif
                            {{ $activity->type_label }}
                        </span>

                        {{-- Status pill --}}
                        @if($activity->is_today)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full border border-emerald-200 dark:border-emerald-700/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Bugun
                            </span>
                        @elseif($activity->is_upcoming)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-bold rounded-full border border-indigo-200 dark:border-indigo-700/50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Kelgusi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-bold rounded-full border border-gray-200 dark:border-gray-600">
                                O'tgan
                            </span>
                        @endif
                    </div>

                    {{-- Title & Description --}}
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-snug line-clamp-2">
                            {{ $activity->name }}
                        </h3>
                        @if($activity->description)
                            <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                {{ $activity->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Meta --}}
                    <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700 flex flex-col gap-1.5">

                        {{-- Date --}}
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold">{{ $activity->date->format('d.m.Y') }}</span>
                            <span class="text-xs text-gray-400">({{ $activity->date->diffForHumans() }})</span>
                        </div>

                        {{-- Group --}}
                        @if($activity->group)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-medium truncate">{{ $activity->group->name }}</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-500">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                                </svg>
                                <span class="text-xs font-medium">Umumiy e'lon</span>
                            </div>
                        @endif

                        {{-- Coins --}}
                        @if($activity->max_coins > 0)
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.93V18h-2v-1.07C9.39 16.59 8 15.42 8 14h2c0 .55.63 1 2 1s2-.45 2-1c0-.64-.39-1-2-1-2.39 0-4-1.03-4-3 0-1.42 1.39-2.59 3-2.93V6h2v1.07C14.61 7.41 16 8.58 16 10h-2c0-.55-.63-1-2-1s-2 .45-2 1c0 .64.39 1 2 1 2.39 0 4 1.03 4 3 0 1.42-1.39 2.59-3 2.93z"/>
                                </svg>
                                <span class="font-semibold text-yellow-600 dark:text-yellow-400">{{ $activity->max_coins }} coin</span>
                            </div>
                        @endif
                    </div>

                    {{-- Hover arrow --}}
                    <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-1 group-hover:translate-x-0">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>
            @empty
                <div class="col-span-full">
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-12 text-center">
                        <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hech qanday e'lon topilmadi</h3>
                        <p class="text-gray-500 dark:text-gray-400">Filterni o'zgartiring yoki keyinroq qarang</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($activities->hasPages())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-4 mt-6">
                {{ $activities->links() }}
            </div>
        @endif

    </div>
@endsection