@extends('Layout.app')

@section('title', 'Coin Tranzaksiyalar')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Coin Tranzaksiyalar
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Barcha coin berilishi va yechimlari tarixi
                </p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-5 border border-emerald-200 dark:border-emerald-700/50">
                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase">Berilgan</p>
                <p class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-300 mt-1">
                    +{{ number_format($stats['total_given']) }}
                    <span class="text-sm font-semibold">🪙</span>
                </p>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-5 border border-red-200 dark:border-red-700/50">
                <p class="text-xs font-bold text-red-600 dark:text-red-400 uppercase">Yechilgan</p>
                <p class="text-2xl font-extrabold text-red-700 dark:text-red-300 mt-1">
                    {{ number_format($stats['total_taken']) }}
                    <span class="text-sm font-semibold">🪙</span>
                </p>
            </div>
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-5 border border-blue-200 dark:border-blue-700/50">
                <p class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase">Jami tranzaksiya</p>
                <p class="text-2xl font-extrabold text-blue-700 dark:text-blue-300 mt-1">
                    {{ number_format($stats['total_count']) }}
                </p>
            </div>
            <div
                class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-5 border border-purple-200 dark:border-purple-700/50">
                <p class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase">O'quvchilar</p>
                <p class="text-2xl font-extrabold text-purple-700 dark:text-purple-300 mt-1">
                    {{ number_format($stats['unique_students']) }}
                </p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Qidiruv</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="O'quvchi ismi..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Turi</label>
                    <select name="type"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Barchasi</option>
                        @foreach ($types as $key => $label)
                            <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Dan</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Gacha</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('coin-transactions.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg transition-colors">
                        ✕
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">#
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">
                                O'quvchi</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">
                                Coin</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">
                                Balans</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">
                                Turi</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">
                                Tavsif</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Kim
                                berdi</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">
                                Sana</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                <td class="px-5 py-3.5 text-sm font-mono text-gray-400">#{{ $tx->id }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <img class="h-8 w-8 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-gray-600"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($tx->student->user->full_name ?? 'N A') }}&background=6366f1&color=fff&size=64"
                                            alt="">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $tx->student->user->full_name ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $tx->student->user->phone ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="text-base font-extrabold {{ $tx->coins > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                                        {{ $tx->coins > 0 ? '+' : '' }}{{ $tx->coins }} 🪙
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                        {{ $tx->balance_after }} 🪙
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $typeColors = [
                                            'participation' =>
                                                'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'homework' =>
                                                'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                            'exam' =>
                                                'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                            'competition' =>
                                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'active_involvement' =>
                                                'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                                            'penalty' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                            'bonus' =>
                                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'purchase' =>
                                                'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                            'refund' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                            'other' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ $typeColors[$tx->type] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $types[$tx->type] ?? $tx->type }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 max-w-[200px]">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate"
                                        title="{{ $tx->description }}">
                                        {{ $tx->description ?? '—' }}
                                    </p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $tx->awardedBy->full_name ?? 'Tizim' }}
                                    </p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $tx->transaction_date->format('d.m.Y') }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $tx->created_at->format('H:i') }}
                                    </p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('coin-transactions.show', $tx->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-lg transition-colors">
                                        Ko'rish
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-14 text-center">
                                    <p class="text-gray-400 font-medium">Tranzaksiyalar topilmadi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        @if ($transactions->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-4 mt-6">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection
