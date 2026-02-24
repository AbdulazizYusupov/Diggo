@extends('Layout.app')

@section('title', 'Do\'kon Buyurtmalari')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-sky-50 dark:bg-sky-500/10 border border-sky-100 dark:border-sky-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Buyurtmalar</h1>
                        @if ($stats['pending'] > 0)
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20 animate-pulse">
                                {{ $stats['pending'] }} ta yangi
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Barcha do'kon buyurtmalarini boshqarish</p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-amber-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Kutilmoqda</p>
                <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ $stats['pending'] }}</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-blue-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Tasdiqlangan</p>
                <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ $stats['approved'] }}</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-emerald-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Yetkazilgan</p>
                <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['delivered'] }}</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-indigo-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Jami coin</p>
                <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1">
                    {{ number_format($stats['total_coins']) }}</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-indigo-500/20 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.shop.orders.index') }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <div class="relative lg:col-span-2">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buyurtma №, student ismi..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm">
                    </div>
                    <select name="status"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none text-sm cursor-pointer">
                        <option value="">Barcha holat</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Tasdiqlangan
                        </option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Yetkazilgan
                        </option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Bekor qilindi
                        </option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rad etildi
                        </option>
                    </select>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none text-sm">
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none text-sm">
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-xl transition-all active:scale-95 text-sm">Filter</button>
                        <a href="{{ route('admin.shop.orders.index') }}"
                            class="px-3 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 text-gray-500 rounded-xl hover:bg-gray-50 transition-all flex items-center active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Buyurtma</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Student</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Mahsulot</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Miqdor</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Coin</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Holat</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Sana</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900 dark:text-white font-mono text-sm">
                                        {{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-bold text-sm flex-shrink-0">
                                            {{ substr($order->student->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $order->student->user->name }} {{ $order->student->user->surname }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->student->user->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $order->product->image_url }}"
                                            class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        <p
                                            class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[150px]">
                                            {{ $order->product->name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $order->quantity }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ number_format($order->total_coins) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $order->status_badge['class'] }}">
                                        {{ $order->status_badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.shop.orders.show', $order) }}"
                                            class="p-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all"
                                            title="Ko'rish">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if ($order->status === 'pending')
                                            <form action="{{ route('admin.shop.orders.approve', $order) }}"
                                                method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="p-2 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl transition-all"
                                                    title="Tasdiqlash">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($order->status === 'approved')
                                            <form action="{{ route('admin.shop.orders.deliver', $order) }}"
                                                method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition-all"
                                                    title="Yetkazildi">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if (in_array($order->status, ['pending', 'approved']))
                                            <button
                                                onclick="document.getElementById('reject-{{ $order->id }}').classList.remove('hidden')"
                                                class="p-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all"
                                                title="Rad etish">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Reject Modal --}}
                                        <div id="reject-{{ $order->id }}"
                                            class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                                            <div
                                                class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-white/5 p-8">
                                                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-4">Rad
                                                    etish sababi</h3>
                                                <form action="{{ route('admin.shop.orders.reject', $order) }}"
                                                    method="POST" class="space-y-4">
                                                    @csrf @method('PATCH')
                                                    <textarea name="admin_note" required rows="3" placeholder="Nima sababdan rad etyapsiz..."
                                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-rose-500/20 resize-none text-sm"></textarea>
                                                    <p
                                                        class="text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded-xl px-3 py-2">
                                                        ⚠️ Coinlar studentga qaytariladi
                                                    </p>
                                                    <div class="grid grid-cols-2 gap-3">
                                                        <button type="button"
                                                            onclick="document.getElementById('reject-{{ $order->id }}').classList.add('hidden')"
                                                            class="py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">Bekor</button>
                                                        <button type="submit"
                                                            class="py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl transition-all active:scale-95">Rad
                                                            etish</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div
                                        class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-gray-900 dark:text-white">Buyurtmalar topilmadi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        @if ($orders->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-4 mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
