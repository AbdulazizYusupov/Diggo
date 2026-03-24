@extends('Layout.app')

@section('title', __('director/students.title'))

@section('content')
    <div class="space-y-6">

        {{-- ═══════════════════════════════════════════════
             HEADER
        ═══════════════════════════════════════════════ --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ __('director/students.my_students') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('director/students.all_stats') }}
                </p>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
             STATISTICS CARDS
        ═══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Jami O'quvchilar --}}
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wide">
                            {{ __('director/students.total_students') }}
                        </p>
                        <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-1">
                            {{ $stats['total_students'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Faol O'quvchilar --}}
            <div
                class="bg-gradient-to-br from-green-100 to-green-200/60 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl p-6 border border-green-200 dark:border-green-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-green-700 dark:text-green-400 uppercase tracking-wide">
                            {{ __('director/students.active_students') }}
                        </p>
                        <p class="text-3xl font-extrabold text-green-900 dark:text-green-50 mt-1">
                            {{ $stats['active_students'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-600 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Bu oy qo'shilganlar --}}
            <div
                class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wide">
                            {{ __('director/students.new_this_month') }}
                        </p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ $stats['new_this_month'] }}
                        </p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                            {{ now()->translatedFormat('F Y') }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-500 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- O'g'il / Qiz nisbati --}}
            @php
                $malePercent =
                    $stats['total_students'] > 0 ? round(($stats['male_count'] / $stats['total_students']) * 100) : 50;
                $femalePercent =
                    $stats['total_students'] > 0
                        ? round(($stats['female_count'] / $stats['total_students']) * 100)
                        : 50;
            @endphp
            <div
                class="bg-gradient-to-br from-pink-100 to-purple-200/60 dark:from-pink-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-pink-200 dark:border-pink-700/50">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-pink-700 dark:text-pink-400 uppercase tracking-wide">
                            {{ __('director/students.gender_ratio') }}
                        </p>
                        <p class="text-3xl font-extrabold text-pink-900 dark:text-pink-50 mt-1">
                            {{ $stats['male_count'] }} / {{ $stats['female_count'] }}
                        </p>
                        <div class="mt-2 w-full bg-purple-200 dark:bg-purple-900/40 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full transition-all duration-500"
                                style="width: {{ $malePercent }}%">
                            </div>
                        </div>
                        <p class="text-xs text-pink-600 dark:text-pink-400 mt-1">
                            {{ __('director/students.boy') }} {{ $malePercent }}% &bull;
                            {{ __('director/students.girl') }} {{ $femalePercent }}%
                        </p>
                    </div>
                    <div class="p-3 bg-pink-500 rounded-xl flex-shrink-0 ml-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════
             FILTERS
        ═══════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form id="searchForm" method="GET" action="{{ route('director.students') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Qidiruv --}}
                <div class="md:col-span-2">
                    <label for="searchInput" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('director/students.search') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput" name="search" value="{{ $search }}"
                            placeholder="{{ __('director/students.search_placeholder') }}" autocomplete="off"
                            class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        @if ($search)
                            <a href="{{ route('director.students', array_filter(request()->except('search'))) }}"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-red-400 hover:text-red-600 transition-colors"
                                title="{{ __('director/students.clear_search') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Guruh --}}
                <div class="md:col-span-1">
                    <label for="groupSelect" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('director/students.group') }}
                    </label>
                    <select id="groupSelect" name="group_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        <option value="">{{ __('director/students.all_groups') }}</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" @selected($groupId == $group->id)>
                                {{ $group->name }}
                            </option>
                        @endforeach
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
                        {{ __('director/students.filter') }}
                    </button>
                    <a href="{{ route('director.students') }}"
                        class="group p-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg transition-all duration-300 flex items-center justify-center"
                        title="{{ __('director/students.clear_all') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6 transition-transform duration-500 group-hover:rotate-180">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </a>
                </div>

            </form>
        </div>

        {{-- ═══════════════════════════════════════════════
             STUDENT CARDS GRID
        ═══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($students as $student)
                @php
                    $fullName = trim(
                        implode(
                            ' ',
                            array_filter([
                                $student->user->surname,
                                $student->user->name,
                                $student->user->middle_name ?? null,
                            ]),
                        ),
                    );

                    $initials = mb_strtoupper(
                        mb_substr($student->user->name ?? '', 0, 1) . mb_substr($student->user->surname ?? '', 0, 1),
                    );

                    $addressParts = array_filter([$student->user->address ?? null, $student->user->city ?? null]);
                    $address = implode(', ', $addressParts);

                    $isActive = (bool) $student->user->is_active;
                @endphp

                <a href="{{ route('director.students.show', $student->id) }}"
                    class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700
                           hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-200 flex flex-col">

                    <div class="p-6 flex flex-col flex-1">

                        {{-- ── Avatar + Name ── --}}
                        <div class="flex items-start gap-4 mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500
                                        flex items-center justify-center text-white text-lg font-bold flex-shrink-0
                                        select-none">
                                {{ $initials }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="font-bold text-gray-900 dark:text-white text-base leading-snug
                                           group-hover:text-indigo-600 dark:group-hover:text-indigo-400
                                           transition-colors break-words">
                                    {{ $fullName }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $student->user->phone ?? '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- ── Parent info & Phone ── --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">

                            {{-- Ota-ona --}}
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                                <p class="text-xs text-blue-700 dark:text-blue-400 font-semibold mb-0.5">
                                    {{ __('director/students.parent') }}</p>
                                <p class="text-sm font-bold text-blue-800 dark:text-blue-300 break-words leading-snug">
                                    {{ $student->parent_name ?? '—' }}
                                </p>
                            </div>

                            {{-- Telefon — FULLY VISIBLE + COPY --}}
                            <div class="p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl">
                                <p class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold mb-0.5">
                                    {{ __('director/students.phone') }}</p>
                                <div class="flex items-center justify-between gap-1">
                                    <p
                                        class="text-sm font-bold text-emerald-700 dark:text-emerald-400 break-all leading-snug">
                                        {{ $student->parent_phone ?? '—' }}
                                    </p>
                                    @if ($student->parent_phone)
                                        {{-- Copy button — stops card navigation --}}
                                        <button type="button" data-phone="{{ $student->parent_phone }}"
                                            onclick="handleCopyPhone(event, this)"
                                            class="copy-btn flex-shrink-0 p-1.5 rounded-lg text-emerald-600
                                                   hover:bg-emerald-200 dark:hover:bg-emerald-800/50
                                                   active:scale-95 transition-all duration-150"
                                            title="{{ __('director/students.copy') }}"
                                            aria-label="Telefon raqamini nusxalash">

                                            {{-- Default: copy icon --}}
                                            <svg class="icon-copy w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                            </svg>

                                            {{-- Success: checkmark icon (hidden by default) --}}
                                            <svg class="icon-check w-4 h-4 hidden text-green-600"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- ── Address & Notes ── --}}
                        @if ($address || $student->notes)
                            <div class="space-y-1.5 mb-4">
                                @if ($address)
                                    <div class="flex items-start gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="break-words leading-snug">{{ $address }}</span>
                                    </div>
                                @endif
                                @if ($student->notes)
                                    <div class="flex items-start gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="italic leading-snug">
                                            {{ Str::limit($student->notes, 60) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Spacer to push footer down --}}
                        <div class="flex-1"></div>

                        {{-- ── Footer: Groups / Enrollment / Status ── --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="text-center">
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ __('director/students.groups_count') }}</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                                    {{ $student->active_groups }}
                                </p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ __('director/students.enroll_date') }}</p>
                                <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400 leading-tight">
                                    {{ $student->enrollment_date?->format('d.m.Y') ?? '—' }}
                                </p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('director/students.status') }}
                                </p>
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold mt-0.5',
                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $isActive,
                                    'bg-red-100   text-red-700   dark:bg-red-900/30   dark:text-red-400' => !$isActive,
                                ])>
                                    {{ $isActive ? __('director/students.active') : __('director/students.inactive') }}
                                </span>
                            </div>
                        </div>

                    </div>

                    {{-- "Copied!" toast anchored inside card (hidden by default) --}}
                    <div class="copy-toast absolute bottom-16 left-1/2 -translate-x-1/2
                                flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                bg-gray-900 dark:bg-gray-700 text-white text-xs font-medium
                                shadow-lg pointer-events-none select-none
                                opacity-0 scale-90 transition-all duration-200"
                        aria-live="polite">
                        <svg class="w-3.5 h-3.5 text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        {{ __('director/students.copied') }}
                    </div>

                </a>
            @empty
                <div class="col-span-full text-center py-16">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold text-lg">
                        {{ __('director/students.not_found') }}</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">{{ __('director/students.filter_help') }}
                    </p>
                </div>
            @endforelse
        </div>

        {{-- ═══════════════════════════════════════════════
             PAGINATION
        ═══════════════════════════════════════════════ --}}
        @if ($students->hasPages())
            <div class="mt-6">
                {{ $students->links() }}
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        /**
         * Copy parent phone number to clipboard.
         * Stops the card <a> navigation, shows icon swap + toast, then resets.
         *
         * @param {MouseEvent} event  - The click event (to stop propagation)
         * @param {HTMLElement} btn   - The copy button element
         */
        async function handleCopyPhone(event, btn) {
            // Prevent the card link from navigating
            event.preventDefault();
            event.stopPropagation();

            const phone = btn.dataset.phone;
            if (!phone) return;

            // ── Icon references inside the button ──
            const iconCopy = btn.querySelector('.icon-copy');
            const iconCheck = btn.querySelector('.icon-check');

            // ── Toast reference (sibling inside the card) ──
            const card = btn.closest('a');
            const toast = card?.querySelector('.copy-toast');

            try {
                await navigator.clipboard.writeText(phone);
            } catch {
                // Fallback for older browsers / insecure contexts
                const ta = document.createElement('textarea');
                ta.value = phone;
                ta.style.cssText = 'position:fixed;opacity:0;pointer-events:none;';
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                ta.remove();
            }

            // ── Swap icons ──
            iconCopy?.classList.add('hidden');
            iconCheck?.classList.remove('hidden');

            // ── Show toast ──
            if (toast) {
                toast.classList.remove('opacity-0', 'scale-90');
                toast.classList.add('opacity-100', 'scale-100');
            }

            // ── Reset after 2 s ──
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
