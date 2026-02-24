@extends('Layout.app')

@section('title', 'Buyurtmalarim')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex p-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl shadow-sm">
                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Buyurtmalarim</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20">
                        {{ $orders->total() }} ta
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Barcha xaridlaringizning ro'yxati</p>
            </div>
        </div>
        <a href="{{ route('student.shop.index') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-all shadow-sm shadow-amber-200 dark:shadow-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Do'konga qaytish
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-0 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('student.shop.index') }}"
           class="px-6 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
            Sotuvda
        </a>
        <a href="{{ route('student.shop.orders') }}"
           class="px-6 py-3 text-sm font-semibold border-b-2 border-amber-500 text-amber-600 dark:text-amber-400 transition-colors">
            Sotib olganlarim
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $statusCounts = [
                'pending'   => ['label' => 'Kutilmoqda',   'color' => 'amber',   'icon' => '⏳'],
                'approved'  => ['label' => 'Tasdiqlangan', 'color' => 'blue',    'icon' => '✅'],
                'delivered' => ['label' => 'Yetkazilgan',  'color' => 'emerald', 'icon' => '📦'],
                'cancelled' => ['label' => 'Bekor',        'color' => 'gray',    'icon' => '❌'],
            ];
            $colorMap = [
                'amber'   => 'bg-amber-50 dark:bg-amber-500/10 border-amber-100 dark:border-amber-500/20 text-amber-700 dark:text-amber-400',
                'blue'    => 'bg-blue-50 dark:bg-blue-500/10 border-blue-100 dark:border-blue-500/20 text-blue-700 dark:text-blue-400',
                'emerald' => 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400',
                'gray'    => 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-400',
            ];
        @endphp
        @foreach($statusCounts as $status => $info)
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3">
            <span class="text-2xl">{{ $info['icon'] }}</span>
            <div>
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $info['label'] }}</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white">
                    {{ $orders->getCollection()->where('status', $status)->count() }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter by status --}}
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('student.shop.orders') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                  {{ !request('status') ? 'bg-amber-500 text-white shadow-sm' : 'bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700' }}">
            Barchasi
        </a>
        @foreach(['pending' => 'Kutilmoqda', 'approved' => 'Tasdiqlangan', 'delivered' => 'Yetkazilgan', 'cancelled' => 'Bekor', 'rejected' => 'Rad etilgan'] as $s => $label)
        <a href="{{ route('student.shop.orders', ['status' => $s]) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                  {{ request('status') === $s ? 'bg-amber-500 text-white shadow-sm' : 'bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Orders List --}}
    <div class="space-y-3">
        @forelse($orders as $order)
        @php $badge = $order->status_badge; @endphp
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all">
            <div class="p-4 lg:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                    {{-- Product image --}}
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-gray-700">
                            <img src="{{ $order->product->image_url ?? asset('images/no-product.png') }}"
                                 alt="{{ $order->product->name ?? '' }}"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start gap-2 flex-wrap">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex-1 min-w-0">
                                <a href="{{ route('student.shop.show', $order->product->slug ?? '#') }}"
                                   class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                    {{ $order->product->name ?? 'Mahsulot o\'chirilgan' }}
                                </a>
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                {{ $order->order_number }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </span>
                            <span class="flex items-center gap-1">
                                📦 {{ $order->quantity }} dona
                            </span>
                        </div>

                        @if($order->admin_note)
                        <div class="mt-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-xs text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">
                            <strong>Admin izohi:</strong> {{ $order->admin_note }}
                        </div>
                        @endif

                        @if($order->delivery_info)
                        <div class="mt-2 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg text-xs text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20">
                            <strong>Yetkazish:</strong> {{ $order->delivery_info }}
                        </div>
                        @endif
                    </div>

                    {{-- Price + Cancel --}}
                    <div class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-2">
                        <div class="text-right">
                            <div class="flex items-center gap-1 justify-end">
                                <span class="text-lg font-black text-amber-600 dark:text-amber-400">{{ number_format($order->total_coins, 0, '.', ' ') }}</span>
                                <span>🪙</span>
                            </div>
                            <p class="text-[10px] text-gray-400">{{ number_format($order->coin_price, 0, '.', ' ') }} 🪙 × {{ $order->quantity }}</p>
                        </div>

                        @if($order->status === 'pending')
                        <form action="{{ route('student.shop.cancel', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                onclick="return confirm('Buyurtmani bekor qilmoqchimisiz?')"
                                class="px-3 py-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-500/30 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-all">
                                Bekor qilish
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-16 text-center">
            <div class="text-5xl mb-4">🛍️</div>
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Hali hech narsa xarid qilmadingiz</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Do'kondan mahsulot tanlang va xarid qiling!</p>
            <a href="{{ route('student.shop.index') }}"
               class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition-all shadow-sm shadow-amber-200 dark:shadow-none">
                Do'konga o'tish
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="flex justify-center">
        {{ $orders->appends(request()->except('page'))->links() }}
    </div>
    @endif

</div>
@endsection