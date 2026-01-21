@extends('Layout.app')

@section('title', 'Rol va Ruxsatlar')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-blue-600 rounded-2xl shadow-lg shadow-blue-500/30 border-b-2 border-blue-800/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Rol va Ruxsatlar
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Rollarga ruxsatlarni biriktirishni boshqaring
                    </p>
                </div>
            </div>

            <button onclick="openCopyModal()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 sm:px-8 sm:py-3.5 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span class="text-sm sm:text-base tracking-wide whitespace-nowrap">Ruxsatlarni ko'chirish</span>
            </button>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-gradient-to-br from-amber-100 to-amber-200/60 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl p-6 border border-amber-200 dark:border-amber-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">
                            Jami rollar
                        </p>
                        <p class="text-3xl font-extrabold text-amber-900 dark:text-amber-50 mt-1">
                            {{ $stats['total_roles'] }}
                        </p>
                    </div>

                    <div
                        class="p-3 bg-amber-500 rounded-xl shadow-lg shadow-amber-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">
                            Jami ruxsatlar
                        </p>
                        <p class="text-3xl font-extrabold text-blue-900 dark:text-blue-50 mt-1">
                            {{ $stats['total_permissions'] }}
                        </p>
                    </div>

                    <div
                        class="p-3 bg-blue-600 rounded-xl shadow-lg shadow-blue-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                            Ruxsatli rollar
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ $stats['roles_with_permissions'] }}
                        </p>
                    </div>

                    <div
                        class="p-3 bg-emerald-600 rounded-xl shadow-lg shadow-emerald-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30 rounded-2xl p-6 border border-red-200 dark:border-red-700/50 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-red-700 dark:text-red-400 uppercase tracking-wider">
                            Ruxsatsiz rollar
                        </p>
                        <p class="text-3xl font-extrabold text-red-900 dark:text-red-50 mt-1">
                            {{ $stats['roles_without_permissions'] }}
                        </p>
                    </div>

                    <div
                        class="p-3 bg-red-600 rounded-xl shadow-lg shadow-red-300/50 dark:shadow-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <form action="{{ route('role-permissions.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input type="text" name="search" value="{{ $search }}" placeholder="Rol nomini qidiring..."
                        class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="group flex-1 sm:flex-none px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-blue-200 dark:shadow-none hover:-translate-y-0.5 flex items-center justify-center border-b-2 border-blue-800/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor"
                            class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>

                    @if ($search)
                        <a href="{{ route('role-permissions.index') }}"
                            class="group px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all flex items-center justify-center"
                            title="Tozalash">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                class="w-5 h-5 transition-transform duration-500 group-hover:rotate-180">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Roles Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($roles as $role)
                <div
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div
                                class="p-2.5 bg-blue-600 rounded-xl shadow-lg shadow-blue-200 dark:shadow-none group-hover:scale-110 transition-transform flex-shrink-0 border-b-2 border-blue-800/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-extrabold text-gray-900 dark:text-white truncate tracking-tight"
                                    title="{{ $role->name }}">
                                    {{ $role->name }}
                                </h3>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="flex h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                        {{ $role->users_count }} foydalanuvchi
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Role Stats --}}
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-sm">
                            <div class="p-1.5 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg shadow-sm">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <span class="font-extrabold text-gray-900 dark:text-white text-base">
                                    {{ $role->permissions_count }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 text-xs font-medium ml-0.5">ruxsat</span>
                            </div>
                        </div>

                        @if ($role->permissions_count > 0)
                            <span
                                class="ml-auto inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-lg border border-emerald-200/50 dark:border-emerald-800/50">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Faol
                            </span>
                        @else
                            <span
                                class="ml-auto inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-lg border border-gray-200 dark:border-gray-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                Bo'sh
                            </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    {{--  --}}
                    <div class="space-y-2">
                        <a href="{{ route('role-permissions.edit', $role->id) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 
          bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 
          dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 dark:border-indigo-500/40 dark:hover:border-indigo-500/60 dark:text-indigo-300 
          font-semibold rounded-xl transition-all shadow-sm active:scale-[0.95]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Ruxsatlarni boshqarish
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('role-permissions.show', $role->id) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 
          bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 
          dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 dark:border-emerald-500/40 dark:hover:border-emerald-500/60 dark:text-emerald-300 
          font-medium rounded-xl transition-all shadow-sm active:scale-[0.95]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Ko'rish
                            </a>

                            @if ($role->permissions_count > 0)
                                <button onclick='openRevokeModal(@json($role))'
                                    class="px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 
           dark:bg-red-500/10 dark:hover:bg-red-500/20 dark:border-red-500/40 dark:hover:border-red-500/60 dark:text-red-400 
           font-medium rounded-xl transition-all shadow-sm active:scale-[0.95]"
                                    title="Barcha ruxsatlarni olib tashlash">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div
                        class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-12 text-center">
                        <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            @if ($search)
                                "{{ $search }}" bo'yicha hech narsa topilmadi
                            @else
                                Hech qanday rol topilmadi
                            @endif
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            @if ($search)
                                Boshqa so'z bilan qidirib ko'ring yoki filterni tozalang
                            @else
                                Avval rollar yarating
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>


        @if ($roles->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm
                border border-gray-200 dark:border-indigo-500/20
                p-4 mt-6">
                {{ $roles->links() }}
            </div>
        @endif

    </div>

    {{-- Copy Permissions Modal --}}
    <div id="copyModal"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div
                        class="p-2.5 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Ruxsatlarni ko'chirish
                    </h3>
                </div>
                <button onclick="closeCopyModal()"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            {{-- {{ route('role-permissions.copy') }} --}}
            <form action="{{ route('role-permissions.copy') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 px-1">
                        Qaysi roldan ko'chirish? <span class="text-red-500">*</span>
                    </label>
                    <select name="from_role_id" required
                        class="w-full px-4 py-2.5 
               bg-white dark:bg-slate-900 
               border border-gray-300 dark:border-indigo-500/30 
               text-gray-900 dark:text-gray-100 
               rounded-xl outline-none
               transition-all duration-200
               focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 
               dark:focus:ring-indigo-500/20 dark:focus:border-indigo-400
               cursor-pointer">
                        <option value="" class="dark:bg-slate-900">Rol tanlang...</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" class="dark:bg-slate-900">
                                {{ $role->name }} ({{ $role->permissions_count }} ruxsat)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-center py-2">
                    <div
                        class="p-2 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-full shadow-sm">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 px-1">
                        Qaysi rolga ko'chirish? <span class="text-red-500">*</span>
                    </label>
                    <select name="to_role_id" required
                        class="w-full px-4 py-2.5 
                        bg-white dark:bg-slate-900 
                        border border-gray-300 dark:border-indigo-500/30 
                        text-gray-900 dark:text-gray-100 
                        rounded-xl outline-none
                        transition-all duration-200
                        focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 
                        dark:focus:ring-indigo-500/20 dark:focus:border-indigo-400
                        cursor-pointer">
                        <option value="" class="dark:bg-slate-900">Rol tanlang...</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" class="dark:bg-slate-900">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div
                    class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800/30">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-800 dark:text-amber-400">Eslatma</p>
                            <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                                Bu amal maqsad roldagi barcha mavjud ruxsatlarni o'chiradi va yangilarini qo'shadi
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" onclick="closeCopyModal()"
                        class="group flex items-center gap-2 px-6 py-2.5 
               bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 
               border border-slate-200 dark:border-slate-700
               text-slate-700 dark:text-slate-300 
               font-semibold rounded-xl transition-all duration-300 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5 transition-transform duration-500 group-hover:rotate-90">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        <span>Yopish</span>
                    </button>

                    <button type="submit"
                        class="group flex items-center gap-2 px-6 py-2.5 
               bg-indigo-600 hover:bg-indigo-700 text-white 
               dark:bg-indigo-500 dark:hover:bg-indigo-600
               font-semibold rounded-xl shadow-md shadow-indigo-200 dark:shadow-none 
               active:scale-95 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span>Ko'chirish</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Revoke All Permissions Modal --}}
    <div id="revokeModal"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div
                    class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 dark:bg-red-900/30 rounded-full">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">Barcha ruxsatlarni olib
                    tashlash</h3>
                <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                    <span id="revokeRoleName" class="font-semibold text-gray-900 dark:text-white"></span>
                    rolidan barcha ruxsatlarni olib tashlashni xohlaysizmi? Bu amalni qaytarib bo'lmaydi.
                </p>

                <form id="revokeForm" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeRevokeModal()"
                            class="flex-1 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all">
                            Bekor qilish
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                            Olib tashlash
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Copy Modal
        function openCopyModal() {
            document.getElementById('copyModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCopyModal() {
            document.getElementById('copyModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Revoke Modal
        function openRevokeModal(role) {
            const modal = document.getElementById('revokeModal');
            const form = document.getElementById('revokeForm');

            form.action = `/role-permissions/${role.id}/revoke-all`;
            document.getElementById('revokeRoleName').textContent = role.name;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRevokeModal() {
            document.getElementById('revokeModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCopyModal();
                closeRevokeModal();
            }
        });

        // Close modals on backdrop click
        document.getElementById('copyModal').addEventListener('click', function(e) {
            if (e.target === this) closeCopyModal();
        });

        document.getElementById('revokeModal').addEventListener('click', function(e) {
            if (e.target === this) closeRevokeModal();
        });
    </script>
@endsection
