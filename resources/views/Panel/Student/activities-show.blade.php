@extends('Layout.app')

@section('title', $activity->name)

@section('content')
    @php
        $typeIcons = [
            'homework'    => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'exam'        => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            'competition' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            'project'     => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
            'other'       => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
        ];
        $typeGradients = [
            'homework'    => 'from-blue-500 to-blue-700',
            'exam'        => 'from-rose-500 to-rose-700',
            'competition' => 'from-purple-500 to-purple-700',
            'project'     => 'from-emerald-500 to-emerald-700',
            'other'       => 'from-gray-500 to-gray-700',
        ];
        $icon = $typeIcons[$activity->type] ?? $typeIcons['other'];
        $grad = $typeGradients[$activity->type] ?? 'from-gray-500 to-gray-700';
    @endphp

    <div class="space-y-6">

        {{-- ══════════════════════════════════════
             HERO BANNER
        ═══════════════════════════════════════════ --}}
        <div class="w-full -mx-4 sm:-mx-6 lg:-mx-8 relative">
            <div class="relative w-full h-64 sm:h-80 lg:h-[26rem] overflow-hidden">

                @if($activity->image)
                    <img src="{{ Storage::url($activity->image) }}"
                         alt="{{ $activity->name }}"
                         class="absolute inset-0 w-full h-full object-cover object-center">
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(2,6,23,0.93) 0%, rgba(2,6,23,0.5) 35%, rgba(2,6,23,0.1) 65%, transparent 100%);"></div>
                @else
                    <div class="w-full h-full bg-gradient-to-br {{ $grad }} relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.4) 0%, transparent 55%), radial-gradient(circle at 75% 75%, rgba(0,0,0,0.3) 0%, transparent 55%);"></div>
                        <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-20 -left-10 w-80 h-80 bg-black/20 rounded-full blur-3xl"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-40 h-40 text-white/15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="{{ $icon }}" />
                            </svg>
                        </div>
                        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(2,6,23,0.85) 0%, rgba(2,6,23,0.3) 45%, transparent 80%);"></div>
                    </div>
                @endif

                {{-- Back button --}}
                <div class="absolute top-4 left-0 right-0 px-4 sm:px-6 lg:px-8">
                    <a href="{{ route('student.activities.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-black/40 hover:bg-black/60 backdrop-blur-md text-white text-sm font-bold rounded-xl border border-white/10 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        E'lonlarga qaytish
                    </a>
                </div>

                {{-- Hero content --}}
                <div class="absolute bottom-0 left-0 right-0 px-4 sm:px-6 lg:px-8 pb-6 pt-12">
                    {{-- Badges --}}
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-white/15 backdrop-blur-sm text-white border border-white/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                            </svg>
                            {{ $activity->type_label }}
                        </span>

                        @if($activity->is_today)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-emerald-500/80 backdrop-blur-sm text-white border border-emerald-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                Bugun
                            </span>
                        @elseif($activity->is_upcoming)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-indigo-600/80 backdrop-blur-sm text-white border border-indigo-400/30">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Kelgusi
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-xl bg-black/40 backdrop-blur-sm text-white/70 border border-white/10">
                                O'tgan
                            </span>
                        @endif

                        @if($activity->is_required)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-rose-500/80 backdrop-blur-sm text-white border border-rose-400/30">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Majburiy
                            </span>
                        @endif

                        @if($activity->max_coins > 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-amber-500/80 backdrop-blur-sm text-white border border-amber-400/30">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $activity->max_coins }} coin
                            </span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight drop-shadow-lg">
                        {{ $activity->name }}
                    </h1>

                    {{-- Meta --}}
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 mt-3 text-sm text-white/75">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold">{{ $activity->date->isoFormat('D MMMM, Y') }}</span>
                        </span>
                        @if($activity->due_time)
                            <span class="flex items-center gap-1.5 text-rose-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-bold">{{ \Carbon\Carbon::parse($activity->due_time)->format('H:i') }} gacha</span>
                            </span>
                        @endif
                        @if($activity->group)
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-semibold">{{ $activity->group->name }}</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- END HERO --}}

        {{-- ══════════════════════════════════════
             ATTENDANCE ALERT BANNER
        ═══════════════════════════════════════════ --}}
        @if($activity->is_required)
            <div class="relative overflow-hidden rounded-2xl border-2 border-rose-300 dark:border-rose-500/50 shadow-sm shadow-rose-100 dark:shadow-none">
                {{-- Background --}}
                <div class="absolute inset-0 bg-gradient-to-r from-rose-50 to-rose-100/60 dark:from-rose-500/10 dark:to-rose-500/5"></div>
                {{-- Left accent bar --}}
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500 rounded-l-2xl"></div>
                <div class="relative px-6 py-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-rose-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-rose-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-black text-rose-800 dark:text-rose-300">Qatnashish majburiy!</p>
                        <p class="text-sm text-rose-700 dark:text-rose-400 mt-1 leading-relaxed">
                            Bu faoliyat <strong>barcha talabalar uchun majburiy</strong> hisoblanadi. Qatnashmaslik bahongizga salbiy ta'sir qilishi mumkin.
                            @if($activity->due_time)
                                Topshirish muddati: <strong>{{ $activity->date->format('d.m.Y') }}, soat {{ \Carbon\Carbon::parse($activity->due_time)->format('H:i') }}</strong>.
                            @else
                                Faoliyat sanasi: <strong>{{ $activity->date->isoFormat('D MMMM, Y') }}</strong>.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ══════════════════════════════════════
             COUNTDOWN / TODAY BANNER
        ═══════════════════════════════════════════ --}}
        @if($activity->is_today)
            <div class="relative overflow-hidden rounded-2xl border border-emerald-200 dark:border-emerald-500/30 shadow-sm">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-transparent"></div>
                <div class="relative px-6 py-4 flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-black text-emerald-800 dark:text-emerald-300">Bu faoliyat bugun!</p>
                        <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">Omad tilaymiz — hammasi yaxshi o'tsin.</p>
                    </div>
                    <span class="ml-auto w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                </div>
            </div>
        @elseif($activity->days_left !== null && $activity->days_left > 0)
            <div class="relative overflow-hidden rounded-2xl border border-indigo-200 dark:border-indigo-500/30 shadow-sm">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-50 to-indigo-100/50 dark:from-indigo-500/10 dark:to-transparent"></div>
                <div class="relative px-6 py-4 flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-black text-indigo-800 dark:text-indigo-300">
                            <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $activity->days_left }}</span>
                            kun qoldi
                        </p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-0.5">{{ $activity->date->isoFormat('D MMMM, Y') }}</p>
                    </div>

                    {{-- Progress dots --}}
                    <div class="ml-auto hidden sm:flex items-center gap-1.5">
                        @php $urgency = min($activity->days_left, 30); @endphp
                        @for($d = 0; $d < 5; $d++)
                            <div class="w-2 h-2 rounded-full {{ $d < (5 - min(intval($urgency/7), 4)) ? 'bg-indigo-500' : 'bg-indigo-200 dark:bg-indigo-800' }}"></div>
                        @endfor
                    </div>
                </div>
            </div>
        @elseif(!$activity->is_today && !$activity->is_upcoming)
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm">
                <div class="absolute inset-0 bg-gray-50 dark:bg-slate-800/50"></div>
                <div class="relative px-6 py-4 flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-400 dark:bg-gray-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-600 dark:text-gray-400">Bu faoliyat muddati o'tib ketgan.</p>
                </div>
            </div>
        @endif

        {{-- ══════════════════════════════════════
             MAIN CONTENT GRID
        ═══════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT — Description + File --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Description --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-slate-800 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Tavsif</h2>
                    </div>
                    <div class="px-6 py-5">
                        @if($activity->description)
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $activity->description }}</p>
                        @else
                            <div class="flex flex-col items-center py-6 text-center">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Tavsif qo'shilmagan</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Attached file --}}
                @if($activity->file)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Biriktirilgan material</h2>
                        </div>
                        <div class="px-6 py-5">
                            <div class="flex items-center gap-4 p-4 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl border border-indigo-100 dark:border-indigo-500/20">
                                @php
                                    $ext = strtolower(pathinfo($activity->file, PATHINFO_EXTENSION));
                                    $extColors = ['pdf'=>'text-rose-600','doc'=>'text-blue-600','docx'=>'text-blue-600','xls'=>'text-emerald-600','xlsx'=>'text-emerald-600','zip'=>'text-amber-600','txt'=>'text-gray-600'];
                                    $extBg = ['pdf'=>'bg-rose-100 dark:bg-rose-500/20','doc'=>'bg-blue-100 dark:bg-blue-500/20','docx'=>'bg-blue-100 dark:bg-blue-500/20','xls'=>'bg-emerald-100 dark:bg-emerald-500/20','xlsx'=>'bg-emerald-100 dark:bg-emerald-500/20','zip'=>'bg-amber-100 dark:bg-amber-500/20','txt'=>'bg-gray-100 dark:bg-gray-500/20'];
                                    $fc = $extColors[$ext] ?? 'text-indigo-600';
                                    $fb = $extBg[$ext] ?? 'bg-indigo-100 dark:bg-indigo-500/20';
                                @endphp
                                <div class="w-12 h-12 {{ $fb }} rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 {{ $fc }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                        {{ basename($activity->file) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 uppercase tracking-wider">{{ strtoupper($ext) }} fayl • O'qish va yuklab olish mumkin</p>
                                </div>
                                <a href="{{ Storage::url($activity->file) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all active:scale-95 shadow-sm flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Yuklab olish
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- My result (if student has a transaction) --}}
                @if(isset($myTransaction) && $myTransaction)
                    @php
                        $pct = $activity->max_coins > 0 ? round(($myTransaction->coins / $activity->max_coins) * 100) : 0;
                        $rc  = $pct >= 80 ? 'emerald' : ($pct >= 50 ? 'amber' : 'rose');
                    @endphp
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3">
                            <div class="w-8 h-8 bg-{{ $rc }}-100 dark:bg-{{ $rc }}-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-{{ $rc }}-600 dark:text-{{ $rc }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Mening natijam</h2>
                        </div>
                        <div class="px-6 py-5">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-4xl font-black text-{{ $rc }}-600 dark:text-{{ $rc }}-400">{{ $myTransaction->coins }}</span>
                                    <span class="text-sm font-bold text-gray-400 dark:text-gray-500 ml-1">/ {{ $activity->max_coins }} coin</span>
                                </div>
                                <span class="text-2xl font-black text-{{ $rc }}-600 dark:text-{{ $rc }}-400">{{ $pct }}%</span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-{{ $rc }}-500 rounded-full transition-all duration-700"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                Baholandi: {{ $myTransaction->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT — Details sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm sticky top-6 overflow-hidden">

                    {{-- Sidebar header --}}
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800">
                        <h3 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Faoliyat tafsiloti</h3>
                    </div>

                    <div class="px-6 py-5 space-y-0 divide-y divide-gray-50 dark:divide-slate-800">

                        {{-- Type --}}
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                                </svg>
                                Turi
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 {{ $activity->type_bg }} {{ $activity->type_text }} text-xs font-bold rounded-lg border {{ $activity->type_border }}">
                                {{ $activity->type_label }}
                            </span>
                        </div>

                        {{-- Date --}}
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Sana
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-gray-900 dark:text-white">{{ $activity->date->format('d.m.Y') }}</p>
                                <p class="text-[11px] text-gray-400">{{ $activity->date->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Due time --}}
                        @if($activity->due_time)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Muddat
                                </div>
                                <span class="text-xs font-black text-rose-600 dark:text-rose-400">
                                    {{ \Carbon\Carbon::parse($activity->due_time)->format('H:i') }}
                                </span>
                            </div>
                        @endif

                        {{-- Max coins --}}
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Maksimal ball
                            </div>
                            <span class="text-xs font-black text-amber-600 dark:text-amber-400">
                                {{ $activity->max_coins > 0 ? $activity->max_coins . ' coin' : '—' }}
                            </span>
                        </div>

                        {{-- Group --}}
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Guruh
                            </div>
                            <span class="text-xs font-black text-purple-600 dark:text-purple-400 text-right max-w-[120px] truncate">
                                {{ $activity->group?->name ?? 'Umumiy' }}
                            </span>
                        </div>

                        {{-- Course --}}
                        @if($activity->group?->course)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Kurs
                                </div>
                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 text-right max-w-[120px] truncate">
                                    {{ $activity->group->course->title }}
                                </span>
                            </div>
                        @endif

                        {{-- Mandatory --}}
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Qatnashish
                            </div>
                            @if($activity->is_required)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-400 text-xs font-black rounded-lg border border-rose-200 dark:border-rose-500/30">
                                    Majburiy
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 text-xs font-bold rounded-lg border border-gray-200 dark:border-slate-700">
                                    Ixtiyoriy
                                </span>
                            @endif
                        </div>

                        {{-- File indicator --}}
                        @if($activity->file)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Fayl
                                </div>
                                <a href="{{ Storage::url($activity->file) }}" target="_blank"
                                   class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Ko'rish
                                </a>
                            </div>
                        @endif

                    </div>

                    {{-- Mandatory call-to-action box inside sidebar --}}
                    @if($activity->is_required && ($activity->is_today || $activity->is_upcoming))
                        <div class="mx-4 mb-4 p-4 bg-rose-50 dark:bg-rose-500/10 rounded-xl border border-rose-200 dark:border-rose-500/20">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <div>
                                    <p class="text-xs font-black text-rose-700 dark:text-rose-300 mb-0.5">Eslatma</p>
                                    <p class="text-xs text-rose-600 dark:text-rose-400 leading-relaxed">
                                        Qatnashish <strong>majburiy</strong>. O'qituvchingiz sizning natijangizni kiritadi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── BACK BUTTON ── --}}
        <div>
            <a href="{{ route('student.activities.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Barcha e'lonlar
            </a>
        </div>

    </div>
@endsection