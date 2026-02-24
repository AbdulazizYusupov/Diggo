@extends('Layout.app')

@section('title', $product->name)

@section('content')
    <div class="space-y-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('student.shop.index') }}"
                class="text-gray-500 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 font-medium transition-colors">Do'kon</a>
            <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            @if ($product->category)
                <a href="{{ route('student.shop.index', ['category' => $product->category->id]) }}"
                    class="text-gray-500 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 font-medium transition-colors">{{ $product->category->name }}</a>
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            @endif
            <span class="text-gray-900 dark:text-white font-semibold truncate max-w-[200px]">{{ $product->name }}</span>
        </nav>

        {{-- ═══════════════════════════════════════════════
         MAIN PRODUCT DETAIL
    ═══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10">

            {{-- LEFT: Images --}}
            <div class="space-y-3">
                {{-- Main image --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden aspect-square shadow-sm">
                    <img id="mainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}"
                        class="w-full h-full object-contain p-4">
                </div>

                {{-- Thumbnail gallery --}}
                @if ($product->images && count($product->images))
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        <button type="button"
                            onclick="document.getElementById('mainImage').src='{{ $product->image_url }}'"
                            class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 border-amber-400 bg-gray-100 dark:bg-slate-800 hover:border-amber-500 transition-all">
                            <img src="{{ $product->image_url }}" alt="" class="w-full h-full object-cover">
                        </button>
                        @foreach ($product->images as $img)
                            <button type="button"
                                onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $img) }}'"
                                class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 border-transparent bg-gray-100 dark:bg-slate-800 hover:border-amber-400 transition-all">
                                <img src="{{ asset('storage/' . $img) }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT: Info & Order --}}
            <div class="flex flex-col gap-5">

                {{-- Title & badges --}}
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        @if ($product->category)
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded-lg">{{ $product->category->name }}</span>
                        @endif
                        @if ($product->is_featured)
                            <span
                                class="text-xs font-bold px-2 py-0.5 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-lg">⭐
                                Featured</span>
                        @endif
                        @php $stock = $product->stock_badge; @endphp
                        <span
                            class="text-xs font-bold px-2 py-0.5 rounded-lg {{ $stock['class'] }}">{{ $stock['label'] }}</span>
                    </div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ $product->name }}</h1>
                    @if ($product->brand)
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Brend: <span
                                class="font-semibold text-gray-700 dark:text-gray-300">{{ $product->brand }}</span></p>
                    @endif
                </div>

                {{-- Price & coin info --}}
                @php
                    $studentCoins = auth()->user()->student->coins ?? 0;
                    $canAfford = $studentCoins >= $product->coin_price;
                    $canBuy = $product->canStudentBuy(auth()->user()->student->id ?? 0);
                @endphp
                <div
                    class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 rounded-2xl p-5">
                    <div class="flex items-end gap-3 justify-between flex-wrap">
                        <div>
                            <p
                                class="text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase tracking-wider mb-1">
                                Narxi</p>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-4xl font-black text-amber-600 dark:text-amber-400">{{ number_format($product->coin_price, 0, '.', ' ') }}</span>
                                <span class="text-2xl">🪙</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Balans:</p>
                            <p
                                class="text-lg font-bold {{ $canAfford ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ number_format($studentCoins, 0, '.', ' ') }} 🪙
                            </p>
                            @if (!$canAfford)
                                <p class="text-xs text-rose-500 font-medium mt-0.5">
                                    {{ number_format($product->coin_price - $studentCoins, 0, '.', ' ') }} 🪙 yetarli emas
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Short description --}}
                @if ($product->short_description)
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $product->short_description }}
                    </p>
                @endif

                {{-- Attributes --}}
                @if ($product->attributes && count($product->attributes))
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($product->attributes as $key => $value)
                            <div
                                class="bg-gray-50 dark:bg-slate-800 rounded-xl px-3 py-2 border border-gray-100 dark:border-gray-700">
                                <p
                                    class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                    {{ $key }}</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Stock info --}}
                @if (!$product->is_unlimited)
                    <div class="flex items-center gap-2 text-sm">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span class="text-gray-500 dark:text-gray-400">Zaxira:</span>
                            <span
                                class="font-bold {{ $product->stock_quantity <= 5 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $product->stock_quantity }} dona
                            </span>
                        </div>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-gray-500 dark:text-gray-400">Max xarid:</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $product->max_per_student }} ta</span>
                        </div>
                    </div>
                @endif

                {{-- SKU --}}
                @if ($product->sku)
                    <p class="text-xs text-gray-400 dark:text-gray-500">SKU: <span
                            class="font-mono font-bold text-gray-600 dark:text-gray-300">{{ $product->sku }}</span></p>
                @endif

                {{-- ORDER FORM --}}
                @if ($canBuy && $canAfford)
                    <form action="{{ route('student.shop.order', $product->slug) }}" method="POST" class="space-y-3 pt-2">
                        @csrf

                        {{-- Quantity --}}
                        @php
                            $bought = \App\Models\ShopOrder::where('student_id', auth()->user()->student->id ?? 0)
                                ->where('shop_product_id', $product->id)
                                ->whereIn('status', ['pending', 'approved', 'delivered'])
                                ->sum('quantity');
                            $maxCanBuy = min(
                                $product->max_per_student - $bought,
                                $product->is_unlimited ? 99 : $product->stock_quantity,
                            );
                        @endphp

                        @if ($maxCanBuy > 1)
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Miqdor</label>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="changeQty(-1)"
                                        class="w-9 h-9 flex items-center justify-center bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all font-bold text-lg">−</button>
                                    <input type="number" id="qty" name="quantity" value="1" min="1"
                                        max="{{ $maxCanBuy }}"
                                        class="w-16 text-center px-2 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-bold text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                                    <button type="button" onclick="changeQty(1)"
                                        class="w-9 h-9 flex items-center justify-center bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all font-bold text-lg">+</button>
                                    <span class="text-sm text-gray-400 ml-1">/ {{ $maxCanBuy }} ta</span>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="quantity" value="1">
                        @endif

                        {{-- Student note --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Izoh <span
                                    class="text-gray-400 font-normal">(ixtiyoriy)</span></label>
                            <textarea name="student_note" rows="2" placeholder="Yetkazish manzili yoki boshqa ma'lumot..."
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all resize-none"></textarea>
                        </div>

                        {{-- Total --}}
                        <div
                            class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Jami:</span>
                            <div class="flex items-center gap-1.5">
                                <span id="totalCoins"
                                    class="text-lg font-black text-amber-600 dark:text-amber-400">{{ number_format($product->coin_price, 0, '.', ' ') }}</span>
                                <span>🪙</span>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-base rounded-2xl shadow-lg shadow-amber-200 dark:shadow-none transition-all active:scale-[0.98] transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Buyurtma berish
                        </button>
                    </form>
                @elseif(!$product->is_in_stock)
                    <div
                        class="p-4 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-2xl text-center">
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">😔 Bu mahsulot tugagan</p>
                    </div>
                @elseif(!$canAfford)
                    <div
                        class="p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-500/30 rounded-2xl text-center">
                        <p class="text-sm font-semibold text-rose-600 dark:text-rose-400">💔 Coiningiz yetarli emas</p>
                        <p class="text-xs text-rose-500 dark:text-rose-500 mt-1">Yangi topshiriqlarni bajarib coin yig'ing!
                        </p>
                    </div>
                @elseif(!$canBuy)
                    <div
                        class="p-4 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-2xl text-center">
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">⛔ Bu mahsulotdan maksimal
                            miqdorda xarid qildingiz</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
         FULL DESCRIPTION
    ═══════════════════════════════════════════════ --}}
        @if ($product->description)
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Mahsulot haqida</h2>
                <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 leading-relaxed">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════
         RELATED PRODUCTS
    ═══════════════════════════════════════════════ --}}
        @if ($relatedProducts && $relatedProducts->count())
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">O'xshash mahsulotlar</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 lg:gap-4">
                    @foreach ($relatedProducts as $related)
                        @php $relStock = $related->stock_badge; @endphp
                        <a href="{{ route('student.shop.show', $related->slug) }}"
                            class="group bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                            <div class="aspect-square overflow-hidden bg-gray-100 dark:bg-slate-800">
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-3">
                                <p
                                    class="text-xs font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                                    {{ $related->name }}</p>
                                <div class="flex items-center gap-1 mt-1.5">
                                    <span
                                        class="text-sm font-black text-amber-600 dark:text-amber-400">{{ number_format($related->coin_price, 0, '.', ' ') }}</span>
                                    <span class="text-xs">🪙</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            function changeQty(delta) {
                const input = document.getElementById('qty');
                const max = parseInt(input.max);
                let val = parseInt(input.value) + delta;
                if (val < 1) val = 1;
                if (val > max) val = max;
                input.value = val;
                updateTotal(val);
            }

            function updateTotal(qty) {
                const price = {{ $product->coin_price }};
                const total = price * qty;
                document.getElementById('totalCoins').textContent = total.toLocaleString('uz-UZ');
            }

            const qtyInput = document.getElementById('qty');
            if (qtyInput) {
                qtyInput.addEventListener('input', () => updateTotal(parseInt(qtyInput.value) || 1));
            }
        </script>
    @endpush
@endsection
