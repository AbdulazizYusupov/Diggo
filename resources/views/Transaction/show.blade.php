@extends('Layout.app')

@section('title', 'Tranzaksiya tafsilotlari')

@section('content')
    <div class="space-y-6">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('transactions.index') }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all group">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>

                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                        Tranzaksiya #{{ $transaction->id }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $transaction->transaction_date->format('d.m.Y H:i') }}
                    </p>
                </div>
            </div>

            {{-- Status Badge --}}
            <span
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl {{ App\Http\Controllers\Transaction\TransactionController::getStatusBadgeClasses($transaction->status) }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3" />
                </svg>
                {{ App\Http\Controllers\Transaction\TransactionController::getStatusLabel($transaction->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info Card -->
            <div class="lg:col-span-2 space-y-6">
                {{-- Transaction Details --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tranzaksiya ma'lumotlari
                    </h2>

                    <dl class="space-y-4">
                        {{-- Type --}}
                        <div class="flex items-start justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Turi</dt>
                            <dd>
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-lg {{ App\Http\Controllers\Transaction\TransactionController::getTypeBadgeClasses($transaction->type) }}">
                                    {{ App\Http\Controllers\Transaction\TransactionController::getTypeLabel($transaction->type) }}
                                </span>
                            </dd>
                        </div>

                        {{-- Amount --}}
                        <div class="flex items-start justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Summa</dt>
                            <dd class="text-right">
                                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($transaction->amount, 0, '.', ' ') }} so'm
                                </div>
                                @if ($transaction->percentage && $transaction->base_amount)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $transaction->percentage }}% dan
                                        {{ number_format($transaction->base_amount, 0, '.', ' ') }} so'm
                                    </div>
                                @endif
                            </dd>
                        </div>

                        {{-- Balance Change --}}
                        <div class="flex items-start justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Balans o'zgarishi</dt>
                            <dd class="text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                        {{ number_format($transaction->balance_before, 0, '.', ' ') }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($transaction->balance_after, 0, '.', ' ') }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $transaction->balance_after > $transaction->balance_before ? '+' : '' }}
                                    {{ number_format($transaction->balance_after - $transaction->balance_before, 0, '.', ' ') }}
                                    so'm
                                </div>
                            </dd>
                        </div>

                        {{-- Description --}}
                        <div class="flex items-start justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Izoh</dt>
                            <dd class="text-sm text-gray-900 dark:text-white text-right max-w-md">
                                {{ $transaction->description }}
                            </dd>
                        </div>

                        {{-- Transaction Date --}}
                        <div class="flex items-start justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tranzaksiya sanasi</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $transaction->transaction_date->format('d.m.Y H:i') }}
                            </dd>
                        </div>

                        {{-- Paid At --}}
                        @if ($transaction->paid_at)
                            <div
                                class="flex items-start justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">To'langan sana</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaction->paid_at->format('d.m.Y H:i') }}
                                </dd>
                            </div>
                        @endif

                        {{-- Created By --}}
                        @if ($transaction->creator)
                            <div
                                class="flex items-start justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kim tomonidan</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaction->creator->name }}
                                </dd>
                            </div>
                        @endif

                        {{-- Created At --}}
                        <div class="flex items-start justify-between py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Yaratilgan sana</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $transaction->created_at->format('d.m.Y H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                {{-- Transactionable Info --}}
                @if ($transaction->transactionable)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Kim uchun
                        </h3>

                        <div class="space-y-4">
                            {{-- Avatar & Name --}}
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">
                                        {{ substr($transaction->transactionable->user->name ?? 'N', 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $transaction->transactionable->user->full_name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ class_basename($transaction->transactionable_type) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Current Balance --}}
                            <div class="p-4 bg-purple-50 dark:bg-purple-500/10 rounded-xl border border-purple-100 dark:border-purple-500/20">
                                <div class="text-xs text-purple-600 dark:text-purple-400 font-semibold mb-1">Joriy balans
                                </div>
                                <div class="text-2xl font-black text-purple-700 dark:text-purple-300">
                                    {{ number_format($transaction->transactionable->balance ?? 0, 0, '.', ' ') }}
                                </div>
                                <div class="text-xs text-purple-600 dark:text-purple-400">so'm</div>
                            </div>

                            {{-- Contact Info --}}
                            @if ($transaction->transactionable->user)
                                <div class="space-y-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                                    @if ($transaction->transactionable->user->phone)
                                        <div class="flex items-center gap-2 text-sm">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span
                                                class="text-gray-600 dark:text-gray-400">{{ $transaction->transactionable->user->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Source Info --}}
                @if ($transaction->source)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            Manba
                        </h3>

                        <div class="space-y-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Turi</div>
                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ class_basename($transaction->source_type) }}
                            </div>

                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-3">ID</div>
                            <div class="text-sm font-mono text-gray-900 dark:text-white">
                                #{{ $transaction->source_id }}
                            </div>

                            @if ($transaction->source_type === 'App\Models\Payment')
                                <a href="{{ route('payments.show', $transaction->source_id) }}"
                                    class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    To'lovni ko'rish
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('transactions.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Orqaga
            </a>
        </div>
    </div>
@endsection