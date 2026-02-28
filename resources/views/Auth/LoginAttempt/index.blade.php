@extends('Layout.app')
@section('title', 'Login Urinishlar')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Login Urinishlari
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Tizimga kirish urinishlari tarixi va xavfsizlik nazorati
                </p>
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('login-attempts.destroy-old') }}" class="inline"
                    onsubmit="return confirm('Eski yozuvlarni o\'chirishni tasdiqlaysizmi?')">
                    @csrf @method('DELETE')
                    <input type="hidden" name="days" value="30">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 font-semibold rounded-xl transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        30 kundan eskisini o'chir
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl px-4 py-3 text-emerald-700 dark:text-emerald-400 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @php
                $statCards = [
                    ['label' => 'Bugun jami',     'value' => $stats['total_today'],     'color' => 'blue'],
                    ['label' => 'Muvaffaqiyatli', 'value' => $stats['success_today'],   'color' => 'emerald'],
                    ['label' => 'Xato',           'value' => $stats['failed_today'],    'color' => 'red'],
                    ['label' => 'Bloklangan',     'value' => $stats['blocked_today'],   'color' => 'orange'],
                    ['label' => 'Yuqori risk',    'value' => $stats['high_risk_today'], 'color' => 'rose'],
                    ['label' => 'Unique IPlar',   'value' => $stats['unique_ips'],      'color' => 'violet'],
                ];
                $colorMap = [
                    'blue'    => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700/50 text-blue-700 dark:text-blue-300',
                    'emerald' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-700/50 text-emerald-700 dark:text-emerald-300',
                    'red'     => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-700/50 text-red-700 dark:text-red-300',
                    'orange'  => 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-700/50 text-orange-700 dark:text-orange-300',
                    'rose'    => 'bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-700/50 text-rose-700 dark:text-rose-300',
                    'violet'  => 'bg-violet-50 dark:bg-violet-900/20 border-violet-200 dark:border-violet-700/50 text-violet-700 dark:text-violet-300',
                ];
            @endphp
            @foreach($statCards as $card)
                <div class="border rounded-2xl px-4 py-3 {{ $colorMap[$card['color']] }}">
                    <p class="text-xs font-semibold uppercase opacity-75">{{ $card['label'] }}</p>
                    <p class="text-2xl font-extrabold mt-0.5">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Suspicious section --}}
        @if($suspiciousIps->isNotEmpty() || $suspiciousPhones->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Suspicious IPs --}}
                @if($suspiciousIps->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-red-200 dark:border-red-700/50 p-5">
                        <h3 class="font-bold text-red-700 dark:text-red-400 text-sm mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                            Shubhali IP manzillar (bugun)
                        </h3>
                        <div class="space-y-2">
                            @foreach($suspiciousIps as $item)
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('login-attempts.show-ip', $item->ip_address) }}"
                                        class="font-mono text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $item->ip_address }}
                                    </a>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">{{ $item->attempts }} urinish</span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold
                                            {{ $item->max_risk >= 80 ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                               ($item->max_risk >= 60 ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400') }}">
                                            Risk: {{ $item->max_risk }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Suspicious Phones --}}
                @if($suspiciousPhones->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-orange-200 dark:border-orange-700/50 p-5">
                        <h3 class="font-bold text-orange-700 dark:text-orange-400 text-sm mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Ko'p urinilgan raqamlar (bugun)
                        </h3>
                        <div class="space-y-2">
                            @foreach($suspiciousPhones as $item)
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('login-attempts.show-phone', $item->phone) }}"
                                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $item->phone }}
                                    </a>
                                    <span class="text-xs bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 px-2 py-0.5 rounded-md font-bold">
                                        {{ $item->attempts }} xato
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Qidiruv (phone / IP)</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="998901234567 yoki 192.168..."
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                </div>
                <div class="min-w-[130px]">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Holat</label>
                    <select name="status"
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">Barchasi</option>
                        <option value="success" @selected(request('status') === 'success')>Muvaffaqiyatli</option>
                        <option value="failed"  @selected(request('status') === 'failed')>Xato</option>
                        <option value="blocked" @selected(request('status') === 'blocked')>Bloklangan</option>
                    </select>
                </div>
                <div class="min-w-[130px]">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Risk darajasi</label>
                    <select name="risk"
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">Barchasi</option>
                        <option value="critical" @selected(request('risk') === 'critical')>Kritik (80+)</option>
                        <option value="high"     @selected(request('risk') === 'high')>Yuqori (60+)</option>
                        <option value="medium"   @selected(request('risk') === 'medium')>O'rta (30+)</option>
                        <option value="low"      @selected(request('risk') === 'low')>Past</option>
                    </select>
                </div>
                <div class="min-w-[130px]">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Sana</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Qidirish
                    </button>
                    <a href="{{ route('login-attempts.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-semibold rounded-xl transition-colors">
                        Tozalash
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if($attempts->isEmpty())
                <div class="p-16 text-center text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-semibold">Hech qanday yozuv topilmadi</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                <th class="text-left px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase">Vaqt</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase">Phone</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase">IP manzil</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase">Holat</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase">Sabab</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase">Qurilma</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase">Risk</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 text-xs uppercase">Amal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach($attempts as $attempt)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    {{-- Vaqt --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-gray-900 dark:text-white font-medium">
                                            {{ $attempt->attempted_at->format('d.m.Y') }}
                                        </span>
                                        <span class="text-gray-400 ml-1">
                                            {{ $attempt->attempted_at->format('H:i:s') }}
                                        </span>
                                    </td>

                                    {{-- Phone --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('login-attempts.show-phone', $attempt->phone) }}"
                                            class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $attempt->phone }}
                                        </a>
                                        @if($attempt->user)
                                            <p class="text-xs text-gray-400">{{ $attempt->user->full_name }}</p>
                                        @endif
                                    </td>

                                    {{-- IP --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('login-attempts.show-ip', $attempt->ip_address) }}"
                                            class="font-mono text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $attempt->ip_address }}
                                        </a>
                                    </td>

                                    {{-- Holat --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($attempt->blocked)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" /></svg>
                                                Bloklangan
                                            </span>
                                        @elseif($attempt->successful)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Muvaffaqiyatli
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                Xato
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Sabab --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                        @php
                                            $reasons = [
                                                'wrong_password'  => ['Noto\'g\'ri parol', 'text-orange-600 dark:text-orange-400'],
                                                'user_not_found'  => ['Foydalanuvchi yo\'q', 'text-red-600 dark:text-red-400'],
                                                'user_inactive'   => ['Hisob o\'chirilgan', 'text-gray-500'],
                                                'rate_limited'    => ['Rate limit', 'text-rose-600 dark:text-rose-400'],
                                            ];
                                            $r = $reasons[$attempt->failure_reason] ?? null;
                                        @endphp
                                        @if($r)
                                            <span class="font-medium {{ $r[1] }}">{{ $r[0] }}</span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600">—</span>
                                        @endif
                                    </td>

                                    {{-- Qurilma --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600 dark:text-gray-300">
                                        {{ $attempt->device ?? '—' }} / {{ $attempt->browser ?? '—' }}
                                    </td>

                                    {{-- Risk --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($attempt->risk_score > 0)
                                            @php
                                                $rClass = match(true) {
                                                    $attempt->risk_score >= 80 => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                                    $attempt->risk_score >= 60 => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                                    $attempt->risk_score >= 30 => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                    default                    => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $rClass }}">
                                                {{ $attempt->risk_score }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600 text-xs">—</span>
                                        @endif
                                    </td>

                                    {{-- Amal --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <form method="POST" action="{{ route('login-attempts.destroy', $attempt->id) }}"
                                            onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($attempts->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                        {{ $attempts->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection