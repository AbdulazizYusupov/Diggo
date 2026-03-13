@extends('Layout.app')

@section('title', $activity->name)

@section('content')
    @php
        $typeLabels = ['homework'=>'Uyga vazifa','exam'=>'Imtihon','competition'=>'Musobaqa','project'=>'Loyiha','other'=>'Boshqa'];
        $typeIcons  = [
            'homework'    => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'exam'        => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'competition' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            'project'     => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
            'other'       => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
        ];
        $typeGradients = [
            'homework'    => ['from-blue-500','to-blue-700','shadow-blue-200 dark:shadow-blue-900/40','bg-blue-100 dark:bg-blue-500/20','text-blue-700 dark:text-blue-300','border-blue-200 dark:border-blue-500/30'],
            'exam'        => ['from-rose-500','to-rose-700','shadow-rose-200 dark:shadow-rose-900/40','bg-rose-100 dark:bg-rose-500/20','text-rose-700 dark:text-rose-300','border-rose-200 dark:border-rose-500/30'],
            'competition' => ['from-purple-500','to-purple-700','shadow-purple-200 dark:shadow-purple-900/40','bg-purple-100 dark:bg-purple-500/20','text-purple-700 dark:text-purple-300','border-purple-200 dark:border-purple-500/30'],
            'project'     => ['from-emerald-500','to-emerald-700','shadow-emerald-200 dark:shadow-emerald-900/40','bg-emerald-100 dark:bg-emerald-500/20','text-emerald-700 dark:text-emerald-300','border-emerald-200 dark:border-emerald-500/30'],
            'other'       => ['from-gray-500','to-gray-700','shadow-gray-200 dark:shadow-gray-900/40','bg-gray-100 dark:bg-gray-500/20','text-gray-700 dark:text-gray-300','border-gray-200 dark:border-gray-500/30'],
        ];
        $tg = $typeGradients[$activity->type] ?? $typeGradients['other'];

        $statusConfig = [
            'draft'     => ['label' => 'Qoralama',    'dot' => 'bg-gray-400',    'class' => 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300 border-gray-200 dark:border-gray-600'],
            'active'    => ['label' => 'Faol',        'dot' => 'bg-emerald-400', 'class' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/30'],
            'completed' => ['label' => 'Tugallangan', 'dot' => 'bg-blue-400',    'class' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 border-blue-200 dark:border-blue-500/30'],
            'cancelled' => ['label' => 'Bekor',       'dot' => 'bg-rose-400',    'class' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 border-rose-200 dark:border-rose-500/30'],
        ];
        $sc = $statusConfig[$activity->status] ?? ['label' => ucfirst($activity->status), 'dot' => 'bg-gray-400', 'class' => 'bg-gray-100 text-gray-700'];

        $totalCoins   = $activity->coinTransactions->sum('coins');
        $avgCoins     = $activity->coinTransactions->count() > 0 ? round($activity->coinTransactions->avg('coins'), 1) : 0;
        $participants = $activity->coinTransactions->count();
        $completionPct = ($activity->max_coins > 0 && $participants > 0)
            ? round(($avgCoins / $activity->max_coins) * 100) : 0;
    @endphp

    {{-- ══════════════════════════════════════════
         HERO SECTION — full-width image banner
    ═══════════════════════════════════════════════ --}}
    <div class="w-full -mx-4 sm:-mx-6 lg:-mx-8 mb-6 relative">

        {{-- Image / Gradient hero --}}
        <div class="relative w-full h-72 sm:h-[26rem] lg:h-[32rem] overflow-hidden">
            @if($activity->image)
                <img src="{{ Storage::url($activity->image) }}"
                     alt="{{ $activity->name }}"
                     class="absolute inset-0 w-full h-full object-cover object-center">
                {{-- Subtle gradient only at very bottom for text readability --}}
                <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(2,6,23,0.95) 0%, rgba(2,6,23,0.55) 30%, rgba(2,6,23,0.1) 60%, transparent 100%);"></div>
            @else
                {{-- Decorative gradient fallback --}}
                <div class="w-full h-full bg-gradient-to-br {{ $tg[0] }} {{ $tg[1] }} relative overflow-hidden">
                    {{-- Noise texture overlay --}}
                    <div class="absolute inset-0 opacity-20"
                         style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/><feColorMatrix type=%22saturate%22 values=%220%22/></filter><rect width=%22200%22 height=%22200%22 filter=%22url(%23n)%22 opacity=%221%22/></svg>');">
                    </div>
                    {{-- Decorative circles --}}
                    <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-20 -left-10 w-80 h-80 bg-black/20 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                        <svg class="w-32 h-32 lg:w-48 lg:h-48 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="{{ $typeIcons[$activity->type] ?? $typeIcons['other'] }}" />
                        </svg>
                    </div>
                    {{-- Dark overlay for text --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-gray-950/30 to-transparent"></div>
                </div>
            @endif

            {{-- Top bar: back + actions --}}
            <div class="absolute top-4 left-0 right-0 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <a href="{{ route('activities.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-black/40 hover:bg-black/60 backdrop-blur-md text-white text-sm font-bold rounded-xl border border-white/10 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Orqaga
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('activities.edit', $activity->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-black/40 hover:bg-amber-500/80 backdrop-blur-md text-white text-sm font-bold rounded-xl border border-white/10 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="hidden sm:inline">Tahrirlash</span>
                    </a>
                    <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-black/40 hover:bg-rose-500/80 backdrop-blur-md text-white text-sm font-bold rounded-xl border border-white/10 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="hidden sm:inline">O'chirish</span>
                    </button>
                </div>
            </div>

            {{-- Bottom hero content --}}
            <div class="absolute bottom-0 left-0 right-0 px-4 sm:px-6 lg:px-8 pb-6 pt-16">
                {{-- Badges row --}}
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    {{-- Type badge --}}
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-white/15 backdrop-blur-sm text-white border border-white/20 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeIcons[$activity->type] ?? $typeIcons['other'] }}" />
                        </svg>
                        {{ $typeLabels[$activity->type] ?? ucfirst($activity->type) }}
                    </span>
                    {{-- Status badge --}}
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-white/15 backdrop-blur-sm text-white border border-white/20 shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }} inline-block"></span>
                        {{ $sc['label'] }}
                    </span>
                    @if($activity->is_required)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-rose-500/80 backdrop-blur-sm text-white border border-rose-400/30 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Majburiy
                        </span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight drop-shadow-lg mb-3">
                    {{ $activity->name }}
                </h1>

                {{-- Meta row --}}
                <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 text-sm text-white/80">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-semibold">{{ $activity->date->format('d.m.Y') }}</span>
                    </span>
                    @if($activity->due_time)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($activity->due_time)->format('H:i') }} gacha</span>
                        </span>
                    @endif
                    @if($activity->group)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-semibold">{{ $activity->group->name }}</span>
                        </span>
                    @endif
                    @if($activity->creator)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-semibold">{{ $activity->creator->name }}</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- END HERO --}}

    <div class="space-y-6">

        {{-- ── STAT CARDS ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Participants --}}
            <div class="group relative bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-5 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent dark:from-indigo-500/5 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-indigo-100 dark:bg-indigo-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="w-9 h-9 bg-indigo-50 dark:bg-indigo-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $participants }}</p>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ishtirokchi</p>
                </div>
            </div>

            {{-- Max coins --}}
            <div class="group relative bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-5 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-transparent dark:from-amber-500/5 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-amber-100 dark:bg-amber-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="w-9 h-9 bg-amber-50 dark:bg-amber-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-400 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $activity->max_coins }}</p>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Maks. ball</p>
                </div>
            </div>

            {{-- Average --}}
            <div class="group relative bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-5 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent dark:from-emerald-500/5 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-emerald-100 dark:bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="w-9 h-9 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-400 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $avgCoins }}</p>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">O'rtacha</p>
                </div>
            </div>

            {{-- Total --}}
            <div class="group relative bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-5 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent dark:from-purple-500/5 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-11 h-11 bg-purple-100 dark:bg-purple-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <div class="w-9 h-9 bg-purple-50 dark:bg-purple-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-400 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $totalCoins }}</p>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jami ball</p>
                </div>
            </div>
        </div>

        {{-- ── MAIN CONTENT GRID ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Description --}}
                @if($activity->description)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 lg:p-8 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-9 h-9 bg-gray-100 dark:bg-slate-800 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            </div>
                            <h2 class="text-base font-black text-gray-900 dark:text-white tracking-tight">Tavsif</h2>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-[15px]">{{ $activity->description }}</p>
                    </div>
                @endif

                {{-- File attachment --}}
                @if($activity->file)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 dark:text-white">Biriktirilgan hujjat</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Faoliyat materiali</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($activity->file) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-indigo-200 dark:shadow-none flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Yuklab olish
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Completion progress bar (if has transactions) --}}
                @if($participants > 0 && $activity->max_coins > 0)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">O'rtacha bajarish darajasi</h3>
                            <span class="text-2xl font-black {{ $completionPct >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($completionPct >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">{{ $completionPct }}%</span>
                        </div>
                        <div class="w-full h-3 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 {{ $completionPct >= 80 ? 'bg-emerald-500' : ($completionPct >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                 style="width: {{ $completionPct }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-400 dark:text-gray-500">
                            <span>0</span>
                            <span>{{ $activity->max_coins }} ball</span>
                        </div>
                    </div>
                @endif

                {{-- Participants / Results --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-purple-100 dark:bg-purple-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <h2 class="text-base font-black text-gray-900 dark:text-white tracking-tight">Natijalar</h2>
                        </div>
                        @if($participants > 0)
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400">
                                {{ $participants }} talaba
                            </span>
                        @endif
                    </div>

                    {{-- List --}}
                    @if($participants > 0)
                        <div class="divide-y divide-gray-50 dark:divide-slate-800/80">
                            @foreach($activity->coinTransactions->sortByDesc('coins') as $i => $transaction)
                                @php
                                    $pct = $activity->max_coins > 0 ? ($transaction->coins / $activity->max_coins) * 100 : 0;
                                    $bc  = $pct >= 80 ? 'emerald' : ($pct >= 50 ? 'amber' : 'rose');
                                    $rank = $i + 1;
                                    $medal = $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : null));
                                @endphp
                                <div class="flex items-center gap-4 px-6 lg:px-8 py-4 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                                    {{-- Rank --}}
                                    <div class="w-8 text-center flex-shrink-0">
                                        @if($medal)
                                            <span class="text-xl">{{ $medal }}</span>
                                        @else
                                            <span class="text-sm font-bold text-gray-400 dark:text-gray-600">#{{ $rank }}</span>
                                        @endif
                                    </div>

                                    {{-- Avatar --}}
                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 font-black text-sm text-white shadow-sm
                                        @if($rank===1) bg-gradient-to-br from-yellow-400 to-yellow-600
                                        @elseif($rank===2) bg-gradient-to-br from-slate-400 to-slate-600
                                        @elseif($rank===3) bg-gradient-to-br from-orange-400 to-orange-600
                                        @else bg-gradient-to-br from-purple-500 to-purple-700 @endif">
                                        {{ strtoupper(substr($transaction->student->user->name ?? 'N', 0, 2)) }}
                                    </div>

                                    {{-- Name & date --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                            {{ $transaction->student->user->name ?? 'Noma\'lum' }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            {{ $transaction->created_at->format('d.m.Y · H:i') }}
                                        </p>
                                    </div>

                                    {{-- Progress + score --}}
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        @if($activity->max_coins > 0)
                                            <div class="hidden sm:flex flex-col items-end gap-1">
                                                <span class="text-[10px] text-gray-400 font-medium">{{ round($pct) }}%</span>
                                                <div class="w-20 h-1.5 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                                    <div class="h-full bg-{{ $bc }}-500 rounded-full" style="width: {{ min($pct, 100) }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-black rounded-xl bg-{{ $bc }}-100 text-{{ $bc }}-700 dark:bg-{{ $bc }}-500/20 dark:text-{{ $bc }}-300 border border-{{ $bc }}-200 dark:border-{{ $bc }}-500/30">
                                            {{ $transaction->coins }}
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.31 14.71c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V10.9h-2.34v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                            <div class="w-20 h-20 bg-gray-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center mb-5">
                                <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-sm font-black text-gray-900 dark:text-white mb-1">Natijalar yo'q</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 max-w-xs leading-relaxed">Bu faoliyat uchun hali natijalar kiritilmagan</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="lg:col-span-1 space-y-5">

                {{-- Details card --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm sticky top-6">
                    <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4">Faoliyat ma'lumotlari</h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Turi</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg {{ $tg[3] }} {{ $tg[4] }} border {{ $tg[5] }}">
                                {{ $typeLabels[$activity->type] ?? ucfirst($activity->type) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Holat</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg border {{ $sc['class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                {{ $sc['label'] }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Sana</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $activity->date->format('d.m.Y') }}</span>
                        </div>
                        @if($activity->due_time)
                            <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Muddat</span>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($activity->due_time)->format('H:i') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Maksimal ball</span>
                            <span class="text-xs font-black text-amber-600 dark:text-amber-400">{{ $activity->max_coins }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Majburiy</span>
                            @if($activity->is_required)
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30">Ha</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">Yo'q</span>
                            @endif
                        </div>
                        @if($activity->group)
                            <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Guruh</span>
                                <span class="text-xs font-bold text-purple-600 dark:text-purple-400">{{ $activity->group->name }}</span>
                            </div>
                        @endif
                        @if($activity->group?->course)
                            <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Kurs</span>
                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 truncate ml-4 text-right max-w-[120px]">{{ $activity->group->course->title }}</span>
                            </div>
                        @endif
                        @if($activity->creator)
                            <div class="flex items-center justify-between py-2.5 border-b border-gray-50 dark:border-slate-800">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Yaratuvchi</span>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $activity->creator->name }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Yaratilgan</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $activity->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>

                    {{-- Quick actions --}}
                    <div class="mt-5 pt-5 border-t border-gray-100 dark:border-slate-800 space-y-2.5">
                        <a href="{{ route('activities.edit', $activity->id) }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-amber-50 dark:hover:bg-amber-500/10 border border-transparent hover:border-amber-200 dark:hover:border-amber-500/20 transition-all group">
                            <div class="w-9 h-9 bg-amber-100 dark:bg-amber-500/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Tahrirlash</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Ma'lumotlarni o'zgartirish</p>
                            </div>
                        </a>

                        @if($activity->group)
                            <a href="{{ route('groups.show', $activity->group->id) }}"
                               class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-purple-500/10 border border-transparent hover:border-purple-200 dark:hover:border-purple-500/20 transition-all group">
                                <div class="w-9 h-9 bg-purple-100 dark:bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Guruhga o'tish</p>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate max-w-[130px]">{{ $activity->group->name }}</p>
                                </div>
                            </a>
                        @endif

                        <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                            class="w-full flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-500/10 border border-transparent hover:border-rose-200 dark:hover:border-rose-500/20 transition-all group text-left">
                            <div class="w-9 h-9 bg-rose-100 dark:bg-rose-500/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-rose-600 dark:text-rose-400 leading-tight">O'chirish</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Faoliyatni butunlay o'chirish</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ DELETE MODAL ══ --}}
    <div id="delete-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm">
        <div class="relative w-full max-w-sm bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-white/5 overflow-hidden">
            {{-- Danger stripe at top --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-rose-400 via-rose-500 to-rose-600"></div>
            <div class="p-8 text-center">
                <div class="mx-auto w-16 h-16 bg-rose-100 dark:bg-rose-500/20 rounded-2xl flex items-center justify-center mb-5 rotate-3">
                    <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">O'chirishni tasdiqlang</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-7 leading-relaxed">
                    <span class="font-bold text-gray-800 dark:text-white">{{ $activity->name }}</span> faoliyati tizimdan butunlay o'chiriladi. Bu amalni qaytarib bo'lmaydi.
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="px-5 py-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-2xl transition-all active:scale-95 text-sm">
                        Bekor qilish
                    </button>
                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-5 py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 dark:shadow-none transition-all active:scale-95 text-sm">
                            O'chirilsin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection