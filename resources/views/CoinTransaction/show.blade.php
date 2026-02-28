@extends('Layout.app')

@section('title', 'Tranzaksiya #' . $transaction->id)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                Tranzaksiya #{{ $transaction->id }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">{{ $transaction->transaction_date->format('d.m.Y') }} — {{ $transaction->created_at->format('H:i') }}</p>
        </div>
        <a href="{{ route('coin-transactions.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors text-sm">
            ← Orqaga
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Asosiy ma'lumot --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5">Tranzaksiya ma'lumoti</h3>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Coin</p>
                        <p class="text-2xl font-extrabold {{ $transaction->coins > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $transaction->coins > 0 ? '+' : '' }}{{ $transaction->coins }} 🪙
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Balans (keyin)</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-white">
                            {{ $transaction->balance_after }} 🪙
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Turi</p>
                        <span class="inline-flex px-3 py-1 rounded-lg text-sm font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                            {{ $transaction->type }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Kim berdi</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ $transaction->awardedBy->full_name ?? 'Tizim' }}
                        </p>
                    </div>
                    @if($transaction->description)
                    <div class="col-span-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Tavsif</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                            {{ $transaction->description }}
                        </p>
                    </div>
                    @endif
                    @if($transaction->activity)
                    <div class="col-span-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Faoliyat</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $transaction->activity->name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Oxirgi tranzaksiyalar --}}
            @if($history->count())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Oxirgi tranzaksiyalar</h3>
                <div class="space-y-2">
                    @foreach($history as $h)
                    <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $h->description ?? $h->type }}</p>
                            <p class="text-xs text-gray-400">{{ $h->transaction_date->format('d.m.Y') }}</p>
                        </div>
                        <span class="font-bold text-sm {{ $h->coins > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $h->coins > 0 ? '+' : '' }}{{ $h->coins }} 🪙
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- O'quvchi kartasi --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 h-fit">
            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase mb-4">O'quvchi</h3>
            <div class="flex items-center gap-3 mb-4">
                <img class="h-12 w-12 rounded-xl object-cover ring-2 ring-indigo-100 dark:ring-indigo-900"
                    src="https://ui-avatars.com/api/?name={{ urlencode($transaction->student->user->full_name ?? 'N A') }}&background=6366f1&color=fff"
                    alt="">
                <div>
                    <p class="font-bold text-gray-900 dark:text-white">{{ $transaction->student->user->full_name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $transaction->student->user->phone ?? '' }}</p>
                </div>
            </div>
            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-4 text-center">
                <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase mb-1">Joriy balans</p>
                <p class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300">
                    {{ $transaction->student->coins ?? 0 }} 🪙
                </p>
            </div>
        </div>
    </div>
</div>
@endsection