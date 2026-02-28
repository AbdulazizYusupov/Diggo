@extends('Layout.app')
@section('title', 'Faoliyat Tarixi')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Faoliyat Tarixi
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Barcha foydalanuvchilarning kirish va chiqish vaqtlari
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">

                {{-- Online Users Button --}}
                <a href="{{ route('activity-logs.online') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-sm font-semibold rounded-xl transition-colors border border-emerald-200 dark:border-emerald-700">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    Online: {{ $stats['online_now'] }} ta
                </a>

                {{-- Clear Old Logs Button --}}
                <form method="POST" action="{{ route('activity-logs.clear') }}"
                    onsubmit="return confirm('30 kundan eski loglarni o\'chirish?')">
                    @csrf @method('DELETE')
                    <input type="hidden" name="days" value="30">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold rounded-xl transition-colors border border-red-200 dark:border-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eski loglarni tozalash
                    </button>
                </form>

            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4 border border-blue-200 dark:border-blue-700/50">
                <p class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase">Bugun jami</p>
                <p class="text-2xl font-extrabold text-blue-700 dark:text-blue-300 mt-1">{{ $stats['total_today'] }}</p>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-4 border border-emerald-200 dark:border-emerald-700/50">
                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase">Login</p>
                <p class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-300 mt-1">{{ $stats['logins_today'] }}</p>
            </div>
            <div class="bg-orange-50 dark:bg-orange-900/20 rounded-2xl p-4 border border-orange-200 dark:border-orange-700/50">
                <p class="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase">Logout</p>
                <p class="text-2xl font-extrabold text-orange-700 dark:text-orange-300 mt-1">{{ $stats['logouts_today'] }}</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-4 border border-purple-200 dark:border-purple-700/50">
                <p class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase">Unique foydalanuvchi</p>
                <p class="text-2xl font-extrabold text-purple-700 dark:text-purple-300 mt-1">{{ $stats['unique_users'] }}</p>
            </div>
            <div class="bg-teal-50 dark:bg-teal-900/20 rounded-2xl p-4 border border-teal-200 dark:border-teal-700/50">
                <p class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase">Hozir online</p>
                <p class="text-2xl font-extrabold text-teal-700 dark:text-teal-300 mt-1">{{ $stats['online_now'] }}</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Qidiruv</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ism yoki telefon..."
                            class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Amal</label>
                    <select name="action"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Barchasi</option>
                        <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Rol</label>
                    <select name="role"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Barchasi</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Qurilma</label>
                    <select name="device"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Barchasi</option>
                        <option value="Desktop" {{ request('device') === 'Desktop' ? 'selected' : '' }}>Desktop</option>
                        <option value="Mobile" {{ request('device') === 'Mobile' ? 'selected' : '' }}>Mobile</option>
                        <option value="Tablet" {{ request('device') === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Sana</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('activity-logs.index') }}"
                        class="inline-flex items-center justify-center p-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">#</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Foydalanuvchi</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Rol</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Amal</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Qurilma</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Browser / OS</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">IP manzil</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Vaqt</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">

                                {{-- ID --}}
                                <td class="px-5 py-3.5 text-xs font-mono text-gray-400">{{ $log->id }}</td>

                                {{-- User --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <img class="h-8 w-8 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-gray-600"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($log->user->full_name ?? 'N A') }}&background=6366f1&color=fff&size=64"
                                            alt="">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $log->user->full_name ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $log->user->phone ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Role --}}
                                <td class="px-5 py-3.5">
                                    @foreach ($log->user->roles ?? [] as $role)
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded-md text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>

                                {{-- Action --}}
                                <td class="px-5 py-3.5">
                                    @if ($log->action === 'login')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            Login
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Logout
                                        </span>
                                    @endif
                                </td>

                                {{-- Device --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                                        @if ($log->device === 'Mobile')
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        @elseif($log->device === 'Tablet')
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                        {{ $log->device ?? '—' }}
                                    </div>
                                </td>

                                {{-- Browser --}}
                                <td class="px-5 py-3.5 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">{{ $log->browser ?? '—' }}</span>
                                    <span class="text-gray-400"> / </span>
                                    <span class="text-xs">{{ $log->platform ?? '—' }}</span>
                                </td>

                                {{-- IP --}}
                                <td class="px-5 py-3.5">
                                    <span class="text-sm font-mono text-gray-600 dark:text-gray-400">
                                        {{ $log->ip_address ?? '—' }}
                                    </span>
                                </td>

                                {{-- Time --}}
                                <td class="px-5 py-3.5">
                                    <p class="text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $log->logged_at->format('d.m.Y') }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $log->logged_at->format('H:i:s') }}</p>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-1.5">

                                        {{-- Show button --}}
                                        <a href="{{ route('activity-logs.show', $log->user_id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Ko'rish
                                        </a>

                                        {{-- Delete button --}}
                                        <form method="POST" action="{{ route('activity-logs.destroy', $log->id) }}"
                                            onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Loglar topilmadi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($logs->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection