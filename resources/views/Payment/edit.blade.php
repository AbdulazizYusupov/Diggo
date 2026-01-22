@extends('Layout.app')
@section('title', 'To\'lovni tahrirlash')
@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('payments.show', $payment->id) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">To'lovni tahrirlash</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">To'lov ma'lumotlarini yangilang</p>
            </div>
        </div>

        <!-- Warning Message -->
        <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-500/20 rounded-xl">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <h3 class="text-sm font-bold text-amber-900 dark:text-amber-300">Diqqat!</h3>
                    <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">
                        Faqat to'lov summasi, chegirma va to'lov turi o'zgartirilishi mumkin. O'quvchi va to'lov oyi o'zgartirilmaydi.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('payments.update', $payment->id) }}" method="POST" class="mt-6 space-y-6" id="paymentForm">
            @csrf
            @method('PUT')

            <!-- Student Info (Read-only) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    O'quvchi ma'lumotlari
                </h2>

                <div class="p-4 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">O'quvchi</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">{{ $payment->student->user->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Telefon</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">{{ $payment->student->user->phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">To'lov oyi</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">{{ $payment->payment_month->format('F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-xl">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Joriy balans</p>
                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($payment->student->balance, 0, '.', ' ') }} so'm</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Direktor komissiyasi</p>
                            <p class="text-lg font-bold text-purple-600 dark:text-purple-400" id="commissionRate">{{ $payment->director->commission_percentage ?? 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    To'lov ma'lumotlari
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            To'lov summasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount', $payment->amount) }}" required
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
                        <input type="number" name="discount" id="discount" step="0.01" min="0" max="100" value="{{ old('discount', $payment->discount) }}"
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
                            <option value="cash" {{ old('payment_type', $payment->payment_type) == 'cash' ? 'selected' : '' }}>Naqd</option>
                            <option value="card" {{ old('payment_type', $payment->payment_type) == 'card' ? 'selected' : '' }}>Karta</option>
                            <option value="transfer" {{ old('payment_type', $payment->payment_type) == 'transfer' ? 'selected' : '' }}>O'tkazma</option>
                            <option value="other" {{ old('payment_type', $payment->payment_type) == 'other' ? 'selected' : '' }}>Boshqa</option>
                        </select>
                        @error('payment_type')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="opacity-50 pointer-events-none">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            To'lov oyi (o'zgartirib bo'lmaydi)
                        </label>
                        <input type="text" value="{{ $payment->payment_month->format('F Y') }}" disabled
                            class="w-full px-4 py-3 bg-gray-100 dark:bg-slate-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-500 dark:text-gray-400">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Izoh
                    </label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white outline-none transition-all resize-none"
                        placeholder="Qo'shimcha ma'lumotlar...">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-500/20 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Yangilangan to'lov xulosasi</h2>
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
                <a href="{{ route('payments.show', $payment->id) }}" 
                    class="flex-1 px-6 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all text-center">
                    Bekor qilish
                </a>
                <button type="submit" 
                    class="flex-1 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    O'zgarishlarni saqlash
                </button>
            </div>
        </form>
    </div>

    {{-- @push('scripts') --}}
    <script>
        const amountInput = document.getElementById('amount');
        const discountInput = document.getElementById('discount');
        const commissionRate = parseFloat(document.getElementById('commissionRate').textContent) || 0;

        amountInput.addEventListener('input', updateSummary);
        discountInput.addEventListener('input', updateSummary);

        function updateSummary() {
            const amount = parseFloat(amountInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            const discountAmount = (amount * discount) / 100;
            const netAmount = amount - discountAmount;
            const commissionAmount = (netAmount * commissionRate) / 100;

            document.getElementById('summaryAmount').textContent = new Intl.NumberFormat('uz-UZ').format(amount) + ' so\'m';
            document.getElementById('summaryDiscount').textContent = new Intl.NumberFormat('uz-UZ').format(discountAmount) + ' so\'m';
            document.getElementById('summaryNet').textContent = new Intl.NumberFormat('uz-UZ').format(netAmount) + ' so\'m';
            document.getElementById('summaryCommission').textContent = new Intl.NumberFormat('uz-UZ').format(commissionAmount) + ' so\'m';
        }

        // Initialize on page load
        updateSummary();
    </script>
    {{-- @endpush --}}
@endsection