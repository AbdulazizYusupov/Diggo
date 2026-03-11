@extends('Layout.app')
@section('title', "O'chirilgan foydalanuvchilar")
@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">O'chirilganlar</h1>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-100 dark:border-rose-500/20">
                            {{ $stats['total'] }} ta
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Soft delete qilingan foydalanuvchilar
                    </p>
                </div>
            </div>

            {{-- Restore All --}}
            @if ($stats['total'] > 0)
                <button type="button"
                    onclick="document.getElementById('restoreAllModal').classList.remove('hidden'); document.body.style.overflow='hidden'"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all shadow-sm active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Hammasini tiklash
                </button>
            @endif
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ($statCards as $card)
                <a href="{{ route('trashed-users.index', array_merge(request()->except('role', 'page'), ['role' => $card['role']])) }}"
                    class="bg-white dark:bg-slate-900 rounded-2xl border p-5 transition-all hover:shadow-md {{ $card['active'] ? $card['activeClass'] : 'border-gray-200 dark:border-gray-700' }}">
                    <p class="text-xs font-bold uppercase tracking-wider {{ $card['textClass'] }}">{{ $card['label'] }}</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $card['count'] }}</p>
                </a>
            @endforeach
        </div>

        {{-- Filters --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 lg:p-6">
            <form method="GET" action="{{ route('trashed-users.index') }}">
                <input type="hidden" name="role" value="{{ $role }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">
                    <div class="relative lg:col-span-5">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Ism, familiya, telefon..."
                            class="w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none text-sm">
                        @if ($search)
                            <a href="{{ route('trashed-users.index', request()->except('search', 'page')) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <div class="lg:col-span-4">
                        <select name="gender"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">Jins (Barchasi)</option>
                            <option value="male" {{ $gender === 'male' ? 'selected' : '' }}>Erkak</option>
                            <option value="female" {{ $gender === 'female' ? 'selected' : '' }}>Ayol</option>
                        </select>
                    </div>

                    <div class="flex gap-2 lg:col-span-3">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Qidirish
                        </button>
                        <a href="{{ route('trashed-users.index') }}"
                            class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-500 dark:text-gray-400 rounded-xl transition-all flex items-center justify-center active:scale-95"
                            title="Tozalash">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Foydalanuvchi</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Rol</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Telefon</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                O'chirilgan vaqt</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">

                                {{-- Foydalanuvchi --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-10 w-10 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                            {{ mb_substr($user->name ?? '', 0, 1) }}{{ mb_substr($user->surname ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $user->full_name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $user->city ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Rol --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg {{ $user->roleInfo['class'] }}">
                                        {{ $user->roleInfo['icon'] }} {{ $user->roleInfo['label'] }}
                                    </span>
                                </td>

                                {{-- Telefon --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $user->phone ?? '-' }}</span>
                                </td>

                                {{-- O'chirilgan vaqt --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $user->deleted_at?->format('d.m.Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->deleted_at?->diffForHumans() }}</div>
                                </td>

                                {{-- Amallar --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Restore button --}}
                                        <button type="button"
                                            onclick="document.getElementById('restoreModal-{{ $user->id }}').classList.remove('hidden'); document.body.style.overflow='hidden'"
                                            class="p-2 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl transition-all"
                                            title="Tiklash">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>

                                        {{-- Force Delete button --}}
                                        <button type="button"
                                            onclick="document.getElementById('forceDeleteModal-{{ $user->id }}').classList.remove('hidden'); document.body.style.overflow='hidden'"
                                            class="p-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all"
                                            title="Butunlay o'chirish">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Restore Modal --}}
                            <div id="restoreModal-{{ $user->id }}"
                                class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                                <div
                                    class="relative w-full max-w-md p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5">
                                    <div class="text-center">
                                        <div
                                            class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-emerald-50 dark:bg-emerald-500/10 mb-6">
                                            <svg class="h-10 w-10 text-emerald-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                                            Foydalanuvchini tiklash</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                                            <span
                                                class="font-bold text-slate-800 dark:text-white">{{ $user->full_name }}</span>
                                            ni tiklaysizmi?
                                        </p>
                                        <div class="flex gap-3">
                                            <button type="button"
                                                onclick="document.getElementById('restoreModal-{{ $user->id }}').classList.add('hidden'); document.body.style.overflow='auto'"
                                                class="flex-1 px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                                                Bekor qilish
                                            </button>
                                            <form action="{{ route('trashed-users.restore', $user->id) }}" method="POST"
                                                class="flex-1">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full px-5 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all active:scale-95">
                                                    Ha, tiklash
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Force Delete Modal --}}
                            <div id="forceDeleteModal-{{ $user->id }}"
                                class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                                <div
                                    class="relative w-full max-w-md p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5">
                                    <div class="text-center">
                                        <div
                                            class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 dark:bg-rose-500/10 mb-6">
                                            <svg class="h-10 w-10 text-rose-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Butunlay
                                            o'chirish</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                                            <span
                                                class="font-bold text-slate-800 dark:text-white">{{ $user->full_name }}</span>
                                            ni butunlay o'chirmoqchimisiz?
                                        </p>
                                        <div
                                            class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-2xl p-4 mb-6">
                                            <p class="text-xs text-rose-800 dark:text-rose-300 font-semibold">
                                                ⚠️ Bu amalni qaytarib bo'lmaydi! Barcha ma'lumotlar butunlay o'chiriladi.
                                            </p>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button"
                                                onclick="document.getElementById('forceDeleteModal-{{ $user->id }}').classList.add('hidden'); document.body.style.overflow='auto'"
                                                class="flex-1 px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                                                Bekor qilish
                                            </button>
                                            <form action="{{ route('trashed-users.force-delete', $user->id) }}"
                                                method="POST" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full px-5 py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 dark:shadow-none transition-all active:scale-95">
                                                    Ha, o'chirish
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                        O'chirilgan foydalanuvchi topilmadi</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $search ? "\"$search\" bo'yicha hech narsa topilmadi" : "Hozircha o'chirilganlar yo'q" }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-rose-500/20 p-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Restore All Modal --}}
    <div id="restoreAllModal"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div
            class="relative w-full max-w-md p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5">
            <div class="text-center">
                <div
                    class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-emerald-50 dark:bg-emerald-500/10 mb-6">
                    <svg class="h-10 w-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Hammasini tiklash</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                    Barcha o'chirilgan foydalanuvchilarni tiklaysizmi?
                </p>
                <div class="flex gap-3">
                    <button type="button"
                        onclick="document.getElementById('restoreAllModal').classList.add('hidden'); document.body.style.overflow='auto'"
                        class="flex-1 px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                        Bekor qilish
                    </button>
                    <form action="{{ route('trashed-users.restore-all') }}" method="POST" class="flex-1">
                        @csrf
                        @if ($role && $role !== 'all')
                            <input type="hidden" name="role" value="{{ $role }}">
                        @endif
                        <button type="submit"
                            class="w-full px-5 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all active:scale-95">
                            Ha, tiklash
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection