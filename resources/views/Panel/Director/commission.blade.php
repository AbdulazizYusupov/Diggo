@extends('Layout.app')

@section('title', 'Komissiyalar')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-emerald-600 rounded-2xl shadow-lg shadow-emerald-500/30 border-b-2 border-emerald-800/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Komissiyalar
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        To'lovlar va komissiyalar tarixi
                    </p>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            To'langan
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($stats['total_paid'], 0, '.', ' ') }}
                            <span class="text-lg">so'm</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-emerald-600 rounded-xl shadow-lg shadow-emerald-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">
                            Kutilmoqda
                        </p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ number_format($stats['total_pending'], 0, '.', ' ') }}
                            <span class="text-lg">so'm</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-amber-500 rounded-xl shadow-lg shadow-amber-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30 rounded-2xl p-6 border border-red-200 dark:border-red-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-red-700 dark:text-red-400 uppercase tracking-wider">
                            Bekor qilingan
                        </p>
                        <p class="text-3xl font-extrabold text-red-900 dark:text-red-50 mt-1">
                            {{ number_format($stats['total_cancelled'], 0, '.', ' ') }}
                            <span class="text-lg">so'm</span>
                        </p>
                    </div>
                    <div
                        class="p-3 bg-red-600 rounded-xl shadow-lg shadow-red-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <form action="{{ route('director.commissions') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <select name="status"
                    class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    <option value="">Barcha holat</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>To'langan</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Bekor qilingan
                    </option>
                </select>

                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">

                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-200 dark:shadow-none flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrlash
                    </button>

                    @if (request()->hasAny(['status', 'from_date', 'to_date']))
                        <a href="{{ route('director.commissions') }}"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Commissions Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                #</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                O'quvchi</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Kurs</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                To'lov</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Komissiya</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Holat</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">
                                Sana</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($commissions as $commission)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="font-mono text-sm text-gray-500 dark:text-gray-400">
                                        #{{ $commission->id }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                            {{ substr($commission->payment->student->user->name, 0, 1) }}{{ substr($commission->payment->student->user->surname ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ $commission->payment->student->user->name }}
                                                {{ $commission->payment->student->user->surname }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $commission->payment->student->user->phone }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-semibold rounded-lg">
                                        {{ $commission->payment->cours->name }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ number_format($commission->payment->amount, 0, '.', ' ') }} so'm
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($commission->amount, 0, '.', ' ') }} so'm
                                    </p>
                                </td>
                                <td class="py-4 px-6">
                                    @if ($commission->status === 'paid')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-lg">
                                            <span class="relative flex h-2 w-2">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            To'langan
                                        </span>
                                    @elseif($commission->status === 'pending')
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-bold rounded-lg">
                                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Kutilmoqda
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-bold rounded-lg">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Bekor qilingan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm">
                                        <p class="text-gray-900 dark:text-white font-medium">
                                            {{ $commission->created_at->format('d.m.Y') }}
                                        </p>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            {{ $commission->created_at->format('H:i') }}
                                        </p>
                                        @if ($commission->paid_at)
                                            <p class="text-emerald-600 dark:text-emerald-400 text-xs mt-1">
                                                To'langan: {{ $commission->paid_at->format('d.m.Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <div class="p-3 bg-gray-100 dark:bg-gray-800 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Komissiyalar topilmadi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($commissions->hasPages())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                {{ $commissions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection