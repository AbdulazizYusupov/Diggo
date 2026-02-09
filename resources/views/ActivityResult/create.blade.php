@extends('Layout.app')

@section('title', 'Natija qo\'shish')

@section('content')
    <div class="w-full">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('activityresults.index') }}"
                    class="w-12 h-12 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl flex items-center justify-center transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white">Yangi natija qo'shish</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Talaba faoliyat natijasini kiriting</p>
                </div>
            </div>

            <div
                class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-none transform rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
        </div>

        <!-- Form Card -->
        <form action="{{ route('activityresults.store') }}" method="POST">
            @csrf

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-6">

                <!-- Activity Selection -->
                <div>
                    <label for="activity_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Faoliyat
                            <span class="text-rose-500">*</span>
                        </span>
                    </label>
                    <select name="activity_id" id="activity_id" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('activity_id') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium appearance-none cursor-pointer">
                        <option value="">Faoliyatni tanlang</option>
                        @foreach ($activities as $activity)
                            <option value="{{ $activity->id }}" {{ old('activity_id', $selectedActivity?->id) == $activity->id ? 'selected' : '' }}>
                                {{ $activity->name }} ({{ ucfirst($activity->type) }})
                            </option>
                        @endforeach
                    </select>
                    @error('activity_id')
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

                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Talaba
                            <span class="text-rose-500">*</span>
                        </span>
                    </label>
                    <select name="student_id" id="student_id" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('student_id') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all font-medium appearance-none cursor-pointer">
                        <option value="">Talabani tanlang</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->user->name ?? 'N/A' }}
                                @if($student->group)
                                    - {{ $student->group->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
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

                <!-- Earned Coins & Rank Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Earned Coins -->
                    <div>
                        <label for="earned_coins" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Olgan coinlar
                                <span class="text-rose-500">*</span>
                            </span>
                        </label>
                        <input type="number" name="earned_coins" id="earned_coins"
                            value="{{ old('earned_coins') }}" required min="0"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('earned_coins') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all font-medium"
                            placeholder="85">
                        @error('earned_coins')
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

                    <!-- Rank -->
                    <div>
                        <label for="rank" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                Rang
                                <span class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                            </span>
                        </label>
                        <input type="number" name="rank" id="rank" value="{{ old('rank') }}" min="1"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all font-medium"
                            placeholder="1">
                        @error('rank')
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

                <!-- Note -->
                <div>
                    <label for="note" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Izoh
                            <span class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                        </span>
                    </label>
                    <textarea name="note" id="note" rows="4"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium resize-none"
                        placeholder="Natija haqida qo'shimcha ma'lumot...">{{ old('note') }}</textarea>
                    @error('note')
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
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-500/5 border border-blue-200 dark:border-blue-500/20 rounded-xl p-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-blue-900 dark:text-blue-300 mb-1">Eslatma</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-400 leading-relaxed">
                                Kiritilgan coinlar faoliyatning maksimal coin sonidan oshmasligi kerak. Bir talaba uchun
                                bir faoliyatda faqat bitta natija kiritilishi mumkin.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('activityresults.index') }}"
                        class="px-6 py-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95">
                        Bekor qilish
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Natijani saqlash
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection