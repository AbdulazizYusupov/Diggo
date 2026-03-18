@extends('Layout.app')

@section('title', $school->name)

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <!-- Left: School Info -->
            <div class="flex items-start gap-4 lg:gap-6">
                {{-- School Icon --}}
                <div
                    class="flex-shrink-0 w-16 h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-3xl shadow-xl shadow-emerald-200 dark:shadow-none flex items-center justify-center transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    {{-- School Name & Status --}}
                    <div class="flex items-start gap-3 mb-2">
                        <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white">
                            {{ $school->name }}
                        </h1>
                        @if ($school->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 shadow-sm">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Faol
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Nofaol
                            </span>
                        @endif
                    </div>

                    {{-- School Code --}}
                    <div class="flex items-center gap-2 mb-3">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-bold rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-mono border border-gray-200 dark:border-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                            {{ $school->code }}
                        </span>
                    </div>

                    {{-- Location & Contact --}}
                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        @if ($school->address || $school->city || $school->region)
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-medium">
                                    {{ $school->address ?? '' }}{{ $school->address && ($school->city || $school->region) ? ', ' : '' }}{{ $school->city }}{{ $school->city && $school->region ? ', ' : '' }}{{ $school->region }}
                                </span>
                            </div>
                        @endif

                        @if ($school->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{{ $school->phone }}"
                                    class="font-medium hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                    {{ $school->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex items-center gap-2 lg:gap-3">
                <a href="{{ route('director.dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 lg:px-5 py-2.5 lg:py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Orqaga</span>
                </a>


            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            {{-- Jami talabalar --}}
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-500/5 rounded-2xl shadow-sm border border-blue-200 dark:border-blue-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400 mb-1">Jami talabalar
                </p>
                <p class="text-3xl lg:text-4xl font-black text-blue-600 dark:text-blue-400">
                    {{ number_format($stats['total_students'], 0, '.', ' ') }}
                </p>
            </div>

            {{-- Faol talabalar --}}
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-500/5 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-emerald-500 dark:bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-1">Faol
                    talabalar</p>
                <p class="text-3xl lg:text-4xl font-black text-emerald-600 dark:text-emerald-400">
                    {{ number_format($stats['active_students'], 0, '.', ' ') }}
                </p>
            </div>

            {{-- O'g'il talabalar --}}
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100/50 dark:from-indigo-500/10 dark:to-indigo-500/5 rounded-2xl shadow-sm border border-indigo-200 dark:border-indigo-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-indigo-500 dark:bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-indigo-600 dark:text-indigo-400 mb-1">O'g'il
                    talabalar</p>
                <p class="text-3xl lg:text-4xl font-black text-indigo-600 dark:text-indigo-400">
                    {{ number_format($stats['male_students'], 0, '.', ' ') }}
                </p>
            </div>

            {{-- Qiz talabalar --}}
            <div
                class="bg-gradient-to-br from-pink-50 to-pink-100/50 dark:from-pink-500/10 dark:to-pink-500/5 rounded-2xl shadow-sm border border-pink-200 dark:border-pink-500/20 p-5 lg:p-6 transition-all hover:shadow-md hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="w-12 h-12 bg-pink-500 dark:bg-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-pink-200 dark:shadow-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs uppercase tracking-wider font-bold text-pink-600 dark:text-pink-400 mb-1">Qiz talabalar
                </p>
                <p class="text-3xl lg:text-4xl font-black text-pink-600 dark:text-pink-400">
                    {{ number_format($stats['female_students'], 0, '.', ' ') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Director & Description -->
            <div class="lg:col-span-1 space-y-6">
                {{-- Director Card --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-black text-gray-900 dark:text-white">Maktab direktori</h2>
                    </div>

                    @if ($school->director && $school->director->user)
                        @php $user = $school->director->user; @endphp
                        <div class="space-y-4">
                            <div class="flex-shrink-0">
                                @if ($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->full_name }}"
                                        class="w-full h-48 rounded-2xl object-cover ring-4 ring-indigo-100 dark:ring-indigo-500/20 shadow-lg">
                                @else
                                    <div
                                        class="w-full h-48 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center ring-4 ring-indigo-100 dark:ring-indigo-500/20 shadow-lg">
                                        <span class="text-white font-black text-5xl">
                                            {{ mb_substr($user->name ?? 'D', 0, 1) }}{{ mb_substr($user->surname ?? '', 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
                                    {{ $user->full_name ?? 'Ism ko\'rsatilmadi' }}
                                </h3>
                                <div class="space-y-2">
                                    @if ($user->email)
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <a href="mailto:{{ $user->email }}"
                                                class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate">
                                                {{ $user->email }}
                                            </a>
                                        </div>
                                    @endif

                                    @if ($user->phone)
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <a href="tel:{{ $user->phone }}"
                                                class="font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                {{ $user->phone }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div
                                class="w-16 h-16 bg-amber-100 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Direktor tayinlanmagan</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tizimda direktor ma'lumotlari topilmadi</p>
                        </div>
                    @endif
                </div>

                {{-- Description Card --}}
                @if ($school->description)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Maktab haqida</h2>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                            {{ $school->description }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Right Column: Students List -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-6">
                    <!-- Students Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 dark:text-white">Talabalar</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $students->total() }} ta talaba topildi
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div
                        class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 mb-6 border border-gray-200 dark:border-gray-700">
                        <form method="GET" class="space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <!-- Search -->

                                <div class="relative md:col-span-2">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Ism, familiya yoki telefon..."
                                        class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-sm text-gray-900 dark:text-gray-100 outline-none placeholder:text-gray-400 dark:placeholder:text-gray-500">
                                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                                <!-- Status -->
                                <select name="status"
                                    class="px-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                                    <option value="">Barcha status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Faol
                                    </option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                        Nofaol</option>
                                </select>

                                <!-- Gender -->
                                <select name="gender"
                                    class="px-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                                    <option value="">Barcha jins</option>
                                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Erkak
                                    </option>
                                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Ayol
                                    </option>
                                </select>
                            </div>

                            <div class="flex items-center justify-between">
                                <!-- Sort -->
                                <select name="sort_by"
                                    class="px-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500">
                                    <option value="latest" {{ request('sort_by') === 'latest' ? 'selected' : '' }}>
                                        Yangilari</option>
                                    <option value="oldest" {{ request('sort_by') === 'oldest' ? 'selected' : '' }}>
                                        Eskilari</option>
                                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Ism
                                        bo'yicha</option>
                                </select>

                                <!-- Buttons -->
                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition-all active:scale-95">
                                        Qidirish
                                    </button>
                                    <a href="{{ route('director.school.show', $school->id) }}"
                                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold transition-all active:scale-95">
                                        Tozalash
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Students Grid -->
                    @if ($students->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            @foreach ($students as $student)
                                <div
                                    class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 border border-gray-200 dark:border-gray-700 transition-all group">
                                    <div class="flex-shrink-0">
                                        @if ($student->user->photo)
                                            <img src="{{ asset('storage/' . $student->user->photo) }}"
                                                alt="{{ $student->user->full_name }}"
                                                class="w-14 h-14 rounded-xl object-cover ring-2 ring-purple-100 dark:ring-purple-500/20">
                                        @else
                                            <div
                                                class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center ring-2 ring-purple-100 dark:ring-purple-500/20">
                                                <span class="text-white font-bold">
                                                    {{ substr($student->user->name, 0, 1) }}{{ substr($student->user->surname, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                            {{ $student->user->full_name }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $student->user->phone }}
                                            </span>
                                            @if ($student->user->is_active)
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-md bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                    <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Faol
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-md bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                                    <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Nofaol
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('director.students.show', $student->id) }}"
                                        class="flex-shrink-0 p-2 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-500/20 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($students->hasPages())
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                {{ $students->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Talabalar topilmadi</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Filtrlarni o'zgartiring yoki yangi talaba
                                qo'shing</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="delete-modal"
            class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
            <div
                class="relative w-full max-w-md p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5 transform transition-all">
                <div class="text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 dark:bg-rose-500/10 mb-6 rotate-3">
                        <svg class="h-10 w-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">O'chirishni tasdiqlang</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                        Rostdan ham <span
                            class="font-bold text-slate-900 dark:text-white underline decoration-rose-500/30">{{ $school->name }}</span>
                        maktabini tizimdan butunlay o'chirmoqchimisiz? Bu amalni ortga qaytarib bo'lmaydi!
                    </p>

                    @if ($stats['total_students'] > 0)
                        <div
                            class="mb-6 p-4 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-2xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="text-left">
                                    <p class="text-sm font-bold text-amber-900 dark:text-amber-300 mb-1">Diqqat!</p>
                                    <p class="text-xs text-amber-700 dark:text-amber-400">
                                        Bu maktabda <strong>{{ $stats['total_students'] }} ta talaba</strong> mavjud.
                                        O'chirishdan oldin talabalarni boshqa maktabga o'tkazing.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                            class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                            Bekor qilish
                        </button>
                        <form action="{{ route('schools.destroy', $school->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full px-5 py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 dark:shadow-none transition-all transform active:scale-95">
                                O'chirilsin
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
