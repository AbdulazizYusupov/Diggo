@extends('Layout.app')
@section('title', 'To\'lov qabul qilish')
@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('payments.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    O'quvchini tanlash
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">O'quvchi</label>
                        <select name="student_id" id="studentSelect" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all">
                            <option value="">-- O'quvchini tanlang --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" 
                                    data-balance="{{ $student->balance }}"
                                    data-commission="{{ $student->director->commission_percentage ?? 0 }}"
                                    {{ old('student_id', $selectedStudent?->id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->full_name }} - {{ $student->user->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Info Display -->
                    <div id="studentInfo" class="hidden p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl">
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

            <!-- Payment Details -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    To'lov ma'lumotlari
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            To'lov summasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount') }}" required
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
                        <input type="number" name="discount" id="discount" step="0.01" min="0" max="100" value="{{ old('discount', 0) }}"
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
                            <option value="transfer" {{ old('payment_type') == 'transfer' ? 'selected' : '' }}>O'tkazma</option>
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
                        <input type="month" name="payment_month" value="{{ old('payment_month', now()->format('Y-m')) }}" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all">
                        @error('payment_month')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Installment Options -->
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_installment" id="isInstallment" value="1" {{ old('is_installment') ? 'checked' : '' }}
                            class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Bo'lib to'lash</span>
                    </label>

                    <div id="installmentFields" class="hidden mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Joriy to'lov raqami
                            </label>
                            <input type="number" name="installment_number" min="1" value="{{ old('installment_number', 1) }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Jami to'lovlar soni
                            </label>
                            <input type="number" name="total_installments" min="1" value="{{ old('total_installments', 1) }}"
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
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">To'lov xulosasi</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Asosiy summa:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white" id="summaryAmount">0 so'm</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Chegirma:</span>
                        <span class="text-sm font-bold text-rose-500" id="summaryDiscount">0 so'm</span>
                    </div>
                    <div class="pt-3 border-t border-emerald-300 dark:border-emerald-700 flex justify-between items-center">
                        <span class="text-base font-bold text-gray-900 dark:text-white">Jami to'lov:</span>
                        <span class="text-xl font-black text-emerald-600 dark:text-emerald-400" id="summaryNet">0 so'm</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Direktor komissiyasi:</span>
                        <span class="text-sm font-bold text-purple-600 dark:text-purple-400" id="summaryCommission">0 so'm</span>
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
        const studentSelect = document.getElementById('studentSelect');
        const studentInfo = document.getElementById('studentInfo');
        const currentBalance = document.getElementById('currentBalance');
        const commissionRate = document.getElementById('commissionRate');
        const amountInput = document.getElementById('amount');
        const discountInput = document.getElementById('discount');
        const isInstallmentCheckbox = document.getElementById('isInstallment');
        const installmentFields = document.getElementById('installmentFields');

        // Student selection change
        studentSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (this.value) {
                const balance = parseFloat(option.dataset.balance) || 0;
                const commission = parseFloat(option.dataset.commission) || 0;
                
                currentBalance.textContent = new Intl.NumberFormat('uz-UZ').format(balance) + ' so\'m';
                commissionRate.textContent = commission + '%';
                studentInfo.classList.remove('hidden');
                
                updateSummary();
            } else {
                studentInfo.classList.add('hidden');
            }
        });

        // Amount and discount change
        amountInput.addEventListener('input', updateSummary);
        discountInput.addEventListener('input', updateSummary);
        studentSelect.addEventListener('change', updateSummary);

        // Installment checkbox
        isInstallmentCheckbox.addEventListener('change', function() {
            if (this.checked) {
                installmentFields.classList.remove('hidden');
            } else {
                installmentFields.classList.add('hidden');
            }
        });

        function updateSummary() {
            const amount = parseFloat(amountInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;
            const option = studentSelect.options[studentSelect.selectedIndex];
            const commission = parseFloat(option.dataset.commission) || 0;

            const discountAmount = (amount * discount) / 100;
            const netAmount = amount - discountAmount;
            const commissionAmount = (netAmount * commission) / 100;

            document.getElementById('summaryAmount').textContent = new Intl.NumberFormat('uz-UZ').format(amount) + ' so\'m';
            document.getElementById('summaryDiscount').textContent = new Intl.NumberFormat('uz-UZ').format(discountAmount) + ' so\'m';
            document.getElementById('summaryNet').textContent = new Intl.NumberFormat('uz-UZ').format(netAmount) + ' so\'m';
            document.getElementById('summaryCommission').textContent = new Intl.NumberFormat('uz-UZ').format(commissionAmount) + ' so\'m';
        }

        // Initialize on page load
        if (studentSelect.value) {
            studentSelect.dispatchEvent(new Event('change'));
        }

        if (isInstallmentCheckbox.checked) {
            installmentFields.classList.remove('hidden');
        }
    </script>
    @endpush
@endsection