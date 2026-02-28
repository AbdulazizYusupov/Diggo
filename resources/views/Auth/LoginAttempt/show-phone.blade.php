@extends('Layout.app')
@section('title', 'Phone: ' . $phone)

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('login-attempts.index') }}"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        {{ $phone }}
                    </h1>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Bu raqam orqali barcha login urinishlari
                </p>
            </div>

            @if($phoneStats['is_blocked_now'])
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-xl text-red-700 dark:text-red-400 text-sm font-bold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                    </svg>
                    Hozir BLOKLANGAN
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl text-emerald-700 dark:text-emerald-400 text-sm font-semibold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Faol (bloklangan emas)
                </div>
            @endif
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            @php
                $cards = [
                    ['label' => 'Jami urinish',  'value' => $phoneStats['total'],       'color' => 'blue'],
                    ['label' => 'Xato',           'value' => $phoneStats['failed'],      'color' => 'red'],
                    ['label' => 'Muvaffaqiyatli', 'value' => $phoneStats['successful'],  'color' => 'emerald'],
                    ['label' => 'Unique IPlar',   'value' => $phoneStats['unique_ips'],  'color' => 'violet'],
                    ['label' => 'Holat',          'value' => $phoneStats['is_blocked_now'] ? 'Blok' : 'Faol', 'color' => $phoneStats['is_blocked_now'] ? 'rose' : 'emerald'],
                ];
                $colorMap = [
                    'blue'    => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700/50 text-blue-700 dark:text-blue-300',
                    'red'     => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-700/50 text-red-700 dark:text-red-300',
                    'emerald' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-700/50 text-emerald-700 dark:text-emerald-300',
                    'violet'  => 'bg-violet-50 dark:bg-violet-900/20 border-violet-200 dark:border-violet-700/50 text-violet-700 dark:text-violet-300',
                    'rose'    => 'bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-700/50 text-rose-700 dark:text-rose-300',
                ];
            @endphp
            @foreach($cards as $card)
                <div class="border rounded-2xl px-4 py-3 {{ $colorMap[$card['color']] }}">
                    <p class="text-xs font-semibold uppercase opacity-75">{{ $card['label'] }}</p>
                    <p class="text-2xl font-extrabold mt-0.5">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- First / Last seen --}}
        <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
            <span>Birinchi urinish:
                <strong class="text-gray-800 dark:text-gray-200">
                    {{ $phoneStats['first_seen'] ? \Carbon\Carbon::parse($phoneStats['first_seen'])->format('d.m.Y H:i') : '—' }}
                </strong>
            </span>
            <span>Oxirgi urinish:
                <strong class="text-gray-800 dark:text-gray-200">
                    {{ $phoneStats['last_seen'] ? \Carbon\Carbon::parse($phoneStats['last_seen'])->format('d.m.Y H:i') : '—' }}
                </strong>
            </span>
        </div>

        {{-- Timeline table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-gray-900 dark:text-white">Urinishlar tarixi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Vaqt</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">IP manzil</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Holat</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Sabab</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Qurilma / Browser</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Risk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($attempts as $attempt)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white">
                                    {{ $attempt->attempted_at->format('d.m.Y') }}
                                    <span class="text-gray-400 ml-1">{{ $attempt->attempted_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('login-attempts.show-ip', $attempt->ip_address) }}"
                                        class="font-mono text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $attempt->ip_address }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($attempt->blocked)
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Bloklangan</span>
                                    @elseif($attempt->successful)
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Muvaffaqiyatli</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Xato</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    @php
                                        $reasons = ['wrong_password'=>"Noto'g'ri parol",'user_not_found'=>"Foydalanuvchi yo'q",'user_inactive'=>"Hisob o'chirilgan",'rate_limited'=>'Rate limit'];
                                    @endphp
                                    {{ $reasons[$attempt->failure_reason] ?? '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    {{ $attempt->device ?? '—' }} / {{ $attempt->browser ?? '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($attempt->risk_score > 0)
                                        @php
                                            $rc = match(true) {
                                                $attempt->risk_score >= 80 => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                                $attempt->risk_score >= 60 => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                                $attempt->risk_score >= 30 => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                default => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $rc }}">{{ $attempt->risk_score }}</span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Yozuv topilmadi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($attempts->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                    {{ $attempts->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection