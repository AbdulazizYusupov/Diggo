@extends('Layout.app')
@section('title', ($student->user?->full_name ?? 'O\'quvchi') . ' — Moliyaviy Karta')
@section('content')

    <div class="space-y-6">

        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Back button --}}
                <a href="{{ route('payments.matrix') }}"
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all shadow-sm hover:shadow active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="flex items-center gap-3.5">
                    {{-- Avatar --}}
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-400 via-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-lg shadow-md shadow-indigo-200 dark:shadow-none">
                        {{ substr($student->user?->name ?? '', 0, 1) }}{{ substr($student->user?->surname ?? '', 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ $student->user?->full_name ?? '—' }}
                        </h1>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $student->user?->phone ?? '—' }}
                            </span>
                            @foreach ($student->activeGroups as $group)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-700/40">
                                    {{ $group->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Year filter --}}
            <form method="GET" action="{{ route('payments.student-card', $student->id) }}"
                class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()"
                    class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none cursor-pointer shadow-sm">
                    @foreach ($availableYears as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}-yil
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- ===== SUMMARY CARDS ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Joriy balans --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-indigo-500/20 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-indigo-600 dark:text-indigo-400 mb-2">
                            Joriy Balans</p>
                        <p
                            class="text-2xl font-black {{ $student->balance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ number_format($student->balance, 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">so'm</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl {{ $student->balance >= 0 ? 'bg-emerald-50 dark:bg-emerald-900/20' : 'bg-rose-50 dark:bg-rose-900/20' }} flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $student->balance >= 0 ? 'text-emerald-500' : 'text-rose-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Yil davomida to'langan --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-emerald-500/20 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-2">
                            {{ $year }}-yilda To'langan</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white">
                            {{ number_format($totalPaid, 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">so'm</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Qarzli oylar --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-rose-500/20 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-rose-600 dark:text-rose-400 mb-2">Qarzli
                            Oylar</p>
                        <p
                            class="text-2xl font-black {{ $debtMonths > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $debtMonths }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">ta oy</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl {{ $debtMonths > 0 ? 'bg-rose-50 dark:bg-rose-900/20' : 'bg-emerald-50 dark:bg-emerald-900/20' }} flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $debtMonths > 0 ? 'text-rose-500' : 'text-emerald-500' }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Oxirgi to'lov --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-blue-500/20 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400 mb-2">Oxirgi
                            To'lov</p>
                        @if ($lastPayment)
                            <p class="text-lg font-black text-gray-900 dark:text-white leading-tight">
                                {{ $lastPayment->paid_at?->format('d.m.Y') ?? '—' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ number_format($lastPayment->net_amount, 0, '.', ' ') }} so'm</p>
                        @else
                            <p class="text-lg font-black text-gray-400">—</p>
                            <p class="text-xs text-gray-400 mt-0.5">To'lov yo'q</p>
                        @endif
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== 12 OYLIK BREAKDOWN ===== --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ $year }}-yil Oylik To'lovlar</h2>
                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">12 oy</span>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($months as $num => $monthName)
                    @php
                        $cell = $breakdown[$num] ?? ['status' => 'future', 'payments' => collect()];
                        $status = $cell['status'];
                    @endphp

                    <div
                        class="px-5 py-4 flex items-center gap-4 {{ $status === 'paid' ? 'hover:bg-emerald-50/30 dark:hover:bg-emerald-900/5' : ($status === 'unpaid' ? 'hover:bg-rose-50/30 dark:hover:bg-rose-900/5' : 'hover:bg-gray-50/50 dark:hover:bg-slate-800/30') }} transition-colors">

                        {{-- Oy raqami va nomi --}}
                        <div class="flex-shrink-0 w-20 flex items-center gap-2">
                            <span
                                class="text-xs font-bold w-5 text-gray-400 dark:text-gray-600 text-right">{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $monthName }}</span>
                        </div>

                        {{-- Status badge --}}
                        <div class="flex-shrink-0 w-28">
                            @if ($status === 'paid')
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700/40">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    To'langan
                                </span>
                            @elseif($status === 'partial')
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-700/40">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 8v4m0 4h.01" />
                                    </svg>
                                    Qisman
                                </span>
                            @elseif($status === 'unpaid')
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-700/40">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Qarzdor
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-gray-600 border border-gray-200 dark:border-gray-700">
                                    — Hali kelmagan
                                </span>
                            @endif
                        </div>

                        {{-- To'lovlar ro'yxati --}}
                        <div class="flex-1 min-w-0">
                            @if ($cell['payments']->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($cell['payments'] as $payment)
                                        <a href="{{ route('payments.show', $payment->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-indigo-300 dark:hover:border-indigo-600 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                                            <span class="font-bold">{{ number_format($payment->net_amount, 0, '.', ' ') }}
                                                so'm</span>
                                            @php
                                                $typeLabels = [
                                                    'cash' => 'Naqd',
                                                    'card' => 'Karta',
                                                    'transfer' => "O'tkazma",
                                                    'other' => 'Boshqa',
                                                ];
                                                $typeColors = [
                                                    'cash' => 'text-emerald-600 dark:text-emerald-400',
                                                    'card' => 'text-blue-600 dark:text-blue-400',
                                                    'transfer' => 'text-purple-600 dark:text-purple-400',
                                                    'other' => 'text-gray-500',
                                                ];
                                            @endphp
                                            <span
                                                class="text-[10px] {{ $typeColors[$payment->payment_type] ?? 'text-gray-400' }}">
                                                {{ $typeLabels[$payment->payment_type] ?? $payment->payment_type }}
                                            </span>
                                            @if ($payment->discount > 0)
                                                <span
                                                    class="text-[10px] text-rose-500 font-bold">-{{ $payment->discount }}%</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @elseif($status === 'unpaid')
                                <span class="text-sm text-gray-400 dark:text-gray-600 italic">To'lov amalga
                                    oshirilmagan</span>
                            @endif
                        </div>

                        {{-- Sana --}}
                        <div class="flex-shrink-0 text-right min-w-[80px]">
                            @if ($cell['payments']->isNotEmpty())
                                @php $firstPay = $cell['payments']->first(); @endphp
                                <div class="text-xs font-semibold text-gray-600 dark:text-gray-400">
                                    {{ $firstPay->paid_at?->format('d.m.Y') ?? '—' }}
                                </div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-600">
                                    {{ $firstPay->paid_at?->format('H:i') ?? '' }}
                                </div>
                            @endif
                        </div>

                        {{-- To'lov tugmasi (faqat qarzdor oylar uchun) --}}
                        @if ($status === 'unpaid')
                            <div class="flex-shrink-0">
                                <a href="{{ route('payments.create', ['student_id' => $student->id]) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm active:scale-95">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    To'lov
                                </a>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ota-ona aloqa --}}
        @if ($student->parent_name || $student->parent_phone)
            <div
                class="bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-700/30 rounded-2xl p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div class="flex-1">
                    <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wide mb-0.5">Ota-ona
                    </p>
                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-300">
                        {{ $student->parent_name ?? '—' }}
                        @if ($student->parent_phone)
                            · <a href="tel:{{ $student->parent_phone }}"
                                class="underline underline-offset-2 hover:no-underline">{{ $student->parent_phone }}</a>
                        @endif
                    </p>
                </div>
            </div>
        @endif

    </div>
@endsection
