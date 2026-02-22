@extends('Layout.app')

@section('title', isset($lesson) ? 'Darsni tahrirlash' : 'Yangi dars qo\'shish')

@section('content')
<div class="w-full">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('teacher.groups.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Guruhlarim</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('teacher.groups.lessons.index', $group->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $group->name }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white font-medium">{{ isset($lesson) ? 'Tahrirlash' : 'Yangi dars' }}</span>
    </nav>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-indigo-600 to-indigo-700">
            <h1 class="text-xl font-extrabold text-white">
                {{ isset($lesson) ? 'Darsni tahrirlash' : 'Yangi dars qo\'shish' }}
            </h1>
            <p class="text-indigo-200 text-sm mt-0.5">{{ $group->name }} — {{ $group->course->title ?? '' }}</p>
        </div>

        <form method="POST"
            action="{{ isset($lesson)
                ? route('teacher.groups.lessons.update', [$group->id, $lesson->id])
                : route('teacher.groups.lessons.store', $group->id) }}"
            class="p-6 space-y-5">
            @csrf
            @if(isset($lesson)) @method('PUT') @endif

            {{-- Errors --}}
            @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-xl p-4">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-sm text-red-700 dark:text-red-400 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Topic --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                    Dars mavzusi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="topic"
                    value="{{ old('topic', $lesson->topic ?? '') }}"
                    placeholder="Masalan: Kirish. O'zgaruvchilar va ma'lumot turlari"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors @error('topic') border-red-400 @enderror">
                @error('topic')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date + Times --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Dars sanasi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="lesson_date"
                        value="{{ old('lesson_date', isset($lesson) ? \Carbon\Carbon::parse($lesson->lesson_date)->format('Y-m-d') : \Carbon\Carbon::today()->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 @error('lesson_date') border-red-400 @enderror">
                    @error('lesson_date')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Boshlanish <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="start_time"
                        value="{{ old('start_time', isset($lesson->start_time) ? \Carbon\Carbon::parse($lesson->start_time)->format('H:i') : (isset($group->start_time) ? \Carbon\Carbon::parse($group->start_time)->format('H:i') : '')) }}"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 @error('start_time') border-red-400 @enderror">
                    @error('start_time')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Tugash
                    </label>
                    <input type="time" name="end_time"
                        value="{{ old('end_time', isset($lesson->end_time) ? \Carbon\Carbon::parse($lesson->end_time)->format('H:i') : (isset($group->end_time) ? \Carbon\Carbon::parse($group->end_time)->format('H:i') : '')) }}"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 @error('end_time') border-red-400 @enderror">
                    @error('end_time')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Status (only edit) --}}
            @if(isset($lesson))
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                    Dars holati
                </label>
                <select name="status"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                    <option value="scheduled"  {{ old('status', $lesson->status) === 'scheduled'  ? 'selected' : '' }}>Rejalashtirilgan</option>
                    <option value="completed"  {{ old('status', $lesson->status) === 'completed'  ? 'selected' : '' }}>Bajarildi</option>
                    <option value="cancelled"  {{ old('status', $lesson->status) === 'cancelled'  ? 'selected' : '' }}>Bekor qilindi</option>
                </select>
            </div>
            @endif

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                    Izoh / Qo'shimcha ma'lumot
                </label>
                <textarea name="description" rows="4"
                    placeholder="Dars haqida qo'shimcha ma'lumot (ixtiyoriy)..."
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 resize-none transition-colors @error('description') border-red-400 @enderror">{{ old('description', $lesson->description ?? '') }}</textarea>
                @error('description')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('teacher.groups.lessons.index', $group->id) }}"
                    class="flex-1 flex items-center justify-center px-4 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold text-sm transition-colors">
                    Bekor qilish
                </a>
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($lesson) ? 'Yangilash' : 'Dars qo\'shish' }}
                </button>
            </div>
        </form>
    </div>

    {{-- Info card --}}
    <div class="bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-sm text-amber-700 dark:text-amber-400">
            <p class="font-semibold mb-1">Dars qo'shgandan keyin</p>
            <p>Darsni qo'shgach, darhol yuqlama olishingiz mumkin. Dars "Bajarildi" statusiga o'tish uchun yuqlama saqlanishi kerak.</p>
        </div>
    </div>

</div>
@endsection