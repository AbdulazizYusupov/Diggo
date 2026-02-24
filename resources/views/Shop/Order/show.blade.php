@extends('Layout.app')

@section('title', $order->order_number . ' — Buyurtma')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.shop.orders.index') }}"
               class="p-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white font-mono">
                        {{ $order->order_number }}
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $order->status_badge['class'] }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $order->status_badge['label'] }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $order->created_at->format('d.m.Y H:i') }} • {{ $order->created_at->diffForHumans() }}
                </p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2 flex-wrap">
            @if($order->status === 'pending')
                <form action="{{ route('admin.shop.orders.approve', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all active:scale-95 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tasdiqlash
                    </button>
                </form>
            @endif

            @if($order->status === 'approved')
                <button onclick="document.getElementById('deliver-modal').classList.remove('hidden')"
                    class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-xl transition-all active:scale-95 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Yetkazildi deb belgilash
                </button>
            @endif

            @if(in_array($order->status, ['pending', 'approved']))
                <button onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                    class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 font-bold rounded-xl transition-all active:scale-95 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Rad etish
                </button>
            @endif
        </div>
    </div>

    {{-- Timeline --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
        <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-sm uppercase tracking-wider">Buyurtma holati</h3>
        <div class="flex items-center gap-0">
            @php
                $steps = [
                    ['key' => 'pending',   'label' => 'Yuborildi',    'icon' => '📤'],
                    ['key' => 'approved',  'label' => 'Tasdiqlandi',  'icon' => '✅'],
                    ['key' => 'delivered', 'label' => 'Yetkazildi',   'icon' => '📦'],
                ];
                $statusOrder = ['pending' => 0, 'approved' => 1, 'delivered' => 2, 'cancelled' => -1, 'rejected' => -1];
                $currentStep = $statusOrder[$order->status] ?? 0;
            @endphp

            @if(in_array($order->status, ['cancelled', 'rejected']))
                <div class="flex items-center gap-3 w-full">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-900/20 flex items-center justify-center text-2xl flex-shrink-0">
                        {{ $order->status === 'cancelled' ? '🚫' : '❌' }}
                    </div>
                    <div>
                        <p class="font-bold text-rose-600 dark:text-rose-400">
                            {{ $order->status === 'cancelled' ? 'Bekor qilindi' : 'Rad etildi' }}
                        </p>
                        @if($order->admin_note)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $order->admin_note }}</p>
                        @endif
                    </div>
                </div>
            @else
                @foreach($steps as $i => $step)
                    <div class="flex items-center {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl transition-all
                                {{ $i <= $currentStep
                                    ? 'bg-indigo-100 dark:bg-indigo-900/30 ring-2 ring-indigo-400'
                                    : 'bg-gray-100 dark:bg-gray-800 opacity-40' }}">
                                {{ $step['icon'] }}
                            </div>
                            <span class="text-xs font-semibold mt-1.5 whitespace-nowrap
                                {{ $i <= $currentStep ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-600' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                        @if($i < count($steps) - 1)
                            <div class="flex-1 h-0.5 mx-2 mb-5 rounded
                                {{ $i < $currentStep ? 'bg-indigo-400' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT col --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Product --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-gray-900 dark:text-white">Mahsulot</h3>
                </div>
                <div class="p-5 flex items-center gap-5">
                    <img src="{{ $order->product->image_url }}"
                         class="w-24 h-24 rounded-2xl object-cover ring-2 ring-gray-100 dark:ring-gray-700 flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="mb-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $order->product->category->color_classes['bg'] }} {{ $order->product->category->color_classes['text'] }}">
                                {{ $order->product->category->icon ?? '' }} {{ $order->product->category->name }}
                            </span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $order->product->name }}</h4>
                        @if($order->product->brand)
                            <p class="text-sm text-gray-400 dark:text-gray-500">{{ $order->product->brand }}</p>
                        @endif
                        <div class="mt-3 grid grid-cols-3 gap-3">
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-3 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Birlik narxi</p>
                                <p class="font-bold text-amber-600 dark:text-amber-400 mt-0.5">🪙 {{ number_format($order->coin_price) }}</p>
                            </div>
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-3 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Miqdor</p>
                                <p class="font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">{{ $order->quantity }} dona</p>
                            </div>
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-3 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Jami coin</p>
                                <p class="font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">🪙 {{ number_format($order->total_coins) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-5 pb-4">
                    <a href="{{ route('admin.shop.products.show', $order->product) }}"
                       class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        Mahsulot sahifasiga o'tish
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Student --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-gray-900 dark:text-white">Student</h3>
                </div>
                <div class="p-5 flex items-center gap-4">
                    @if($order->student->user->photo)
                        <img src="{{ asset('storage/' . $order->student->user->photo) }}"
                             class="w-16 h-16 rounded-2xl object-cover ring-2 ring-indigo-100 dark:ring-indigo-500/20 flex-shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                            <span class="text-xl font-bold text-indigo-700 dark:text-indigo-300">
                                {{ substr($order->student->user->name, 0, 1) }}{{ substr($order->student->user->surname ?? '', 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $order->student->user->name }} {{ $order->student->user->surname }}
                        </h4>
                        <div class="flex flex-wrap gap-3 mt-2">
                            <span class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $order->student->user->phone }}
                            </span>
                            @if($order->student->user->city)
                                <span class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $order->student->user->city }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($order->student_note)
                    <div class="mx-5 mb-5 p-4 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-500/20 rounded-xl">
                        <p class="text-xs font-bold text-blue-600 dark:text-blue-400 mb-1">Student izohi</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $order->student_note }}</p>
                    </div>
                @endif
            </div>

            {{-- Admin note --}}
            @if($order->admin_note)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Admin izohi</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $order->admin_note }}</p>
                </div>
            @endif

            {{-- Delivery info --}}
            @if($order->delivery_info)
                <div class="bg-emerald-50 dark:bg-emerald-900/10 rounded-2xl border border-emerald-100 dark:border-emerald-500/20 p-5">
                    <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-2">Yetkazish ma'lumoti</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $order->delivery_info }}</p>
                </div>
            @endif
        </div>

        {{-- RIGHT col --}}
        <div class="space-y-5">

            {{-- Order Summary --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-gray-900 dark:text-white">Buyurtma xulosasi</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div class="px-5 py-3.5 flex justify-between gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Buyurtma №</span>
                        <span class="text-sm font-mono font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                    </div>
                    <div class="px-5 py-3.5 flex justify-between gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Holat</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold {{ $order->status_badge['class'] }}">
                            {{ $order->status_badge['label'] }}
                        </span>
                    </div>
                    <div class="px-5 py-3.5 flex justify-between gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Miqdor</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->quantity }} dona</span>
                    </div>
                    <div class="px-5 py-3.5 flex justify-between gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Birlik narxi</span>
                        <span class="text-sm font-bold text-amber-600 dark:text-amber-400">🪙 {{ number_format($order->coin_price) }}</span>
                    </div>
                    <div class="px-5 py-4 flex justify-between gap-4 bg-indigo-50 dark:bg-indigo-900/20">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Jami to'langan</span>
                        <span class="text-base font-black text-indigo-600 dark:text-indigo-400">🪙 {{ number_format($order->total_coins) }}</span>
                    </div>
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-gray-900 dark:text-white">Vaqt ma'lumotlari</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div class="px-5 py-3.5 flex justify-between gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Yuborilgan</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if($order->processed_at)
                        <div class="px-5 py-3.5 flex justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Ishlov berilgan</span>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $order->processed_at->format('d.m.Y H:i') }}</span>
                        </div>
                    @endif
                    @if($order->delivered_at)
                        <div class="px-5 py-3.5 flex justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Yetkazilgan</span>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $order->delivered_at->format('d.m.Y H:i') }}</span>
                        </div>
                    @endif
                    @if($order->processedBy)
                        <div class="px-5 py-3.5 flex justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Mas'ul admin</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $order->processedBy->name }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 space-y-2">
                <a href="{{ route('admin.shop.products.show', $order->product) }}"
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors group">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Mahsulotni ko'rish</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('admin.shop.categories.show', $order->product->category) }}"
                   class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors group">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Kategoriyani ko'rish</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Deliver Modal --}}
@if($order->status === 'approved')
    <div id="deliver-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-white/5 p-8">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center mx-auto mb-5 text-3xl">
                📦
            </div>
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 text-center">Yetkazildi deb belgilash</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 text-center">
                <span class="font-bold text-slate-900 dark:text-white">{{ $order->order_number }}</span> buyurtmasi yetkazildimi?
            </p>
            <form action="{{ route('admin.shop.orders.deliver', $order) }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Yetkazish ma'lumoti</label>
                    <input type="text" name="delivery_info" placeholder="Qo'lga topshirildi, ofisda berildi..."
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Izoh (ixtiyoriy)</label>
                    <textarea name="admin_note" rows="2" placeholder="Qo'shimcha izoh..."
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20 resize-none text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <button type="button" onclick="document.getElementById('deliver-modal').classList.add('hidden')"
                        class="py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor
                    </button>
                    <button type="submit"
                        class="py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-2xl transition-all active:scale-95">
                        Tasdiqlash
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Reject Modal --}}
@if(in_array($order->status, ['pending', 'approved']))
    <div id="reject-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-white/5 p-8">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center mx-auto mb-5 text-3xl rotate-3">
                ❌
            </div>
            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 text-center">Rad etish</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-5 text-center">
                <span class="font-bold text-slate-900 dark:text-white">{{ $order->order_number }}</span> buyurtmasini rad etmoqchimisiz?
            </p>
            <form action="{{ route('admin.shop.orders.reject', $order) }}" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Sabab <span class="text-rose-500">*</span></label>
                    <textarea name="admin_note" required rows="3" placeholder="Nima sababdan rad etyapsiz..."
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 resize-none text-sm"></textarea>
                </div>
                <div class="flex items-center gap-2 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-500/20 rounded-xl">
                    <span class="text-amber-500 text-lg">⚠️</span>
                    <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">
                        Rad etilsa <span class="font-bold">🪙 {{ number_format($order->total_coins) }} coin</span> studentga qaytariladi.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                        class="py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor
                    </button>
                    <button type="submit"
                        class="py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl transition-all active:scale-95">
                        Rad etish
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection