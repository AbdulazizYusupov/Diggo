@extends('Layout.app')

@section('title', 'Yangi kurs qo\'shish')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Back Button --}}
                <a href="{{ route('courses.index') }}"
                    class="p-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>

                {{-- Icon Box --}}
                <div
                    class="hidden sm:flex p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg shadow-purple-200 dark:shadow-none">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Yangi kurs qo'shish
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        Yangi o'quv kursini tizimga qo'shish
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 overflow-hidden">
                <div class="p-6 lg:p-8 space-y-6">
                    <!-- Basic Information Section -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Asosiy ma'lumotlar</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Course Title -->
                            <div class="lg:col-span-2">
                                <label for="title"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Kurs nomi <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none @error('title') border-rose-500 dark:border-rose-500 @enderror"
                                    placeholder="Masalan: Web Dasturlash asoslari">
                                @error('title')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Course Code -->
                            <div>
                                <label for="code" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Kurs kodi <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="code" id="code" value="{{ old('code') }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none font-mono @error('code') border-rose-500 dark:border-rose-500 @enderror"
                                    placeholder="Masalan: WEB-101">
                                @error('code')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Course Level -->
                            <div>
                                <label for="level" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Kurs darajasi <span class="text-rose-500">*</span>
                                </label>
                                <select name="level" id="level" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white transition-all outline-none cursor-pointer @error('level') border-rose-500 dark:border-rose-500 @enderror">
                                    <option value="">Darajani tanlang</option>
                                    <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>
                                        🟢 Boshlang'ich
                                    </option>
                                    <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>
                                        🟡 O'rta
                                    </option>
                                    <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>
                                        🔴 Ilg'or
                                    </option>
                                </select>
                                @error('level')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="lg:col-span-2">
                                <label for="description"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Kurs tavsifi
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none resize-none @error('description') border-rose-500 dark:border-rose-500 @enderror"
                                    placeholder="Kurs haqida qisqacha ma'lumot...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>

                    <!-- Course Details Section -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Kurs tafsilotlari</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Kurs narxi (so'm) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" required
                                        min="0" step="0.01"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none @error('price') border-rose-500 dark:border-rose-500 @enderror"
                                        placeholder="500000">
                                </div>
                                @error('price')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Duration -->
                            <div>
                                <label for="duration_months"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Davomiyligi (oylar) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="number" name="duration_months" id="duration_months"
                                        value="{{ old('duration_months') }}" required min="1" max="36"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none @error('duration_months') border-rose-500 dark:border-rose-500 @enderror"
                                        placeholder="6">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">1 dan 36 oygacha</p>
                                @error('duration_months')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="lg:col-span-2">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" id="is_active" value="1"
                                            {{ old('is_active') ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="w-14 h-7 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600">
                                        </div>
                                    </div>
                                    <div>
                                        <span
                                            class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                            Kursni faollashtirish
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Faol kurslar tizimda ko'rinadi va talabalar yozilishi mumkin
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="px-6 lg:px-8 py-4 bg-gray-50 dark:bg-slate-800/50 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between gap-4">
                    <a href="{{ route('courses.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Bekor qilish
                    </a>

                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-lg shadow-purple-200 dark:shadow-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Kurs qo'shish
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection