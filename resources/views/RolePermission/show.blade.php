@extends('Layout.app')

@section('title', 'Rol ruxsatlari - ' . $role->name)

@php
    use App\Helpers\PermissionLabel;
@endphp

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('role-permissions.index') }}"
                    class="p-2.5 inline-flex items-center justify-center bg-slate-100 border border-slate-200 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 dark:bg-slate-800 dark:border-slate-700 dark:text-gray-400 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 dark:hover:border-indigo-500/30 rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div class="flex items-center gap-4">
                    <div
                        class="hidden sm:flex p-3 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-2xl shadow-sm">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            {{ $role->name }}
                        </h1>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-semibold text-indigo-600/80 dark:text-indigo-400/80">Jami
                                {{ $role->permissions->count() }} ta ruxsat</span>
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('role-permissions.edit', $role) }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 font-semibold dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 dark:border-indigo-500/30 dark:text-indigo-400 rounded-xl shadow-sm transition-all duration-200 active:scale-95 group">
                <svg class="w-5 h-5 text-indigo-500 group-hover:scale-110 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Ruxsatlarni tahrirlash</span>
            </a>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div
                class="bg-indigo-100/80 dark:bg-indigo-500/10 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-500/30 shadow-sm transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-indigo-700 dark:text-indigo-400">Jami
                            ruxsatlar</p>
                        <p class="text-3xl font-black text-indigo-900 dark:text-indigo-50 mt-1">
                            {{ $role->permissions->count() }}
                        </p>
                    </div>
                    <div
                        class="p-3 bg-indigo-600 dark:bg-indigo-500/20 text-white dark:text-indigo-400 rounded-xl shadow-sm border border-indigo-700 dark:border-indigo-500/40">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-sky-100/80 dark:bg-sky-500/10 rounded-2xl p-6 border border-sky-200 dark:border-sky-500/30 shadow-sm transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-sky-700 dark:text-sky-400">Modullar</p>
                        <p class="text-3xl font-black text-sky-900 dark:text-sky-50 mt-1">
                            {{ $groupedPermissions->count() }}
                        </p>
                    </div>
                    <div
                        class="p-3 bg-sky-600 dark:bg-sky-500/20 text-white dark:text-sky-400 rounded-xl shadow-sm border border-sky-700 dark:border-sky-500/40">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-emerald-100/80 dark:bg-emerald-500/10 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-500/30 shadow-sm transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-emerald-700 dark:text-emerald-400">
                            Foydalanuvchilar</p>
                        <p class="text-3xl font-black text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ $role->users()->count() }}
                        </p>
                    </div>
                    <div
                        class="p-3 bg-emerald-600 dark:bg-emerald-500/20 text-white dark:text-emerald-400 rounded-xl shadow-sm border border-emerald-700 dark:border-emerald-500/40">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions by Module --}}
        @if ($role->permissions->count() > 0)
            <div class="space-y-4">
                @foreach ($groupedPermissions as $group)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-800 overflow-hidden transition-all">

                        {{-- Module Header --}}
                        <div
                            class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-700/50 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="p-2.5 bg-indigo-100 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-500/30 rounded-xl text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">
                                            {{ $group['moduleLabel'] }}
                                        </h3>
                                        <p class="text-xs font-mono text-gray-400 dark:text-gray-500 mt-0.5">
                                            {{ $group['module'] }}
                                        </p>
                                    </div>
                                </div>

                                <span
                                    class="px-4 py-1.5 bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 text-sm font-bold rounded-xl border border-indigo-200 dark:border-indigo-500/30">
                                    {{ $group['count'] }} ta
                                </span>
                            </div>
                        </div>

                        {{-- Permission Items --}}
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                @foreach ($group['items'] as $item)
                                    <div
                                        class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700/50 group hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-colors">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 group-hover:scale-110 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 block"
                                                title="{{ $item['label'] }}">
                                                {{ $item['label'] }}
                                            </span>
                                            <span
                                                class="text-xs font-mono text-gray-400 dark:text-gray-500 block truncate mt-0.5"
                                                title="{{ $item['name'] }}">
                                                {{ $item['name'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border-2 border-dashed border-gray-300 dark:border-gray-700 p-12 text-center">
                <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Bu rolda hech qanday ruxsat yo'q
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Ushbu rolga ruxsatlar berishni boshlang
                </p>
                <a href="{{ route('role-permissions.edit', $role->id) }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white border border-indigo-400/50 shadow-sm shadow-indigo-100 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:border-indigo-500/30 dark:shadow-none font-semibold rounded-xl transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] group">
                    <svg class="w-5 h-5 text-white/90 group-hover:rotate-90 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Ruxsatlar qo'shish</span>
                </a>
            </div>
        @endif
    </div>
@endsection
