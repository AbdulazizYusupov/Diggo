@extends('Layout.app')

@section('title', 'Foydalanuvchilar va Rollar')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30 border-b-2 border-indigo-800/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Foydalanuvchilar va Rollar
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Foydalanuvchilarga rollarni boshqaring
                    </p>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">

            {{-- Jami foydalanuvchilar --}}
            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200/60 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-4 border border-blue-200 dark:border-blue-700/50 shadow-md">
                <div class="flex flex-col">
                    <p class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider mb-1">Jami</p>
                    <p class="text-2xl font-extrabold text-blue-900 dark:text-blue-50">{{ $stats['total_users'] }}</p>
                </div>
            </div>

            {{-- O'qituvchilar --}}
            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-4 border border-purple-200 dark:border-purple-700/50 shadow-md">
                <div class="flex flex-col">
                    <p class="text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider mb-1">
                        O'qituvchilar</p>
                    <p class="text-2xl font-extrabold text-purple-900 dark:text-purple-50">{{ $stats['total_teachers'] }}
                    </p>
                </div>
            </div>

            {{-- O'quvchilar --}}
            <div
                class="bg-gradient-to-br from-green-100 to-green-200/60 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl p-4 border border-green-200 dark:border-green-700/50 shadow-md">
                <div class="flex flex-col">
                    <p class="text-xs font-bold text-green-700 dark:text-green-400 uppercase tracking-wider mb-1">
                        O'quvchilar</p>
                    <p class="text-2xl font-extrabold text-green-900 dark:text-green-50">{{ $stats['total_students'] }}</p>
                </div>
            </div>

            {{-- Xodimlar --}}
            <div
                class="bg-gradient-to-br from-orange-100 to-orange-200/60 dark:from-orange-900/30 dark:to-orange-800/30 rounded-2xl p-4 border border-orange-200 dark:border-orange-700/50 shadow-md">
                <div class="flex flex-col">
                    <p class="text-xs font-bold text-orange-700 dark:text-orange-400 uppercase tracking-wider mb-1">Xodimlar
                    </p>
                    <p class="text-2xl font-extrabold text-orange-900 dark:text-orange-50">{{ $stats['total_staff'] }}</p>
                </div>
            </div>

            {{-- Direktorlar --}}
            <div
                class="bg-gradient-to-br from-pink-100 to-pink-200/60 dark:from-pink-900/30 dark:to-pink-800/30 rounded-2xl p-4 border border-pink-200 dark:border-pink-700/50 shadow-md">
                <div class="flex flex-col">
                    <p class="text-xs font-bold text-pink-700 dark:text-pink-400 uppercase tracking-wider mb-1">Direktorlar
                    </p>
                    <p class="text-2xl font-extrabold text-pink-900 dark:text-pink-50">{{ $stats['total_directors'] }}</p>
                </div>
            </div>

            {{-- Rolli foydalanuvchilar --}}
            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-4 border border-emerald-200 dark:border-emerald-700/50 shadow-md">
                <div class="flex flex-col">
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider mb-1">Rolli
                    </p>
                    <p class="text-2xl font-extrabold text-emerald-900 dark:text-emerald-50">
                        {{ $stats['users_with_roles'] }}</p>
                </div>
            </div>

            {{-- Rolsiz foydalanuvchilar --}}
            <div
                class="bg-gradient-to-br from-red-100 to-red-200/60 dark:from-red-900/30 dark:to-red-800/30 rounded-2xl p-4 border border-red-200 dark:border-red-700/50 shadow-md">
                <div class="flex flex-col">
                    <p class="text-xs font-bold text-red-700 dark:text-red-400 uppercase tracking-wider mb-1">Rolsiz</p>
                    <p class="text-2xl font-extrabold text-red-900 dark:text-red-50">{{ $stats['users_without_roles'] }}</p>
                </div>
            </div>

            {{-- Jami Rollar (Indigo Soft uslubida) --}}
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-2xl p-4 border border-indigo-200 dark:border-indigo-700/50 shadow-md">
                <div class="flex flex-col">
                    <p class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-wider mb-1">Jami
                        Rollar</p>
                    <p class="text-2xl font-extrabold text-indigo-900 dark:text-indigo-50">{{ $stats['total_roles'] }}</p>
                </div>
            </div>

        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <form action="{{ route('settings.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

                    {{-- Search - Kengroq qismini egallaydi (5 ustun) --}}
                    <div class="relative group lg:col-span-5">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Ism, familiya yoki telefon..."
                            class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>

                    {{-- Role Filter (2 ustun) --}}
                    <div class="lg:col-span-2">
                        <select name="role"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                            <option value="">Barcha rollar</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ $roleFilter == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Type Filter (2 ustun) --}}
                    <div class="lg:col-span-2">
                        <select name="type"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                            <option value="">Barcha turlar</option>
                            <option value="teacher" {{ $typeFilter == 'teacher' ? 'selected' : '' }}>O'qituvchilar</option>
                            <option value="student" {{ $typeFilter == 'student' ? 'selected' : '' }}>O'quvchilar</option>
                            <option value="staff" {{ $typeFilter == 'staff' ? 'selected' : '' }}>Xodimlar</option>
                            <option value="director" {{ $typeFilter == 'director' ? 'selected' : '' }}>Direktorlar</option>
                        </select>
                    </div>

                    {{-- Buttons (3 ustun) --}}
                    <div class="lg:col-span-3 flex gap-2">
                        <button type="submit"
                            class="group flex-1 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-indigo-200 dark:shadow-none flex items-center justify-center">
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <span class="ml-2 lg:hidden xl:inline">Qidirish</span>
                        </button>

                        @if ($search || $roleFilter || $typeFilter)
                            <a href="{{ route('settings.index') }}"
                                class="group px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all flex items-center justify-center"
                                title="Tozalash">
                                <svg class="w-5 h-5 transition-transform duration-500 group-hover:rotate-180"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>

        {{-- Users Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($users as $user)
                <div
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-300">
                    {{-- User Info --}}
                    <div class="flex items-start gap-3 mb-4">
                        <div class="flex-shrink-0">
                            @if ($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->full_name }}"
                                    class="w-12 h-12 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-700">
                            @else
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center border-2 border-indigo-200 dark:border-indigo-700">
                                    <span class="text-white font-bold text-lg">
                                        {{ mb_substr($user->name, 0, 1) }}{{ mb_substr($user->surname, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white truncate"
                                title="{{ $user->full_name }}">
                                {{ $user->full_name }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ $user->phone }}
                            </p>

                            {{-- User Type Badge --}}
                            <div class="flex flex-wrap gap-1 mt-2">
                                @if ($user->teacher)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs font-semibold rounded-lg">
                                        O'qituvchi
                                    </span>
                                @endif
                                @if ($user->student)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold rounded-lg">
                                        O'quvchi
                                    </span>
                                @endif
                                @if ($user->staff)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-semibold rounded-lg">
                                        Xodim
                                    </span>
                                @endif
                                @if ($user->director)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400 text-xs font-semibold rounded-lg">
                                        Direktor
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Current Roles --}}
                    <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">
                            Hozirgi rollar:
                        </p>
                        @if ($user->roles->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($user->roles as $role)
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-bold rounded-lg border border-indigo-200/50 dark:border-indigo-800/50">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-lg border border-gray-200 dark:border-gray-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                Rolsiz
                            </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="space-y-2">
                        <button onclick='openManageRolesModal(@json($user))'
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 dark:border-indigo-500/40 dark:text-indigo-300 font-semibold rounded-xl transition-all shadow-sm active:scale-[0.95]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Rollarni boshqarish
                        </button>

                        @if ($user->roles->isNotEmpty())
                            <button onclick='openRemoveAllRolesModal(@json($user))'
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 dark:bg-red-500/10 dark:hover:bg-red-500/20 dark:border-red-500/40 dark:text-red-400 font-medium rounded-xl transition-all shadow-sm active:scale-[0.95]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Barcha rollarni olib tashlash
                            </button>
                        @endif
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
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hech qanday foydalanuvchi
                            topilmadi</h3>
                        <p class="text-gray-500 dark:text-gray-400">Filterni o'zgartiring yoki tozalang</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-4 mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Manage Roles Modal --}}
    <div id="manageRolesModal"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] flex flex-col transform transition-all overflow-hidden">

            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div
                        class="p-2.5 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-xl">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Rollarni boshqarish</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400" id="manageUserName"></p>
                    </div>
                </div>
                <button onclick="closeManageRolesModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="manageRolesForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto space-y-4 custom-scrollbar">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 px-1">
                            Rollarni tanlang:
                        </label>
                        <div class="grid gap-2">
                            @foreach ($roles as $role)
                                <label
                                    class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl cursor-pointer transition-all">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                    <span
                                        class="text-sm font-medium text-gray-900 dark:text-white">{{ $role->name }}</span>
                                    <span
                                        class="ml-auto text-xs text-gray-500 dark:text-gray-400">({{ $role->permissions_count }}
                                        ruxsat)</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div
                        class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800/30">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-blue-700 dark:text-blue-300">
                                Tanlangan rollar foydalanuvchining hozirgi rollarini to'liq almashtiradi.
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex items-center justify-end gap-3 flex-shrink-0">
                    <button type="button" onclick="closeManageRolesModal()"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">
                        Yopish
                    </button>

                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-md shadow-indigo-200 dark:shadow-none transition-all">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Remove All Roles Modal --}}
    <div id="removeAllRolesModal"
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

                <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-2">Barcha rollarni olib tashlash
                </h3>
                <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                    <span id="removeAllUserName" class="font-semibold text-gray-900 dark:text-white"></span>
                    dan barcha rollarni olib tashlashni xohlaysizmi? Bu amalni qaytarib bo'lmaydi.
                </p>

                <form id="removeAllRolesForm" method="POST" class="space-y-4">
                    @csrf

                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeRemoveAllRolesModal()"
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
        // Manage Roles Modal
        function openManageRolesModal(user) {
            const modal = document.getElementById('manageRolesModal');
            const form = document.getElementById('manageRolesForm');

            form.action = `/settings/sync-roles/${user.id}`;
            document.getElementById('manageUserName').textContent = user.full_name;

            // Reset checkboxes
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = user.roles.some(role => role.name === checkbox.value);
            });

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeManageRolesModal() {
            document.getElementById('manageRolesModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Remove All Roles Modal
        function openRemoveAllRolesModal(user) {
            const modal = document.getElementById('removeAllRolesModal');
            const form = document.getElementById('removeAllRolesForm');

            form.action = `/settings/sync-roles/${user.id}`;
            document.getElementById('removeAllUserName').textContent = user.full_name;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRemoveAllRolesModal() {
            document.getElementById('removeAllRolesModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeManageRolesModal();
                closeRemoveAllRolesModal();
            }
        });

        // Close modals on backdrop click
        document.getElementById('manageRolesModal').addEventListener('click', function(e) {
            if (e.target === this) closeManageRolesModal();
        });

        document.getElementById('removeAllRolesModal').addEventListener('click', function(e) {
            if (e.target === this) closeRemoveAllRolesModal();
        });
    </script>
@endsection
