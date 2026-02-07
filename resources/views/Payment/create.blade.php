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

            <!-- Student Selection -->
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
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
                                    @php
                                        $currentStudentId = old('student_id', $selectedStudent?->id);
                                        $foundStudent = $students->firstWhere('id', $currentStudentId);
                                    @endphp
                                    {{ $foundStudent ? $foundStudent->user->full_name . ' - ' . $foundStudent->user->phone : '-- O\'quvchini tanlang --' }}
                                </span>

                                <input type="hidden" name="student_id" id="student_id_input"
                                    value="{{ $currentStudentId }}" required>

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
                                        placeholder="Ism yoki telefon orqali qidirish..."
                                        class="w-full px-3 py-2.5 bg-white dark:bg-slate-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                </div>

                                <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                    @foreach ($students as $student)
                                        <div onclick="selectStudent(
                                            '{{ $student->id }}', 
                                            '{{ $student->user->full_name }}', 
                                            '{{ $student->user->phone }}', 
                                            '{{ $student->balance }}', 
                                            '{{ $student->director->commission_percentage ?? 0 }}'
                                        )"
                                            data-student-id="{{ $student->id }}"
                                            data-student-name="{{ $student->user->full_name }}"
                                            data-student-phone="{{ $student->user->phone }}"
                                            class="student-item px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-none flex flex-col">
                                            <span class="font-bold student-name-text">{{ $student->user->full_name }}</span>
                                            <span class="text-xs text-gray-500 student-phone-text">{{ $student->user->phone }}</span>
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
                        class="{{ $currentStudentId ? '' : 'hidden' }} p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Joriy balans</p>
                                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400" id="currentBalance">
                                    {{ number_format($foundStudent->balance ?? 0, 0, '.', ' ') }} so'm
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Direktor komissiyasi</p>
                                <p class="text-lg font-bold text-purple-600 dark:text-purple-400" id="commissionRate">
                                    {{ $foundStudent->director->commission_percentage ?? 0 }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
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
                        <input type="number" name="amount" id="amount" step="0.01" min="0"
                            value="{{ old('amount') }}" required
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
                        <input type="number" name="discount" id="discount" step="0.01" min="0" max="100"
                            value="{{ old('discount', 0) }}"
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
                            <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Naqd</option>
                            <option value="card" {{ old('payment_type') == 'card' ? 'selected' : '' }}>Karta</option>
                            <option value="transfer" {{ old('payment_type') == 'transfer' ? 'selected' : '' }}>O'tkazma
                            </option>
                            <option value="other" {{ old('payment_type') == 'other' ? 'selected' : '' }}>Boshqa</option>
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

                <!-- Installment Options -->
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_installment" id="isInstallment" value="1"
                            {{ old('is_installment') ? 'checked' : '' }}
                            class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Bo'lib to'lash</span>
                    </label>

                    <div id="installmentFields" class="hidden mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Joriy to'lov raqami
                            </label>
                            <input type="number" name="installment_number" min="1"
                                value="{{ old('installment_number', 1) }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Jami to'lovlar soni
                            </label>
                            <input type="number" name="total_installments" min="1"
                                value="{{ old('total_installments', 1) }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all">
                        </div>
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
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-500/20 p-6">
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
                    <div
                        class="pt-3 border-t border-emerald-300 dark:border-emerald-700 flex justify-between items-center">
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
        <script>
            const studentInfo = document.getElementById('studentInfo');
            const currentBalance = document.getElementById('currentBalance');
            const commissionRate = document.getElementById('commissionRate');
            const amountInput = document.getElementById('amount');
            const discountInput = document.getElementById('discount');
            const isInstallmentCheckbox = document.getElementById('isInstallment');
            const installmentFields = document.getElementById('installmentFields');

            let currentCommissionPercent = {{ $foundStudent->director->commission_percentage ?? 0 }};

            // Toggle student dropdown
            function toggleStudentDropdown() {
                const menu = document.getElementById('studentMenu');
                const arrow = document.getElementById('studentArrow');
                const isHidden = menu.classList.contains('hidden');

                menu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');

                if (isHidden) {
                    document.getElementById('studentSearchInput').focus();
                }
            }

            // Select student
            function selectStudent(id, name, phone, balance, commission) {
                // Update input and label
                document.getElementById('student_id_input').value = id;
                document.getElementById('selectedStudentLabel').innerText = name + " - " + phone;

                // Update info block
                const infoBlock = document.getElementById('studentInfo');
                const balanceText = document.getElementById('currentBalance');
                const commissionText = document.getElementById('commissionRate');

                infoBlock.classList.remove('hidden');

                const formattedBalance = new Intl.NumberFormat('uz-UZ').format(balance) + " so'm";
                balanceText.innerText = formattedBalance;
                commissionText.innerText = commission + "%";

                // Store commission for calculations
                currentCommissionPercent = parseFloat(commission);

                // Close dropdown
                toggleStudentDropdown();

                // Update summary
                updateSummary();
            }

            // Filter students
            function filterStudents() {
                const input = document.getElementById('studentSearchInput');
                const filter = input.value.toLowerCase();
                const items = document.querySelectorAll('.student-item');
                const noResult = document.getElementById('noStudentFound');
                let found = false;

                items.forEach(item => {
                    const name = item.querySelector('.student-name-text').innerText.toLowerCase();
                    const phone = item.querySelector('.student-phone-text').innerText.toLowerCase();

                    if (name.includes(filter) || phone.includes(filter)) {
                        item.style.display = "";
                        found = true;
                    } else {
                        item.style.display = "none";
                    }
                });

                noResult.classList.toggle('hidden', found);
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const container = document.getElementById('studentSearchContainer');
                if (container && !container.contains(e.target)) {
                    document.getElementById('studentMenu').classList.add('hidden');
                    document.getElementById('studentArrow').classList.remove('rotate-180');
                }
            });

            // Amount and discount change
            amountInput.addEventListener('input', updateSummary);
            discountInput.addEventListener('input', updateSummary);

            // Installment checkbox
            isInstallmentCheckbox.addEventListener('change', function() {
                installmentFields.classList.toggle('hidden', !this.checked);
            });

            // Update summary calculations
            function updateSummary() {
                const amount = parseFloat(amountInput.value) || 0;
                const discountPercent = parseFloat(discountInput.value) || 0;

                // Calculate amounts
                const discountAmount = (amount * discountPercent) / 100;
                const netAmount = amount - discountAmount;
                const commissionAmount = (amount * currentCommissionPercent) / 100; // Commission from PAID amount

                // Update display
                document.getElementById('summaryAmount').textContent = formatNumber(amount) + ' so\'m';
                document.getElementById('discountPercent').textContent = discountPercent.toFixed(1);
                document.getElementById('summaryDiscount').textContent = formatNumber(discountAmount) + ' so\'m';
                document.getElementById('summaryNet').textContent = formatNumber(netAmount) + ' so\'m';
                document.getElementById('summaryStudentBalance').textContent = formatNumber(amount) + ' so\'m';
                document.getElementById('commissionPercent').textContent = currentCommissionPercent.toFixed(1);
                document.getElementById('summaryCommission').textContent = formatNumber(commissionAmount) + ' so\'m';
            }

            // Format number helper
            function formatNumber(num) {
                return new Intl.NumberFormat('uz-UZ').format(num);
            }

            // Initialize on page load
            if (isInstallmentCheckbox.checked) {
                installmentFields.classList.remove('hidden');
            }

            // Initial calculation if student is selected
            if (document.getElementById('student_id_input').value) {
                updateSummary();
            }
        </script>
    @endpush
@endsection