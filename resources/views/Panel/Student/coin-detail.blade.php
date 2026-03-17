@extends('Layout.app')

@section('title', 'Coin tafsiloti')

@section('content')
<div class="space-y-5">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('student.coins') }}"
            class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Coinlar
        </a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 dark:text-white font-medium">Tranzaksiya #{{ $transaction->id }}</span>
    </nav>

    {{-- Main Card --}}
    @php
        $isEarned = $transaction->coins > 0;
        $absCoins = abs($transaction->coins);

        $typeMap = [
            'participation'      => ['label' => 'Ishtirok',       'icon' => '🏫', 'color' => 'indigo'],
            'homework'           => ['label' => 'Uy ishi',         'icon' => '📝', 'color' => 'blue'],
            'exam'               => ['label' => 'Imtihon',         'icon' => '📊', 'color' => 'purple'],
            'competition'        => ['label' => 'Musobaqa',        'icon' => '🏆', 'color' => 'amber'],
            'active_involvement' => ['label' => 'Faol ishtirok',   'icon' => '⚡', 'color' => 'yellow'],
            'penalty'            => ['label' => 'Jarima',          'icon' => '⚠️',  'color' => 'red'],
            'bonus'              => ['label' => 'Bonus',           'icon' => '🎁', 'color' => 'green'],
            'purchase'           => ['label' => 'Xarid',          'icon' => '🛒', 'color' => 'pink'],
            'other'              => ['label' => 'Boshqa',          'icon' => '📌', 'color' => 'gray'],
        ];
        $typeInfo = $typeMap[$transaction->type] ?? ['label' => ucfirst($transaction->type ?? 'N/A'), 'icon' => '💰', 'color' => 'gray'];
    @endphp

    {{-- Hero amount card --}}
    <div class="rounded-2xl overflow-hidden shadow-sm border
        {{ $isEarned
            ? 'border-green-200 dark:border-green-800/30 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/10'
            : 'border-red-200 dark:border-red-800/30 bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/10' }}">

        <div class="px-6 py-8 text-center">
            <div class="text-5xl mb-3">{{ $typeInfo['icon'] }}</div>

            <div class="flex items-center justify-center gap-2 mb-2">
                <span class="text-4xl font-extrabold {{ $isEarned ? 'text-green-600 dark:text-green-400' : 'text-red-500' }}">
                    {{ $isEarned ? '+' : '-' }}{{ $absCoins }}
                </span>
                <div class="flex flex-col items-start">
                    <span class="text-sm font-bold text-gray-500 dark:text-gray-400">coin</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $isEarned ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400' }}">
                        {{ $isEarned ? 'Yig\'ildi' : 'Sarflandi' }}
                    </span>
                </div>
            </div>

            <p class="text-base font-semibold text-gray-700 dark:text-gray-300">
                {{ $typeInfo['label'] }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d MMMM Y', 'uz') }}
                {{ \Carbon\Carbon::parse($transaction->transaction_date)->translatedFormat('d F Y') }}
            </p>
        </div>
    </div>

    {{-- Details Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h2 class="font-bold text-gray-900 dark:text-white">Tafsilotlar</h2>
        </div>

        <dl class="divide-y divide-gray-100 dark:divide-gray-700">
            <div class="flex items-center px-5 py-3.5">
                <dt class="text-sm text-gray-500 dark:text-gray-400 w-40 flex-shrink-0">Tranzaksiya ID</dt>
                <dd class="text-sm font-semibold text-gray-900 dark:text-white">#{{ $transaction->id }}</dd>
            </div>

            <div class="flex items-center px-5 py-3.5">
                <dt class="text-sm text-gray-500 dark:text-gray-400 w-40 flex-shrink-0">Turi</dt>
                <dd class="flex items-center gap-2">
                    <span>{{ $typeInfo['icon'] }}</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $typeInfo['label'] }}</span>
                </dd>
            </div>

            <div class="flex items-center px-5 py-3.5">
                <dt class="text-sm text-gray-500 dark:text-gray-400 w-40 flex-shrink-0">Miqdor</dt>
                <dd class="text-sm font-bold {{ $isEarned ? 'text-green-600 dark:text-green-400' : 'text-red-500' }}">
                    {{ $isEarned ? '+' : '-' }}{{ $absCoins }} coin
                </dd>
            </div>

            <div class="flex items-center px-5 py-3.5">
                <dt class="text-sm text-gray-500 dark:text-gray-400 w-40 flex-shrink-0">Sana</dt>
                <dd class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->translatedFormat('d F Y, l') }}
                </dd>
            </div>

            @if($transaction->description)
            <div class="flex items-start px-5 py-3.5">
                <dt class="text-sm text-gray-500 dark:text-gray-400 w-40 flex-shrink-0 pt-0.5">Izoh</dt>
                <dd class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                    {{ $transaction->description }}
                </dd>
            </div>
            @endif

            @if($transaction->activity)
            <div class="flex items-center px-5 py-3.5">
                <dt class="text-sm text-gray-500 dark:text-gray-400 w-40 flex-shrink-0">Faoliyat</dt>
                <dd class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $transaction->activity->name ?? 'N/A' }}
                </dd>
            </div>
            @endif

            @if($transaction->user)
            <div class="flex items-center px-5 py-3.5">
                <dt class="text-sm text-gray-500 dark:text-gray-400 w-40 flex-shrink-0">Qo'shgan</dt>
                <dd class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                        {{ strtoupper(mb_substr($transaction->user->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $transaction->user->name ?? '' }} {{ $transaction->user->surname ?? '' }}
                    </span>
                </dd>
            </div>
            @endif

            <div class="flex items-center px-5 py-3.5">
                <dt class="text-sm text-gray-500 dark:text-gray-400 w-40 flex-shrink-0">Yaratilgan</dt>
                <dd class="text-sm text-gray-600 dark:text-gray-400">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i') }}
                </dd>
            </div>
        </dl>
    </div>

    {{-- Current Balance Card --}}
    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-5 text-white shadow-sm flex items-center justify-between">
        <div>
            <p class="text-indigo-200 text-sm font-medium">Joriy balans</p>
            <p class="text-4xl font-extrabold mt-0.5">{{ number_format($student->coins, 0, '.', ' ') }}</p>
            <p class="text-indigo-200 text-sm mt-1">coin</p>
        </div>
        <div class="opacity-20">
            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm.75-11.25h-1.5v3.5H8v1.5h3.25V17h1.5v-3.25H16v-1.5h-3.25v-3.5z"/>
            </svg>
        </div>
    </div>

    {{-- Navigation between transactions --}}
    <div class="flex justify-between gap-3">
        <a href="{{ route('student.coins') }}"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-semibold shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Barcha coinlar
        </a>
    </div>

</div>
@endsection