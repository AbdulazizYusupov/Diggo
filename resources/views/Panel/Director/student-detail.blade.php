@extends('Layout.app')

@section('title', 'O\'quvchi Tafsilotlari')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('director.students') }}" 
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    O'quvchi Tafsilotlari
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    To'liq ma'lumot va statistika
                </p>
            </div>
        </div>

        {{-- Student Profile Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row items-start gap-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold flex-shrink-0">
                    {{ substr($student->user->name, 0, 1) }}{{ substr($student->user->surname ?? '', 0, 1) }}
                </div>
                
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $student->user->name }} {{ $student->user->surname }}
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        ID: #{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Telefon</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $student->user->phone }}</p>
                        </div>
                        @if($student->user->email)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $student->user->email }}</p>
                            </div>
                        @endif
                        @if($student->enrollment_date)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Qo'shilgan</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($student->enrollment_date)->format('d.m.Y') }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Maktab</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $student->school->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase">Coinlar</p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ number_format($student->coins, 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                            Jami: {{ number_format($stats['total_coins_earned'], 0, '.', ' ') }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase">Balans</p>
                        <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50 mt-1">
                            {{ number_format($student->balance, 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">so'm</p>
                    </div>
                    <div class="p-3 bg-purple-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase">To'langan</p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($stats['total_paid'], 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">so'm</p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase">Guruhlar</p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ $stats['active_groups'] }}
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Faol</p>
                    </div>
                    <div class="p-3 bg-blue-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Groups --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Faol Guruhlar</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($activeGroups as $groupStudent)
                    @php $group = $groupStudent->group; @endphp
                    <div class="p-4 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/10 dark:to-blue-900/10 rounded-xl border border-indigo-200 dark:border-indigo-800/30">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $group->name }}</h4>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400">{{ $group->cours->title ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded">
                                Faol
                            </span>
                        </div>
                        <div class="space-y-2">
                            @if($group->start_time)
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold">Vaqt:</span> {{ $group->start_time }}{{ $group->end_time ? ' - ' . $group->end_time : '' }}
                                </p>
                            @endif
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-semibold">Qo'shilgan:</span> {{ \Carbon\Carbon::parse($groupStudent->joined_at)->format('d.m.Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="col-span-2 text-center text-gray-500 dark:text-gray-400 py-8">Faol guruhlar yo'q</p>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Recent Payments --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">So'nggi To'lovlar</h3>
                
                <div class="space-y-3">
                    @forelse($recentPayments as $payment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $payment->group->cours->title ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d.m.Y') : '-' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($payment->net_amount, 0, '.', ' ') }} so'm
                                </p>
                                @if($payment->discount > 0)
                                    <p class="text-xs text-red-500">
                                        -{{ number_format($payment->discount, 0, '.', ' ') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">To'lovlar yo'q</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Coins --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">So'nggi Coin Tranzaksiyalari</h3>
                
                <div class="space-y-3">
                    @forelse($recentCoins as $transaction)
                        <div class="flex items-center justify-between p-3 rounded-xl {{ $transaction->coins > 0 ? 'bg-green-50 dark:bg-green-900/10' : 'bg-red-50 dark:bg-red-900/10' }}">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg {{ $transaction->coins > 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                    <svg class="w-4 h-4 {{ $transaction->coins > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($transaction->coins > 0)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d.m.Y') }}
                                    </p>
                                </div>
                            </div>
                            <span class="font-bold {{ $transaction->coins > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->coins > 0 ? '+' : '' }}{{ number_format($transaction->coins, 0, '.', ' ') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Tranzaksiyalar yo'q</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Parent Info --}}
        @if($student->parent_name || $student->parent_phone)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Ota-ona Ma'lumotlari</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($student->parent_name)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ism</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $student->parent_name }}</p>
                        </div>
                    @endif
                    @if($student->parent_phone)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Telefon</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $student->parent_phone }}</p>
                        </div>
                    @endif
                    @if($student->emergency_contact_name)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Favqulodda Aloqa</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $student->emergency_contact_name }}</p>
                        </div>
                    @endif
                    @if($student->emergency_contact_phone)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Favqulodda Telefon</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $student->emergency_contact_phone }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection