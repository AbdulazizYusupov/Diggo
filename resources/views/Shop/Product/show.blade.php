@extends('Layout.app')

@section('title', $product->name . ' — Mahsulot')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.shop.products.index') }}"
                    class="p-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            {{ $product->name }}
                        </h1>
                        @if ($product->is_featured)
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20">
                                ⭐ Featured
                            </span>
                        @endif
                        <span
                            class="px-2.5 py-1 rounded-full text-xs font-bold
                        {{ $product->is_active
                            ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700' }}">
                            {{ $product->is_active ? 'Faol' : 'Nofaol' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">{{ $product->sku }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <form action="{{ route('admin.shop.products.toggle-status', $product) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="px-4 py-2.5 font-bold rounded-xl border transition-all active:scale-95 text-sm
                    {{ $product->is_active
                        ? 'bg-white dark:bg-slate-800 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50'
                        : 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-400' }}">
                        {{ $product->is_active ? 'Nofaol qilish' : 'Faollashtirish' }}
                    </button>
                </form>
                <a href="{{ route('admin.shop.products.edit', $product) }}"
                    class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all active:scale-95 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Tahrirlash
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-amber-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Narxi</p>
                <p class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1">🪙
                    {{ number_format($product->coin_price) }}</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-indigo-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Zaxira</p>
                <p
                    class="text-2xl font-black mt-1 {{ $product->stock_quantity === 0 && !$product->is_unlimited ? 'text-rose-600 dark:text-rose-400' : 'text-indigo-600 dark:text-indigo-400' }}">
                    {{ $product->is_unlimited ? '∞' : number_format($product->stock_quantity) }}
                </p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-emerald-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Jami sotilgan
                </p>
                <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                    {{ number_format($product->total_sold) }}</p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-sky-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Jami buyurtma</p>
                <p class="text-3xl font-black text-sky-600 dark:text-sky-400 mt-1">
                    {{ number_format($orderStats['total']) }}</p>
            </div>
        </div>

        {{-- Order mini stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach ([['label' => 'Kutilmoqda', 'value' => $orderStats['pending'], 'class' => 'text-amber-600 dark:text-amber-400', 'bg' => 'bg-amber-50 dark:bg-amber-500/10 border-amber-100 dark:border-amber-500/20'], ['label' => 'Yetkazilgan', 'value' => $orderStats['delivered'], 'class' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20'], ['label' => 'Jami coin', 'value' => number_format($orderStats['total_coins']) . ' 🪙', 'class' => 'text-indigo-600 dark:text-indigo-400', 'bg' => 'bg-indigo-50 dark:bg-indigo-500/10 border-indigo-100 dark:border-indigo-500/20'], ['label' => '1 studentga max', 'value' => $product->max_per_student . ' ta', 'class' => 'text-gray-700 dark:text-gray-300', 'bg' => 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700']] as $stat)
                <div class="rounded-2xl border p-4 {{ $stat['bg'] }}">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</p>
                    <p class="text-xl font-black mt-1 {{ $stat['class'] }}">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Image + Details --}}
            <div class="space-y-5">
                {{-- Main Image --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                    @if ($product->images && count($product->images))
                        <div class="p-3 flex gap-2 overflow-x-auto">
                            @foreach ($product->images as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="h-16 w-16 rounded-xl object-cover flex-shrink-0 ring-2 ring-gray-100 dark:ring-gray-700 cursor-pointer hover:ring-indigo-400 transition-all">
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white">Mahsulot ma'lumotlari</h3>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Kategoriya</span>
                            <a href="{{ route('admin.shop.categories.show', $product->category) }}"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $product->category->color_classes['bg'] }} {{ $product->category->color_classes['text'] }} hover:opacity-80 transition-opacity">
                                {{ $product->category->icon ?? '' }} {{ $product->category->name }}
                            </a>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">SKU</span>
                            <span class="text-sm font-mono text-indigo-600 dark:text-indigo-400">{{ $product->sku }}</span>
                        </div>
                        @if ($product->brand)
                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Brend</span>
                                <span
                                    class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->brand }}</span>
                            </div>
                        @endif
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Zaxira turi</span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $product->stock_badge['class'] }}">
                                {{ $product->stock_badge['label'] }}
                            </span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Tartib</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->sort_order }}</span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Yaratilgan</span>
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300">{{ $product->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">O'zgartirilgan</span>
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300">{{ $product->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if ($product->description || $product->short_description)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 space-y-3">
                        <h3 class="font-bold text-gray-900 dark:text-white">Tavsif</h3>
                        @if ($product->short_description)
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $product->short_description }}</p>
                        @endif
                        @if ($product->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $product->description }}
                            </p>
                        @endif
                    </div>
                @endif

                {{-- Attributes --}}
                @if ($product->attributes && count($product->attributes))
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-gray-900 dark:text-white">Xususiyatlar</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($product->attributes as $key => $value)
                                <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                    <span
                                        class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $key }}</span>
                                    <span
                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right: Orders --}}
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white">So'nggi buyurtmalar</h3>
                    </div>

                    @forelse($orders as $order)
                        <div
                            class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center gap-4">
                                {{-- Student avatar --}}
                                <div
                                    class="w-11 h-11 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-bold text-sm flex-shrink-0">
                                    {{ substr($order->student->user->name, 0, 1) }}{{ substr($order->student->user->surname ?? '', 0, 1) }}
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-bold text-gray-900 dark:text-white text-sm">
                                            {{ $order->student->user->name }} {{ $order->student->user->surname }}
                                        </p>
                                        <span class="text-xs font-mono text-gray-400">{{ $order->order_number }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-0.5 flex-wrap">
                                        <span class="text-xs text-amber-600 dark:text-amber-400 font-bold">
                                            🪙 {{ number_format($order->total_coins) }}
                                        </span>
                                        <span class="text-xs text-gray-400">× {{ $order->quantity }} dona</span>
                                        <span
                                            class="text-xs text-gray-400">{{ $order->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>

                                {{-- Status + Link --}}
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $order->status_badge['class'] }}">
                                        {{ $order->status_badge['label'] }}
                                    </span>
                                    <a href="{{ route('admin.shop.orders.show', $order) }}"
                                        class="p-1.5 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center">
                            <div
                                class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white">Buyurtmalar yo'q</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bu mahsulotga hali buyurtma berilmagan
                            </p>
                        </div>
                    @endforelse

                </div>
                @if ($orders->hasPages())
                    <div
                        class="p-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
