@extends('Layout.index')

@section('title', "O'qituvchi tahrirlash")

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-4 bg-blue-100 dark:bg-blue-900/30 rounded-2xl shadow-inner border border-blue-200 dark:border-blue-800/50">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>

                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                            Tahrirlash: <span
                                class="text-blue-600 dark:text-blue-400">{{ $teacher->user->full_name }}</span>
                        </h1>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                        <p
                            class="text-xs lg:text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Oxirgi tahrir: {{ $teacher->updated_at->format('d.m.Y, H:i') }}
                        </p>
                        <span class="hidden md:inline text-gray-300 dark:text-gray-600">|</span>
                        <p
                            class="text-xs lg:text-sm font-semibold text-blue-500 dark:text-blue-400 uppercase tracking-widest">
                            ID: #{{ $teacher->id }}
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('teachers.index') }}"
                class="group inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-2xl shadow-sm hover:bg-gray-50 hover:text-blue-600 hover:border-blue-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 transition-all duration-300">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Orqaga qaytish
            </a>
        </div>

        <!-- Main Form -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data"
                class="p-6 lg:p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Teacher Info Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-indigo-100 dark:border-indigo-900/30">
                        <div class="p-2.5 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Shaxsiy ma'lumotlar</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">O'qituvchi asosiy ma'lumotlari</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Familiya --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Familiya <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="surname" value="{{ old('surname', $teacher->user->surname) }}"
                                required placeholder="Karimov"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none dark:text-white transition-all 
                            @error('surname') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('surname')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ism --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Ism <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $teacher->user->name) }}" required
                                placeholder="Jahongir"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none dark:text-white transition-all
                            @error('name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Otasining ismi --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Otasining ismi</label>
                            <input type="text" name="middle_name"
                                value="{{ old('middle_name', $teacher->user->middle_name) }}" placeholder="Karimovich"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none dark:text-white transition-all
                            @error('middle_name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('middle_name')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Telefon --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Telefon raqami <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">+998</span>
                                <input type="text" name="phone" value="{{ old('phone', $teacher->user->phone) }}"
                                    required placeholder=" (90) 123-45-67"
                                    class="w-full pl-14 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none dark:text-white transition-all
                            @error('phone') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tug'ilgan sana --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Tug'ilgan sana</label>
                            <input type="date" name="birth_date"
                                value="{{ old('birth_date', $teacher->user->birth_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-indigo-500 dark:text-white outline-none transition-all
                            @error('birth_date') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('birth_date')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jinsi --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Jinsi <span
                                    class="text-red-500">*</span></label>
                            <select name="gender" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-indigo-500 dark:text-white outline-none transition-all
                            @error('gender') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <option value="male"
                                    {{ old('gender', $teacher->user->gender) == 'male' ? 'selected' : '' }}>Erkak</option>
                                <option value="female"
                                    {{ old('gender', $teacher->user->gender) == 'female' ? 'selected' : '' }}>Ayol</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Manzil qismi --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-amber-100 dark:border-amber-900/30">
                        <div class="p-2.5 bg-amber-50 dark:bg-amber-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Yashash manzili</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manzil ma'lumotlarini yangilash</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Viloyat</label>
                            <input type="text" name="region" value="{{ old('region', $teacher->user->region) }}"
                                placeholder="Toshkent viloyati"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                    @error('region') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Shahar / Tuman</label>
                            <input type="text" name="city" value="{{ old('city', $teacher->user->city) }}"
                                placeholder="Chilonzor tumani"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                    @error('city') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Ko'cha va uy</label>
                            <input type="text" name="address" value="{{ old('address', $teacher->user->address) }}"
                                placeholder="Bunyodkor ko'chasi, 12-uy"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                    @error('address') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                    </div>
                </div>

                {{-- Maosh shartlari --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-emerald-100 dark:border-emerald-900/30">
                        <div class="p-2.5 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Maosh shartlari</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Maosh turi va miqdorini sozlash</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Maosh turi <span
                                    class="text-red-500">*</span></label>
                            <select name="salary_type" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-indigo-500 dark:text-white outline-none transition-all
                    @error('salary_type') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <option value="fixed"
                                    {{ old('salary_type', $teacher->salary_type) == 'fixed' ? 'selected' : '' }}>Qat'iy
                                    maosh</option>
                                <option value="hourly"
                                    {{ old('salary_type', $teacher->salary_type) == 'hourly' ? 'selected' : '' }}>Soatlik
                                </option>
                                <option value="per_group"
                                    {{ old('salary_type', $teacher->salary_type) == 'per_group' ? 'selected' : '' }}>Guruh
                                    uchun</option>
                                <option value="percent"
                                    {{ old('salary_type', $teacher->salary_type) == 'percent' ? 'selected' : '' }}>Foiz
                                    asosida</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Maosh miqdori (so'm)
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="salary_amount"
                                value="{{ old('salary_amount', $teacher->salary_amount) }}" required min="0"
                                step="0.01" placeholder="3000000"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none dark:text-white transition-all
                    @error('salary_amount') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('salary_amount')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Har bir talaba uchun
                            qo'shimcha foiz (%)</label>
                        <div class="relative">
                            <input type="number" name="percent_per_student"
                                value="{{ old('percent_per_student', $teacher->percent_per_student) }}" min="0"
                                max="100" step="0.01" placeholder="5"
                                class="w-full pl-4 pr-12 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-purple-500 outline-none dark:text-white transition-all
                            @error('percent_per_student') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            <span
                                class="absolute inset-y-0 right-4 flex items-center text-gray-500 dark:text-gray-400 font-semibold">%</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 ml-1">Agar kerak bo'lsa, har bir talaba uchun
                            qo'shimcha foiz kiriting</p>
                        @error('percent_per_student')
                            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div
                            class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-200 dark:border-blue-800/30">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase">Maosh
                                        turlari</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">Qat'iy, soatlik, guruh
                                        bo'yicha yoki foiz asosida maosh belgilang</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-2xl border border-purple-200 dark:border-purple-800/30">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/40 rounded-lg">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-purple-700 dark:text-purple-400 uppercase">
                                        Qo'shimcha foiz</p>
                                    <p class="text-xs text-purple-600 dark:text-purple-300 mt-1">Har bir talaba uchun
                                        qo'shimcha rag'batlantirish foizi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hisob ma'lumotlari --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-blue-100 dark:border-blue-900/30">
                        <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Hisob ma'lumotlari</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Profil va parol sozlamalari</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">O'qituvchi surati</label>
                            <div class="flex items-center justify-center w-full">
                                <div class="relative w-full">
                                    <div id="preview-box"
                                        class="{{ $teacher->user->photo ? '' : 'hidden' }} relative w-full h-32 rounded-2xl overflow-hidden border-2 border-indigo-500">
                                        <img id="image-display"
                                            src="{{ $teacher->user->photo ? asset('storage/' . $teacher->user->photo) : '' }}"
                                            class="w-full h-full object-cover">
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
                                        class="{{ $teacher->user->photo ? 'hidden' : '' }} flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-2xl cursor-pointer bg-gray-50 dark:hover:bg-gray-700/50 dark:bg-gray-700 hover:bg-gray-100 transition-all
                                        @error('photo') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG (Max. 2MB)</p>
                                        </div>
                                        <input type="file" name="photo" id="photo-input" class="hidden"
                                            accept="image/*" onchange="showPreview(this)" />
                                    </label>
                                </div>
                            </div>
                            @error('photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Yangi
                                    parol</label>
                                <input type="password"name="password" placeholder="••••••••"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none dark:text-white transition-all
                                @error('password') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <p class="text-xs text-gray-500 dark:text-gray-400 ml-1">Bo'sh qoldiring agar parolni
                                    o'zgartirmasangiz</p>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
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
                                            Parol kamida 8 ta belgidan iborat bo'lishi kerak. Staff telefon raqami orqali
                                            tizimga kiradi.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-600">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $teacher->user->is_active) ? 'checked' : '' }}
                                    class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="is_active"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">O'qituvchi
                                    faol (Tizimga kira oladi)</label>
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
                                            Bu yerda O'qituvchi faol tizimga kira oladi ni belgilamasangiz o'qituvchi hech
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
                    class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t-2 border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Oxirgi saqlash: {{ $teacher->updated_at->format('d.m.Y, H:i') }}
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('teachers.index') }}"
                            class="flex-1 sm:flex-initial px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all text-center">
                            Bekor qilish
                        </a>
                        <button type="submit"
                            class="group inline-flex items-center justify-center px-10 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 bg-size-200 bg-pos-0 hover:bg-pos-100 rounded-2xl shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 dark:shadow-purple-900/30 dark:hover:shadow-purple-900/50 transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Saqlash
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Additional Info -->
        <div
            class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-800/30">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-400">Eslatma</p>
                    <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                        O'qituvchi ma'lumotlarini ehtiyotkorlik bilan tahrirlang. Barcha o'zgarishlar darhol saqlangan
                        bo'ladi va tizimda aks etadi.
                    </p>
                </div>
            </div>
        </div>
    </div>

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
    </script>
@endsection
