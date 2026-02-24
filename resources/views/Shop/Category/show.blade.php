@extends('Layout.app')

@section('title', $category->name . ' — Kategoriya')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.shop.categories.index') }}"
                    class="p-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="flex items-center gap-4">
                    {{-- Category Icon --}}
                    <div
                        class="w-16 h-16 rounded-2xl {{ $category->color_classes['bg'] }} border {{ $category->color_classes['border'] }} flex items-center justify-center text-4xl shadow-sm flex-shrink-0">
                        {{ $category->icon ?? '📦' }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                                {{ $category->name }}
                            </h1>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $category->is_active
                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20'
                                : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700' }}">
                                {{ $category->is_active ? 'Faol' : 'Nofaol' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-mono">
                            /{{ $category->slug }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:flex-shrink-0">
                <form action="{{ route('admin.shop.categories.toggle-status', $category) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="px-4 py-2.5 font-bold rounded-xl border transition-all active:scale-95 text-sm
                        {{ $category->is_active
                            ? 'bg-white dark:bg-slate-800 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50'
                            : 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-400' }}">
                        {{ $category->is_active ? 'Nofaol qilish' : 'Faollashtirish' }}
                    </button>
                </form>
                <a href="{{ route('admin.shop.categories.edit', $category) }}"
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
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-indigo-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Jami mahsulot</p>
                <p class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $category->products_count }}</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-emerald-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Faol mahsulot
                </p>
                <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                    {{ $category->products->where('is_active', true)->count() }}
                </p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-amber-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Eng arzon</p>
                <p class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1">
                    {{ $category->products->min('coin_price') ? number_format($category->products->min('coin_price')) . ' 🪙' : '—' }}
                </p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-gray-200 dark:border-rose-500/20 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Eng qimmat</p>
                <p class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">
                    {{ $category->products->max('coin_price') ? number_format($category->products->max('coin_price')) . ' 🪙' : '—' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Info card --}}
            <div class="space-y-5">

                {{-- Category Image --}}
                @if ($category->image)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                            class="w-full h-56 object-cover">
                    </div>
                @endif

                {{-- Details --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white">Kategoriya ma'lumotlari</h3>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nomi</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $category->name }}</span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Slug</span>
                            <span
                                class="text-sm font-mono text-indigo-600 dark:text-indigo-400">{{ $category->slug }}</span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Icon</span>
                            <span class="text-2xl">{{ $category->icon ?? '—' }}</span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Rang</span>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $category->color_classes['bg'] }} {{ $category->color_classes['text'] }}">
                                <span class="w-2 h-2 rounded-full bg-current opacity-70"></span>
                                {{ ucfirst($category->color) }}
                            </span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Tartib</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $category->sort_order }}</span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Holat</span>
                            <span
                                class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full
                            {{ $category->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ $category->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                {{ $category->is_active ? 'Faol' : 'Nofaol' }}
                            </span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Yaratilgan</span>
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300">{{ $category->created_at->format('d.m.Y') }}</span>
                        </div>
                        <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">O'zgartirilgan</span>
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300">{{ $category->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if ($category->description)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Tavsif</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $category->description }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Right: Products list --}}
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-bold text-gray-900 dark:text-white">Mahsulotlar</h3>
                        <a href="{{ route('admin.shop.products.create') }}"
                            class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Mahsulot qo'shish
                        </a>
                    </div>

                    @forelse($products as $product)
                        <div
                            class="p-4 flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
                            {{-- Image --}}
                            <div class="flex-shrink-0">
                                <img src="{{ $product->image_url }}"
                                    class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-100 dark:ring-gray-700">
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-bold text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                    @if ($product->is_featured)
                                        <span class="text-amber-500 text-xs">⭐</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 mt-1 flex-wrap">
                                    <span
                                        class="text-sm font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                        🪙 {{ number_format($product->coin_price) }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold {{ $product->stock_badge['class'] }}">
                                        {{ $product->stock_badge['label'] }}
                                        @if (!$product->is_unlimited)
                                            ({{ $product->stock_quantity }})
                                        @endif
                                    </span>
                                    @if (!$product->is_active)
                                        <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">Nofaol</span>
                                    @endif
                                </div>
                                @if ($product->short_description)
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 truncate">
                                        {{ $product->short_description }}</p>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <a href="{{ route('admin.shop.products.show', $product) }}"
                                    class="p-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all"
                                    title="Ko'rish">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.shop.products.edit', $product) }}"
                                    class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all"
                                    title="Tahrirlash">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center">
                            <div
                                class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                                {{ $category->icon ?? '📦' }}
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white">Mahsulotlar yo'q</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bu kategoriyaga mahsulot qo'shing</p>
                            <a href="{{ route('admin.shop.products.create') }}"
                                class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-bold rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Mahsulot qo'shish
                            </a>
                        </div>
                    @endforelse

                    {{-- Pagination --}}
                </div>
                @if ($products->hasPages())
                    <div
                        class="p-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
