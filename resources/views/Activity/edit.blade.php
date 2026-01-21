@extends('Layout.app')

@section('title', 'Faoliyatni tahrirlash')

@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('activities.show', $activity->id) }}"
                    class="w-12 h-12 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl flex items-center justify-center transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white">Faoliyatni tahrirlash</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $activity->name }}</p>
                </div>
            </div>

            <div
                class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200 dark:shadow-none transform rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>

        <!-- Form Card -->
        <form action="{{ route('activities.update', $activity->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-6">
                
                <!-- Activity Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Faoliyat nomi
                            <span class="text-rose-500">*</span>
                        </span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $activity->name) }}" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium"
                        placeholder="Faoliyat nomini kiriting">
                    @error('name')
                        <p class="mt-1.5 text-sm text-rose-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Activity Type & Date Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Activity Type -->
                    <div>
                        <label for="type" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                                Faoliyat turi
                                <span class="text-rose-500">*</span>
                            </span>
                        </label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all font-medium appearance-none cursor-pointer">
                            @php
                                $typeNames = [
                                    'homework' => 'Uyga vazifa',
                                    'exam' => 'Imtihon',
                                    'competition' => 'Musobaqa',
                                    'project' => 'Loyiha',
                                    'other' => 'Boshqa',
                                ];
                            @endphp
                            @foreach ($types as $type)
                                <option value="{{ $type }}" {{ old('type', $activity->type) == $type ? 'selected' : '' }}>
                                    {{ $typeNames[$type] ?? ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1.5 text-sm text-rose-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Sana
                                <span class="text-rose-500">*</span>
                            </span>
                        </label>
                        <input type="date" name="date" id="date" value="{{ old('date', $activity->date->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-medium">
                        @error('date')
                            <p class="mt-1.5 text-sm text-rose-500 flex items-center gap-1">
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

                <!-- Group & Max Coins Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Group Selection -->
                    <div>
                        <label for="group_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Guruh
                                <span class="text-rose-500">*</span>
                            </span>
                        </label>
                        <select name="group_id" id="group_id" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-medium appearance-none cursor-pointer">
                            <option value="">Guruhni tanlang</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id', $activity->group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                    @if($group->course)
                                        - {{ $group->course->title }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('group_id')
                            <p class="mt-1.5 text-sm text-rose-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Max Coins -->
                    <div>
                        <label for="max_coins" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Maksimal ball
                                <span class="text-rose-500">*</span>
                            </span>
                        </label>
                        <input type="number" name="max_coins" id="max_coins" value="{{ old('max_coins', $activity->max_coins) }}" required min="1" max="1000"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all font-medium"
                            placeholder="100">
                        @error('max_coins')
                            <p class="mt-1.5 text-sm text-rose-500 flex items-center gap-1">
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

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Tavsif
                            <span class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                        </span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium resize-none"
                        placeholder="Faoliyat haqida qo'shimcha ma'lumot kiriting...">{{ old('description', $activity->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-rose-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-500/5 border border-blue-200 dark:border-blue-500/20 rounded-xl p-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-blue-900 dark:text-blue-300 mb-1">Ma'lumot</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-400 leading-relaxed">
                                Faoliyatni tahrirlash faqat asosiy ma'lumotlarni o'zgartiradi. Talabalar natijalari saqlanib qoladi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('activities.show', $activity->id) }}"
                        class="px-6 py-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95">
                        Bekor qilish
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-200 dark:shadow-none transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        O'zgarishlarni saqlash
                    </button>
                </div>
            </div>
        </form>

        <!-- Additional Info Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Qo'shimcha ma'lumot
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Yaratilgan:</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                        {{ $activity->created_at->format('d.m.Y H:i') }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Oxirgi o'zgarish:</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                        {{ $activity->updated_at->format('d.m.Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection