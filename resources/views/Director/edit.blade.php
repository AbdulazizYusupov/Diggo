@extends('Layout.app')

@section('title', "Direktor tahrirlash")

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-4 bg-purple-100 dark:bg-purple-900/30 rounded-2xl shadow-inner border border-purple-200 dark:border-purple-800/50">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>

                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                            Tahrirlash: <span
                                class="text-purple-600 dark:text-purple-400">{{ $director->user->full_name }}</span>
                        </h1>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                        <p class="text-xs lg:text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Oxirgi tahrir: {{ $director->updated_at->format('d.m.Y, H:i') }}
                        </p>
                        <span class="hidden md:inline text-gray-300 dark:text-gray-600">|</span>
                        <p
                            class="text-xs lg:text-sm font-semibold text-purple-500 dark:text-purple-400 uppercase tracking-widest">
                            ID: #{{ $director->id }}
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('directors.index') }}"
                class="group inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-2xl shadow-sm hover:bg-gray-50 hover:text-purple-600 hover:border-purple-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 transition-all duration-300">
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
            <form action="{{ route('directors.update', $director->id) }}" method="POST" class="p-6 lg:p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Director Info Section -->
                <div class="space-y-6">
                    <div
                        class="flex items-center gap-3 pb-4 border-b-2 border-purple-100 dark:border-purple-900/30">
                        <div class="p-2.5 bg-purple-50 dark:bg-purple-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Direktor ma'lumotlari</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Asosiy ma'lumotlarni ko'ring</p>
                        </div>
                    </div>

                    <!-- User Info Display (Read-only) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div
                            class="p-4 bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-2xl border border-purple-100 dark:border-purple-800/30">
                            <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">
                                To'liq ism</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $director->user->full_name }}</p>
                        </div>

                        <div
                            class="p-4 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-2xl border border-blue-100 dark:border-blue-800/30">
                            <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">
                                Telefon</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $director->user->phone }}</p>
                        </div>

                        <div
                            class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl border border-green-100 dark:border-green-800/30">
                            <p class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">
                                Jinsi</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $director->user->gender === 'male' ? 'Erkak' : 'Ayol' }}</p>
                        </div>
                    </div>
                </div>

                <!-- School Selection -->
                <div class="space-y-6">
                    <div
                        class="flex items-center gap-3 pb-4 border-b-2 border-orange-100 dark:border-orange-900/30">
                        <div class="p-2.5 bg-orange-50 dark:bg-orange-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Maktab biriktirilishi</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Direktorni maktabga biriktiring</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Maktabni tanlang
                            </label>
                            <select name="school_id"
                                class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-orange-500 dark:text-white outline-none transition-all text-sm
                                @error('school_id') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <option value="">Maktab tanlanmagan</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}"
                                        {{ old('school_id', $director->school_id) == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                        @if($school->address)
                                            - {{ Str::limit($school->address, 40) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('school_id')
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
                    </div>
                </div>

                <!-- Commission & Balance Section -->
                <div class="space-y-6">
                    <div
                        class="flex items-center gap-3 pb-4 border-b-2 border-emerald-100 dark:border-emerald-900/30">
                        <div class="p-2.5 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Moliyaviy ma'lumotlar</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Komissiya va balans sozlamalari</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Commission Percentage -->
                        <div class="space-y-2">
                            <label
                                class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Komissiya foizi
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="commission_percentage" step="0.01" min="0" max="100"
                                    value="{{ old('commission_percentage', $director->commission_percentage) }}" required
                                    placeholder="0.00"
                                    class="w-full pl-4 pr-12 py-3.5 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none dark:text-white transition-all
                                    @error('commission_percentage') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <span
                                    class="absolute inset-y-0 right-4 flex items-center text-gray-500 dark:text-gray-400 font-semibold">%</span>
                            </div>
                            @error('commission_percentage')
                                <p class="text-red-500 text-xs mt-1 ml-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Har bir to'lovdan olinadigan foiz
                            </p>
                        </div>

                        <!-- Balance -->
                        <div class="space-y-2">
                            <label
                                class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Balans
                            </label>
                            <div class="relative">
                                <input type="number" name="balance" step="0.01" min="0"
                                    value="{{ old('balance', $director->balance) }}" placeholder="0.00"
                                    class="w-full pl-4 pr-20 py-3.5 bg-gray-50 dark:bg-gray-700/50 border rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none dark:text-white transition-all
                                    @error('balance') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror">
                                <span
                                    class="absolute inset-y-0 right-4 flex items-center text-gray-500 dark:text-gray-400 font-semibold text-xs">so'm</span>
                            </div>
                            @error('balance')
                                <p class="text-red-500 text-xs mt-1 ml-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 ml-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Joriy hisob balansi
                            </p>
                        </div>
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
                                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase">Komissiya
                                        haqida</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">Har bir talaba to'lovidan
                                        belgilangan foiz miqdorida komissiya olinadi</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-4 bg-green-50 dark:bg-green-900/20 rounded-2xl border border-green-200 dark:border-green-800/30">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-green-100 dark:bg-green-900/40 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-green-700 dark:text-green-400 uppercase">Balans
                                        haqida</p>
                                    <p class="text-xs text-green-600 dark:text-green-300 mt-1">To'plangan komissiyalar
                                        balansda saqlanadi va istalgan vaqt chiqarib olish mumkin</p>
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
                        Oxirgi saqlash: {{ $director->updated_at->format('d.m.Y, H:i') }}
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('directors.index') }}"
                            class="flex-1 sm:flex-initial px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all text-center">
                            Bekor qilish
                        </a>
                        <button type="submit"
                            class="flex-1 sm:flex-initial px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-purple-200 dark:shadow-none transform hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Saqlash
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-800/30">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-400">Eslatma</p>
                    <p class="text-xs text-amber-700 dark:text-amber-300 mt-1">
                        Direktor shaxsiy ma'lumotlarini tahrirlash uchun foydalanuvchi profilini yangilang. Bu yerda faqat
                        maktab, komissiya va balans ma'lumotlarini o'zgartirishingiz mumkin.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection