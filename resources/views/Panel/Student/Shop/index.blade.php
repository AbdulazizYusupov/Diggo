@extends('Layout.app')

@section('title', 'Do\'kon')

@section('content')
<div class="space-y-6">

    {{-- ═══════════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex p-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl shadow-sm">
                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Do'kon</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20 shadow-sm">
                        {{ $products->total() }} ta mahsulot
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Coinlaringiz: <strong class="text-amber-600 dark:text-amber-400">{{ number_format(auth()->user()->student->coins ?? 0, 0, '.', ' ') }} 🪙</strong>
                </p>
            </div>
        </div>

        {{-- My Orders --}}
        <a href="{{ route('student.shop.orders') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all duration-200 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Buyurtmalarim
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════
         TABS: Sotuvda / Sotib olganlarim
    ═══════════════════════════════════════════════ --}}
    <div class="flex gap-0 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('student.shop.index') }}"
           class="px-6 py-3 text-sm font-semibold border-b-2 transition-colors
                  {{ !request('tab') || request('tab') === 'available'
                     ? 'border-amber-500 text-amber-600 dark:text-amber-400'
                     : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
            Sotuvda
        </a>
        <a href="{{ route('student.shop.orders') }}"
           class="px-6 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
            Sotib olganlarim
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════
         FILTERS
    ═══════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-amber-500/20 p-4 lg:p-5">
        <form method="GET" action="{{ route('student.shop.index') }}" id="filterForm">
            <div class="flex flex-wrap gap-3 items-end">

                {{-- Kategoriya --}}
                <div class="min-w-[140px]">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Kategoriya</label>
                    <div class="relative">
                        <select name="category" onchange="this.form.submit()"
                            class="w-full pl-3 pr-8 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all cursor-pointer appearance-none">
                            <option value="">Barchasi</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Aksessuar qiymati (dan – gacha) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Aksessuar qiymati</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="price_from" value="{{ request('price_from') }}" placeholder="0"
                            class="w-20 px-3 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                        <span class="text-sm text-gray-400 font-medium">dan</span>
                        <input type="number" name="price_to" value="{{ request('price_to') }}" placeholder="∞"
                            class="w-20 px-3 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                        <span class="text-sm text-gray-400 font-medium">gacha</span>
                    </div>
                </div>

                {{-- Aksessuar nomi --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Aksessuar nomi</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Qidirish..."
                            class="w-full pl-4 pr-10 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                        <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-amber-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Coinlarim yetadi toggle --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Kumushlarim yetadi</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="affordable" value="1" {{ request('affordable') ? 'checked' : '' }}
                            class="sr-only peer" onchange="this.form.submit()">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>

                {{-- Reset --}}
                @if(request()->hasAny(['category', 'search', 'price_from', 'price_to', 'affordable']))
                <div class="self-end">
                    <a href="{{ route('student.shop.index') }}"
                       class="flex items-center justify-center w-10 h-10 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-400 hover:text-rose-500 rounded-xl transition-all"
                       title="Tozalash">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                    </a>
                </div>
                @endif

                {{-- Spacer --}}
                <div class="flex-1"></div>

                {{-- Per page + pagination info --}}
                <div class="flex items-center gap-3 self-end">
                    <select name="per_page" onchange="this.form.submit()"
                        class="px-3 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/20 transition-all cursor-pointer">
                        <option value="8" {{ request('per_page', 8) == 8 ? 'selected' : '' }}>8</option>
                        <option value="16" {{ request('per_page') == 16 ? 'selected' : '' }}>16</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                    </select>
                    <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
                    </span>
                    <div class="flex gap-1">
                        @if($products->onFirstPage())
                            <span class="p-1.5 text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                               class="p-1.5 text-gray-500 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @endif
                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}"
                               class="p-1.5 text-gray-500 hover:text-amber-600 dark:text-gray-400 dark:hover:text-amber-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <span class="p-1.5 text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ═══════════════════════════════════════════════
         PRODUCTS GRID
    ═══════════════════════════════════════════════ --}}
    @forelse($products as $product)
        @if($loop->first)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6">
        @endif

        @php
            $studentCoins = auth()->user()->student->coins ?? 0;
            $canAfford    = $studentCoins >= $product->coin_price;
            $canBuy       = $product->canStudentBuy(auth()->user()->student->id ?? 0);
            $stock        = $product->stock_badge;
        @endphp

        <div class="group bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col">

            {{-- Image --}}
            <a href="{{ route('student.shop.show', $product->slug) }}" class="block relative overflow-hidden bg-gray-100 dark:bg-slate-800 aspect-square">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                {{-- Featured badge --}}
                @if($product->is_featured)
                <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-500 text-white shadow-sm">
                    ⭐ Top
                </span>
                @endif

                {{-- Stock badge --}}
                <span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $stock['class'] }} shadow-sm">
                    {{ $stock['label'] }}
                </span>
            </a>

            {{-- Body --}}
            <div class="p-4 flex flex-col flex-1 gap-2">
                <div>
                    @if($product->category)
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">{{ $product->category->name }}</span>
                    @endif
                    <h3 class="mt-0.5 text-sm font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors leading-snug">
                        <a href="{{ route('student.shop.show', $product->slug) }}">{{ $product->name }}</a>
                    </h3>
                    @if($product->short_description)
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ $product->short_description }}</p>
                    @endif
                </div>

                <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-1">
                            <span class="text-lg font-black text-amber-600 dark:text-amber-400">{{ number_format($product->coin_price, 0, '.', ' ') }}</span>
                            <span class="text-sm">🪙</span>
                        </div>
                        @if(!$canAfford)
                        <p class="text-[10px] text-rose-500 font-medium">Coiningiz yetarli emas</p>
                        @endif
                    </div>

                    @if($canBuy && $canAfford)
                    <a href="{{ route('student.shop.show', $product->slug) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl transition-all active:scale-95 shadow-sm shadow-amber-200 dark:shadow-none whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Xarid
                    </a>
                    @elseif(!$canBuy && $product->is_in_stock)
                    <span class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs font-bold rounded-xl cursor-not-allowed">
                        Limit
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs font-bold rounded-xl cursor-not-allowed">
                        Tugagan
                    </span>
                    @endif
                </div>
            </div>
        </div>

        @if($loop->last)
        </div>
        @endif
    @empty
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h3 class="mt-4 text-base font-bold text-gray-900 dark:text-white">Mahsulotlar topilmadi</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Filter shartlarini o'zgartirib ko'ring</p>
            <a href="{{ route('student.shop.index') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-semibold rounded-xl hover:bg-amber-600 transition-all">
                Barchasini ko'rish
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="flex justify-center">
        {{ $products->appends(request()->except('page'))->links() }}
    </div>
    @endif

</div>
@endsection