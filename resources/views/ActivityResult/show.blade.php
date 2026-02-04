@extends('Layout.app')

@section('title', 'Natija tafsilotlari')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <!-- Left: Result Info -->
            <div class="flex items-start gap-4 lg:gap-6">
                {{-- Result Icon --}}
                <div
                    class="flex-shrink-0 w-16 h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-3xl shadow-xl shadow-emerald-200 dark:shadow-none flex items-center justify-center transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    {{-- Student Name --}}
                    <div class="flex items-start gap-3 mb-2">
                        <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white">
                            {{ $result->student->user->name ?? 'Noma\'lum' }}
                        </h1>
                        @if ($result->rank)
                            @php
                                $rankColors = [
                                    1 => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/30',
                                    2 => 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-400 border-gray-200 dark:border-gray-500/30',
                                    3 => 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400 border-orange-200 dark:border-orange-500/30',
                                ];
                                $defaultRankColor =
                                    'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 border-blue-200 dark:border-blue-500/30';
                            @endphp
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl {{ $rankColors[$result->rank] ?? $defaultRankColor }} border shadow-sm">
                                @if ($result->rank <= 3)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endif
                                {{ $result->rank }}-o'rin
                            </span>
                        @endif
                    </div>

                    {{-- Activity Name --}}
                    <div class="flex items-center gap-2 mb-3">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-bold rounded-xl bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            {{ $result->activity->name }}
                        </span>
                    </div>

                    {{-- Date --}}
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-bold">{{ $result->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex items-center gap-2 lg:gap-3">
                <a href="{{ route('activity-results.index') }}"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Orqaga</span>
                </a>

                <a href="{{ route('activity-results.edit', $result->id) }}"
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
            {{-- Earned Coins --}}
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-500/5 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-emerald-500 dark:bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1">Olgan
                    coinlar</p>
                <p class="text-3xl lg:text-4xl font-black text-emerald-600 dark:text-emerald-400">
                    {{ $result->earned_coins }}
                </p>
            </div>

            {{-- Max Coins --}}
            <div
                class="bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-500/10 dark:to-amber-500/5 rounded-2xl shadow-sm border border-amber-200 dark:border-amber-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-amber-500 dark:bg-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400 mb-1">Maksimal</p>
                <p class="text-3xl lg:text-4xl font-black text-amber-600 dark:text-amber-400">
                    {{ $result->activity->max_coins }}
                </p>
            </div>

            {{-- Percentage --}}
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-500/5 rounded-2xl shadow-sm border border-blue-200 dark:border-blue-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400 mb-1">Foiz</p>
                <p class="text-3xl lg:text-4xl font-black text-blue-600 dark:text-blue-400">
                    {{ $result->activity->max_coins > 0 ? round(($result->earned_coins / $result->activity->max_coins) * 100) : 0 }}%
                </p>
            </div>

            {{-- Rank --}}
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100/50 dark:from-purple-500/10 dark:to-purple-500/5 rounded-2xl shadow-sm border border-purple-200 dark:border-purple-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-purple-500 dark:bg-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400 mb-1">Rang</p>
                <p class="text-3xl lg:text-4xl font-black text-purple-600 dark:text-purple-400">
                    {{ $result->rank ? $result->rank . '-o\'rin' : '-' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                {{-- Note Card --}}
                @if ($result->note)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white">Izoh</h2>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $result->note }}</p>
                    </div>
                @endif

                {{-- Related Results --}}
                @if ($relatedResults->count() > 0)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">Boshqa natijalar</h2>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400">
                                {{ $relatedResults->count() }} ta
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach ($relatedResults as $related)
                                <a href="{{ route('activity-results.show', $related->id) }}"
                                    class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all group">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                {{ $related->activity->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $related->created_at->format('d.m.Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $related->earned_coins }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Info -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-6 lg:p-8 sticky top-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4">Natija ma'lumotlari</h3>
                        <div class="space-y-3">
                            {{-- Student --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Talaba:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white truncate ml-2">
                                    {{ $result->student->user->name ?? 'N/A' }}
                                </span>
                            </div>

                            {{-- Activity --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Faoliyat:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ ucfirst($result->activity->type) }}
                                </span>
                            </div>

                            {{-- Group --}}
                            @if ($result->activity->group)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Guruh:</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white truncate ml-2">
                                        {{ $result->activity->group->name }}
                                    </span>
                                </div>
                            @endif

                            {{-- Date --}}
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Sana:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $result->created_at->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Tezkor amallar</h4>
                        <div class="space-y-2">
                            <a href="{{ route('activity-results.edit', $result->id) }}"
                                class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-xl transition-all group">
                                <div
                                    class="w-10 h-10 bg-amber-100 dark:bg-amber-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Tahrirlash</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Natija ma'lumotlarini o'zgartirish
                                    </p>
                                </div>
                            </a>

                            <a href="{{ route('activities.show', $result->activity->id) }}"
                                class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-xl transition-all group">
                                <div
                                    class="w-10 h-10 bg-indigo-100 dark:bg-indigo-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Faoliyatga o'tish</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $result->activity->name }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
        <div
            class="relative w-full max-w-sm p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5 transform transition-all">
            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 dark:bg-rose-500/10 mb-6 rotate-3">
                    <svg class="h-10 w-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">O'chirishni tasdiqlang</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Rostdan ham <span
                        class="font-bold text-slate-900 dark:text-white underline decoration-rose-500/30">{{ $result->student->user->name ?? 'N/A' }}</span>
                    natijasini tizimdan butunlay o'chirmoqchimisiz?
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor qilish
                    </button>
                    <form action="{{ route('activity-results.destroy', $result->id) }}" method="POST" class="m-0">
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