@extends('Layout.index')

@section('title', "Yangi direktor qo'shish")

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div
                        class="hidden sm:flex p-4 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>

                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white">
                                Yangi direktor
                            </h1>
                            <span
                                class="px-3 py-1 bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                                Yaratish
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tizimga yangi direktor qo'shish uchun quyidagi ma'lumotlarni to'ldiring
                        </p>
                    </div>
                </div>

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

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 dark:bg-gray-700 -z-10"></div>

                <div class="flex-1 flex items-center">
                    <div class="flex flex-col items-center relative">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-full flex items-center justify-center font-bold shadow-lg">
                            1
                        </div>
                        <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 mt-2">Foydalanuvchi</span>
                    </div>
                </div>

                <div class="flex-1 flex items-center justify-center">
                    <div class="flex flex-col items-center relative">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 text-white rounded-full flex items-center justify-center font-bold shadow-lg">
                            2
                        </div>
                        <span class="text-xs font-semibold text-orange-600 dark:text-orange-400 mt-2">Maktab</span>
                    </div>
                </div>

                <div class="flex-1 flex items-center justify-end">
                    <div class="flex flex-col items-center relative">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold shadow-lg">
                            3
                        </div>
                        <span class="text-xs font-semibold text-green-600 dark:text-green-400 mt-2">Moliyaviy</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('directors.store') }}" method="POST" class="p-6 lg:p-10 space-y-10">
                @csrf

                <!-- Step 1: User Selection -->
                <div class="space-y-6">
                    <div
                        class="flex items-center justify-between pb-5 border-b-2 border-purple-100 dark:border-purple-900/30">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/40 dark:to-indigo-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Foydalanuvchi tanlash</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Direktor bo'ladigan foydalanuvchini
                                    tanlang</p>
                            </div>
                        </div>
                        <div
                            class="hidden sm:flex items-center justify-center w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                            <span class="text-2xl font-black text-purple-600 dark:text-purple-400">1</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Foydalanuvchi
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" id="user_select" required
                                class="w-full px-5 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 dark:text-white outline-none transition-all text-base font-medium
                                @error('user_id') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <option value="">-- Foydalanuvchini tanlang --</option>
                                {{-- @foreach (\App\Models\User::whereDoesntHave('director')->where('role', '!=', 'student')->orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->full_name }} - {{ $user->phone }}
                                        @if($user->role)
                                            ({{ ucfirst($user->role) }})
                                        @endif
                                    </option>
                                @endforeach --}}
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-sm mt-2 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <div
                                class="mt-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800/30">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Ma'lumot</p>
                                        <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                            Faqat direktor bo'lmagan va talaba emas foydalanuvchilar ro'yxatda ko'rsatiladi.
                                            Agar kerakli foydalanuvchi topilmasa, avval uni yaratishingiz kerak.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: School Selection -->
                <div class="space-y-6">
                    <div
                        class="flex items-center justify-between pb-5 border-b-2 border-orange-100 dark:border-orange-900/30">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/40 dark:to-red-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Maktab biriktirilishi</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Direktorni maktabga biriktiring
                                    (ixtiyoriy)</p>
                            </div>
                        </div>
                        <div
                            class="hidden sm:flex items-center justify-center w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full">
                            <span class="text-2xl font-black text-orange-600 dark:text-orange-400">2</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Maktab
                                <span class="text-xs text-gray-400 normal-case">(ixtiyoriy)</span>
                            </label>
                            <select name="school_id"
                                class="w-full px-5 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 dark:text-white outline-none transition-all text-base font-medium
                                @error('school_id') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <option value="">-- Maktab tanlanmagan --</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}"
                                        {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                        @if ($school->address)
                                            - {{ Str::limit($school->address, 50) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('school_id')
                                <p class="text-red-500 text-sm mt-2 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <div
                                class="mt-3 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800/30">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Diqqat</p>
                                        <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">
                                            Direktorni maktabga biriktirish majburiy emas. Keyinchalik istalgan vaqtda
                                            maktab biriktirishingiz mumkin.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Financial Information -->
                <div class="space-y-6">
                    <div
                        class="flex items-center justify-between pb-5 border-b-2 border-emerald-100 dark:border-emerald-900/30">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/40 dark:to-emerald-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Moliyaviy ma'lumotlar</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Komissiya va balans sozlamalari</p>
                            </div>
                        </div>
                        <div
                            class="hidden sm:flex items-center justify-center w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                            <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">3</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Commission Percentage -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Komissiya foizi
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="commission_percentage" step="0.01" min="0" max="100"
                                    value="{{ old('commission_percentage', 0) }}" required placeholder="0.00"
                                    class="w-full pl-5 pr-14 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none dark:text-white transition-all text-base font-semibold
                                    @error('commission_percentage') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span
                                        class="text-lg font-black text-emerald-600 dark:text-emerald-400">%</span>
                                </div>
                            </div>
                            @error('commission_percentage')
                                <p class="text-red-500 text-sm mt-2 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Har bir talaba to'lovidan olinadigan foiz
                            </p>
                        </div>

                        <!-- Initial Balance -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Boshlang'ich balans
                                <span class="text-xs text-gray-400 normal-case">(ixtiyoriy)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="balance" step="0.01" min="0"
                                    value="{{ old('balance', 0) }}" placeholder="0.00"
                                    class="w-full pl-5 pr-24 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none dark:text-white transition-all text-base font-semibold
                                    @error('balance') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span
                                        class="text-sm font-black text-emerald-600 dark:text-emerald-400">so'm</span>
                                </div>
                            </div>
                            @error('balance')
                                <p class="text-red-500 text-sm mt-2 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Dastlabki hisob balansi (sukut bo'yicha 0)
                            </p>
                        </div>
                    </div>

                    <!-- Info Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div
                            class="p-5 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/40 dark:to-cyan-900/40 rounded-xl border border-blue-200 dark:border-blue-800/30">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Komissiya haqida</p>
                                    <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                        Bu foiz har bir talaba to'lovidan avtomatik ravishda hisoblanadi va direktor
                                        balansiga qo'shiladi.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-5 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/40 dark:to-teal-900/40 rounded-xl border border-emerald-200 dark:border-emerald-800/30">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">Balans haqida
                                    </p>
                                    <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-1">
                                        Boshlang'ich balans direktorda mavjud bo'lgan dastlabki mablag'ni bildiradi.
                                        Keyinchalik to'lovlar orqali avtomatik yangilanadi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t-2 border-gray-100 dark:border-gray-700">
                    <button type="reset"
                        class="group inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-2xl shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600 hover:shadow-md transform hover:scale-[1.02] active:scale-95 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-180 transition-transform duration-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Tozalash
                    </button>

                    <button type="submit"
                        class="group inline-flex items-center justify-center px-10 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 bg-size-200 bg-pos-0 hover:bg-pos-100 rounded-2xl shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 dark:shadow-purple-900/30 dark:hover:shadow-purple-900/50 transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Direktorni saqlash
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Info Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="p-6 bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-2xl border border-purple-100 dark:border-purple-800/30">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/40 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Foydalanuvchi</h3>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Direktor sifatida ishlash uchun tizimda ro'yxatdan o'tgan foydalanuvchini tanlang
                </p>
            </div>

            <div
                class="p-6 bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-2xl border border-orange-100 dark:border-orange-800/30">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/40 rounded-lg">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Maktab</h3>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Direktorni biror maktabga biriktiring yoki keyinroq belgilashingiz mumkin
                </p>
            </div>

            <div
                class="p-6 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl border border-green-100 dark:border-green-800/30">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-green-100 dark:bg-green-900/40 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Moliyaviy</h3>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Komissiya foizi va boshlang'ich balansni belgilang
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .bg-size-200 {
            background-size: 200%;
        }

        .bg-pos-0 {
            background-position: 0%;
        }

        .bg-pos-100:hover {
            background-position: 100%;
        }
    </style>
@endsection