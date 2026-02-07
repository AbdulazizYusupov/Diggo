@extends('Layout.app')

@section('title', 'Dars qo\'shish')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('lessons.index') }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Yangi dars qo'shish
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Dars ma'lumotlarini to'ldiring
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('lessons.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Group Selection -->
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 ml-1">
                                Guruhni tanlang <span class="text-red-500">*</span>
                            </label>

                            <div class="relative" id="groupCustomContainer">
                                <div onclick="toggleGroupDropdown()"
                                    class="w-full flex items-center justify-between px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border @error('group_id') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror rounded-xl cursor-pointer group hover:border-indigo-500 transition-all shadow-sm">

                                    <span id="selectedGroupLabel" class="text-sm dark:text-white">
                                        @php
                                            $currentGroupId = old('group_id');
                                            $selectedGroup = ($groups ?? collect())->firstWhere('id', $currentGroupId);
                                        @endphp
                                        {{ $selectedGroup ? $selectedGroup->name . ' (' . ($selectedGroup->course->title ?? 'N/A') . ')' : 'Guruhni tanlang...' }}
                                    </span>

                                    <input type="hidden" name="group_id" id="group_id_input" value="{{ $currentGroupId }}">

                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-transform duration-200"
                                        id="groupArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div id="groupMenu"
                                    class="hidden absolute z-[170] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">

                                    <div class="p-2 border-b dark:border-gray-700 bg-gray-50/30 dark:bg-slate-900/30">
                                        <input type="text" id="groupSearchInput" oninput="filterGroups()"
                                            placeholder="Guruh nomi yoki kurs bo'yicha qidirish..."
                                            class="w-full px-3 py-2 bg-white dark:bg-slate-800 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none">
                                    </div>

                                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                        @foreach ($groups ?? [] as $group)
                                            <div onclick="selectGroup('{{ $group->id }}', '{{ $group->name }} ({{ $group->course->title ?? 'N/A' }})')"
                                                class="group-item px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-none">
                                                <div class="flex flex-col">
                                                    <span class="font-medium group-item-name">{{ $group->name }}</span>
                                                    <span
                                                        class="text-[10px] text-gray-500 dark:text-gray-400 group-item-course">
                                                        {{ $group->course->title ?? 'Kurs nomi mavjud emas' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div id="noGroupFound" class="hidden p-4 text-center text-xs text-gray-500 italic">
                                        Guruh topilmadi...
                                    </div>
                                </div>
                            </div>

                            @error('group_id')
                                <p class="text-red-500 text-[10px] mt-1 ml-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Teacher Selection -->
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 ml-1">
                                O'qituvchini tanlang <span class="text-red-500">*</span>
                            </label>

                            <div class="relative" id="teacherCustomContainer">
                                <div onclick="toggleTeacherDropdown()"
                                    class="w-full flex items-center justify-between px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border @error('teacher_id') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror rounded-xl cursor-pointer group hover:border-indigo-500 transition-all shadow-sm">

                                    <span id="selectedTeacherLabel" class="text-sm dark:text-white">
                                        @php
                                            $currentTeacherId = old('teacher_id');
                                            $selectedTeacher = ($teachers ?? collect())->firstWhere(
                                                'id',
                                                $currentTeacherId,
                                            );
                                        @endphp
                                        {{ $selectedTeacher ? $selectedTeacher->user->full_name : "O'qituvchini tanlang..." }}
                                    </span>

                                    <input type="hidden" name="teacher_id" id="teacher_id_input"
                                        value="{{ $currentTeacherId }}">

                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-transform duration-200"
                                        id="teacherArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>

                                <div id="teacherMenu"
                                    class="hidden absolute z-[160] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">

                                    <div class="p-2 border-b dark:border-gray-700 bg-gray-50/30 dark:bg-slate-900/30">
                                        <input type="text" id="teacherSearchInput" oninput="filterTeachers()"
                                            placeholder="Ism bo'yicha qidirish..."
                                            class="w-full px-3 py-2 bg-white dark:bg-slate-800 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none">
                                    </div>

                                    <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                        @foreach ($teachers ?? [] as $teacher)
                                            <div onclick="selectTeacher('{{ $teacher->id }}', '{{ $teacher->user->full_name }}')"
                                                class="teacher-item px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-none">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xs">
                                                        {{ substr($teacher->user->full_name, 0, 1) }}
                                                    </div>
                                                    <span
                                                        class="font-medium teacher-item-name">{{ $teacher->user->full_name }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div id="noTeacherFound" class="hidden p-4 text-center text-xs text-gray-500 italic">
                                        O'qituvchi topilmadi...
                                    </div>
                                </div>
                            </div>

                            @error('teacher_id')
                                <p class="text-red-500 text-[10px] mt-1 ml-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Lesson Date -->
                    <div>
                        <label for="lesson_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Dars sanasi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="lesson_date" id="lesson_date" value="{{ old('lesson_date') }}"
                            required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        @error('lesson_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>
                                Rejalashtirilgan
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Yakunlangan
                            </option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Bekor qilingan
                            </option>
                            <option value="rescheduled" {{ old('status') == 'rescheduled' ? 'selected' : '' }}>
                                Ko'chirilgan
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Boshlanish vaqti <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                            required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tugash vaqti <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Topic -->
                    <div class="lg:col-span-2">
                        <label for="topic" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Dars mavzusi
                        </label>
                        <input type="text" name="topic" id="topic" value="{{ old('topic') }}"
                            placeholder="Masalan: If/Else Statements"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                        @error('topic')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="lg:col-span-2">
                        <label for="description"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Qo'shimcha ma'lumot
                        </label>
                        <textarea name="description" id="description" rows="4" placeholder="Dars haqida qo'shimcha ma'lumotlar..."
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('lessons.index') }}"
                        class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-xl transition-all">
                        Bekor qilish
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            'use strict';

            // ============================================================================
            // GROUP DROPDOWN
            // ============================================================================

            // DOM elementlarini keshlab olish
            const groupElements = {
                menu: null,
                arrow: null,
                searchInput: null,
                idInput: null,
                selectedLabel: null,
                container: null,
                noFound: null
            };

            let groupSearchTimeout = null;

            // Elementlarni initialization
            function initGroupElements() {
                groupElements.menu = document.getElementById('groupMenu');
                groupElements.arrow = document.getElementById('groupArrow');
                groupElements.searchInput = document.getElementById('groupSearchInput');
                groupElements.idInput = document.getElementById('group_id_input');
                groupElements.selectedLabel = document.getElementById('selectedGroupLabel');
                groupElements.container = document.getElementById('groupCustomContainer');
                groupElements.noFound = document.getElementById('noGroupFound');
            }

            // XSS himoyasi
            function sanitizeText(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Dropdownni ochish/yopish
            function toggleGroupDropdown() {
                if (!groupElements.menu || !groupElements.arrow) return;

                const isHidden = groupElements.menu.classList.contains('hidden');

                // Barcha dropdownlarni yopish
                document.querySelectorAll('[id$="Menu"]').forEach(menu => {
                    if (menu !== groupElements.menu) {
                        menu.classList.add('hidden');
                    }
                });
                document.querySelectorAll('[id$="Arrow"]').forEach(arrow => {
                    if (arrow !== groupElements.arrow) {
                        arrow.classList.remove('rotate-180');
                    }
                });

                groupElements.menu.classList.toggle('hidden');
                groupElements.arrow.classList.toggle('rotate-180');

                if (isHidden && groupElements.searchInput) {
                    setTimeout(() => groupElements.searchInput.focus(), 100);
                }
            }

            // Guruhni tanlash
            function selectGroup(id, displayName) {
                if (!groupElements.idInput || !groupElements.selectedLabel) return;

                const sanitizedId = String(id).trim();
                const sanitizedName = sanitizeText(String(displayName).trim());

                if (!sanitizedId || !sanitizedName) {
                    console.error('Invalid group selection');
                    return;
                }

                groupElements.idInput.value = sanitizedId;
                groupElements.selectedLabel.textContent = sanitizedName;

                toggleGroupDropdown();
            }

            // Guruhlarni qidirish (debounced)
            function filterGroups() {
                if (!groupElements.searchInput || !groupElements.noFound) return;

                clearTimeout(groupSearchTimeout);
                groupSearchTimeout = setTimeout(() => {
                    const filter = groupElements.searchInput.value.toLowerCase().trim();
                    const items = document.querySelectorAll('.group-item');
                    let hasResults = false;

                    items.forEach(item => {
                        const nameElement = item.querySelector('.group-item-name');
                        const courseElement = item.querySelector('.group-item-course');

                        if (!nameElement || !courseElement) return;

                        const groupName = nameElement.textContent.toLowerCase();
                        const courseName = courseElement.textContent.toLowerCase();

                        if (groupName.includes(filter) || courseName.includes(filter)) {
                            item.style.display = '';
                            hasResults = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    groupElements.noFound.classList.toggle('hidden', hasResults);
                }, 150);
            }

            // ============================================================================
            // TEACHER DROPDOWN
            // ============================================================================

            // DOM elementlarini keshlab olish
            const teacherElements = {
                menu: null,
                arrow: null,
                searchInput: null,
                idInput: null,
                selectedLabel: null,
                container: null,
                noFound: null
            };

            let teacherSearchTimeout = null;

            // Elementlarni initialization
            function initTeacherElements() {
                teacherElements.menu = document.getElementById('teacherMenu');
                teacherElements.arrow = document.getElementById('teacherArrow');
                teacherElements.searchInput = document.getElementById('teacherSearchInput');
                teacherElements.idInput = document.getElementById('teacher_id_input');
                teacherElements.selectedLabel = document.getElementById('selectedTeacherLabel');
                teacherElements.container = document.getElementById('teacherCustomContainer');
                teacherElements.noFound = document.getElementById('noTeacherFound');
            }

            // Dropdownni ochish/yopish
            function toggleTeacherDropdown() {
                if (!teacherElements.menu || !teacherElements.arrow) return;

                const isHidden = teacherElements.menu.classList.contains('hidden');

                // Barcha dropdownlarni yopish
                document.querySelectorAll('[id$="Menu"]').forEach(menu => {
                    if (menu !== teacherElements.menu) {
                        menu.classList.add('hidden');
                    }
                });
                document.querySelectorAll('[id$="Arrow"]').forEach(arrow => {
                    if (arrow !== teacherElements.arrow) {
                        arrow.classList.remove('rotate-180');
                    }
                });

                teacherElements.menu.classList.toggle('hidden');
                teacherElements.arrow.classList.toggle('rotate-180');

                if (isHidden && teacherElements.searchInput) {
                    setTimeout(() => teacherElements.searchInput.focus(), 100);
                }
            }

            // O'qituvchini tanlash
            function selectTeacher(id, name) {
                if (!teacherElements.idInput || !teacherElements.selectedLabel) return;

                const sanitizedId = String(id).trim();
                const sanitizedName = sanitizeText(String(name).trim());

                if (!sanitizedId || !sanitizedName) {
                    console.error('Invalid teacher selection');
                    return;
                }

                teacherElements.idInput.value = sanitizedId;
                teacherElements.selectedLabel.textContent = sanitizedName;

                toggleTeacherDropdown();
            }

            // O'qituvchilarni qidirish (debounced)
            function filterTeachers() {
                if (!teacherElements.searchInput || !teacherElements.noFound) return;

                clearTimeout(teacherSearchTimeout);
                teacherSearchTimeout = setTimeout(() => {
                    const filter = teacherElements.searchInput.value.toLowerCase().trim();
                    const items = document.querySelectorAll('.teacher-item');
                    let hasResults = false;

                    items.forEach(item => {
                        const nameElement = item.querySelector('.teacher-item-name');

                        if (!nameElement) return;

                        const name = nameElement.textContent.toLowerCase();

                        if (name.includes(filter)) {
                            item.style.display = '';
                            hasResults = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    teacherElements.noFound.classList.toggle('hidden', hasResults);
                }, 150);
            }

            // ============================================================================
            // GLOBAL EVENT HANDLERS
            // ============================================================================

            // Tashqariga bosilganda yopish
            function handleOutsideClick(event) {
                // Group dropdown
                if (groupElements.container && !groupElements.container.contains(event.target)) {
                    if (groupElements.menu) groupElements.menu.classList.add('hidden');
                    if (groupElements.arrow) groupElements.arrow.classList.remove('rotate-180');
                }

                // Teacher dropdown
                if (teacherElements.container && !teacherElements.container.contains(event.target)) {
                    if (teacherElements.menu) teacherElements.menu.classList.add('hidden');
                    if (teacherElements.arrow) teacherElements.arrow.classList.remove('rotate-180');
                }
            }

            // Escape tugmasi bilan yopish
            function handleEscapeKey(event) {
                if (event.key !== 'Escape') return;

                // Group dropdown
                if (groupElements.menu && !groupElements.menu.classList.contains('hidden')) {
                    groupElements.menu.classList.add('hidden');
                    if (groupElements.arrow) groupElements.arrow.classList.remove('rotate-180');
                }

                // Teacher dropdown
                if (teacherElements.menu && !teacherElements.menu.classList.contains('hidden')) {
                    teacherElements.menu.classList.add('hidden');
                    if (teacherElements.arrow) teacherElements.arrow.classList.remove('rotate-180');
                }
            }

            // Event listenerlarni o'rnatish
            function attachEventListeners() {
                document.addEventListener('click', handleOutsideClick);
                document.addEventListener('keydown', handleEscapeKey);
            }

            // Cleanup funksiyasi
            function cleanupDropdowns() {
                document.removeEventListener('click', handleOutsideClick);
                document.removeEventListener('keydown', handleEscapeKey);
                clearTimeout(groupSearchTimeout);
                clearTimeout(teacherSearchTimeout);
            }

            // ============================================================================
            // INITIALIZATION
            // ============================================================================

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initGroupElements();
                    initTeacherElements();
                    attachEventListeners();
                });
            } else {
                initGroupElements();
                initTeacherElements();
                attachEventListeners();
            }

            // Global cleanup funksiya (agar kerak bo'lsa)
            window.cleanupDropdowns = cleanupDropdowns;
        </script>
    @endpush
@endsection
