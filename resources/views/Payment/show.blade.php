@extends('Layout.app')
@section('title', 'To\'lov tafsilotlari')
@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('payments.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">To'lov tafsilotlari</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">To'lov #{{ $payment->id }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                @if($payment->status !== 'completed')
                    <a href="{{ route('payments.edit', $payment->id) }}" 
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Tahrirlash
                    </a>
                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Haqiqatan ham bu to\'lovni o\'chirmoqchimisiz?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-xl transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            O'chirish
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mt-6">
            @if($payment->status === 'paid')
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-semibold rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    To'landi
                </span>
            @elseif($payment->status === 'pending')
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-semibold rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Kutilmoqda
                </span>
            @elseif($payment->status === 'cancelled')
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 font-semibold rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Bekor qilingan
                </span>
            @endif
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Payment Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Student Information -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        O'quvchi ma'lumotlari
                    </h2>

                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold text-lg">
                                    {{ substr($payment->student->user->name, 0, 1) }}{{ substr($payment->student->user->surname, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 dark:text-white">{{ $payment->student->user->full_name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payment->student->user->phone }}</p>
                            </div>
                            <a href="{{ route('students.show', $payment->student->id) }}" 
                                class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm font-semibold">
                                Ko'rish →
                            </a>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl">
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Joriy balans</p>
                                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                    {{ number_format($payment->student->balance, 0, '.', ' ') }} so'm
                                </p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Ro'yxatdan o'tgan sana</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                    {{ $payment->student->enrollment_date->format('d.m.Y') }}
                                </p>
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

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Asosiy summa</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($payment->amount, 0, '.', ' ') }} so'm
                            </p>
                        </div>

                        <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Chegirma</p>
                            <p class="text-lg font-bold text-rose-500 mt-1">
                                {{ $payment->discount }}% ({{ number_format($payment->amount - $payment->net_amount, 0, '.', ' ') }} so'm)
                            </p>
                        </div>

                        <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl col-span-2">
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Jami to'lov</p>
                            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                                {{ number_format($payment->net_amount, 0, '.', ' ') }} so'm
                            </p>
                        </div>

                        <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">To'lov turi</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                @switch($payment->payment_type)
                                    @case('cash') Naqd @break
                                    @case('card') Karta @break
                                    @case('transfer') O'tkazma @break
                                    @default Boshqa
                                @endswitch
                            </p>
                        </div>

                        <div class="p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">To'lov oyi</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                                {{ $payment->payment_month->format('F Y') }}
                            </p>
                        </div>

                        @if($payment->is_installment)
                            <div class="p-4 bg-blue-50 dark:bg-blue-500/10 rounded-xl col-span-2">
                                <p class="text-xs text-gray-600 dark:text-gray-400 font-medium">Bo'lib to'lash</p>
                                <p class="text-sm font-bold text-blue-600 dark:text-blue-400 mt-1">
                                    {{ $payment->installment_number }} / {{ $payment->total_installments }} to'lov
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($payment->notes)
                        <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl">
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium mb-2">Izoh</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $payment->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Director Commission -->
                @if($payment->director_commission > 0)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Direktor komissiyasi
                        </h2>

                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 bg-purple-50 dark:bg-purple-500/10 rounded-xl">
                                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $payment->director->user->full_name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payment->director->commission_percentage }}% komissiya</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-black text-purple-600 dark:text-purple-400">
                                        {{ number_format($payment->director_commission, 0, '.', ' ') }} so'm
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Transaction Info -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Qo'shimcha ma'lumotlar
                    </h2>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-gray-600 dark:text-gray-400">To'lov sanasi</span>
                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ $payment->paid_at ? $payment->paid_at->format('d.m.Y H:i') : '-' }}
                            </span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-gray-600 dark:text-gray-400">Qabul qildi</span>
                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ $payment->recordedBy->full_name }}
                            </span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-gray-600 dark:text-gray-400">Yaratilgan</span>
                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ $payment->created_at->format('d.m.Y H:i') }}
                            </span>
                        </div>

                        @if($payment->updated_at != $payment->created_at)
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600 dark:text-gray-400">Yangilangan</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    {{ $payment->updated_at->format('d.m.Y H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Transaction -->
                @if($payment->transaction)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Tranzaksiya
                        </h2>

                        <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-600 dark:text-gray-400">Tranzaksiya #{{ $payment->transaction->id }}</span>
                                <span class="text-xs px-2 py-1 bg-emerald-200 dark:bg-emerald-700 text-emerald-800 dark:text-emerald-200 font-semibold rounded">
                                    {{ $payment->transaction->type }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-900 dark:text-white font-semibold">{{ $payment->transaction->description }}</p>
                            <div class="mt-2 pt-2 border-t border-emerald-200 dark:border-emerald-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-600 dark:text-gray-400">Balans:</span>
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($payment->transaction->balance_after, 0, '.', ' ') }} so'm
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection