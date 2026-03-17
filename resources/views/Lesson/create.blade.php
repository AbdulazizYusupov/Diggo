@extends('Layout.app')

@section('title', 'Dars qo\'shish')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('lessons.index') }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Yangi dars qo'shish
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Dars ma'lumotlarini to'ldiring
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('lessons.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Group Selection -->
                    <!-- Group Selection -->
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 ml-1">
                                Guruhni tanlang <span class="text-red-500">*</span>
                            </label>

                            <select name="group_id" id="group_id" required
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border @error('group_id') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror rounded-xl cursor-pointer text-sm text-gray-900 dark:text-white hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                                <option value="">Guruhni tanlang...</option>

                                @foreach ($groups ?? [] as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} ({{ $group->course->title ?? 'N/A' }})
                                    </option>
                                @endforeach

                            </select>

                            @error('group_id')
                                <p class="text-red-500 text-[10px] mt-1 ml-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Teacher Selection -->
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 ml-1">
                                O'qituvchini tanlang <span class="text-red-500">*</span>
                            </label>

                            <select name="teacher_id" id="teacher_id" required
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border @error('teacher_id') border-red-500 @else border-gray-200 dark:border-gray-600 @enderror rounded-xl shadow-sm text-sm text-gray-700 dark:text-white hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">

                                <option value="">O'qituvchini tanlang...</option>

                                @foreach ($teachers ?? [] as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->full_name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('teacher_id')
                                <p class="text-red-500 text-[10px] mt-1 ml-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Lesson Date -->
                    <div>
                        <label for="lesson_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Dars sanasi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="lesson_date" id="lesson_date" value="{{ old('lesson_date') }}" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        @error('lesson_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>
                                Rejalashtirilgan
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Yakunlangan
                            </option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Bekor qilingan
                            </option>
                            <option value="rescheduled" {{ old('status') == 'rescheduled' ? 'selected' : '' }}>
                                Ko'chirilgan
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Boshlanish vaqti <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tugash vaqti <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Topic -->
                    <div class="lg:col-span-2">
                        <label for="topic" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Dars mavzusi
                        </label>
                        <input type="text" name="topic" id="topic" value="{{ old('topic') }}"
                            placeholder="Masalan: If/Else Statements"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                        @error('topic')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Qo'shimcha ma'lumot
                        </label>
                        <textarea name="description" id="description" rows="4" placeholder="Dars haqida qo'shimcha ma'lumotlar..."
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('lessons.index') }}"
                        class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-xl transition-all">
                        Bekor qilish
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                new TomSelect("#group_id", {
                    plugins: ['dropdown_input'],
                    create: false,
                    allowEmptyOption: true,
                    placeholder: "Guruhni tanlang...",
                    
                });
                new TomSelect("#teacher_id", {
                    plugins: ['dropdown_input'],
                    create: false,
                    allowEmptyOption: true,
                    placeholder: "Teacherni tanlang...",
                   
                });
            });
        </script>
    @endpush
@endsection
