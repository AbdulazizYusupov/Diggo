@extends('Layout.app')

@section('title', $activity->name)

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <!-- Left: Activity Info -->
            <div class="flex items-start gap-4 lg:gap-6">
                {{-- Activity Icon --}}
                <div
                    class="flex-shrink-0 w-16 h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 rounded-3xl shadow-xl shadow-indigo-200 dark:shadow-none flex items-center justify-center transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    @if($activity->type === 'homework')
                        <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    @elseif($activity->type === 'exam')
                        <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    @else
                        <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    {{-- Activity Name & Type --}}
                    <div class="flex items-start gap-3 mb-2">
                        <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white">
                            {{ $activity->name }}
                        </h1>
                        @php
                            $typeColors = [
                                'homework' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 border-blue-200 dark:border-blue-500/30',
                                'exam' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400 border-rose-200 dark:border-rose-500/30',
                                'attendance' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/30',
                                'participation' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border-amber-200 dark:border-amber-500/30',
                            ];
                            $typeNames = [
                                'homework' => 'Uyga vazifa',
                                'exam' => 'Imtihon',
                                'attendance' => 'Davomat',
                                'participation' => 'Faollik',
                            ];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl {{ $typeColors[$activity->type] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }} border shadow-sm">
                            {{ $typeNames[$activity->type] ?? ucfirst($activity->type) }}
                        </span>
                    </div>

                    {{-- Group & Course Info --}}
                    @if ($activity->group)
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-bold rounded-xl bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $activity->group->name }}
                            </span>
                            @if($activity->group->course)
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-bold rounded-xl bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    {{ $activity->group->course->title }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Date --}}
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-bold">{{ $activity->date->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex items-center gap-2 lg:gap-3">
                <a href="{{ route('activities.index') }}"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Orqaga</span>
                </a>

                <a href="{{ route('activities.edit', $activity->id) }}"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-amber-500 hover:bg-amber-600 dark:bg-amber-600 dark:hover:bg-amber-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-amber-200 dark:shadow-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="hidden sm:inline">Tahrirlash</span>
                </a>

                <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-rose-500 hover:bg-rose-600 dark:bg-rose-600 dark:hover:bg-rose-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-rose-200 dark:shadow-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="hidden sm:inline">O'chirish</span>
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            {{-- Total Participants --}}
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100/50 dark:from-indigo-500/10 dark:to-indigo-500/5 rounded-2xl shadow-sm border border-indigo-200 dark:border-indigo-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-indigo-500 dark:bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-indigo-600 dark:text-indigo-400 mb-1">Ishtirokchilar</p>
                <p class="text-3xl lg:text-4xl font-black text-indigo-600 dark:text-indigo-400">
                    {{ $activity->coinTransactions->count() }}
                </p>
            </div>

            {{-- Max Coins --}}
            <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-500/10 dark:to-amber-500/5 rounded-2xl shadow-sm border border-amber-200 dark:border-amber-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-amber-500 dark:bg-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400 mb-1">Maksimal ball</p>
                <p class="text-3xl lg:text-4xl font-black text-amber-600 dark:text-amber-400">
                    {{ $activity->max_coins }}
                </p>
            </div>

            {{-- Average Coins --}}
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-500/5 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-emerald-500 dark:bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1">O'rtacha ball</p>
                <p class="text-3xl lg:text-4xl font-black text-emerald-600 dark:text-emerald-400">
                    {{ $activity->coinTransactions->count() > 0 ? round($activity->coinTransactions->avg('coins'), 1) : 0 }}
                </p>
            </div>

            {{-- Total Coins Awarded --}}
            <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 dark:from-purple-500/10 dark:to-purple-500/5 rounded-2xl shadow-sm border border-purple-200 dark:border-purple-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-500 dark:bg-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400 mb-1">Jami berilgan</p>
                <p class="text-3xl lg:text-4xl font-black text-purple-600 dark:text-purple-400">
                    {{ $activity->coinTransactions->sum('coins') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Description & Participants -->
            <div class="lg:col-span-2 space-y-6">
                {{-- Description Card --}}
                @if($activity->description)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white">Tavsif</h2>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $activity->description }}</p>
                    </div>
                @endif

                {{-- Participants List --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6 lg:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Natijalar</h2>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400">
                            {{ $activity->coinTransactions->count() }} talaba
                        </span>
                    </div>

                    @if($activity->coinTransactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($activity->coinTransactions->sortByDesc('coins') as $transaction)
                                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                                            <span class="text-white font-bold text-sm">
                                                {{ substr($transaction->student->user->name ?? 'N/A', 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                {{ $transaction->student->user->name ?? 'Noma\'lum' }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $transaction->created_at->format('d.m.Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @php
                                            $percentage = $activity->max_coins > 0 ? ($transaction->coins / $activity->max_coins) * 100 : 0;
                                            $badgeColor = $percentage >= 90 ? 'emerald' : ($percentage >= 70 ? 'amber' : 'rose');
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold rounded-lg bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-700 dark:bg-{{ $badgeColor }}-500/20 dark:text-{{ $badgeColor }}-400">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                                            </svg>
                                            {{ $transaction->coins }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Natijalar yo'q</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Bu faoliyat uchun hali natijalar kiritilmagan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Activity Info -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-6 lg:p-8 sticky top-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4">Faoliyat ma'lumotlari</h3>
                        <div class="space-y-3">
                            {{-- Type --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Turi:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $typeNames[$activity->type] ?? ucfirst($activity->type) }}
                                </span>
                            </div>

                            {{-- Date --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Sana:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $activity->date->format('d.m.Y') }}
                                </span>
                            </div>

                            {{-- Max Coins --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Maksimal ball:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $activity->max_coins }}
                                </span>
                            </div>

                            {{-- Created At --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Yaratilgan:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $activity->created_at->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Tezkor amallar</h4>
                        <div class="space-y-2">
                            <a href="{{ route('activities.edit', $activity->id) }}"
                                class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-xl transition-all group">
                                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Tahrirlash</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Faoliyat ma'lumotlarini o'zgartirish</p>
                                </div>
                            </a>

                            @if($activity->group)
                                <a href="{{ route('groups.show', $activity->group->id) }}"
                                    class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-purple-500/10 rounded-xl transition-all group">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">Guruhga o'tish</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->group->name }}</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
        <div class="relative w-full max-w-sm p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 dark:bg-rose-500/10 mb-6 rotate-3">
                    <svg class="h-10 w-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">O'chirishni tasdiqlang</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Rostdan ham <span class="font-bold text-slate-900 dark:text-white underline decoration-rose-500/30">{{ $activity->name }}</span> faoliyatini tizimdan butunlay o'chirmoqchimisiz?
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor qilish
                    </button>
                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-5 py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 dark:shadow-none transition-all transform active:scale-95">
                            O'chirilsin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection