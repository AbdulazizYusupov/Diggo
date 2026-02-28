@extends('Layout.app')
@section('title', $user->full_name . ' — Faoliyat')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <img class="h-14 w-14 rounded-2xl ring-2 ring-indigo-100 dark:ring-indigo-900"
                src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=6366f1&color=fff&size=128" alt="">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $user->full_name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-sm text-gray-500">{{ $user->phone }}</span>
                    @foreach($user->roles as $role)
                        <span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                            {{ ucfirst($role->name) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
        <a href="{{ route('activity-logs.index') }}"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors text-sm">
            ← Orqaga
        </a>
    </div>

    {{-- User Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-bold text-gray-500 uppercase">Jami loginlar</p>
            <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $stats['total_logins'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-bold text-gray-500 uppercase">So'nggi login</p>
            <p class="text-base font-bold text-gray-900 dark:text-white mt-1">
                {{ $stats['last_login'] ? $stats['last_login']->format('d.m.Y H:i') : '—' }}
            </p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-bold text-gray-500 uppercase">Qurilmalar</p>
            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                {{ $stats['devices_used']->filter()->join(', ') ?: '—' }}
            </p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-200 dark:border-gray-700">
            <p class="text-xs font-bold text-gray-500 uppercase">Browserlar</p>
            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                {{ $stats['browsers_used']->filter()->join(', ') ?: '—' }}
            </p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Amal</label>
                <select name="action" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">Barchasi</option>
                    <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Dan</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Gacha</label>
                <input type="date" name="to" value="{{ request('to') }}"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Filter
            </button>
            <a href="{{ route('activity-logs.show', $user->id) }}"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg transition-colors">
                ✕
            </a>
        </form>
    </div>

    {{-- Logs table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Amal</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Qurilma</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Browser / OS</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">IP</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Vaqt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-5 py-3.5">
                                @if($log->action === 'login')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        ↗ Login
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                        ↙ Logout
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-sm text-gray-600 dark:text-gray-400">
                                @if($log->device === 'Mobile') 📱
                                @elseif($log->device === 'Tablet') 📟
                                @else 🖥
                                @endif
                                {{ $log->device ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-sm text-gray-600 dark:text-gray-400">
                                {{ $log->browser ?? '—' }} / {{ $log->platform ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-sm text-gray-600 dark:text-gray-400">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $log->logged_at->format('d.m.Y') }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $log->logged_at->format('H:i:s') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-400">Loglar topilmadi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection