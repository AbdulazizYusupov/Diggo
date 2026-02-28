@extends('Layout.app')

@section('title', 'To\'lov #' . $payment->id)

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    To'lov tafsiloti
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    #{{ $payment->id }} raqamli to'lov ma'lumotlari
                </p>
            </div>
            <a href="{{ route('student.payments') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Orqaga
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Asosiy ma'lumotlar --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Status Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">To'lov ma'lumotlari</h3>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-bold
                            {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                            {{ $payment->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                            {{ $payment->status === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                            {{ $payment->status === 'cancelled' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' : '' }}">
                            @if($payment->status === 'paid') ✓ To'langan
                            @elseif($payment->status === 'pending') ⏳ Kutilmoqda
                            @elseif($payment->status === 'failed') ✗ Muvaffaqiyatsiz
                            @elseif($payment->status === 'cancelled') Bekor qilingan
                            @endif
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">To'lov ID</p>
                            <p class="text-base font-mono font-bold text-gray-900 dark:text-white">#{{ $payment->id }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">To'lov turi</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold
                                {{ $payment->payment_type === 'cash' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                {{ $payment->payment_type === 'card' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                {{ $payment->payment_type === 'transfer' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : '' }}">
                                @if($payment->payment_type === 'cash') 💵 Naqd
                                @elseif($payment->payment_type === 'card') 💳 Karta
                                @elseif($payment->payment_type === 'transfer') 🔄 O'tkazma
                                @else {{ ucfirst($payment->payment_type) }}
                                @endif
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">To'lov oyi</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $payment->payment_month ? \Carbon\Carbon::parse($payment->payment_month)->format('F Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">To'langan sana</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d.m.Y H:i') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Yaratilgan</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $payment->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                        @if($payment->note)
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Izoh</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                {{ $payment->note }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Guruh / Kurs --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Guruh & Kurs</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Guruh</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $payment->group->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Kurs</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $payment->group->course->title ?? $payment->group->cours->title ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summa breakdown --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Summa</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Asosiy summa</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($payment->amount, 0, '.', ' ') }} so'm
                            </span>
                        </div>

                        @if($payment->discount > 0)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Chegirma</span>
                            <span class="text-sm font-bold text-red-500">
                                -{{ number_format($payment->discount, 0, '.', ' ') }} so'm
                            </span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between py-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl px-3 mt-2">
                            <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">Jami to'landi</span>
                            <span class="text-lg font-extrabold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($payment->net_amount, 0, '.', ' ') }} so'm
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Qabul qilgan xodim --}}
                @if($payment->user)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase mb-4">Qabul qildi</h3>
                    <div class="flex items-center gap-3">
                        <img class="h-10 w-10 rounded-xl object-cover ring-2 ring-indigo-100 dark:ring-indigo-900"
                            src="https://ui-avatars.com/api/?name={{ urlencode($payment->user->full_name) }}&background=6366f1&color=fff"
                            alt="{{ $payment->user->full_name }}">
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $payment->user->full_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->user->phone ?? '' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection