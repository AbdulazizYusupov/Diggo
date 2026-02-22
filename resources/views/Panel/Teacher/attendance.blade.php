@extends('Layout.app')

@section('title', 'Yuqlama — ' . $lesson->topic)

@section('content')
<div class="space-y-4 pb-24">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 flex-wrap">
        <a href="{{ route('teacher.groups.index') }}"
            class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            Guruhlarim
        </a>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('teacher.groups.lessons.index', $group->id) }}"
            class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate max-w-[140px]">
            {{ $group->name }}
        </a>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 dark:text-white font-medium">Yuqlama</span>
    </nav>

    {{-- Lesson Info Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
            <div>
                <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 dark:text-white">
                    {{ $lesson->topic ?? 'Mavzu belgilanmagan' }}
                </h1>
                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold mt-0.5">
                    {{ $group->name }} • {{ $group->course->title ?? '' }}
                </p>
                <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($lesson->lesson_date)->translatedFormat('d F Y, l') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $lesson->start_time }}{{ $lesson->end_time ? ' — '.$lesson->end_time : '' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        {{ $students->count() }} o'quvchi
                    </span>
                </div>
            </div>

            {{-- Status badge — controller dan keladi --}}
            <span class="self-start px-3 py-1.5 text-xs font-bold rounded-xl {{ $lessonStatusBadge['class'] }}">
                {{ $lessonStatusBadge['label'] }}
            </span>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 rounded-xl px-4 py-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm font-semibold text-green-700 dark:text-green-400">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Coin tizimi haqida ma'lumot --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-blue-500/20 p-4 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start gap-3">
            <div class="flex-shrink-0 w-11 h-11 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center border border-amber-100 dark:border-amber-500/20">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 w-full">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Coin tizimi (Max: 8 coin/kun)</h3>
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-2">
                    <div class="flex items-center gap-2 p-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg border border-emerald-100 dark:border-emerald-500/20">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">Vaqtida keldi</p>
                            <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300">+2</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg border border-emerald-100 dark:border-emerald-500/20">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">0–15 daqiqa kech</p>
                            <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300">+2</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg border border-amber-100 dark:border-amber-500/20">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-[10px] text-amber-600 dark:text-amber-400 font-medium">15–30 daqiqa kech</p>
                            <p class="text-xs font-bold text-amber-700 dark:text-amber-300">+1</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-2 bg-blue-50 dark:bg-blue-500/10 rounded-lg border border-blue-100 dark:border-blue-500/20">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <p class="text-[10px] text-blue-600 dark:text-blue-400 font-medium">Uy vazifasi</p>
                            <p class="text-xs font-bold text-blue-700 dark:text-blue-300">0–3</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-2 bg-purple-50 dark:bg-purple-500/10 rounded-lg border border-purple-100 dark:border-purple-500/20">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <div>
                            <p class="text-[10px] text-purple-600 dark:text-purple-400 font-medium">Faollik</p>
                            <p class="text-xs font-bold text-purple-700 dark:text-purple-300">0–3</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- No students --}}
    @if($students->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center shadow-sm">
        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <p class="text-gray-500 dark:text-gray-400 font-medium">Guruhda faol o'quvchilar yo'q</p>
    </div>

    @else

    {{-- Attendance Form --}}
    <form method="POST"
        action="{{ route('teacher.attendance.store', [$group->id, $lesson->id]) }}"
        id="attendanceForm">
        @csrf

        {{-- Quick mass actions --}}
        <div class="flex flex-wrap gap-2 mb-4">
            <button type="button" id="markAllPresent"
                class="px-3 py-2 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-xl transition-colors">
                ✓ Hammasini "Keldi" qilish
            </button>
            <button type="button" id="markAllAbsent"
                class="px-3 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-xs font-bold rounded-xl transition-colors">
                ✗ Hammasini "Kelmadi" qilish
            </button>
            <button type="button" id="clearAll"
                class="px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-xl transition-colors">
                Tozalash
            </button>
        </div>

        {{-- Student cards --}}
        <div class="space-y-3">
            @foreach($students as $index => $student)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden student-card"
                data-index="{{ $index }}">

                {{-- Hidden student_id --}}
                <input type="hidden"
                    name="attendances[{{ $index }}][student_id]"
                    value="{{ $student->id }}">

                <div class="flex flex-col lg:flex-row lg:items-center gap-3 p-4">

                    {{-- ① Student info --}}
                    <div class="flex items-center gap-3 lg:w-52 flex-shrink-0">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(mb_substr($student->user->surname ?? 'S', 0, 1)) }}{{ strtoupper(mb_substr($student->user->name ?? 'N', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                {{ $student->user->surname ?? '' }} {{ $student->user->name ?? '' }}
                            </p>
                            <div class="flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-bold text-amber-600 dark:text-amber-400">{{ $student->coins ?? 0 }}</span>
                                @if($student->existing_coins > 0)
                                <span class="text-xs text-green-500 font-semibold">(+{{ $student->existing_coins }} bu dars)</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ② Status buttons — controller dan kelgan $statusOptions --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0 flex-wrap">
                        <span class="text-xs text-gray-400 font-medium">Holat:</span>
                        @foreach($statusOptions as $opt)
                        <label class="cursor-pointer">
                            <input type="radio"
                                name="attendances[{{ $index }}][status]"
                                value="{{ $opt['value'] }}"
                                @checked($student->existing_status === $opt['value'])
                                class="sr-only attendance-radio"
                                data-type="{{ $opt['value'] }}"
                                data-index="{{ $index }}"
                                data-checked-class="{{ $opt['checked_class'] }}"
                                data-hover-class="{{ $opt['hover_class'] }}">
                            <div class="px-3 py-1.5 rounded-lg text-xs font-bold border border-transparent
                                bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400
                                transition-all cursor-pointer radio-label {{ $opt['hover_class'] }}"
                                data-checked-class="{{ $opt['checked_class'] }}">
                                {{ $opt['label'] }}
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- ③ Homework score 0-3 --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0 scores-container
                        {{ $student->existing_status === 'absent' ? 'opacity-30 pointer-events-none' : '' }}">
                        <span class="text-xs font-bold text-blue-500">HW:</span>
                        @foreach([0, 1, 2, 3] as $score)
                        <label class="cursor-pointer">
                            <input type="radio"
                                name="attendances[{{ $index }}][homework_score]"
                                value="{{ $score }}"
                                @checked($student->existing_hw !== null ? $student->existing_hw == $score : $score === 0)
                                class="sr-only hw-radio"
                                data-checked-class="bg-blue-600 text-white">
                            <div class="w-8 h-8 rounded-lg text-xs font-bold flex items-center justify-center
                                bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400
                                hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all cursor-pointer radio-label"
                                data-checked-class="bg-blue-600 text-white">
                                {{ $score }}
                            </div>
                        </label>
                        @endforeach
                        <span class="text-[10px] font-bold text-blue-400">HW</span>
                    </div>

                    {{-- ④ Activity score 0-3 --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0 scores-container
                        {{ $student->existing_status === 'absent' ? 'opacity-30 pointer-events-none' : '' }}">
                        <span class="text-xs font-bold text-purple-500">FA:</span>
                        @foreach([0, 1, 2, 3] as $score)
                        <label class="cursor-pointer">
                            <input type="radio"
                                name="attendances[{{ $index }}][activity_score]"
                                value="{{ $score }}"
                                @checked($student->existing_act !== null ? $student->existing_act == $score : $score === 0)
                                class="sr-only act-radio"
                                data-checked-class="bg-purple-600 text-white">
                            <div class="w-8 h-8 rounded-lg text-xs font-bold flex items-center justify-center
                                bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400
                                hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all cursor-pointer radio-label"
                                data-checked-class="bg-purple-600 text-white">
                                {{ $score }}
                            </div>
                        </label>
                        @endforeach
                        <span class="text-[10px] font-bold text-purple-400">FA</span>
                    </div>

                    {{-- ⑤ Kechikish va izoh --}}
                    <div class="flex-1 min-w-0">
                        {{-- Kechikish maydonlari (faqat "late" da ko'rinadi) --}}
                        <div class="late-fields {{ $student->existing_status === 'late' ? '' : 'hidden' }} flex gap-2">
                            <input type="number"
                                name="attendances[{{ $index }}][late_minutes]"
                                value="{{ $student->existing_late ?? 5 }}"
                                min="1" max="120"
                                placeholder="Daqiqa"
                                class="w-24 px-3 py-1.5 bg-gray-50 dark:bg-gray-900 border border-amber-300 dark:border-amber-700 rounded-lg text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-400/30">
                            <input type="text"
                                name="attendances[{{ $index }}][notes]"
                                value="{{ $student->existing_status === 'late' ? ($student->existing_notes ?? '') : '' }}"
                                placeholder="Kechikish sababi..."
                                class="flex-1 px-3 py-1.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-400/30">
                        </div>

                        {{-- Oddiy izoh (late emas) --}}
                        <div class="normal-notes {{ $student->existing_status === 'late' ? 'hidden' : '' }}">
                            <input type="text"
                                name="attendances[{{ $index }}][notes]"
                                value="{{ $student->existing_status !== 'late' ? ($student->existing_notes ?? '') : '' }}"
                                placeholder="Izoh (ixtiyoriy)..."
                                class="w-full px-3 py-1.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-400/30">
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        {{-- Fixed Save Button --}}
        <div class="fixed bottom-5 right-5 md:bottom-7 md:right-7 z-50">
            <button type="submit"
                class="px-5 py-3.5 md:px-7 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-2xl shadow-xl shadow-emerald-500/30 transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Saqlash
            </button>
        </div>

    </form>
    @endif

</div>

@push('scripts')
<script>
(function () {
    'use strict';

    // Radio vizualini yangilash
    function syncRadioVisual(radio) {
        const label = radio.nextElementSibling;
        if (!label || !label.classList.contains('radio-label')) return;

        const checkedClass  = label.dataset.checkedClass || '';
        const defaultClass  = 'bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400';

        if (radio.checked) {
            defaultClass.split(' ').forEach(c => label.classList.remove(c));
            checkedClass.split(' ').forEach(c => c && label.classList.add(c));
        } else {
            checkedClass.split(' ').forEach(c => c && label.classList.remove(c));
            defaultClass.split(' ').forEach(c => label.classList.add(c));
        }
    }

    // Bir guruh ichidagi barcha radiolari yangilash
    function refreshGroup(name) {
        document.querySelectorAll(`input[name="${CSS.escape(name)}"]`).forEach(syncRadioVisual);
    }

    // Absent bo'lganda score larni disable/reset qilish
    function handleAttendanceChange(radio) {
        const card   = radio.closest('.student-card');
        const status = radio.value;

        // Scores
        card.querySelectorAll('.scores-container').forEach(el => {
            if (status === 'absent' || status === 'excused') {
                el.classList.add('opacity-30', 'pointer-events-none');
                el.querySelectorAll('input[type="radio"]').forEach(r => {
                    if (r.value === '0') { r.checked = true; syncRadioVisual(r); }
                    else                 { r.checked = false; syncRadioVisual(r); }
                });
            } else {
                el.classList.remove('opacity-30', 'pointer-events-none');
            }
        });

        // Kechikish maydonlari
        const lateFields  = card.querySelector('.late-fields');
        const normalNotes = card.querySelector('.normal-notes');
        if (status === 'late') {
            lateFields?.classList.remove('hidden');
            normalNotes?.classList.add('hidden');
        } else {
            lateFields?.classList.add('hidden');
            normalNotes?.classList.remove('hidden');
        }
    }

    // Barcha radiolarga listener qo'shish + boshlang'ich vizual
    function init() {
        document.querySelectorAll('input[type="radio"].sr-only').forEach(radio => {
            syncRadioVisual(radio);

            radio.addEventListener('change', function () {
                refreshGroup(this.name);
                if (this.classList.contains('attendance-radio') && this.checked) {
                    handleAttendanceChange(this);
                }
            });
        });

        // Mavjud davomatlar uchun boshlang'ich holat
        document.querySelectorAll('.attendance-radio:checked').forEach(handleAttendanceChange);
    }

    // ─── Quick action buttons ───────────────────────────────

    document.getElementById('markAllPresent')?.addEventListener('click', function () {
        document.querySelectorAll('.attendance-radio[data-type="present"]').forEach(r => {
            r.checked = true;
            r.dispatchEvent(new Event('change'));
        });
    });

    document.getElementById('markAllAbsent')?.addEventListener('click', function () {
        document.querySelectorAll('.attendance-radio[data-type="absent"]').forEach(r => {
            r.checked = true;
            r.dispatchEvent(new Event('change'));
        });
    });

    document.getElementById('clearAll')?.addEventListener('click', function () {
        document.querySelectorAll('.attendance-radio').forEach(r => {
            r.checked = false;
            syncRadioVisual(r);
        });
        document.querySelectorAll('.student-card').forEach(card => {
            card.querySelectorAll('.scores-container').forEach(el =>
                el.classList.remove('opacity-30', 'pointer-events-none')
            );
            card.querySelector('.late-fields')?.classList.add('hidden');
            card.querySelector('.normal-notes')?.classList.remove('hidden');
        });
    });

    // Init
    init();
})();
</script>
@endpush
@endsection