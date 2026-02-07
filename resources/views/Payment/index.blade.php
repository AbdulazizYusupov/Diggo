@extends('Layout.app')
@section('title', 'To\'lovlar')
@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">To'lovlar</h1>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20 shadow-sm">
                            {{ $payments->total() }} ta to'lov
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        O'quvchilar to'lovlarini boshqarish
                    </p>
                </div>
            </div>

            <a href="{{ route('payments.create') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white dark:bg-emerald-600 dark:hover:bg-emerald-700 border border-emerald-400/50 shadow-sm shadow-emerald-100 dark:shadow-none font-semibold rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5 text-white group-hover:rotate-12 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="font-bold">To'lov qabul qilish</span>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400">Jami
                            to'lovlar</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white mt-2">
                            {{ number_format($totalPayments, 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">so'm</p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400">
                            Komissiyalar</p>
                        <p class="text-3xl font-black text-purple-600 dark:text-purple-400 mt-2">
                            {{ number_format($totalCommissions, 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">so'm</p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-purple-50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400">Shu oyda</p>
                        <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-2">
                            {{ number_format($monthlyPayments, 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">so'm</p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-amber-500/20 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400">Kutilmoqda
                        </p>
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-2">
                            {{ $pendingPayments }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ta to'lov</p>
                    </div>
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-4 lg:p-6 transition-all">
            <form method="GET" action="{{ route('payments.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

                    <div class="relative lg:col-span-4">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="O'quvchi qidirish..."
                            class="w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none text-sm">
                        @if (request('search'))
                            <a href="{{ route('payments.index', request()->except('search')) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <div class="lg:col-span-4">
                        <select name="status"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">Status (Barchasi)</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>
                                To'langan</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Kutilmoqda
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Bekor
                                qilingan</option>
                        </select>
                    </div>

                    <div class="lg:col-span-4">
                        <select name="payment_type"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">To'lov turi (Barchasi)</option>
                            <option value="cash" {{ request('payment_type') === 'cash' ? 'selected' : '' }}>Naqd
                            </option>
                            <option value="card" {{ request('payment_type') === 'card' ? 'selected' : '' }}>Karta
                            </option>
                            <option value="transfer" {{ request('payment_type') === 'transfer' ? 'selected' : '' }}>
                                O'tkazma</option>
                            <option value="other" {{ request('payment_type') === 'other' ? 'selected' : '' }}>Boshqa
                            </option>
                        </select>
                    </div>

                    <div class="lg:col-span-4">
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    <div class="lg:col-span-4">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    <div class="flex gap-2 lg:col-span-4">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <span>Qidirish</span>
                        </button>
                        <a href="{{ route('payments.index') }}"
                            class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-500 dark:text-gray-400 rounded-xl transition-all flex items-center justify-center active:scale-95"
                            title="Tozalash">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Payments Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                O'quvchi</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                To'langan summa</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Jami summa</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                To'lov turi</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Sana</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($payment->payable->user->name, 0, 1) }}{{ substr($payment->payable->user->surname, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $payment->payable->user->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $payment->payable->user->phone }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                            {{ number_format($payment->amount, 0, '.', ' ') }} so'm
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Balansga tushdi
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="space-y-1">
                                        @if ($payment->discount > 0)
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ number_format($payment->net_amount, 0, '.', ' ') }} so'm
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span class="line-through">{{ number_format($payment->amount, 0, '.', ' ') }}</span>
                                                <span class="ml-1 text-rose-500 font-semibold">-{{ $payment->discount }}%</span>
                                            </div>
                                        @else
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ number_format($payment->net_amount, 0, '.', ' ') }} so'm
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'cash' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            'card' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'transfer' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                            'other' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                        ];
                                        $typeNames = [
                                            'cash' => 'Naqd',
                                            'card' => 'Karta',
                                            'transfer' => 'O\'tkazma',
                                            'other' => 'Boshqa',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg {{ $typeColors[$payment->payment_type] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $typeNames[$payment->payment_type] ?? $payment->payment_type }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $payment->paid_at?->format('d.m.Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $payment->paid_at?->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            'failed' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                            'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                        ];
                                        $statusNames = [
                                            'paid' => 'To\'langan',
                                            'pending' => 'Kutilmoqda',
                                            'failed' => 'Muvaffaqiyatsiz',
                                            'cancelled' => 'Bekor qilingan',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ $statusNames[$payment->status] ?? $payment->status }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('payments.show', $payment->id) }}"
                                            class="p-2 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl transition-all duration-200 group/btn"
                                            title="Ko'rish">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if ($payment->status !== 'paid')
                                            <a href="{{ route('payments.edit', $payment->id) }}"
                                                class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all duration-200 group/btn"
                                                title="Tahrirlash">
                                                <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <button type="button"
                                                onclick="document.getElementById('delete-modal-{{ $payment->id }}').classList.remove('hidden')"
                                                class="p-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all duration-200 group/btn"
                                                title="O'chirish">
                                                <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <!-- Delete Modal -->
                                            <div id="delete-modal-{{ $payment->id }}"
                                                class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
                                                <div
                                                    class="relative w-full max-w-sm p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5 transform transition-all">
                                                    <div class="text-center">
                                                        <div
                                                            class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 dark:bg-rose-500/10 mb-6 rotate-3">
                                                            <svg class="h-10 w-10 text-rose-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                        </div>
                                                        <h3
                                                            class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                                                            O'chirishni tasdiqlang</h3>
                                                        <p
                                                            class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                                                            Rostdan ham bu to'lovni o'chirmoqchimisiz?
                                                        </p>
                                                        <div class="grid grid-cols-2 gap-3">
                                                            <button type="button"
                                                                onclick="document.getElementById('delete-modal-{{ $payment->id }}').classList.add('hidden')"
                                                                class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                                                                Bekor qilish
                                                            </button>
                                                            <form action="{{ route('payments.destroy', $payment->id) }}"
                                                                method="POST" class="m-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="w-full px-5 py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 dark:shadow-none transition-all transform active:scale-95">
                                                                    O'chirilsin
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">To'lovlar topilmadi
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Yangi to'lov qabul qilish
                                        uchun yuqoridagi tugmani bosing.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($payments->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection