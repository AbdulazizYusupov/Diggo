@extends('Layout.app')

@section('title', 'To\'lovlar Tarixi')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    To'lovlar Tarixi
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Barcha to'lovlaringiz va ularning tafsilotlari
                </p>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase">Jami to'landi</p>
                        <p class="text-2xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($stats['total_paid'], 0, '.', ' ') }} <span class="text-sm">so'm</span>
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase">Kutilmoqda</p>
                        <p class="text-2xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ number_format($stats['pending_amount'], 0, '.', ' ') }} <span class="text-sm">so'm</span>
                        </p>
                    </div>
                    <div class="p-3 bg-amber-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase">Chegirma</p>
                        <p class="text-2xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ number_format($stats['total_discount'], 0, '.', ' ') }} <span class="text-sm">so'm</span>
                        </p>
                    </div>
                    <div class="p-3 bg-blue-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase">To'lovlar soni</p>
                        <p class="text-2xl font-extrabold text-purple-900 dark:text-purple-50 mt-1">
                            {{ $stats['total_payments'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('student.payments') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Barchasi</option>
                        <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>To'langan</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                        <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Muvaffaqiyatsiz</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Bekor qilingan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Oy</label>
                    <select name="month"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Barcha oylar</option>
                        @foreach ($months as $key => $monthName)
                            <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>
                                {{ $monthName }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Yil</label>
                    <select name="year"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors">
                        Filtrlash
                    </button>
                </div>
            </form>
        </div>

        {{-- Payments Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">To'lovlar ro'yxati</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Guruh/Kurs</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Summa</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Chegirma</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Jami</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Turi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Sana</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Amal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-mono text-gray-900 dark:text-white">#{{ $payment->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $payment->group->name ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $payment->group->cours->title ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($payment->amount, 0, '.', ' ') }} so'm
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($payment->discount > 0)
                                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                                            -{{ number_format($payment->discount, 0, '.', ' ') }} so'm
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($payment->net_amount, 0, '.', ' ') }} so'm
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                        {{ $payment->payment_type === 'cash' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                        {{ $payment->payment_type === 'card' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                        {{ $payment->payment_type === 'transfer' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : '' }}">
                                        @if ($payment->payment_type === 'cash')
                                            Naqd
                                        @elseif($payment->payment_type === 'card')
                                            Karta
                                        @elseif($payment->payment_type === 'transfer')
                                            O'tkazma
                                        @else
                                            {{ ucfirst($payment->payment_type) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                        {{ $payment->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                        {{ $payment->status === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                        {{ $payment->status === 'cancelled' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' : '' }}">
                                        @if ($payment->status === 'paid')
                                            To'langan
                                        @elseif($payment->status === 'pending')
                                            Kutilmoqda
                                        @elseif($payment->status === 'failed')
                                            Muvaffaqiyatsiz
                                        @elseif($payment->status === 'cancelled')
                                            Bekor qilingan
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d.m.Y') : '-' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('H:i') : '' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('student.payments.show', $payment->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ko'rish
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">To'lovlar topilmadi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($payments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
