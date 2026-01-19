@extends('Layout.app')

@section('title', "Direktor: {$director->name}")

@section('content')
    <div class="w-full max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="hidden sm:flex p-4 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white">
                                Direktor profili
                            </h1>
                            @if ($director->user->is_active)
                                <span
                                    class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                    Faol
                                </span>
                            @else
                                <span
                                    class="px-3 py-1 bg-gradient-to-r from-gray-400 to-gray-500 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                    Nofaol
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            To'liq ma'lumotlar va statistika
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('directors.edit', $director->id) }}"
                        class="group inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Tahrirlash
                    </a>

                    <a href="{{ route('directors.index') }}"
                        class="group inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-gray-600 bg-white border-2 border-gray-200 rounded-2xl shadow-sm hover:bg-gray-50 hover:text-purple-600 hover:border-purple-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Orqaga
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Profile Card -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="relative h-32 bg-gradient-to-br from-purple-500 via-indigo-600 to-purple-700">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                            <div class="relative">
                                @if ($director->user->photo)
                                    <img src="{{ asset('storage/' . $director->user->photo) }}"
                                        alt="{{ $director->user->name }}"
                                        class="w-32 h-32 rounded-2xl object-cover border-4 border-white dark:border-gray-800 shadow-xl">
                                @else
                                    <div
                                        class="w-32 h-32 rounded-2xl bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900 dark:to-indigo-900 border-4 border-white dark:border-gray-800 shadow-xl flex items-center justify-center">
                                        <svg class="w-16 h-16 text-purple-600 dark:text-purple-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                                <div
                                    class="absolute -bottom-1 -right-1 w-8 h-8 bg-{{ $director->user->is_active ? 'green' : 'gray' }}-500 rounded-full border-4 border-white dark:border-gray-800">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-20 pb-6 px-6 text-center">
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-1">
                            {{ $director->user->surname }} {{ $director->user->name }}
                        </h2>
                        @if ($director->user->middle_name)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $director->user->middle_name }}</p>
                        @endif

                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Direktor</span>
                        </div>
                    </div>

                    <div class="px-6 pb-6 space-y-3">
                        <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Telefon</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">+998 {{ $director->user->phone }}
                                </p>
                            </div>
                        </div>

                        @if ($director->user->birth_date)
                            <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/40 rounded-lg">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Tug'ilgan sana</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($director->user->birth_date)->format('d.m.Y') }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="p-2 bg-pink-100 dark:bg-pink-900/40 rounded-lg">
                                <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Jinsi</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $director->user->gender == 'male' ? 'Erkak' : 'Ayol' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Stats Card -->
                <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-3xl shadow-2xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold">Moliyaviy ma'lumot</h3>
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-emerald-100 mb-1">Joriy balans</p>
                            <p class="text-3xl font-black">{{ number_format($director->balance, 0, ',', ' ') }} <span
                                    class="text-lg">so'm</span></p>
                        </div>

                        <div class="h-px bg-white/20"></div>

                        <div>
                            <p class="text-sm text-emerald-100 mb-1">Komissiya foizi</p>
                            <p class="text-2xl font-black">{{ $director->commission_percentage }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Address Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b-2 border-amber-100 dark:border-amber-900/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-3 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/40 dark:to-orange-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Yashash manzili</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Direktorning to'liq manzili</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div
                            class="p-4 bg-gradient-to-br from-gray-50 to-amber-50 dark:from-gray-700/30 dark:to-amber-900/10 rounded-2xl">
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Viloyat</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $director->user->region ?: 'Ko\'rsatilmagan' }}</p>
                        </div>

                        <div
                            class="p-4 bg-gradient-to-br from-gray-50 to-amber-50 dark:from-gray-700/30 dark:to-amber-900/10 rounded-2xl">
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Shahar / Tuman</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $director->user->city ?: 'Ko\'rsatilmagan' }}</p>
                        </div>

                        <div
                            class="p-4 bg-gradient-to-br from-gray-50 to-amber-50 dark:from-gray-700/30 dark:to-amber-900/10 rounded-2xl">
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Ko'cha va uy</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $director->user->address ?: 'Ko\'rsatilmagan' }}</p>
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b-2 border-orange-100 dark:border-orange-900/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-3 bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/40 dark:to-red-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Maktab ma'lumotlari</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Biriktirilgan maktab</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if ($director->school)
                            <div
                                class="flex items-start gap-4 p-6 bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-2xl border-2 border-orange-200 dark:border-orange-800/30">
                                <div class="p-3 bg-orange-100 dark:bg-orange-900/40 rounded-xl">
                                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $director->school->name }}</h3>
                                    @if ($director->school->address)
                                        <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>{{ $director->school->address }}</span>
                                        </div>
                                    @endif
                                    <a href=""
                                        class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all">
                                        Maktabni ko'rish
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700/50 rounded-2xl mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">Hech qanday maktab biriktirilmagan
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- System Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b-2 border-blue-100 dark:border-blue-900/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-3 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/40 dark:to-cyan-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tizim ma'lumotlari</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Hisob va kirish ma'lumotlari</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            class="p-5 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-700/30 dark:to-blue-900/10 rounded-2xl">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Holat</p>
                            </div>
                            <p class="text-lg font-black text-gray-900 dark:text-white">
                                @if ($director->user->is_active)
                                    <span class="text-green-600 dark:text-green-400">✓ Faol</span>
                                @else
                                    <span class="text-gray-500">○ Nofaol</span>
                                @endif
                            </p>
                        </div>

                        <div
                            class="p-5 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-700/30 dark:to-blue-900/10 rounded-2xl">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Qo'shilgan sana</p>
                            </div>
                            <p class="text-lg font-black text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($director->created_at)->format('d.m.Y') }}</p>
                        </div>

                        <div
                            class="p-5 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-700/30 dark:to-blue-900/10 rounded-2xl">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Oxirgi yangilanish
                                </p>
                            </div>
                            <p class="text-lg font-black text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($director->updated_at)->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
