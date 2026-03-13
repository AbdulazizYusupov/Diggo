@extends('Layout.app')

@section('title', 'E\'lonlar')

@section('content')
    <div class="space-y-6">

        {{-- ── HEADER ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-indigo-900/40 items-center justify-center flex-shrink-0 transform -rotate-2">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900 dark:text-white">E'lonlar</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Guruhingiz uchun barcha faoliyat va e'lonlar
                    </p>
                </div>
            </div>
        </div>

        {{-- ── STATS ── --}}
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
            @foreach ($statItems as $s)
                <div
                    class="relative bg-white dark:bg-slate-900 rounded-2xl border {{ $s['border'] }} p-4 shadow-sm overflow-hidden group hover:shadow-md transition-all duration-200">
                    <div
                        class="absolute -top-3 -right-3 w-14 h-14 {{ $s['bg'] }} rounded-full opacity-60 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative">
                        <div class="w-8 h-8 {{ $s['bg'] }} rounded-lg flex items-center justify-center mb-2.5">
                            <svg class="w-4 h-4 {{ $s['text'] }}" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $s['icon'] }}" />
                            </svg>
                        </div>
                        <p class="text-2xl font-black {{ $s['text'] }}">{{ $s['value'] }}</p>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-0.5">
                            {{ $s['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── FILTERS ── --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-800 p-4">
            <form action="{{ route('student.activities.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                    {{-- Search --}}
                    <div class="relative group lg:col-span-4">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="E'lon nomini qidiring..."
                            class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    {{-- Type --}}
                    <div class="lg:col-span-2">
                        <select name="type"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all cursor-pointer">
                            <option value="">Barcha turlar</option>
                            <option value="homework" {{ request('type') === 'homework' ? 'selected' : '' }}>Uy vazifa
                            </option>
                            <option value="exam" {{ request('type') === 'exam' ? 'selected' : '' }}>Imtihon
                            </option>
                            <option value="competition" {{ request('type') === 'competition' ? 'selected' : '' }}>Musobaqa
                            </option>
                            <option value="project" {{ request('type') === 'project' ? 'selected' : '' }}>Loyiha
                            </option>
                            <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Boshqa
                            </option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div class="lg:col-span-2">
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    {{-- Date To --}}
                    <div class="lg:col-span-2">
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    {{-- Buttons --}}
                    <div class="lg:col-span-2 flex gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-indigo-200 dark:shadow-none flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="hidden xl:inline">Qidirish</span>
                        </button>
                        @if (request('search') || request('type') || request('from') || request('to'))
                            <a href="{{ route('student.activities.index') }}"
                                class="px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-xl transition-all flex items-center justify-center"
                                title="Tozalash">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
                {{-- Time Filter Tabs --}}
                <div class="flex items-center gap-2 mt-3 flex-wrap">
                    <a href="{{ route('student.activities.index', array_merge(request()->except('time_filter', 'page'), [])) }}"
                        class="px-4 py-2 rounded-xl text-sm font-bold border transition-all
            {{ !request('time_filter')
                ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                : 'bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-slate-700 hover:border-indigo-400' }}">
                        Hammasi
                    </a>
                    <a href="{{ route('student.activities.index', array_merge(request()->except('time_filter', 'page'), ['time_filter' => 'today'])) }}"
                        class="px-4 py-2 rounded-xl text-sm font-bold border transition-all
            {{ request('time_filter') === 'today'
                ? 'bg-emerald-500 text-white border-emerald-500 shadow-sm'
                : 'bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-slate-700 hover:border-emerald-400' }}">
                        Bugun
                    </a>
                    <a href="{{ route('student.activities.index', array_merge(request()->except('time_filter', 'page'), ['time_filter' => 'upcoming'])) }}"
                        class="px-4 py-2 rounded-xl text-sm font-bold border transition-all
            {{ request('time_filter') === 'upcoming'
                ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                : 'bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-slate-700 hover:border-indigo-400' }}">
                        Kelgusi
                    </a>
                    <a href="{{ route('student.activities.index', array_merge(request()->except('time_filter', 'page'), ['time_filter' => 'past'])) }}"
                        class="px-4 py-2 rounded-xl text-sm font-bold border transition-all
            {{ request('time_filter') === 'past'
                ? 'bg-gray-600 text-white border-gray-600 shadow-sm'
                : 'bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-slate-700 hover:border-gray-400' }}">
                        O'tgan
                    </a>
                </div>
            </form>
        </div>

        {{-- ── ACTIVITY CARDS ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse ($activities as $activity)
                <a href="{{ route('student.activities.show', $activity) }}"
                    class="group bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-800 hover:shadow-xl hover:border-indigo-300 dark:hover:border-indigo-500/50 hover:-translate-y-0.5 transition-all duration-300 flex flex-col overflow-hidden relative">

                    {{-- Top accent line --}}
                    @if ($activity->is_today)
                        <div
                            class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-emerald-400 to-emerald-500 z-10">
                        </div>
                    @elseif ($activity->is_upcoming)
                        <div
                            class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-indigo-400 to-purple-500 z-10">
                        </div>
                    @endif

                    {{-- ── IMAGE / FALLBACK HERO ── --}}
                    <div class="relative h-44 overflow-hidden flex-shrink-0">
                        @if ($activity->image)
                            <img src="{{ Storage::url($activity->image) }}" alt="{{ $activity->name }}"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0"
                                style="background: linear-gradient(to top, rgba(0,0,0,0.45) 0%, transparent 60%);"></div>
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br {{ $activity->type_gradient }} flex items-center justify-center relative overflow-hidden">
                                <div class="absolute inset-0 opacity-10"
                                    style="background-image: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.3) 0%, transparent 60%), radial-gradient(circle at 80% 80%, rgba(0,0,0,0.2) 0%, transparent 50%);">
                                </div>
                                <svg class="w-16 h-16 text-white/30 group-hover:text-white/45 transition-all duration-300 group-hover:scale-110"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                        d="{{ $activity->type_icon }}" />
                                </svg>
                            </div>
                        @endif

                        {{-- Status pill --}}
                        <div class="absolute top-3 right-3 z-10">
                            @if ($activity->is_today)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Bugun
                                </span>
                            @elseif ($activity->is_upcoming)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full shadow-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Kelgusi
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 bg-black/40 backdrop-blur-sm text-white/80 text-xs font-bold rounded-full">
                                    O'tgan
                                </span>
                            @endif
                        </div>

                        {{-- Coin badge --}}
                        @if ($activity->max_coins > 0)
                            <div class="absolute bottom-3 left-3 z-10">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-500/90 backdrop-blur-sm text-white text-xs font-black rounded-full shadow-md">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $activity->max_coins }} coin
                                </span>
                            </div>
                        @endif

                        {{-- File attachment indicator --}}
                        @if ($activity->file)
                            <div class="absolute bottom-3 right-3 z-10">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-bold rounded-lg border border-white/20"
                                    title="Fayl biriktirilgan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Fayl
                                </span>
                            </div>
                        @endif
                    </div>


                    {{-- ── CARD BODY ── --}}
                    <div class="flex flex-col flex-1 p-4 gap-3">

                        {{-- Type badge + mandatory --}}
                        <div class="flex items-center gap-2 flex-wrap">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $activity->type_bg }} {{ $activity->type_text }} text-xs font-bold rounded-lg border {{ $activity->type_border }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="{{ $activity->type_icon }}" />
                                </svg>
                                {{ $activity->type_label }}
                            </span>
                            @if ($activity->is_required)
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-lg border border-rose-200 dark:border-rose-500/30">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Majburiy
                                </span>
                            @endif
                        </div>

                        {{-- Title & description --}}
                        <div class="flex-1">
                            <h3
                                class="text-sm font-black text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-snug line-clamp-2">
                                {{ $activity->name }}
                            </h3>
                            @if ($activity->description)
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                    {{ $activity->description }}
                                </p>
                            @endif
                        </div>

                        {{-- ── META FOOTER ── --}}
                        <div class="pt-3 border-t border-gray-100 dark:border-slate-800 space-y-2">

                            {{-- Date row --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                    <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-bold">{{ $activity->date->format('d.m.Y') }}</span>
                                </div>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ $activity->date->diffForHumans() }}
                                </span>
                            </div>
                            @if ($activity->start_time)
                                <div
                                    class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg {{ $activity->is_today ? 'bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20' : 'bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20' }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0 {{ $activity->is_today ? 'text-emerald-500' : 'text-indigo-500' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span
                                        class="text-xs font-black {{ $activity->is_today ? 'text-emerald-700 dark:text-emerald-400' : 'text-indigo-700 dark:text-indigo-400' }}">
                                        {{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} da boshlanadi
                                    </span>
                                    @if ($activity->is_today)
                                        <span
                                            class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                                    @endif
                                </div>
                            @endif
                            {{-- Due time --}}
                            @if ($activity->due_time)
                                <div
                                    class="flex items-center gap-1.5 text-xs text-rose-600 dark:text-rose-400 font-semibold">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($activity->due_time)->format('H:i') }} gacha topshirish
                                </div>
                            @endif

                            {{-- Group --}}
                            <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                @if ($activity->group)
                                    <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="font-medium text-purple-600 dark:text-purple-400 truncate">
                                        {{ $activity->group->name }}
                                    </span>
                                @else
                                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                                    </svg>
                                    <span class="text-gray-400 dark:text-gray-500">Umumiy e'lon</span>
                                @endif
                            </div>
                        </div>

                        {{-- CTA --}}
                        <div class="pt-1">
                            <div
                                class="w-full flex items-center justify-center gap-2 py-2 bg-indigo-50 dark:bg-indigo-500/10 group-hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 group-hover:text-white text-xs font-bold rounded-xl border border-indigo-200 dark:border-indigo-500/30 group-hover:border-indigo-600 transition-all duration-200">
                                Batafsil ko'rish
                                <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full">
                    <div
                        class="bg-gray-50 dark:bg-slate-900 rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-800 p-16 text-center">
                        <div
                            class="w-20 h-20 bg-gray-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Hech qanday e'lon topilmadi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Filterni o'zgartiring yoki keyinroq qarang</p>
                        @if (request('search') || request('type') || request('from') || request('to') || request('time_filter'))
                            <a href="{{ route('student.activities.index') }}"
                                class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Filtrni tozalash
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ── PAGINATION ── --}}
        @if ($activities->hasPages())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-800 p-4">
                {{ $activities->links() }}
            </div>
        @endif

    </div>
@endsection
