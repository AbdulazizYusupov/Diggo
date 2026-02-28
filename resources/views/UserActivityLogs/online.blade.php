@extends('Layout.app')
@section('title', 'Online Foydalanuvchilar')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Online Foydalanuvchilar
                </h1>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                So'nggi 15 daqiqada faol bo'lganlar — {{ now()->format('H:i:s') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('activity-logs.index') }}"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors text-sm">
                ← Barcha loglar
            </a>
            <button onclick="location.reload()"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Yangilash
            </button>
        </div>
    </div>

    {{-- Count badge --}}
    <div class="flex items-center gap-3">
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/50 rounded-2xl px-6 py-4 flex items-center gap-4">
            <div class="p-3 bg-emerald-500 rounded-xl">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase">Hozir online</p>
                <p class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-300">{{ $users->count() }}</p>
            </div>
        </div>
    </div>

    @if($users->isEmpty())
        {{-- Empty state --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-16 text-center">
            <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-lg font-bold text-gray-500 dark:text-gray-400">Hozir hech kim online emas</p>
            <p class="text-sm text-gray-400 mt-1">So'nggi 15 daqiqada hech kim tizimga kirmagan</p>
        </div>
    @else
        {{-- Users grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($users as $user)
                @php $log = $lastLogs->get($user->id); @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">

                    {{-- Online indicator + avatar --}}
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <img class="h-12 w-12 rounded-xl object-cover ring-2 ring-indigo-100 dark:ring-indigo-900"
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=6366f1&color=fff&size=128"
                                alt="{{ $user->full_name }}">
                            <span class="absolute -bottom-1 -right-1 flex h-3.5 w-3.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 ring-2 ring-white dark:ring-gray-800"></span>
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-1 justify-end">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 uppercase">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- User info --}}
                    <div class="mb-4">
                        <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $user->full_name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $user->phone }}</p>
                    </div>

                    {{-- Last login info --}}
                    @if($log)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Kirish vaqti</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $log->logged_at->format('H:i:s') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Qurilma</span>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">
                                    @if($log->device === 'Mobile') 📱
                                    @elseif($log->device === 'Tablet') 📟
                                    @else 🖥
                                    @endif
                                    {{ $log->device ?? '—' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">Browser</span>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $log->browser ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">IP</span>
                                <span class="font-mono text-gray-700 dark:text-gray-300">{{ $log->ip_address ?? '—' }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- View button --}}
                    <a href="{{ route('activity-logs.show', $user->id) }}"
                        class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-sm font-semibold rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Tarixni ko'rish
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Auto refresh --}}
        <p class="text-center text-xs text-gray-400">
            Sahifa har 60 soniyada avtomatik yangilanadi
        </p>
        <script>
            setTimeout(() => location.reload(), 60000);
        </script>
    @endif
</div>
@endsection