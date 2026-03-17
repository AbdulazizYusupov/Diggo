@extends('Layout.app')

@section('title', "Yangi o'quvchi qo'shish")

@section('content')
    <div class="w-full">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-800/50">
                    <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Yangi o'quvchi
                        </h1>
                        <span class="flex h-2 w-2 rounded-full bg-cyan-500 animate-pulse"
                            title="Yangi kiritish rejimi"></span>
                    </div>

                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Tizimga yangi o'quvchini qo'shish va guruhga biriktirish
                        </p>
                        <span
                            class="hidden md:inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-cyan-600 bg-cyan-50 dark:bg-cyan-900/20 rounded-md border border-cyan-100 dark:border-cyan-800">
                            Step 1/1
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('students.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Orqaga qaytish
            </a>
        </div>
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                {{-- Shaxsiy ma'lumotlar (User) --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div
                            class="p-2 bg-gradient-to-br from-pink-50 to-rose-50 dark:from-pink-900/30 dark:to-rose-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Shaxsiy ma'lumotlar</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Familiya --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Familiya <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="surname" value="{{ old('surname') }}" required placeholder="Aliyev"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-pink-500 outline-none dark:text-white transition-all 
                            @error('surname') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('surname')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ism --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Ism <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Aziz"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-pink-500 outline-none dark:text-white transition-all
                            @error('name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Otasining ismi --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Otasining ismi</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                placeholder="Rustamovich"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-pink-500 outline-none dark:text-white transition-all
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
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                    placeholder=" (90) 123-45-67"
                                    class="w-full pl-14 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-pink-500 outline-none dark:text-white transition-all
                            @error('phone') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tug'ilgan sana --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Tug'ilgan sana <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-pink-500 dark:text-white outline-none transition-all
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
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-pink-500 dark:text-white outline-none transition-all
                            @error('gender') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>O'g'il bola</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Qiz bola</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Manzil qismi --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div
                            class="p-2 bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/30 dark:to-cyan-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Yashash manzili</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Viloyat</label>
                            <input type="text" name="region" value="{{ old('region') }}"
                                placeholder="Toshkent viloyati"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-teal-500 outline-none dark:text-white transition-all
                    @error('region') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Shahar / Tuman</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                placeholder="Chilonzor tumani"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-teal-500 outline-none dark:text-white transition-all
                    @error('city') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Ko'cha va uy</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                placeholder="Bunyodkor ko'chasi, 12-uy"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-teal-500 outline-none dark:text-white transition-all
                    @error('address') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                    </div>
                </div>

                {{-- Ota-ona va favqulodda aloqa --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div
                            class="p-2 bg-gradient-to-br from-violet-50 to-purple-50 dark:from-violet-900/30 dark:to-purple-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Ota-ona va favqulodda aloqa</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Ota-onaning ismi --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Ota-onaning to'liq
                                ismi</label>
                            <input type="text" name="parent_name" value="{{ old('parent_name') }}"
                                placeholder="Aliyev Rustam Karimovich"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-violet-500 outline-none dark:text-white transition-all
                            @error('parent_name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('parent_name')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ota-onaning telefoni --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Ota-onaning telefon
                                raqami</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">+998</span>
                                <input type="text" name="parent_phone" value="{{ old('parent_phone') }}"
                                    placeholder=" (90) 123-45-67"
                                    class="w-full pl-14 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-violet-500 outline-none dark:text-white transition-all
                            @error('parent_phone') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            </div>
                            @error('parent_phone')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Favqulodda aloqa ismi --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Favqulodda holat uchun
                                aloqa (ismi)</label>
                            <input type="text" name="emergency_contact_name"
                                value="{{ old('emergency_contact_name') }}" placeholder="Karimova Nodira"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-violet-500 outline-none dark:text-white transition-all
                            @error('emergency_contact_name') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('emergency_contact_name')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Favqulodda aloqa telefoni --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Favqulodda holat uchun
                                telefon</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">+998</span>
                                <input type="text" name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone') }}" placeholder=" (91) 234-56-78"
                                    class="w-full pl-14 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-violet-500 outline-none dark:text-white transition-all
                            @error('emergency_contact_phone') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            </div>
                            @error('emergency_contact_phone')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Ta'lim va moliyaviy ma'lumotlar --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div
                            class="p-2 bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/30 dark:to-amber-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Ta'lim va moliyaviy ma'lumotlar
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Ro'yxatdan o'tgan sana --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Ro'yxatdan o'tgan sana
                                <span class="text-red-500">*</span></label>
                            <input type="date" name="enrollment_date"
                                value="{{ old('enrollment_date', date('Y-m-d')) }}" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-orange-500 dark:text-white outline-none transition-all
                            @error('enrollment_date') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('enrollment_date')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Dastlabki balans --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Dastlabki balans
                                (so'm)</label>
                            <input type="number" name="balance" value="{{ old('balance', 0) }}" min="0"
                                step="0.01" placeholder="0"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none dark:text-white transition-all
                            @error('balance') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('balance')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Dastlabki tangalar --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Dastlabki
                                tangalar</label>
                            <input type="number" name="coins" value="{{ old('coins', 0) }}" min="0"
                                placeholder="0"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none dark:text-white transition-all
                            @error('coins') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('coins')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Qo'shimcha izoh</label>
                        <textarea name="notes" rows="3" placeholder="O'quvchi haqida qo'shimcha ma'lumotlar..."
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none dark:text-white transition-all resize-none
                        @error('notes') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Guruh tanlash --}}
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="school_id" class="text-xs font-semibold text-gray-700 dark:text-gray-300 ml-1">
                            Schoolni tanlang
                        </label>

                        <select name="school_id" id="school_id" @class([
                            'w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border rounded-xl cursor-pointer text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all shadow-sm',
                            'border-red-500' => $errors->has('school_id'),
                            'border-gray-200 dark:border-gray-600' => !$errors->has('school_id'),
                        ])>

                            <option value="">-- Schoolni tanlang --</option>

                            @foreach ($schools ?? [] as $school)
                                <option value="{{ $school->id }}" @selected(old('school_id', $teacher->school_id ?? '') == $school->id)>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('school_id')
                            <p class="text-red-500 text-[10px] mt-1 ml-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Rasm va parol --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div
                            class="p-2 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Hisob ma'lumotlari</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">O'quvchi surati</label>
                            <div class="flex items-center justify-center w-full">
                                <div class="relative w-full">
                                    <div id="preview-box"
                                        class="hidden relative w-full h-32 rounded-2xl overflow-hidden border-2 border-cyan-500">
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
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-2xl cursor-pointer bg-gray-50 dark:hover:bg-gray-700/50 dark:bg-gray-700 hover:bg-gray-100 transition-all
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
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Tizimga kirish
                                    paroli <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required placeholder="••••••••"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-cyan-500 outline-none dark:text-white transition-all
                                @error('password') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
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
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                    class="w-5 h-5 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                                <label for="is_active"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">O'quvchi
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
                                            Bu yerda O'quvchi faol tizimga kira oladi ni belgilamasangiz o'quvchi hech
                                            qachon login qila olmaydi!!
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

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
                        <span>O'quvchini saqlash</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            // Dropdownni ochish/yopish
            function toggleTeacherSchoolDropdown() {
                const menu = document.getElementById('teacherSchoolMenu');
                const arrow = document.getElementById('teacherSchoolArrow');
                const isHidden = menu.classList.contains('hidden');

                // Barcha dropdownlarni yopish
                document.querySelectorAll('[id$="Menu"]').forEach(m => {
                    if (m.id !== 'teacherSchoolMenu') m.classList.add('hidden');
                });

                menu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');

                if (isHidden) {
                    document.getElementById('teacherSchoolSearchInput').focus();
                }
            }

            // Maktabni tanlash
            function selectTeacherSchool(id, name) {
                document.getElementById('teacher_school_id_input').value = id;
                document.getElementById('selectedTeacherSchoolLabel').innerText = name;
                toggleTeacherSchoolDropdown();
            }

            // Qidiruv funksiyasi
            function filterTeacherSchools() {
                const input = document.getElementById('teacherSchoolSearchInput');
                const filter = input.value.toLowerCase();
                const items = document.querySelectorAll('.teacher-school-item');
                const noResult = document.getElementById('noTeacherSchoolFound');
                let hasResults = false;

                items.forEach(item => {
                    const name = item.querySelector('.teacher-item-name').innerText.toLowerCase();
                    if (name.includes(filter)) {
                        item.style.display = "";
                        hasResults = true;
                    } else {
                        item.style.display = "none";
                    }
                });

                noResult.classList.toggle('hidden', hasResults);
            }

            // Tashqariga bosilganda yopish
            document.addEventListener('click', function(e) {
                const container = document.getElementById('teacherSchoolCustomContainer');
                if (container && !container.contains(e.target)) {
                    document.getElementById('teacherSchoolMenu').classList.add('hidden');
                    document.getElementById('teacherSchoolArrow').classList.remove('rotate-180');
                }
            });

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
            document.addEventListener("DOMContentLoaded", function() {
                new TomSelect("#school_id", {
                    plugins: ['dropdown_input'],
                    create: false,
                    allowEmptyOption: true,
                    placeholder: "Schoolni tanlang...",
                    
                });
            });
        </script>
    @endpush
@endsection
