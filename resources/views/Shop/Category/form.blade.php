@extends('Layout.app')

@section('title', isset($category) ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya')

@section('content')
<div class="w-full">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.shop.categories.index') }}"
           class="p-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                {{ isset($category) ? 'Kategoriyani tahrirlash' : 'Yangi kategoriya qo\'shish' }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ isset($category) ? $category->name : 'Do\'kon uchun yangi kategoriya' }}
            </p>
        </div>
    </div>

    <form method="POST"
          action="{{ isset($category) ? route('admin.shop.categories.update', $category) : route('admin.shop.categories.store') }}"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        {{-- Errors --}}
        @if($errors->any())
            <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-500/30 rounded-2xl p-4">
                <ul class="list-disc list-inside text-sm text-rose-700 dark:text-rose-400 space-y-1">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-5">

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kategoriya nomi <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                    placeholder="Masalan: Elektronika"
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>

            {{-- Slug --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}"
                    placeholder="avtomatik generatsiya qilinadi"
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white font-mono text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                <p class="text-xs text-gray-400 mt-1">Bo'sh qoldirsangiz avtomatik yaratiladi</p>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tavsif</label>
                <textarea name="description" rows="3" placeholder="Kategoriya haqida qisqacha ma'lumot..."
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            {{-- Icon & Color row --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon ?? '') }}" maxlength="10"
                        placeholder="📦"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white text-2xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rang <span class="text-rose-500">*</span></label>
                    <select name="color" required
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer">
                        @foreach($colors as $value => $color)
                            <option value="{{ $value }}" {{ old('color', $category->color ?? 'indigo') === $value ? 'selected' : '' }}>
                                {{ $color['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rasm</label>
                @if(isset($category) && $category->image)
                    <div class="mb-3">
                        <img src="{{ $category->image_url }}" class="h-24 w-24 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white text-sm outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 2MB</p>
            </div>

            {{-- Sort Order & Active --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tartib raqami</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
                <div class="flex flex-col justify-center">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Holat</label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                            class="w-5 h-5 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Faol</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                class="flex-1 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                {{ isset($category) ? 'Saqlash' : 'Qo\'shish' }}
            </button>
            <a href="{{ route('admin.shop.categories.index') }}"
               class="px-6 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all active:scale-95">
                Bekor qilish
            </a>
        </div>
    </form>
</div>
@endsection