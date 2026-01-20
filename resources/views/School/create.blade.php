@extends('Layout.app')

@section('title', "Yangi maktab qo'shish")

@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-800/50">
                    <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Yangi maktab
                        </h1>
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"
                            title="Yangi kiritish rejimi"></span>
                    </div>

                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Tizimga yangi ta'lim muassasasini qo'shish
                        </p>
                        <span
                            class="hidden md:inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 rounded-md border border-emerald-100 dark:border-emerald-800">
                            Step 1/1
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('schools.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Orqaga qaytish
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('schools.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Asosiy ma'lumotlar -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Maktab ma'lumotlari</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Maktab nomi --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">
                                Maktab nomi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                placeholder="Masalan: 25-son maktab"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none dark:text-white transition-all
                                @error('name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Maktab kodi --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">
                                Maktab kodi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" value="{{ old('code') }}" required
                                placeholder="Masalan: SCH-001"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none dark:text-white transition-all
                                @error('code') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('code')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                Noyob identifikator kod
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Telefon --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">
                                Telefon raqami
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">+998</span>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    placeholder=" (71) 123-45-67"
                                    class="w-full pl-14 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none dark:text-white transition-all
                                    @error('phone') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">
                                Holat
                            </label>
                            <div
                                class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-600">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                    class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Maktab faol
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manzil ma'lumotlari -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div class="p-2 bg-amber-50 dark:bg-amber-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Joylashuv manzili</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Viloyat --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Viloyat</label>
                            <input type="text" name="region" value="{{ old('region') }}"
                                placeholder="Toshkent viloyati"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                                @error('region') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('region')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Shahar/Tuman --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Shahar / Tuman</label>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Toshkent sh."
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                                @error('city') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('city')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ko'cha --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Ko'cha va uy</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                placeholder="Amir Temur ko'chasi, 25"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                                @error('address') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('address')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Qo'shimcha ma'lumot -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Qo'shimcha ma'lumot</h2>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Izoh va tavsif</label>
                        <textarea name="description" rows="4" placeholder="Maktab haqida qo'shimcha ma'lumotlar..."
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none dark:text-white transition-all resize-none
                            @error('description') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info card -->
                    <div
                        class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800/30">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Ma'lumot</p>
                                <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                    Maktab yaratilgandan so'ng, unga direktor tayinlash, kurslar va talabalar qo'shish
                                    mumkin bo'ladi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tugmalar -->
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
                        class="group w-full sm:w-auto inline-flex items-center justify-center px-10 py-3.5 text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 rounded-2xl shadow-xl shadow-emerald-100 dark:shadow-none transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Maktabni saqlash</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection