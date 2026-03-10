@extends('Layout.app')
@section('title', 'To\'lov qabul qilish')
@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('payments.index') }}"
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">To'lov qabul qilish</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">O'quvchidan to'lov qabul qiling</p>
            </div>
        </div>

        <form action="{{ route('payments.store') }}" method="POST" class="space-y-6" id="paymentForm">
            @csrf

            <!-- FIX #1: Hidden input that carries the raw (unformatted) amount to backend -->
            <input type="hidden" name="amount" id="amount_hidden" value="{{ old('amount') }}">

            <!-- Student Selection -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    O'quvchini tanlash
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">O'quvchi</label>

                        <div class="relative" id="studentSearchContainer">
                            <div onclick="toggleStudentDropdown()"
                                class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('student_id') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl cursor-pointer group hover:border-emerald-500 transition-all shadow-sm">

                                <span id="selectedStudentLabel" class="text-sm text-gray-900 dark:text-white">
                                    -- O'quvchini tanlang --
                                </span>

                                <input type="hidden" name="student_id" id="student_id_input"
                                    value="{{ old('student_id', $selectedStudent->id ?? '') }}">

                                <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transition-transform"
                                    id="studentArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <div id="studentMenu"
                                class="hidden absolute z-[200] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                <div class="p-2 border-b dark:border-gray-700 bg-gray-50/30 dark:bg-slate-900/30">
                                    <input type="text" id="studentSearchInput" oninput="filterStudents()"
                                        placeholder="Ism, familiya, sharif yoki telefon orqali qidirish..."
                                        class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                </div>

                                <div class="max-h-64 overflow-y-auto custom-scrollbar" id="studentListContainer">
                                    {{-- FIX #2: e() escape applied to all data-* attributes to prevent XSS --}}
                                    @foreach ($students as $student)
                                        <div onclick="selectStudent({{ $student->id }})"
                                            data-student-id="{{ $student->id }}"
                                            data-student-surname="{{ e($student->user->surname ?? '') }}"
                                            data-student-name="{{ e($student->user->name ?? '') }}"
                                            data-student-middle="{{ e($student->user->middle_name ?? '') }}"
                                            data-student-phone="{{ e($student->user->phone ?? '') }}"
                                            data-student-balance="{{ (float) $student->balance }}"
                                            data-student-commission="{{ (float) ($student->director->commission_percentage ?? 0) }}"
                                            class="student-item px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-none flex flex-col">
                                            <span class="font-bold student-fullname">
                                                {{ trim(($student->user->surname ?? '') . ' ' . ($student->user->name ?? '') . ' ' . ($student->user->middle_name ?? '')) }}
                                            </span>
                                            <span class="text-xs text-gray-500 student-phone">{{ $student->user->phone ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div id="noStudentFound" class="hidden p-5 text-center text-sm text-gray-500">
                                    O'quvchi topilmadi...
                                </div>
                            </div>
                        </div>

                        @error('student_id')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Info -->
                    <div id="studentInfo"
                        class="hidden p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Joriy balans</p>
                                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400" id="currentBalance">0 so'm</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Direktor komissiyasi</p>
                                <p class="text-lg font-bold text-purple-600 dark:text-purple-400" id="commissionRate">0%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Group Selection -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Guruhni tanlash
                </h2>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Guruh</label>

                    <div class="relative" id="groupSearchContainer">
                        <div onclick="toggleGroupDropdown()"
                            class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('group_id') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl cursor-pointer group hover:border-emerald-500 transition-all shadow-sm">

                            <span id="selectedGroupLabel" class="text-sm text-gray-900 dark:text-white">
                                -- Guruhni tanlang --
                            </span>

                            <input type="hidden" name="group_id" id="group_id_input" value="{{ old('group_id') }}">

                            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transition-transform"
                                id="groupArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <div id="groupMenu"
                            class="hidden absolute z-[199] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                            <div class="p-2 border-b dark:border-gray-700 bg-gray-50/30 dark:bg-slate-900/30">
                                <input type="text" id="groupSearchInput" oninput="filterGroups()"
                                    placeholder="Guruh nomi yoki fan orqali qidirish..."
                                    class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                {{-- FIX #2: e() escape applied to group data-* attributes as well --}}
                                @foreach ($groups as $group)
                                    <div onclick="selectGroup({{ $group->id }})"
                                        data-group-id="{{ $group->id }}"
                                        data-group-name="{{ e($group->name) }}"
                                        data-group-subject="{{ e($group->subject->name ?? '') }}"
                                        data-group-teacher="{{ e(trim(($group->teacher->user->surname ?? '') . ' ' . ($group->teacher->user->name ?? ''))) }}"
                                        class="group-item px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-none flex flex-col">
                                        <span class="font-bold group-name-text">{{ $group->name }}</span>
                                        <span class="text-xs text-gray-500 group-subject-text">
                                            {{ $group->subject->name ?? "Fan ko'rsatilmagan" }}
                                            @if ($group->teacher)
                                                • {{ trim(($group->teacher->user->surname ?? '') . ' ' . ($group->teacher->user->name ?? '')) }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <div id="noGroupFound" class="hidden p-5 text-center text-sm text-gray-500">
                                Guruh topilmadi...
                            </div>
                        </div>
                    </div>

                    @error('group_id')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    To'lov ma'lumotlari
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            To'lov summasi <span class="text-rose-500">*</span>
                        </label>
                        {{-- FIX #1: This is display-only (formatted). Real value goes via #amount_hidden --}}
                        <input type="text" id="amount_display"
                            value="{{ old('amount') ? number_format((float) old('amount'), 0, '.', ' ') : '' }}"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all"
                            placeholder="Summa kiriting">
                        @error('amount')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Chegirma (%)
                        </label>
                        <input type="number" name="discount" id="discount" step="0.01" min="0"
                            max="100" value="{{ old('discount', 0) }}"
                            oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all"
                            placeholder="0">
                        @error('discount')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            To'lov turi <span class="text-rose-500">*</span>
                        </label>
                        <select name="payment_type" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all">
                            <option value="">-- Tanlang --</option>
                            <option value="cash"     {{ old('payment_type') == 'cash'     ? 'selected' : '' }}>Naqd</option>
                            <option value="card"     {{ old('payment_type') == 'card'     ? 'selected' : '' }}>Karta</option>
                            <option value="transfer" {{ old('payment_type') == 'transfer' ? 'selected' : '' }}>O'tkazma</option>
                            <option value="other"    {{ old('payment_type') == 'other'    ? 'selected' : '' }}>Boshqa</option>
                        </select>
                        @error('payment_type')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            To'lov oyi <span class="text-rose-500">*</span>
                        </label>
                        <input type="month" name="payment_month"
                            value="{{ old('payment_month', now()->format('Y-m')) }}" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all">
                        @error('payment_month')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Izoh
                    </label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all resize-none"
                        placeholder="Qo'shimcha ma'lumotlar...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">To'lov xulosasi</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">To'langan summa:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white" id="summaryAmount">0 so'm</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Chegirma (<span id="discountPercent">0</span>%):</span>
                        <span class="text-sm font-bold text-rose-500" id="summaryDiscount">0 so'm</span>
                    </div>
                    <div class="pt-3 border-t border-emerald-300 dark:border-emerald-700 flex justify-between items-center">
                        <span class="text-base font-bold text-gray-900 dark:text-white">Jami (chegirmadan keyin):</span>
                        <span class="text-xl font-black text-emerald-600 dark:text-emerald-400" id="summaryNet">0 so'm</span>
                    </div>
                    <div class="pt-3 border-t border-emerald-300 dark:border-emerald-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Student balansiga tushadi:</span>
                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400" id="summaryStudentBalance">0 so'm</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Direktor komissiyasi (<span id="commissionPercent">0</span>%):</span>
                            <span class="text-sm font-bold text-purple-600 dark:text-purple-400" id="summaryCommission">0 so'm</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3">
                <a href="{{ route('payments.index') }}"
                    class="flex-1 px-6 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all text-center">
                    Bekor qilish
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    To'lovni saqlash
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
        <script>
            // ─── Global State ─────────────────────────────────────────────────────────────
            let currentCommissionPercent = 0;
            let studentsData = [];
            let groupsData   = [];
            let amountCleave = null;

            // ─── DOM Elements ─────────────────────────────────────────────────────────────
            const studentInfo   = document.getElementById('studentInfo');
            const currentBalance = document.getElementById('currentBalance');
            const commissionRate = document.getElementById('commissionRate');
            const discountInput  = document.getElementById('discount');
            const amountHidden   = document.getElementById('amount_hidden');

            // ─── Helpers ─────────────────────────────────────────────────────────────────

            function formatCurrency(number) {
                return new Intl.NumberFormat('uz-UZ').format(Math.round(number)) + " so'm";
            }

            function formatNumber(num) {
                return new Intl.NumberFormat('uz-UZ').format(num);
            }

            function normalizeQuery(query) {
                return query.toLowerCase().trim().replace(/\s+/g, ' ');
            }

            // ─── Data Initialization ──────────────────────────────────────────────────────

            function initializeStudentsData() {
                studentsData = Array.from(document.querySelectorAll('.student-item')).map(item => {
                    const surname     = item.dataset.studentSurname || '';
                    const name        = item.dataset.studentName    || '';
                    const middle_name = item.dataset.studentMiddle  || '';
                    const phone       = item.dataset.studentPhone   || '';
                    const fullName    = [surname, name, middle_name].filter(Boolean).join(' ');

                    const student = {
                        id:         item.dataset.studentId,
                        fullName,
                        phone,
                        balance:    parseFloat(item.dataset.studentBalance)    || 0,
                        commission: parseFloat(item.dataset.studentCommission) || 0,
                    };

                    student.searchText = [
                        fullName,
                        phone,
                        surname + name,
                        surname + name + middle_name,
                        phone.replace(/[\s\-\+\(\)]/g, '')
                    ].join(' ').toLowerCase();

                    return student;
                });
            }

            function initializeGroupsData() {
                groupsData = Array.from(document.querySelectorAll('.group-item')).map(item => {
                    const group = {
                        id:      item.dataset.groupId,
                        name:    item.dataset.groupName    || '',
                        subject: item.dataset.groupSubject || '',
                        teacher: item.dataset.groupTeacher || '',
                    };
                    group.searchText = [group.name, group.subject, group.teacher].join(' ').toLowerCase();
                    return group;
                });
            }

            // ─── Search ───────────────────────────────────────────────────────────────────

            function matchesQuery(searchText, query) {
                return normalizeQuery(query).split(' ').every(word => searchText.includes(word));
            }

            function filterStudents() {
                const normalized = normalizeQuery(document.getElementById('studentSearchInput').value);
                let hasVisible   = false;

                document.querySelectorAll('.student-item').forEach(item => {
                    const student   = studentsData.find(s => s.id === item.dataset.studentId);
                    const isVisible = !normalized || (student && matchesQuery(student.searchText, normalized));
                    item.style.display = isVisible ? '' : 'none';
                    if (isVisible) hasVisible = true;
                });

                document.getElementById('noStudentFound').classList.toggle('hidden', hasVisible);
            }

            function filterGroups() {
                const normalized = normalizeQuery(document.getElementById('groupSearchInput').value);
                let found        = false;

                document.querySelectorAll('.group-item').forEach(item => {
                    const group     = groupsData.find(g => g.id === item.dataset.groupId);
                    const isVisible = !normalized || (group && matchesQuery(group.searchText, normalized));
                    item.style.display = isVisible ? '' : 'none';
                    if (isVisible) found = true;
                });

                document.getElementById('noGroupFound').classList.toggle('hidden', found);
            }

            // ─── Dropdowns ────────────────────────────────────────────────────────────────

            function closeDropdown(menuId, arrowId) {
                document.getElementById(menuId).classList.add('hidden');
                document.getElementById(arrowId).classList.remove('rotate-180');
            }

            function openDropdown(menuId, arrowId, focusId) {
                document.getElementById(menuId).classList.remove('hidden');
                document.getElementById(arrowId).classList.add('rotate-180');
                document.getElementById(focusId).focus();
            }

            function toggleStudentDropdown() {
                const isHidden = document.getElementById('studentMenu').classList.contains('hidden');
                closeDropdown('groupMenu', 'groupArrow');
                isHidden
                    ? openDropdown('studentMenu', 'studentArrow', 'studentSearchInput')
                    : closeDropdown('studentMenu', 'studentArrow');
            }

            function toggleGroupDropdown() {
                const isHidden = document.getElementById('groupMenu').classList.contains('hidden');
                closeDropdown('studentMenu', 'studentArrow');
                isHidden
                    ? openDropdown('groupMenu', 'groupArrow', 'groupSearchInput')
                    : closeDropdown('groupMenu', 'groupArrow');
            }

            document.addEventListener('click', function (e) {
                const studentContainer = document.getElementById('studentSearchContainer');
                const groupContainer   = document.getElementById('groupSearchContainer');

                if (studentContainer && !studentContainer.contains(e.target)) closeDropdown('studentMenu', 'studentArrow');
                if (groupContainer   && !groupContainer.contains(e.target))   closeDropdown('groupMenu',   'groupArrow');
            });

            // ─── Selection ────────────────────────────────────────────────────────────────

            function selectStudent(id) {
                const student = studentsData.find(s => s.id == id);
                if (!student) return;

                document.getElementById('student_id_input').value    = id;
                document.getElementById('selectedStudentLabel').innerText = `${student.fullName} - ${student.phone}`;

                studentInfo.classList.remove('hidden');
                currentBalance.innerText  = formatNumber(student.balance) + " so'm";
                commissionRate.innerText  = student.commission + "%";
                currentCommissionPercent  = student.commission;

                closeDropdown('studentMenu', 'studentArrow');
                updateSummary();
            }

            function selectGroup(id) {
                const group = groupsData.find(g => g.id == id);
                if (!group) return;

                document.getElementById('group_id_input').value      = id;
                document.getElementById('selectedGroupLabel').innerText = group.name + (group.subject ? ' - ' + group.subject : '');

                closeDropdown('groupMenu', 'groupArrow');
            }

            // ─── Summary Calculation ──────────────────────────────────────────────────────

            function updateSummary() {
                // FIX #1: Always read from Cleave raw value and sync to hidden input
                const rawValue = amountCleave ? amountCleave.getRawValue() : '';
                const amount   = parseFloat(rawValue) || 0;

                amountHidden.value = amount; // keep hidden input in sync

                let discountPercent = parseFloat(discountInput?.value) || 0;
                discountPercent     = Math.min(100, Math.max(0, discountPercent));
                discountInput.value = discountPercent;

                const discountAmount   = (amount * discountPercent) / 100;
                const netAmount        = amount - discountAmount;
                const commissionAmount = (netAmount * currentCommissionPercent) / 100;

                document.getElementById('summaryAmount').innerText        = formatCurrency(amount);
                document.getElementById('summaryDiscount').innerText      = formatCurrency(discountAmount);
                document.getElementById('summaryNet').innerText           = formatCurrency(netAmount);
                document.getElementById('summaryCommission').innerText    = formatCurrency(commissionAmount);
                document.getElementById('summaryStudentBalance').innerText = formatCurrency(netAmount);
                document.getElementById('discountPercent').innerText      = discountPercent;
                document.getElementById('commissionPercent').innerText    = currentCommissionPercent;
            }

            // ─── FIX #3: Prevent double submit & sync hidden amount on submit ─────────────

            document.getElementById('paymentForm').addEventListener('submit', function (e) {
                // Sync raw amount to hidden field one final time
                const rawValue = amountCleave ? amountCleave.getRawValue() : '';
                amountHidden.value = parseFloat(rawValue) || 0;

                // FIX #4: Basic client-side validation before submit
                const studentId = document.getElementById('student_id_input').value;
                if (!studentId) {
                    e.preventDefault();
                    alert("Iltimos, o'quvchini tanlang.");
                    return;
                }

                if (!amountHidden.value || parseFloat(amountHidden.value) <= 0) {
                    e.preventDefault();
                    alert("Iltimos, to'lov summasini kiriting.");
                    return;
                }

                // FIX #5: Prevent double submit
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled    = true;
                    btn.innerText   = "Saqlanmoqda...";
                }
            });

            // ─── Restore State ────────────────────────────────────────────────────────────

            function restoreSelectedStudent() {
                const id = document.getElementById('student_id_input').value;
                if (!id) return;

                const student = studentsData.find(s => s.id == id);
                if (!student) return;

                document.getElementById('selectedStudentLabel').innerText = `${student.fullName} - ${student.phone}`;
                studentInfo.classList.remove('hidden');
                currentBalance.innerText = formatNumber(student.balance) + " so'm";
                commissionRate.innerText = student.commission + "%";
                currentCommissionPercent = student.commission;
                updateSummary();
            }

            function restoreSelectedGroup() {
                const id = document.getElementById('group_id_input').value;
                if (!id) return;

                const group = groupsData.find(g => g.id == id);
                if (!group) return;

                document.getElementById('selectedGroupLabel').innerText =
                    group.name + (group.subject ? ' - ' + group.subject : '');
            }

            // ─── Init ─────────────────────────────────────────────────────────────────────

            document.addEventListener('DOMContentLoaded', function () {
                // FIX #1: Cleave is attached to display-only input; hidden input carries raw value
                amountCleave = new Cleave('#amount_display', {
                    numeral:                    true,
                    numeralThousandsGroupStyle: 'thousand',
                    delimiter:                  ' ',
                    onValueChanged:             () => updateSummary()
                });

                if (discountInput) discountInput.addEventListener('input', updateSummary);

                initializeStudentsData();
                initializeGroupsData();
                restoreSelectedStudent();
                restoreSelectedGroup();
            });
            
        </script>
    @endpush
@endsection