@extends('Layout.app')

@section('title', 'O\'quvchi Tafsilotlari')

@section('content')
    @php
        $fullName = trim(
            implode(
                ' ',
                array_filter([$student->user->surname, $student->user->name, $student->user->middle_name ?? null]),
            ),
        );

        $initials = mb_strtoupper(
            mb_substr($student->user->name ?? '', 0, 1) . mb_substr($student->user->surname ?? '', 0, 1),
        );
    @endphp

    <div class="space-y-6">

        {{-- ═══════════════════════════════════════════════
             HEADER
        ═══════════════════════════════════════════════ --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('director.students') }}"
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Ortga">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    O'quvchi Tafsilotlari
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">To'liq ma'lumot va statistika</p>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
             PROFILE CARD
        ═══════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-start gap-6">

                <div
                    class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500
                            flex items-center justify-center text-white text-2xl font-bold
                            flex-shrink-0 select-none">
                    {{ $initials }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white break-words">
                            {{ $fullName }}
                        </h2>
                        <span @class([
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold',
                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' =>
                                $student->user->is_active,
                            'bg-red-100   text-red-700   dark:bg-red-900/30   dark:text-red-400' => !$student->user->is_active,
                        ])>
                            {{ $student->user->is_active ? 'Faol' : 'Faol emas' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        ID: #{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}
                    </p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-3 mt-4">
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Telefon</p>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm mt-0.5">
                                {{ $student->user->phone ?? '—' }}
                            </p>
                        </div>
                        @if ($student->user->email)
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Email</p>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm mt-0.5 break-all">
                                    {{ $student->user->email }}
                                </p>
                            </div>
                        @endif
                        @if ($student->user->gender)
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Jinsi</p>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm mt-0.5">
                                    {{ $student->user->gender === 'male' ? "O'g'il" : 'Qiz' }}
                                </p>
                            </div>
                        @endif
                        @if ($student->enrollment_date)
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Qabul sanasi</p>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm mt-0.5">
                                    {{ $student->enrollment_date->format('d.m.Y') }}
                                </p>
                            </div>
                        @endif
                        @if ($student->school)
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Maktab</p>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm mt-0.5">
                                    {{ $student->school->name }}
                                </p>
                            </div>
                        @endif
                        @if ($student->user->address)
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide">Manzil</p>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm mt-0.5">
                                    {{ $student->user->address }}{{ $student->user->city ? ', ' . $student->user->city : '' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
             ATTENDANCE STATISTICS
        ═══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Davomat % --}}
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30
                        rounded-2xl p-5 border border-indigo-200 dark:border-indigo-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wide">Davomat
                        </p>
                        <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-1">
                            {{ $stats['attendance_rate'] }}<span class="text-lg font-bold">%</span>
                        </p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                            {{ $stats['present_count'] + $stats['late_count'] }} / {{ $stats['total_lessons'] }} dars
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-600 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 w-full bg-indigo-200 dark:bg-indigo-900/40 rounded-full h-1.5">
                    <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500"
                        style="width: {{ $stats['attendance_rate'] }}%"></div>
                </div>
            </div>

            {{-- Kechikkan --}}
            <div
                class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30
                        rounded-2xl p-5 border border-amber-200 dark:border-amber-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wide">Kechikkan
                        </p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ $stats['late_count'] }}
                        </p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                            O'rtacha {{ $stats['avg_late_minutes'] }} daqiqa
                        </p>
                    </div>
                    <div class="p-3 bg-amber-500 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Kelmagan --}}
            <div
                class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30
                        rounded-2xl p-5 border border-red-200 dark:border-red-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-red-700 dark:text-red-400 uppercase tracking-wide">Kelmagan</p>
                        <p class="text-3xl font-extrabold text-red-900 dark:text-red-50 mt-1">
                            {{ $stats['absent_count'] }}
                        </p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                            {{ $stats['total_lessons'] > 0 ? round(($stats['absent_count'] / $stats['total_lessons']) * 100) : 0 }}%
                            darslar
                        </p>
                    </div>
                    <div class="p-3 bg-red-500 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- O'rtacha Ball --}}
            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30
                        rounded-2xl p-5 border border-emerald-200 dark:border-emerald-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">
                            O'rtacha Ball
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ $stats['avg_activity'] }}
                        </p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                            Uy ishi: {{ $stats['avg_homework'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════
             DIRECTOR-ONLY EARNINGS BANNER
             What the director has personally earned from
             this student via commission transactions.
             Never visible to the student.
        ═══════════════════════════════════════════════ --}}
        <div
            class="bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-slate-800 dark:to-slate-900
                    rounded-2xl border border-amber-200 dark:border-slate-700">
            <div class="px-5 py-3 border-b border-amber-200 dark:border-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-500 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest">
                    Mening daromadim — ushbu o'quvchidan
                </p>
            </div>

            <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-10">

                {{-- Total earned --}}
                <div>
                    <p class="text-xs text-amber-700/70 dark:text-slate-400 uppercase tracking-wide">
                        Bu o'quvchidan daromad
                    </p>
                    <p class="text-2xl font-extrabold text-green-600 dark:text-green-400 mt-0.5">
                        {{ number_format($stats['director_total_earned'], 0, '.', ' ') }}
                        <span class="text-sm font-semibold text-amber-700/60 dark:text-slate-400">so'm</span>
                    </p>
                    <p class="text-xs text-amber-700/60 dark:text-slate-500 mt-0.5">
                        Komissiya: {{ $stats['director_commission_pct'] }}%
                    </p>
                </div>

                <div class="hidden sm:block w-px h-10 bg-amber-200 dark:bg-slate-700"></div>

                {{-- Payment count --}}
                <div>
                    <p class="text-xs text-amber-700/70 dark:text-slate-400 uppercase tracking-wide">
                        Komissiya hisoblangan to'lovlar
                    </p>
                    <p class="text-2xl font-extrabold text-gray-800 dark:text-slate-200 mt-0.5">
                        {{ $stats['director_payment_count'] }}
                        <span class="text-sm font-semibold text-amber-700/60 dark:text-slate-400">ta to'lov</span>
                    </p>
                    <p class="text-xs text-amber-700/60 dark:text-slate-500 mt-0.5">
                        O'rtacha:
                        {{ $stats['director_payment_count'] > 0
                            ? number_format($stats['director_total_earned'] / $stats['director_payment_count'], 0, '.', ' ')
                            : 0 }}
                        so'm / to'lov
                    </p>
                </div>

            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
             ACTIVE GROUPS + RECENT ATTENDANCE
        ═══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Active Groups --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5">
                    Faol Guruhlar
                    <span
                        class="ml-2 text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/30
                                 text-indigo-700 dark:text-indigo-400 px-2 py-0.5 rounded-full">
                        {{ $stats['active_groups'] }}
                    </span>
                </h3>

                <div class="space-y-3">
                    @forelse($activeGroups as $groupStudent)
                        @php $group = $groupStudent->group; @endphp
                        <div
                            class="p-4 bg-indigo-50 dark:bg-indigo-900/10 rounded-xl
                                    border border-indigo-100 dark:border-indigo-800/30">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">
                                        {{ $group->name }}
                                    </h4>
                                    <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-0.5">
                                        {{ $group->cours->title ?? '—' }}
                                    </p>
                                </div>
                                <span
                                    class="flex-shrink-0 px-2 py-0.5 bg-green-100 text-green-700
                                             dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded-full">
                                    Faol
                                </span>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
                                @if ($group->start_time)
                                    <span>🕐
                                        {{ $group->start_time }}{{ $group->end_time ? ' – ' . $group->end_time : '' }}</span>
                                @endif
                                @if ($groupStudent->joined_at)
                                    <span>📅 {{ \Carbon\Carbon::parse($groupStudent->joined_at)->format('d.m.Y') }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Faol guruhlar yo'q</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Attendance --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5">So'nggi Davomatlar</h3>

                <div class="space-y-2">
                    @forelse($recentAttendance as $att)
                        @php
                            $statusConfig = match ($att->status) {
                                'present' => [
                                    'label' => 'Keldi',
                                    'bg' => 'bg-green-50 dark:bg-green-900/10',
                                    'badge' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                ],
                                'late' => [
                                    'label' => 'Kechikdi',
                                    'bg' => 'bg-amber-50 dark:bg-amber-900/10',
                                    'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                ],
                                'absent' => [
                                    'label' => 'Kelmadi',
                                    'bg' => 'bg-red-50   dark:bg-red-900/10',
                                    'badge' => 'bg-red-100   text-red-700   dark:bg-red-900/30   dark:text-red-400',
                                ],
                                default => [
                                    'label' => $att->status,
                                    'bg' => 'bg-gray-50  dark:bg-gray-700/30',
                                    'badge' => 'bg-gray-100  text-gray-600  dark:bg-gray-700    dark:text-gray-400',
                                ],
                            };
                        @endphp
                        <div class="flex items-center justify-between px-4 py-2.5 rounded-xl {{ $statusConfig['bg'] }}">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">
                                    {{ $att->lesson->group->name ?? '—' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $att->marked_at?->format('d.m.Y') ?? '—' }}
                                    @if ($att->status === 'late' && $att->late_minutes)
                                        · {{ $att->late_minutes }} daq kechikdi
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if ($att->activity_score !== null)
                                    <span class="text-xs text-gray-400 dark:text-gray-500">⭐
                                        {{ $att->activity_score }}</span>
                                @endif
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusConfig['badge'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Davomat ma'lumotlari yo'q</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════
             PARENT INFO
        ═══════════════════════════════════════════════ --}}
        @if (
            $student->parent_name ||
                $student->parent_phone ||
                $student->emergency_contact_name ||
                $student->emergency_contact_phone)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5">Ota-ona Ma'lumotlari</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if ($student->parent_name)
                        <div
                            class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-800/30">
                            <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold uppercase tracking-wide mb-1">
                                Ota-ona ismi</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $student->parent_name }}</p>
                        </div>
                    @endif

                    @if ($student->parent_phone)
                        <div
                            class="p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <p
                                class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold uppercase tracking-wide mb-1">
                                Ota-ona telefoni</p>
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-bold text-gray-900 dark:text-white break-all">{{ $student->parent_phone }}
                                </p>
                                <button type="button" data-phone="{{ $student->parent_phone }}"
                                    onclick="handleCopyPhone(event, this)"
                                    class="copy-btn flex-shrink-0 p-1.5 rounded-lg text-emerald-600
                                           hover:bg-emerald-200 dark:hover:bg-emerald-800/50 active:scale-95 transition-all duration-150"
                                    title="Nusxalash">
                                    <svg class="icon-copy w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                    </svg>
                                    <svg class="icon-check w-4 h-4 hidden text-green-600" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if ($student->emergency_contact_name)
                        <div
                            class="p-4 bg-orange-50 dark:bg-orange-900/10 rounded-xl border border-orange-100 dark:border-orange-800/30">
                            <p
                                class="text-xs text-orange-600 dark:text-orange-400 font-semibold uppercase tracking-wide mb-1">
                                Favqulodda aloqa</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $student->emergency_contact_name }}</p>
                        </div>
                    @endif

                    @if ($student->emergency_contact_phone)
                        <div
                            class="p-4 bg-orange-50 dark:bg-orange-900/10 rounded-xl border border-orange-100 dark:border-orange-800/30">
                            <p
                                class="text-xs text-orange-600 dark:text-orange-400 font-semibold uppercase tracking-wide mb-1">
                                Favqulodda telefon</p>
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-bold text-gray-900 dark:text-white break-all">
                                    {{ $student->emergency_contact_phone }}</p>
                                <button type="button" data-phone="{{ $student->emergency_contact_phone }}"
                                    onclick="handleCopyPhone(event, this)"
                                    class="copy-btn flex-shrink-0 p-1.5 rounded-lg text-orange-600
                                           hover:bg-orange-200 dark:hover:bg-orange-800/50 active:scale-95 transition-all duration-150"
                                    title="Nusxalash">
                                    <svg class="icon-copy w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                    </svg>
                                    <svg class="icon-check w-4 h-4 hidden text-green-600" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════
             NOTES
        ═══════════════════════════════════════════════ --}}
        @if ($student->notes)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-3">Izohlar</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-wrap">
                    {{ $student->notes }}
                </p>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        async function handleCopyPhone(event, btn) {
            event.preventDefault();
            event.stopPropagation();

            const phone = btn.dataset.phone;
            if (!phone) return;

            const iconCopy = btn.querySelector('.icon-copy');
            const iconCheck = btn.querySelector('.icon-check');

            try {
                await navigator.clipboard.writeText(phone);
            } catch {
                const ta = document.createElement('textarea');
                ta.value = phone;
                ta.style.cssText = 'position:fixed;opacity:0;pointer-events:none;';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                ta.remove();
            }

            iconCopy?.classList.add('hidden');
            iconCheck?.classList.remove('hidden');

            const card = btn.closest('a');
            const toast = card?.querySelector('.copy-toast');
            if (toast) {
                toast.classList.remove('opacity-0', 'scale-90');
                toast.classList.add('opacity-100', 'scale-100');
            }

            setTimeout(() => {
                iconCopy?.classList.remove('hidden');
                iconCheck?.classList.add('hidden');
                if (toast) {
                    toast.classList.remove('opacity-100', 'scale-100');
                    toast.classList.add('opacity-0', 'scale-90');
                }
            }, 2000);
        }
    </script>
@endpush
