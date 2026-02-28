@extends('Layout.app')

@section('title', 'Bildirishnomalar')

@section('content')
    <div class="space-y-6">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30 border-b-2 border-indigo-800/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Bildirishnomalar
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Barcha bildirishnomalaringiz shu yerda
                    </p>
                </div>
            </div>

            @if (Auth::user()->unreadNotifications()->count() > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 sm:px-8 sm:py-3.5
                               bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-2xl
                               shadow-lg shadow-indigo-100 dark:shadow-none
                               transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm sm:text-base tracking-wide whitespace-nowrap">Barchasini o'qildi deb belgilash</span>
                    </button>
                </form>
            @endif
        </div>

        {{-- Statistics Cards --}}
        @php
            $total    = Auth::user()->notifications()->count();
            $unread   = Auth::user()->unreadNotifications()->count();
            $read     = $total - $unread;
            $todayCount = Auth::user()->notifications()->whereDate('created_at', today())->count();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30
                        rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wider">
                            Jami
                        </p>
                        <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-1">{{ $total }}</p>
                    </div>
                    <div class="p-3 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30
                        rounded-2xl p-6 border border-red-200 dark:border-red-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-red-700 dark:text-red-400 uppercase tracking-wider">
                            O'qilmagan
                        </p>
                        <p class="text-3xl font-extrabold text-red-900 dark:text-red-50 mt-1">{{ $unread }}</p>
                    </div>
                    <div class="p-3 bg-red-600 rounded-xl shadow-lg shadow-red-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30
                        rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            O'qilgan
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">{{ $read }}</p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl shadow-lg shadow-emerald-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30
                        rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">
                            Bugun
                        </p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">{{ $todayCount }}</p>
                    </div>
                    <div class="p-3 bg-amber-500 rounded-xl shadow-lg shadow-amber-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Filter Bar --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-col sm:flex-row items-center gap-3">

                {{-- Filter Tabs --}}
                <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-700/50 rounded-xl p-1 w-full sm:w-auto">
                    <a href="{{ route('notifications.index') }}"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold transition-all
                               {{ !request('filter') || request('filter') === 'all'
                                    ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Barchasi
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold transition-all
                               {{ request('filter') === 'unread'
                                    ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                        O'qilmagan
                        @if ($unread > 0)
                            <span class="inline-flex items-center justify-center h-5 min-w-[20px] px-1.5
                                         rounded-full bg-red-500 text-white text-[10px] font-bold">
                                {{ $unread > 99 ? '99+' : $unread }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'read']) }}"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold transition-all
                               {{ request('filter') === 'read'
                                    ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        O'qilgan
                    </a>
                </div>

                {{-- Spacer --}}
                <div class="hidden sm:block flex-1"></div>

                {{-- Result count --}}
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                    <span class="font-bold text-gray-900 dark:text-white">{{ $notifications->total() }}</span>
                    ta bildirishnoma
                </p>

            </div>
        </div>

        {{-- Notifications List --}}
        <div class="space-y-3">
            @forelse ($notifications as $notification)
                <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm
                            border transition-all duration-300 hover:shadow-lg
                            {{ is_null($notification->read_at)
                                ? 'border-indigo-200 dark:border-indigo-500/40 hover:border-indigo-400 dark:hover:border-indigo-500/70'
                                : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">

                    {{-- Unread left accent bar --}}
                    @if (is_null($notification->read_at))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-l-2xl"></div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('notifications.read', $notification->id) }}"
                        class="block"
                    >
                        @csrf
                        <button type="submit" class="w-full text-left px-6 py-5 pl-7">
                            <div class="flex items-start gap-4">

                                {{-- Icon --}}
                                <div class="flex-shrink-0 mt-0.5">
                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm
                                        {{ is_null($notification->read_at)
                                            ? 'bg-indigo-100 dark:bg-indigo-900/40'
                                            : 'bg-gray-100 dark:bg-gray-700' }}">
                                        <svg class="w-5 h-5 {{ is_null($notification->read_at) ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3 flex-wrap">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $notification->data['title'] }}
                                            </p>
                                            @if (is_null($notification->read_at))
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5
                                                             bg-indigo-100 dark:bg-indigo-900/40
                                                             text-indigo-700 dark:text-indigo-400
                                                             text-[10px] font-bold rounded-full border border-indigo-200/50 dark:border-indigo-800/50">
                                                    <span class="relative flex h-1.5 w-1.5">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-500"></span>
                                                    </span>
                                                    Yangi
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5
                                                             bg-gray-100 dark:bg-gray-700
                                                             text-gray-500 dark:text-gray-400
                                                             text-[10px] font-bold rounded-full border border-gray-200 dark:border-gray-600">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    O'qilgan
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Timestamp --}}
                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-xs text-gray-400 dark:text-gray-500 font-medium whitespace-nowrap"
                                                  title="{{ $notification->created_at->format('d.m.Y H:i') }}">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1.5 leading-relaxed">
                                        {{ $notification->data['message'] }}
                                    </p>

                                    {{-- Action hint --}}
                                    <div class="flex items-center gap-1.5 mt-3">
                                        <span class="text-xs font-semibold
                                            {{ is_null($notification->read_at)
                                                ? 'text-indigo-500 dark:text-indigo-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-300'
                                                : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}
                                            transition-colors">
                                            {{ isset($notification->data['action_url']) ? 'Ko\'rish uchun bosing' : 'O\'qildi deb belgilash' }}
                                        </span>
                                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1
                                            {{ is_null($notification->read_at)
                                                ? 'text-indigo-400 dark:text-indigo-500'
                                                : 'text-gray-400 dark:text-gray-500' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>

                            </div>
                        </button>
                    </form>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="inline-flex p-5 bg-gray-100 dark:bg-gray-700/50 rounded-full mb-4">
                            <svg class="w-14 h-14 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            @if (request('filter') === 'unread')
                                O'qilmagan bildirishnomalar yo'q
                            @elseif (request('filter') === 'read')
                                O'qilgan bildirishnomalar yo'q
                            @else
                                Hech qanday bildirishnoma yo'q
                            @endif
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            @if (request('filter'))
                                Boshqa filtr tanlang yoki barchasini ko'ring
                            @else
                                Yangi bildirishnomalar bu yerda ko'rinadi
                            @endif
                        </p>
                        @if (request('filter'))
                            <a href="{{ route('notifications.index') }}"
                                class="mt-4 inline-flex items-center gap-2 px-5 py-2.5
                                       bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40
                                       border border-indigo-200 dark:border-indigo-500/30
                                       text-indigo-700 dark:text-indigo-300 font-semibold rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Barchasini ko'rish
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($notifications->hasPages())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm
                        border border-gray-200 dark:border-indigo-500/20 p-4 mt-6">
                {{ $notifications->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
@endsection