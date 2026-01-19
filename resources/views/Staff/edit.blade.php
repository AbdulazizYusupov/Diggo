@extends('Layout.app')

@section('title', "Yangi xodim qo'shish")

@section('content')
    <div class="w-full">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-5">
                <div
                    class="hidden sm:flex p-4 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl shadow-inner border border-indigo-200 dark:border-indigo-800/50">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>

                <div class="space-y-1.5">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                            Tahrirlash: <span class="text-indigo-600 dark:text-indigo-400">{{ $staff->name }}
                                {{ $staff->surname }}</span>
                        </h1>
                        @if ($staff->user->is_active)
                            <span
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold uppercase tracking-wider border border-emerald-200 dark:border-emerald-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active
                            </span>
                        @else
                            <span
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-[11px] font-bold uppercase tracking-wider border border-gray-200 dark:border-gray-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                Inactive
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Oxirgi tahrir: {{ $staff->updated_at->format('d.m.Y, H:i') }}
                        </p>
                        <span class="hidden md:inline text-gray-300 dark:text-gray-600">|</span>
                        <p
                            class="text-sm font-semibold text-indigo-500 dark:text-indigo-400 uppercase tracking-widest text-[11px]">
                            ID: #{{ $staff->id }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('staffs.index') }}"
                    class="group inline-flex items-center px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-2xl shadow-sm hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2.5 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Bekor qilish
                </a>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('staffs.update', $staff->id) }}" method="POST" enctype="multipart/form-data"
                class="p-8 space-y-8">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
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
                            <input type="text" name="surname" value="{{ old('surname', $staff->user->surname) }}"
                                placeholder="Karimov"
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
                            <input type="text" name="name" value="{{ old('name', $staff->user->name) }}"
                                placeholder="Azizbek"
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
                                value="{{ old('middle_name', $staff->user->middle_name) }}" placeholder="Anvar o'g'li"
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
                                <input type="text" name="phone" value="{{ old('phone', $staff->user->phone) }}"
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
                            <label
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1 flex items-center gap-1">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tug'ilgan sana
                            </label>
                            <input type="date" name="birth_date" {{-- format('Y-m-d') HTML5 date input uchun shart --}}
                                value="{{ old('birth_date', $staff->user->birth_date ? \Carbon\Carbon::parse($staff->user->birth_date)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/40 border rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-gray-700 outline-none dark:text-white transition-all duration-200
                                @error('birth_date') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">

                            @error('birth_date')
                                <p class="text-red-500 text-xs mt-1 ml-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
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
                                    {{ old('gender', $staff->user->gender) == 'male' ? 'selected' : '' }}>Erkak
                                </option>
                                <option value="female"
                                    {{ old('gender', $staff->user->gender) == 'female' ? 'selected' : '' }}>Ayol
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Manzil qismi --}}
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
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Yashash manzili</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Viloyat</label>
                            <input type="text" name="region" value="{{ old('region', $staff->user->region) }}"
                                placeholder="Toshkent viloyati"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                    @error('region') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Shahar / Tuman</label>
                            <input type="text" name="city" value="{{ old('city', $staff->user->city) }}"
                                placeholder="Nurafshon sh."
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                    @error('city') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Ko'cha va uy</label>
                            <input type="text" name="address" value="{{ old('address', $staff->user->address) }}"
                                placeholder="Mustaqillik ko'chasi, 12-uy"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-amber-500 outline-none dark:text-white transition-all
                    @error('address') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                        </div>
                    </div>
                </div>

                {{-- Maosh shartlari --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Ish va Maosh shartlari</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Maosh turi <span
                                    class="text-red-500">*</span></label>
                            <select name="salary_type" id="salary_type_select" required onchange="updateSalaryFields()"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-indigo-500 dark:text-white outline-none transition-all
                    @error('salary_type') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <option value="fixed"
                                    {{ old('salary_type', $staff->salary_type) == 'fixed' ? 'selected' : '' }}>Qat'iy
                                    (Fixed)</option>
                                <option value="percent"
                                    {{ old('salary_type', $staff->salary_type) == 'percent' ? 'selected' : '' }}>Foizli
                                    (Percentage)</option>
                                <option value="per_student"
                                    {{ old('salary_type', $staff->salary_type) == 'per_student' ? 'selected' : '' }}>
                                    Har bir talaba uchun</option>
                            </select>
                        </div>

                        <div id="salary_input_container"
                            class="space-y-2 animate-fadeIn {{ old('salary_type', $staff->salary_type) == 'percent' ? 'hidden' : '' }}">
                            <label id="salary_label"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Maosh miqdori
                                (so'mda)</label>
                            <input type="number" name="salary_amount"
                                value="{{ old('salary_type', $staff->salary_amount) }}" placeholder="0"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none dark:text-white transition-all
                    @error('salary_amount') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('salary_amount')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="percent_fields"
                        class="{{ $staff->salary_type == 'percent' ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-6 animate-slideDown">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Talaba uchun (%)</label>
                            <input type="number" name="percent_per_student"
                                value="{{ old('percent_per_student', $staff->percent_per_student) }}"
                                placeholder="Masalan: 40"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-purple-500 outline-none dark:text-white transition-all
                            @error('percent_per_student') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('percent_per_student')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">To'lovdan (%)</label>
                            <input type="number" name="percent_per_payment"
                                value="{{ old('percent_per_payment', $staff->percent_per_payment) }}"
                                placeholder="Masalan: 10"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-purple-500 outline-none dark:text-white transition-all
                            @error('percent_per_payment') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                            @error('percent_per_payment')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Hisob ma'lumotlari --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Hisob ma'lumotlari</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Xodim surati</label>
                            <div class="flex items-center justify-center w-full">
                                <div class="relative w-full">
                                    @php
                                        $photoPath = $staff->photo ?? ($staff->user->photo ?? null);
                                    @endphp

                                    <div id="preview-box"
                                        class="{{ $photoPath ? '' : 'hidden' }} relative w-full h-32 rounded-2xl overflow-hidden border-2 border-indigo-500">
                                        <img id="image-display"
                                            src="{{ $photoPath ? asset('storage/' . $photoPath) : '' }}"
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
                                        class="{{ $photoPath ? 'hidden' : '' }} flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-2xl cursor-pointer bg-gray-50 dark:hover:bg-gray-700/50 dark:bg-gray-700 hover:bg-gray-100 transition-all
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
                                <input type="password" name="password" placeholder="••••••••"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none dark:text-white transition-all
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
                                    {{ old('is_active', $staff->user->is_active) == '1' ? 'checked' : '' }}
                                    class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Xodim
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
                                            Bu yerda Xodim faol tizimga kira oladi ni belgilamasangiz Xodim hech
                                            qachon login qila olmaydi!!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">

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

            </form>
        </div>
    </div>

    <script>
        function updateSalaryFields() {
            const type = document.getElementById('salary_type_select').value;
            const container = document.getElementById('salary_input_container');
            const label = document.getElementById('salary_label');
            const percentFields = document.getElementById('percent_fields');

            if (type === 'percent') {
                container.classList.add('hidden');
                percentFields.classList.remove('hidden');
            } else {
                container.classList.remove('hidden');
                percentFields.classList.add('hidden');

                if (type === 'fixed') {
                    label.innerText = "Qat'iy maosh miqdori (so'm)";
                } else {
                    label.innerText = "Har bir talaba uchun to'lov (so'm)";
                }
            }
        }

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

            // Input qiymatini tozalash
            photoInput.value = "";
            // Previewni yashirish
            imageDisplay.src = "";
            previewBox.classList.add('hidden');
            // Yuklash tugmasini qaytarish
            uploadLabel.classList.remove('hidden');
        }
    </script>
@endsection
