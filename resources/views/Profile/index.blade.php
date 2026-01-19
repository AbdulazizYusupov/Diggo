@extends('Layout.app')

@section('title', 'Profil')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Shaxsiy profil
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ma'lumotlaringizni boshqaring
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Sidebar - User Info Card --}}
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
                    {{-- Profile Photo --}}
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative group">

                            {{-- Avatar --}}
                            <img id="profile-photo" data-has-photo="{{ $user->photo ? '1' : '0' }}"
                                src="{{ $user->photo ? asset('storage/' . $user->photo) : '' }}"
                                class="w-32 h-32 rounded-full object-cover shadow-lg ring-4 {{ $user->photo ? 'ring-gray-200 dark:ring-gray-700' : 'hidden' }}">

                            <div id="profile-placeholder"
                                class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-4xl shadow-lg ring-4 ring-gray-200 dark:ring-gray-700 {{ $user->photo ? 'hidden' : '' }}">
                                {{ strtoupper(substr($user->name, 0, 1) . substr($user->surname, 0, 1)) }}
                            </div>

                            {{-- Hover overlay --}}
                            <button type="button" onclick="document.getElementById('photo-input').click()"
                                class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                            </button>

                            {{-- REMOVE (X) --}}
                            <button id="remove-photo" onclick="removePhoto()"
                                class="absolute -top-1 -right-1 bg-red-600 text-white w-7 h-7 rounded-full flex items-center justify-center text-sm shadow-lg hidden">
                                ✕
                            </button>
                        </div>

                        <p id="photo-changed"
                            class="mt-2 text-sm text-emerald-600 dark:text-emerald-400 font-semibold hidden">
                            ✔ Rasm tanlandi
                        </p>

                        <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white text-center">
                            {{ $user->full_name }}
                        </h2>

                        @if ($user->roles->isNotEmpty())
                            <div
                                class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 dark:from-indigo-900/60 dark:to-purple-900/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 shadow-sm">
                                {{ $user->roles->first()->name }}
                            </div>
                        @endif

                        <div class="mt-2">
                            @if ($user->is_active)
                                <span class="inline-flex items-center gap-1.5 text-green-600 dark:text-green-400 text-sm">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    Faol
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-gray-500 text-sm">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                    Nofaol
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                        @if ($user->phone)
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Telefon</p>
                                    <p class="font-medium text-gray-900 dark:text-white truncate">{{ $user->phone }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($user->city)
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Shahar</p>
                                    <p class="font-medium text-gray-900 dark:text-white truncate">{{ $user->city }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($user->birth_date)
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Tug'ilgan kun</p>
                                    <p class="font-medium text-gray-900 dark:text-white truncate">
                                        {{ $user->birth_date->format('d.m.Y') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Content - Edit Form --}}
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Hidden Photo Input --}}
                        <input type="file" id="photo-input" name="photo" accept="image/jpeg,image/jpg,image/png"
                            class="hidden" onchange="previewPhoto(this)">
                        <input type="hidden" name="remove_photo" id="remove-photo-input" value="0">


                        {{-- Personal Information Section --}}
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Shaxsiy ma'lumotlar
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Surname --}}
                                <div>
                                    <label for="surname"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Familiya <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="surname" name="surname"
                                        value="{{ old('surname', $user->surname) }}" required
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('surname') border-red-500 @enderror">
                                    @error('surname')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Name --}}
                                <div>
                                    <label for="name"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Ism <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Middle Name --}}
                                <div>
                                    <label for="middle_name"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Otasining ismi
                                    </label>
                                    <input type="text" id="middle_name" name="middle_name"
                                        value="{{ old('middle_name', $user->middle_name) }}"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="phone"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Telefon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="phone" name="phone"
                                        value="{{ old('phone', $user->phone) }}" required
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Birth Date --}}
                                <div>
                                    <label for="birth_date"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Tug'ilgan kun
                                    </label>
                                    <input type="date" id="birth_date" name="birth_date"
                                        value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition-all">
                                </div>

                                {{-- Gender --}}
                                <div>
                                    <label for="gender"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Jinsi
                                    </label>
                                    <select id="gender" name="gender"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white cursor-pointer transition-all">
                                        <option value="">Tanlang</option>
                                        <option value="male"
                                            {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>
                                            Erkak</option>
                                        <option value="female"
                                            {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Ayol</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Address Information Section --}}
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Manzil ma'lumotlari
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Region --}}
                                <div>
                                    <label for="region"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Viloyat
                                    </label>
                                    <input type="text" id="region" name="region"
                                        value="{{ old('region', $user->region) }}"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                                </div>

                                {{-- City --}}
                                <div>
                                    <label for="city"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Shahar
                                    </label>
                                    <input type="text" id="city" name="city"
                                        value="{{ old('city', $user->city) }}"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                                </div>

                                {{-- Address --}}
                                <div class="sm:col-span-2">
                                    <label for="address"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Batafsil manzil
                                    </label>
                                    <textarea id="address" name="address" rows="3"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all resize-none">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Password Section --}}
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Parolni o'zgartirish
                            </h3>



                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- New Password --}}
                                <div>
                                    <label for="password"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Yangi parol
                                    </label>
                                    <input type="password" id="password" name="password"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all @error('password') border-red-500 @enderror">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Confirm Password --}}
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Parolni tasdiqlash
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                                </div>
                            </div>
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-4 mt-4">
                                <p class="text-sm text-blue-800 dark:text-blue-300 flex items-start gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Parolni o'zgartirish ixtiyoriy. Agar yangi parol kiritmagan bo'lsangiz, eski parol
                                        saqlanadi.</span>
                                </p>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div
                            class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ url()->previous() }}"
                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all shadow-sm hover:shadow-md">
                                Bekor qilish
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                                Saqlash
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('photo-input');
        const img = document.getElementById('profile-photo');
        const placeholder = document.getElementById('profile-placeholder');
        const badge = document.getElementById('photo-changed');
        const removeBtn = document.getElementById('remove-photo');
        const removePhotoInput = document.getElementById('remove-photo-input');


        // 🔑 DASTLABKI HOLATNI TO‘G‘RI ANIQLASH
        document.addEventListener('DOMContentLoaded', () => {
            const hasPhoto = img.dataset.hasPhoto === '1';

            if (hasPhoto) {
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
                removeBtn.classList.add('hidden');
            }
        });


        function previewPhoto(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];


            if (!file.type.startsWith('image/')) {
                alert('Faqat rasm fayl tanlang');
                input.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Rasm hajmi 2MB dan oshmasin');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.classList.remove('hidden');
                img.classList.add('ring-emerald-400');

                placeholder.classList.add('hidden');
                badge.classList.remove('hidden');
                removeBtn.classList.remove('hidden');

                removePhotoInput.value = 0;
            };

            reader.readAsDataURL(file);
        }

        function removePhoto() {
            // serverga signal beramiz
            removePhotoInput.value = 1;

            // input ochiladi
            input.disabled = false;
            input.value = '';

            // preview o‘chirish
            img.src = '';
            img.classList.add('hidden');
            img.classList.remove('ring-emerald-400');

            placeholder.classList.remove('hidden');

            badge.classList.add('hidden');
            removeBtn.classList.add('hidden');

            // overlay qayta aktiv
        }
    </script>


@endsection
