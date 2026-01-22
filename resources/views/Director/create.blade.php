@extends('Layout.app')

@section('title', "Yangi direktor qo'shish")
@push('styles')
    <style>
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
@endpush
@section('content')
    <div class="w-full max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div
                        class="hidden sm:flex p-3.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-800/50">
                        <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
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
                            Tizimga yangi direktor qo'shish uchun barcha ma'lumotlarni to'ldiring
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

        <!-- Main Form -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('directors.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 lg:p-10 space-y-10">
                @csrf

                <!-- Section 1: Personal Information -->
                <div class="space-y-6">
                    <div
                        class="flex items-center justify-between pb-5 border-b-2 border-indigo-100 dark:border-indigo-900/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-3 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Shaxsiy ma'lumotlar</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Direktorning asosiy ma'lumotlari
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Surname -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Familiya
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="surname" value="{{ old('surname') }}" required placeholder="Karimov"
                                class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-white outline-none transition-all
                                @error('surname') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('surname')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Ism
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Azizbek"
                                class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-white outline-none transition-all
                                @error('name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Otasining ismi
                            </label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                placeholder="Anvarovich"
                                class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-white outline-none transition-all
                                @error('middle_name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('middle_name')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Phone -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Telefon raqami
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-4 flex items-center text-gray-500 dark:text-gray-400 font-semibold">+998</span>
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                    placeholder="(90) 123-45-67"
                                    class="w-full pl-16 pr-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-white outline-none transition-all
                                    @error('phone') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Birth Date -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tug'ilgan sana
                            </label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-white outline-none transition-all
                                @error('birth_date') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('birth_date')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Jinsi
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="gender" required
                                class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 dark:text-white outline-none transition-all
                                @error('gender') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Erkak</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Ayol</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
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

                <!-- Section 2: Address Information -->
                <div class="space-y-6">
                    <div
                        class="flex items-center justify-between pb-5 border-b-2 border-amber-100 dark:border-amber-900/30">
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
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Direktorning to'liq manzili</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Region -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                Viloyat
                            </label>
                            <input type="text" name="region" value="{{ old('region') }}"
                                placeholder="Toshkent viloyati"
                                class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 dark:text-white outline-none transition-all
                                @error('region') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('region')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Shahar / Tuman
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                placeholder="Nurafshon sh."
                                class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 dark:text-white outline-none transition-all
                                @error('city') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Ko'cha va uy
                            </label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                placeholder="Mustaqillik ko'chasi, 12-uy"
                                class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 dark:text-white outline-none transition-all
                                @error('address') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('address')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
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

                <!-- Section 3: School & Director Details -->
                <div class="space-y-6">
                    <div
                        class="flex items-center justify-between pb-5 border-b-2 border-orange-100 dark:border-orange-900/30">
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
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Direktorni maktabga biriktirish
                                    (ixtiyoriy)</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 space-y-2">
                        <label
                            class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Maktabni tanlang
                        </label>

                        <div class="relative" id="schoolCustomSelect">
                            <div onclick="toggleSchoolDropdown()"
                                class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer group hover:border-orange-400 transition-all">
                                <span id="selectedSchoolLabel" class="text-sm text-gray-500 dark:text-gray-400">--
                                    Maktabni qidiring va tanlang --</span>
                                <input type="hidden" name="school_id" id="school_id_input">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-transform"
                                    id="schoolArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <div id="schoolDropdown"
                                class="hidden absolute z-[110] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                <div class="p-2 border-b dark:border-gray-700">
                                    <input type="text" id="schoolSearchInput" oninput="filterSchools()"
                                        placeholder="Maktab nomini yozing..."
                                        class="w-full px-3 py-2 bg-gray-100 dark:bg-slate-900 dark:text-white border-none rounded-lg text-sm focus:ring-1 focus:ring-orange-500">
                                </div>
                                <div class="max-h-48 overflow-y-auto custom-scrollbar" id="schoolOptionsList">
                                    <div onclick="selectSchool('', '-- Maktab tanlanmagan --')"
                                        class="px-4 py-2 text-sm text-gray-500 hover:bg-orange-50 dark:hover:bg-orange-500/10 cursor-pointer">
                                        -- Tanlamaslik --</div>
                                    @foreach ($schools as $school)
                                        <div onclick="selectSchool('{{ $school->id }}', '{{ $school->name }}')"
                                            class="school-option px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-orange-50 dark:hover:bg-orange-500/10 cursor-pointer flex flex-col">
                                            <span class="font-bold school-name">{{ $school->name }}</span>
                                            @if ($school->address)
                                                <span
                                                    class="text-[10px] text-gray-400">{{ Str::limit($school->address, 40) }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-800 mb-4">
                </div>

                <!-- Section 4: Financial Information -->
                <div class="space-y-6">
                    <div
                        class="flex items-center justify-between pb-5 border-b-2 border-emerald-100 dark:border-emerald-900/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/40 dark:to-emerald-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Moliyaviy ma'lumotlar</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Komissiya va balans sozlamalari
                                </p>
                            </div>
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
                                <input type="number" name="commission_percentage" step="0.01" min="0"
                                    max="100" value="{{ old('commission_percentage', 0) }}" required
                                    placeholder="0.00"
                                    class="w-full pl-5 pr-14 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none dark:text-white transition-all text-base font-semibold
                                    @error('commission_percentage') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">%</span>
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
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">so'm</span>
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
                </div>

                <!-- Section 5: Account Information -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-5 border-b-2 border-blue-100 dark:border-blue-900/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-3 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/40 dark:to-cyan-900/40 rounded-2xl">
                                <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hisob ma'lumotlari</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rasm va parol sozlamalari</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <!-- Photo Upload -->
                        <div class="space-y-3">
                            <label
                                class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Direktor surati
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <div class="relative w-full">
                                    <div id="preview-box"
                                        class="hidden relative w-full h-48 rounded-2xl overflow-hidden border-2 border-blue-500">
                                        <img id="image-display" src="" class="w-full h-full object-cover">
                                        <button type="button" onclick="resetImage()"
                                            class="absolute top-2 right-2 p-1.5 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <label id="upload-label"
                                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-2xl cursor-pointer bg-gray-50 dark:hover:bg-gray-700/50 dark:bg-gray-700 hover:bg-gray-100 transition-all
                                        @error('photo') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                                    class="font-semibold">Yuklash uchun bosing</span></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG (Maks. 2MB)</p>
                                        </div>
                                        <input type="file" name="photo" id="photo-input" class="hidden"
                                            accept="image/*" onchange="showPreview(this)" />
                                    </label>
                                </div>
                            </div>
                            @error('photo')
                                <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password & Status -->
                        <div class="space-y-6">
                            <div class="space-y-3">
                                <label
                                    class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Tizimga kirish paroli
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" required placeholder="••••••••"
                                    class="w-full px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-700/50 border-2 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 outline-none dark:text-white transition-all
                                    @error('password') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1 ml-1 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div
                                class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800/30">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Ma'lumot</p>
                                        <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                            Parol kamida 8 ta belgidan iborat bo'lishi kerak. Direktor telefon raqami orqali
                                            tizimga kiradi.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-3 p-5 bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700/30 dark:to-blue-900/20 rounded-2xl border-2 border-blue-100 dark:border-blue-800/30">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                <label for="is_active"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                    Direktor faol (Tizimga kira oladi)
                                </label>
                            </div>
                            <div
                                class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-800/30">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-400">Eslatma</p>
                                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                                            Bu yerda Director faol tizimga kira oladi ni belgilamasangiz Director hech
                                            qachon login qila olmaydi!!
                                        </p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4 pt-6 border-t-2 border-gray-100 dark:border-gray-700">

                    {{-- Tozalash tugmasi --}}
                    <button type="reset"
                        class="group w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-2xl shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600 hover:shadow-md transform hover:scale-[1.02] active:scale-95 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-180 transition-transform duration-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Tozalash
                    </button>

                    {{-- Saqlash tugmasi --}}
                    <button type="submit"
                        class="group w-full sm:w-auto inline-flex items-center justify-center px-10 py-3.5 text-sm font-bold text-white bg-indigo-500 hover:bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-100 dark:shadow-none transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Direktorni saqlash</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            function showPreview(input) {
                const previewBox = document.getElementById('preview-box');
                const uploadLabel = document.getElementById('upload-label');
                const imageDisplay = document.getElementById('image-display');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        imageDisplay.src = e.target.result;
                        previewBox.classList.remove('hidden');
                        uploadLabel.classList.add('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            function resetImage() {
                const previewBox = document.getElementById('preview-box');
                const uploadLabel = document.getElementById('upload-label');
                const photoInput = document.getElementById('photo-input');
                const imageDisplay = document.getElementById('image-display');

                photoInput.value = "";
                imageDisplay.src = "";
                previewBox.classList.add('hidden');
                uploadLabel.classList.remove('hidden');
            }
            // School Select Logic
            function toggleSchoolDropdown() {
                const dropdown = document.getElementById('schoolDropdown');
                const arrow = document.getElementById('schoolArrow');
                dropdown.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
                if (!dropdown.classList.contains('hidden')) {
                    document.getElementById('schoolSearchInput').focus();
                }
            }

            function selectSchool(id, name) {
                document.getElementById('school_id_input').value = id;
                document.getElementById('selectedSchoolLabel').innerText = name;
                document.getElementById('selectedSchoolLabel').classList.replace('text-gray-500', 'text-gray-900');
                document.getElementById('selectedSchoolLabel').classList.add('dark:text-white');
                toggleSchoolDropdown();
            }

            function filterSchools() {
                const input = document.getElementById('schoolSearchInput');
                const filter = input.value.toLowerCase();
                const options = document.querySelectorAll('.school-option');

                options.forEach(option => {
                    const text = option.querySelector('.school-name').innerText.toLowerCase();
                    if (text.includes(filter)) {
                        option.style.display = "";
                    } else {
                        option.style.display = "none";
                    }
                });
            }

            // Modal ochilganda dropdown yopilishi uchun
            function closeAddModal() {
                document.getElementById('add-modal').classList.add('hidden');
                document.getElementById('schoolDropdown').classList.add('hidden');
            }

            // Dropdown tashqarisiga bosilganda yopish
            document.addEventListener('click', function(event) {
                const isClickInside = document.getElementById('schoolCustomSelect').contains(event.target);
                if (!isClickInside) {
                    document.getElementById('schoolDropdown').classList.add('hidden');
                    document.getElementById('schoolArrow').classList.remove('rotate-180');
                }
            });
        </script>
    @endpush

@endsection
