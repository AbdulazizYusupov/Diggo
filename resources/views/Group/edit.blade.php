@extends('Layout.app')

@section('title', 'Guruhni tahrirlash')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Icon Box --}}
                <div
                    class="hidden sm:flex p-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Guruhni tahrirlash
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        {{ $group->name }} guruhini yangilash
                    </p>
                </div>
            </div>

            {{-- Back Button --}}
            <a href="{{ route('groups.show', $group->id) }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 
               bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300
               border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700
               font-semibold rounded-xl transition-all duration-200 
               transform hover:scale-[1.02] active:scale-[0.98] shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-bold">Orqaga</span>
            </a>
        </div>

        <!-- Form Card -->
        <form action="{{ route('groups.update', $group->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-amber-500/20 overflow-hidden">
                <div class="p-6 lg:p-8 space-y-6">

                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Asosiy ma'lumotlar</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Group Name --}}
                            <div class="lg:col-span-2">
                                <label for="name"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Guruh nomi <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $group->name) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 
                                           text-gray-900 dark:text-white placeholder-gray-400 
                                           transition-all outline-none"
                                    placeholder="Masalan: Frontend-101">
                                @error('name')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Course --}}
                            <div class="lg:col-span-2">
                                <label for="cours_id"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Kurs <span class="text-rose-500">*</span>
                                </label>
                                <select name="cours_id" id="cours_id" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 
                                           text-gray-900 dark:text-white 
                                           transition-all outline-none cursor-pointer">
                                    <option value="">Kursni tanlang</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ old('cours_id', $group->cours_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->code }} - {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cours_id')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Capacity --}}
                            <div>
                                <label for="capacity"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Sig'im (talabalar soni) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="capacity" id="capacity"
                                    value="{{ old('capacity', $group->capacity) }}" min="1" max="100" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 
                                           text-gray-900 dark:text-white placeholder-gray-400 
                                           transition-all outline-none"
                                    placeholder="20">
                                @error('capacity')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Holat
                                </label>
                                <div class="flex items-center h-[50px]">
                                    <label class="relative inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                            {{ old('is_active', $group->is_active) ? 'checked' : '' }}>
                                        <div
                                            class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-amber-600">
                                        </div>
                                        <span
                                            class="ms-3 text-sm font-bold text-gray-900 dark:text-gray-300">Faol</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">Dars jadvali</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Start Time --}}
                            <div>
                                <label for="start_time"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Boshlanish vaqti <span class="text-rose-500">*</span>
                                </label>
                                <input type="time" name="start_time" id="start_time"
                                    value="{{ old('start_time', \Carbon\Carbon::parse($group->start_time)->format('H:i')) }}"
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 
                                           text-gray-900 dark:text-white 
                                           transition-all outline-none">
                                @error('start_time')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- End Time --}}
                            <div>
                                <label for="end_time"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Tugash vaqti <span class="text-rose-500">*</span>
                                </label>
                                <input type="time" name="end_time" id="end_time"
                                    value="{{ old('end_time', \Carbon\Carbon::parse($group->end_time)->format('H:i')) }}"
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 
                                           text-gray-900 dark:text-white 
                                           transition-all outline-none">
                                @error('end_time')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Days of Week --}}
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    Dars kunlari <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                                    @php
                                        $days = [
                                            'monday' => 'Dushanba',
                                            'tuesday' => 'Seshanba',
                                            'wednesday' => 'Chorshanba',
                                            'thursday' => 'Payshanba',
                                            'friday' => 'Juma',
                                            'saturday' => 'Shanba',
                                            'sunday' => 'Yakshanba',
                                        ];
                                        $oldDays = old('days_of_week', $group->days_of_week ?? []);
                                    @endphp
                                    @foreach ($days as $value => $label)
                                        <label
                                            class="relative flex items-center justify-center px-4 py-3 bg-gray-50 dark:bg-slate-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer transition-all hover:border-amber-500 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-500/10">
                                            <input type="checkbox" name="days_of_week[]" value="{{ $value }}"
                                                class="sr-only peer" {{ in_array($value, $oldDays) ? 'checked' : '' }}>
                                            <span
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 peer-checked:text-amber-600 dark:peer-checked:text-amber-400">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('days_of_week')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label for="start_date"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Boshlanish sanasi <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', $group->start_date->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 
                                           text-gray-900 dark:text-white 
                                           transition-all outline-none">
                                @error('start_date')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label for="end_date"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Tugash sanasi <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date', $group->end_date->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 
                                           text-gray-900 dark:text-white 
                                           transition-all outline-none">
                                @error('end_date')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('groups.show', $group->id) }}"
                            class="px-6 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 
                                   hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 
                                   font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                            Bekor qilish
                        </a>

                        <button type="submit"
                            class="px-6 py-3 bg-amber-600 hover:bg-amber-700 dark:bg-amber-600 dark:hover:bg-amber-700 
                                   text-white font-bold rounded-xl transition-all active:scale-95 
                                   shadow-lg shadow-amber-200 dark:shadow-none
                                   flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            O'zgarishlarni saqlash
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection