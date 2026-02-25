@extends('Layout.app')
@section('title', 'Dashboard')
@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <span class="text-4xl">🎓</span>
                O'quv Markazi Boshqaruvi
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Faol guruhlar va talabalar holati</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- Total Students --}}
        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/50 p-6 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">Jami o'quvchilar</span>
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalStudents) }}</div>
            <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">Ro'yxatdagi barcha o'quvchilar</p>
        </div>

        {{-- Active Groups --}}
        <div class="bg-gradient-to-br from-white to-indigo-50 dark:from-gray-800 dark:to-indigo-900/10 p-6 rounded-2xl shadow-lg border border-indigo-100 dark:border-indigo-900/30 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">Faol guruhlar</span>
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $activeGroups }} ta</div>
            <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">Hozirda faol o'quv guruhlari</p>
        </div>

        {{-- Monthly Revenue --}}
        <div class="bg-gradient-to-br from-white to-green-50 dark:from-gray-800 dark:to-green-900/10 p-6 rounded-2xl shadow-lg border border-green-100 dark:border-green-900/30 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">Oylik tushum</span>
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                {{ number_format($monthlyRevenue, 0, '.', ' ') }} so'm
            </div>
            <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">{{ now()->translatedFormat('F Y') }} oyi uchun</p>
        </div>

    </div>

    {{-- Active Groups Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl dark:shadow-black/40 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">

        {{-- Table Header --}}
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800/50 dark:to-gray-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Faol Guruhlar</h2>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
                        Davomat ko'rsatkichi ({{ now()->format('F') }}):
                        <span class="font-semibold {{ $attendanceRate >= 75 ? 'text-green-500' : ($attendanceRate >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                            {{ $attendanceRate }}%
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Guruh / Kurs</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">O'qituvchi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Dars vaqti</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">O'quvchilar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Holat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($groups as $group)
                        @php
                            $activeCount  = $group->activeStudents->count();
                            $capacity     = $group->capacity ?: 1;
                            $fillPercent  = min(100, round(($activeCount / $capacity) * 100));
                            $primaryTeacher = $group->teachers->firstWhere('pivot.is_primary', true)
                                ?? $group->teachers->first();
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors group">

                            {{-- Course / Group name --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-lg group-hover:scale-110 transition-transform shrink-0">
                                        {{ mb_strtoupper(mb_substr($group->course->title ?? 'N/A', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white text-base">
                                            {{ $group->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $group->course->title ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Teacher --}}
                            <td class="px-6 py-5">
                                @if ($primaryTeacher)
                                    <div class="flex items-center gap-3">
                                        <img
                                            class="w-9 h-9 rounded-lg object-cover ring-2 ring-gray-200 dark:ring-gray-600"
                                            src="{{ $primaryTeacher->user->photo
                                                ? asset('storage/' . $primaryTeacher->user->photo)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($primaryTeacher->user->name . ' ' . $primaryTeacher->user->surname) . '&background=6366f1&color=fff' }}"
                                            alt="{{ $primaryTeacher->user->name }}">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $primaryTeacher->user->surname }} {{ $primaryTeacher->user->name }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500 italic">Belgilanmagan</span>
                                @endif
                            </td>

                            {{-- Schedule --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @if ($group->start_time && $group->end_time)
                                        {{ \Carbon\Carbon::parse($group->start_time)->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($group->end_time)->format('H:i') }}
                                    @else
                                        <span class="text-gray-400 italic">—</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Students / Capacity --}}
                            <td class="px-6 py-5">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        {{ $activeCount }} / {{ $group->capacity }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div
                                                class="h-full rounded-full {{ $fillPercent >= 90 ? 'bg-gradient-to-r from-green-500 to-green-600' : ($fillPercent >= 60 ? 'bg-gradient-to-r from-indigo-500 to-indigo-600' : 'bg-gradient-to-r from-yellow-400 to-yellow-500') }}"
                                                style="width: {{ $fillPercent }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 w-8 text-right">
                                            {{ $fillPercent }}%
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-5">
                                @if ($fillPercent >= 100)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Guruh to'lgan
                                    </span>
                                @elseif ($fillPercent >= 75)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Guruh to'lmoqda
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Faol
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <p class="text-sm font-medium">Faol guruhlar topilmadi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($groups->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $groups->firstItem() }}–{{ $groups->lastItem() }} dan {{ $groups->total() }} ta guruh ko'rsatilyapti
                </span>
                <div class="flex gap-2">
                    @if ($groups->onFirstPage())
                        <span class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-400 dark:text-gray-600 opacity-50 cursor-not-allowed flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Oldingi
                        </span>
                    @else
                        <a href="{{ $groups->previousPageUrl() }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Oldingi
                        </a>
                    @endif

                    @if ($groups->hasMorePages())
                        <a href="{{ $groups->nextPageUrl() }}"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 transition-all flex items-center gap-2">
                            Keyingi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-400 dark:text-gray-600 opacity-50 cursor-not-allowed flex items-center gap-2">
                            Keyingi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        @endif

    </div>

@endsection