@extends('Layout.app')

@section('title', 'Faoliyatni tahrirlash')

@section('content')
    <div class="w-full space-y-6">
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

        <!-- Alert errors -->
        @if ($errors->any())
            <div
                class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-xl p-4 flex gap-3">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-bold text-rose-700 dark:text-rose-400 mb-1">Xatoliklar mavjud:</p>
                    <ul class="text-sm text-rose-600 dark:text-rose-400 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: Main Fields -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Info -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-5">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Asosiy
                            ma'lumotlar</h2>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Faoliyat nomi <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $activity->name) }}"
                                required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('name') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-medium outline-none"
                                placeholder="Faoliyat nomini kiriting">
                            @error('name')
                                <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Tavsif <span class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none resize-none"
                                placeholder="Faoliyat haqida qo'shimcha ma'lumot...">{{ old('description', $activity->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Type, Status, Group -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-5">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Turkumlash
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Turi <span class="text-rose-500">*</span>
                                </label>
                                @php $typeLabels = ['homework'=>'Uyga vazifa','exam'=>'Imtihon','competition'=>'Musobaqa','project'=>'Loyiha','other'=>'Boshqa']; @endphp
                                <select name="type" id="type" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('type') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all cursor-pointer outline-none">
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $activity->type) == $type ? 'selected' : '' }}>
                                            {{ $typeLabels[$type] ?? ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Holat <span class="text-rose-500">*</span>
                                </label>
                                @php $statusLabels = ['draft'=>'Qoralama','active'=>'Faol','completed'=>'Tugallangan','cancelled'=>'Bekor']; @endphp
                                <select name="status" id="status" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('status') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all cursor-pointer outline-none">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status', $activity->status) == $status ? 'selected' : '' }}>
                                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Group -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Guruh <span class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                            </label>
                            <div class="relative" id="actionGroupCustomContainer">
                                <div onclick="toggleActionGroupDropdown()"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('group_id') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl cursor-pointer group hover:border-amber-500 transition-all">
                                    <span id="selectedActionGroupLabel" class="text-sm font-medium dark:text-white">
                                        @php
                                            $initialId = old('group_id', $activity->group_id ?? '');
                                            $selected = $groups->firstWhere('id', $initialId);
                                        @endphp
                                        {{ $selected ? $selected->name . ($selected->course ? ' - ' . $selected->course->title : '') : 'Guruhni tanlang' }}
                                    </span>
                                    <input type="hidden" name="group_id" id="action_group_id_input"
                                        value="{{ $initialId }}">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-500 transition-transform"
                                        id="actionGroupArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div id="actionGroupMenu"
                                    class="hidden absolute z-[150] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                    <div class="p-2 border-b dark:border-gray-700 bg-gray-50/50 dark:bg-slate-900/50">
                                        <input type="text" id="actionGroupSearchInput" oninput="filterActionGroups()"
                                            placeholder="Guruh nomini yozing..."
                                            class="w-full px-3 py-2 bg-white dark:bg-slate-800 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none">
                                    </div>
                                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                        <div onclick="selectActionGroup('', 'Guruhni tanlang')"
                                            class="px-4 py-3 text-sm text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer italic">
                                            Tanlovni bekor qilish
                                        </div>
                                        @foreach ($groups as $group)
                                            <div onclick="selectActionGroup('{{ $group->id }}', '{{ $group->name }}{{ $group->course ? ' - ' . $group->course->title : '' }}')"
                                                class="action-group-item px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-amber-50 dark:hover:bg-amber-500/10 cursor-pointer flex flex-col border-b border-gray-50 dark:border-gray-700/50 last:border-none">
                                                <span class="font-bold action-item-name">{{ $group->name }}</span>
                                                @if ($group->course)
                                                    <span
                                                        class="text-xs text-gray-400 action-item-course">{{ $group->course->title }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="noActionGroupFound" class="hidden p-5 text-center text-sm text-gray-500">
                                        Guruh topilmadi...</div>
                                </div>
                            </div>
                            @error('group_id')
                                <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date, Time, Coins -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-5">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Vaqt va
                            ball</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                            {{-- 1. Sana --}}
                            <div>
                                <label for="date"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Sana <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="date" id="date"
                                    value="{{ old('date', $activity->date->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('date') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                                @error('date')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 2. Boshlanish vaqti (YANGI) --}}
                            <div>
                                <label for="start_time"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Boshlanish vaqti
                                    <span class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                                </label>
                                <input type="time" name="start_time" id="start_time"
                                    value="{{ old('start_time', $activity->start_time ? \Carbon\Carbon::parse($activity->start_time)->format('H:i') : '') }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('start_time') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                                @error('start_time')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 3. Topshirish vaqti --}}
                            <div>
                                <label for="due_time"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Topshirish vaqti
                                    <span class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                                </label>
                                <input type="time" name="due_time" id="due_time"
                                    value="{{ old('due_time', $activity->due_time ? \Carbon\Carbon::parse($activity->due_time)->format('H:i') : '') }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('due_time') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                                @error('due_time')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 4. Maksimal coin --}}
                            <div>
                                <label for="max_coins"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Maksimal coin <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="max_coins" id="max_coins"
                                    value="{{ old('max_coins', $activity->max_coins) }}" required min="0"
                                    max="1000"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('max_coins') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none"
                                    placeholder="100">
                                @error('max_coins')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- ══════════════════════════════════════════════════
                                 FAYLLAR — Image + Document with live preview
                            ═══════════════════════════════════════════════════════ -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-6">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Fayllar
                            <span class="normal-case font-normal">(ixtiyoriy)</span>
                        </h2>

                        {{-- ── IMAGE ── --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                Rasm
                                <span class="ml-1 text-xs font-normal text-gray-400">jpeg, png, gif, webp — max 2MB</span>
                            </label>

                            {{-- Existing image (shown when no new file is selected yet) --}}
                            @if ($activity->image)
                                <div id="existingImgWrap" class="mb-3">
                                    <div class="relative inline-block group/thumb">
                                        <img src="{{ Storage::url($activity->image) }}" alt="Joriy rasm"
                                            class="h-36 w-36 rounded-2xl object-cover ring-2 ring-amber-300 dark:ring-amber-500/50 shadow-lg shadow-amber-100 dark:shadow-none">
                                        <div
                                            class="absolute bottom-0 left-0 right-0 px-2 py-1.5 bg-black/55 backdrop-blur-sm rounded-b-2xl">
                                            <p class="text-[10px] text-white font-medium truncate leading-tight">Joriy rasm
                                            </p>
                                            <p class="text-[10px] text-white/70 truncate leading-tight">Almashtiriladi yoki
                                                o'chiriladi</p>
                                        </div>
                                        {{-- Remove existing checkbox styled as button --}}
                                        <label
                                            class="absolute -top-2 -right-2 flex items-center gap-1 cursor-pointer opacity-0 group-hover/thumb:opacity-100 transition-all">
                                            <input type="checkbox" name="remove_image" value="1"
                                                id="removeExistingImg" class="sr-only peer"
                                                onchange="handleRemoveExistingImg(this)">
                                            <span
                                                class="w-7 h-7 bg-rose-500 hover:bg-rose-600 peer-checked:bg-rose-700 text-white rounded-full flex items-center justify-center shadow-lg font-bold text-lg leading-none transition-all active:scale-90">×</span>
                                        </label>
                                    </div>
                                    <p id="removeImgNote" class="hidden mt-1.5 text-xs text-rose-500 font-medium">Rasm
                                        o'chiriladi (saqlanganda)</p>
                                </div>
                            @endif

                            {{-- New image preview card (hidden until file selected) --}}
                            <div id="imgPreviewWrap" class="hidden mb-3">
                                <div class="relative inline-block group/thumb">
                                    <img id="imgPreviewTag" src="" alt="rasm ko'rinishi"
                                        class="h-36 w-36 rounded-2xl object-cover ring-2 ring-purple-300 dark:ring-purple-500/50 shadow-lg shadow-purple-100 dark:shadow-none">
                                    <div
                                        class="absolute bottom-0 left-0 right-0 px-2 py-1.5 bg-black/55 backdrop-blur-sm rounded-b-2xl">
                                        <p id="imgPreviewName"
                                            class="text-[10px] text-white font-medium truncate leading-tight"></p>
                                        <p id="imgPreviewSize" class="text-[10px] text-white/70 truncate leading-tight">
                                        </p>
                                    </div>
                                    <button type="button" id="removeImgBtn"
                                        class="absolute -top-2 -right-2 w-7 h-7 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center shadow-lg font-bold text-lg leading-none transition-all active:scale-90 opacity-0 group-hover/thumb:opacity-100">×</button>
                                </div>
                            </div>

                            {{-- Drop zone --}}
                            <label for="imageInput" id="imageDropZone"
                                class="flex flex-col items-center justify-center gap-2 w-full h-28 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 bg-gray-50 dark:bg-slate-800/60 cursor-pointer transition-all group/drop">
                                <div
                                    class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center group-hover/drop:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="text-center pointer-events-none">
                                    <p
                                        class="text-sm font-semibold text-gray-600 dark:text-gray-300 group-hover/drop:text-amber-600 dark:group-hover/drop:text-amber-400 transition-colors">
                                        Yangi rasm tanlang yoki torting</p>
                                    <p class="text-xs text-gray-400 mt-0.5">PNG, JPG, WEBP, GIF</p>
                                </div>
                                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                            </label>
                            @error('image')
                                <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ── DOCUMENT FILE ── --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                Hujjat
                                <span class="ml-1 text-xs font-normal text-gray-400">pdf, doc, xls, zip, txt — max
                                    10MB</span>
                            </label>

                            {{-- Existing file (shown when no new file selected) --}}
                            @if ($activity->file)
                                <div id="existingFileWrap" class="mb-3">
                                    <div
                                        class="flex items-center gap-3 px-4 py-3 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 rounded-xl">
                                        <div
                                            class="w-11 h-11 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ Storage::url($activity->file) }}" target="_blank"
                                                class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline truncate block">Joriy
                                                hujjatni ko'rish</a>
                                            <p class="text-xs text-gray-400 mt-0.5">Yangi fayl yuklash eskisini
                                                almashtiradi</p>
                                        </div>
                                        <label class="flex items-center gap-1.5 cursor-pointer flex-shrink-0">
                                            <input type="checkbox" name="remove_file" value="1"
                                                id="removeExistingFile" class="w-3.5 h-3.5 rounded text-rose-500"
                                                onchange="handleRemoveExistingFile(this)">
                                            <span class="text-xs text-rose-500 font-bold">O'chirish</span>
                                        </label>
                                    </div>
                                    <p id="removeFileNote" class="hidden mt-1.5 text-xs text-rose-500 font-medium">Hujjat
                                        o'chiriladi (saqlanganda)</p>
                                </div>
                            @endif

                            {{-- New file preview card (hidden until file selected) --}}
                            <div id="filePreviewWrap" class="hidden mb-3">
                                <div
                                    class="flex items-center gap-3 px-4 py-3 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 rounded-xl">
                                    <div id="fileIconBox"
                                        class="w-11 h-11 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                                        {{-- Icon injected by JS --}}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p id="filePreviewName"
                                            class="text-sm font-bold text-gray-900 dark:text-white truncate"></p>
                                        <p id="filePreviewSize" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        </p>
                                    </div>
                                    <button type="button" id="removeFileBtn"
                                        class="w-8 h-8 bg-rose-100 hover:bg-rose-200 dark:bg-rose-500/20 dark:hover:bg-rose-500/40 text-rose-600 dark:text-rose-400 rounded-lg flex items-center justify-center font-bold text-lg transition-all flex-shrink-0 active:scale-90">×</button>
                                </div>
                            </div>

                            {{-- Drop zone --}}
                            <label for="fileInput" id="fileDropZone"
                                class="flex flex-col items-center justify-center gap-2 w-full h-28 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 bg-gray-50 dark:bg-slate-800/60 cursor-pointer transition-all group/fdrop">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center group-hover/fdrop:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <div class="text-center pointer-events-none">
                                    <p
                                        class="text-sm font-semibold text-gray-600 dark:text-gray-300 group-hover/fdrop:text-indigo-600 dark:group-hover/fdrop:text-indigo-400 transition-colors">
                                        Yangi hujjat tanlang yoki torting</p>
                                    <p class="text-xs text-gray-400 mt-0.5">PDF, DOC, XLS, ZIP, TXT</p>
                                </div>
                                <input type="file" name="file" id="fileInput"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.txt" class="hidden">
                            </label>
                            @error('file')
                                <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- END FAYLLAR --}}
                </div>

                <!-- Right: Settings & Meta -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Settings -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-5 sticky top-6">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Sozlamalar
                        </h2>

                        <!-- is_required toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Majburiy faoliyat</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Barcha talabalar uchun majburiy
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_required" value="0">
                                <input type="checkbox" name="is_required" id="is_required" value="1"
                                    {{ old('is_required', $activity->is_required) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-500/30 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                                </div>
                            </label>
                        </div>

                        <!-- Info box -->
                        <div
                            class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed">
                                    Tahrirlash faqat asosiy ma'lumotlarni o'zgartiradi. Talabalar natijalari saqlanib
                                    qoladi.
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                class="w-full px-6 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-200 dark:shadow-none transition-all active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                O'zgarishlarni saqlash
                            </button>
                            <a href="{{ route('activities.show', $activity->id) }}"
                                class="w-full px-6 py-3.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95 flex items-center justify-center">
                                Bekor qilish
                            </a>
                        </div>
                    </div>

                    <!-- Meta Info -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">Meta
                            ma'lumotlar</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Yaratilgan:</span>
                                <span
                                    class="text-xs font-bold text-gray-900 dark:text-white">{{ $activity->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">O'zgartirilgan:</span>
                                <span
                                    class="text-xs font-bold text-gray-900 dark:text-white">{{ $activity->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                            @if ($activity->creator)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Yaratuvchi:</span>
                                    <span
                                        class="text-xs font-bold text-gray-900 dark:text-white">{{ $activity->creator->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /* ── helpers ─────────────────────────────────────────────── */
                function fmtBytes(b) {
                    if (b < 1024) return b + ' B';
                    if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
                    return (b / 1048576).toFixed(1) + ' MB';
                }

                function fileExtIcon(name) {
                    const ext = name.split('.').pop().toLowerCase();
                    const map = {
                        pdf: '#ef4444',
                        doc: '#2563eb',
                        docx: '#2563eb',
                        xls: '#16a34a',
                        xlsx: '#16a34a',
                        zip: '#d97706',
                        txt: '#6b7280',
                    };
                    const col = map[ext] || '#6b7280';
                    return `<svg width="24" height="24" fill="none" stroke="${col}" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>`;
                }

                /* ── IMAGE ───────────────────────────────────────────────── */
                const imgInput = document.getElementById('imageInput');
                const imgWrap = document.getElementById('imgPreviewWrap');
                const imgTag = document.getElementById('imgPreviewTag');
                const imgName = document.getElementById('imgPreviewName');
                const imgSize = document.getElementById('imgPreviewSize');
                const imgRemove = document.getElementById('removeImgBtn');
                const imgDrop = document.getElementById('imageDropZone');

                function showImg(file) {
                    imgTag.src = URL.createObjectURL(file);
                    imgName.textContent = file.name;
                    imgSize.textContent = fmtBytes(file.size);
                    imgWrap.classList.remove('hidden');
                    imgDrop.classList.add('hidden');
                    // Hide existing image wrap if present
                    const existing = document.getElementById('existingImgWrap');
                    if (existing) existing.classList.add('hidden');
                }

                function clearImg() {
                    imgInput.value = '';
                    imgTag.src = '';
                    imgWrap.classList.add('hidden');
                    imgDrop.classList.remove('hidden');
                    // Restore existing image wrap if present
                    const existing = document.getElementById('existingImgWrap');
                    if (existing) existing.classList.remove('hidden');
                }

                imgInput.addEventListener('change', e => {
                    if (e.target.files[0]) showImg(e.target.files[0]);
                });
                imgRemove.addEventListener('click', clearImg);

                imgDrop.addEventListener('dragover', e => {
                    e.preventDefault();
                    imgDrop.classList.add('border-amber-400');
                });
                imgDrop.addEventListener('dragleave', () => imgDrop.classList.remove('border-amber-400'));
                imgDrop.addEventListener('drop', e => {
                    e.preventDefault();
                    imgDrop.classList.remove('border-amber-400');
                    const f = e.dataTransfer.files[0];
                    if (f && f.type.startsWith('image/')) {
                        const dt = new DataTransfer();
                        dt.items.add(f);
                        imgInput.files = dt.files;
                        showImg(f);
                    }
                });

                /* ── FILE ────────────────────────────────────────────────── */
                const fileInput = document.getElementById('fileInput');
                const fileWrap = document.getElementById('filePreviewWrap');
                const fileIcon = document.getElementById('fileIconBox');
                const fileName = document.getElementById('filePreviewName');
                const fileSize = document.getElementById('filePreviewSize');
                const fileRemove = document.getElementById('removeFileBtn');
                const fileDrop = document.getElementById('fileDropZone');

                function showFile(file) {
                    fileIcon.innerHTML = fileExtIcon(file.name);
                    fileName.textContent = file.name;
                    fileSize.textContent = fmtBytes(file.size);
                    fileWrap.classList.remove('hidden');
                    fileDrop.classList.add('hidden');
                    // Hide existing file wrap if present
                    const existing = document.getElementById('existingFileWrap');
                    if (existing) existing.classList.add('hidden');
                }

                function clearFile() {
                    fileInput.value = '';
                    fileWrap.classList.add('hidden');
                    fileDrop.classList.remove('hidden');
                    // Restore existing file wrap if present
                    const existing = document.getElementById('existingFileWrap');
                    if (existing) existing.classList.remove('hidden');
                }

                fileInput.addEventListener('change', e => {
                    if (e.target.files[0]) showFile(e.target.files[0]);
                });
                fileRemove.addEventListener('click', clearFile);

                fileDrop.addEventListener('dragover', e => {
                    e.preventDefault();
                    fileDrop.classList.add('border-indigo-400');
                });
                fileDrop.addEventListener('dragleave', () => fileDrop.classList.remove('border-indigo-400'));
                fileDrop.addEventListener('drop', e => {
                    e.preventDefault();
                    fileDrop.classList.remove('border-indigo-400');
                    const f = e.dataTransfer.files[0];
                    if (f) {
                        const dt = new DataTransfer();
                        dt.items.add(f);
                        fileInput.files = dt.files;
                        showFile(f);
                    }
                });

                /* ── GROUP DROPDOWN ──────────────────────────────────────── */
                window.toggleActionGroupDropdown = function() {
                    const menu = document.getElementById('actionGroupMenu');
                    const arrow = document.getElementById('actionGroupArrow');
                    const isHidden = menu.classList.contains('hidden');
                    menu.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-180');
                    if (isHidden) document.getElementById('actionGroupSearchInput').focus();
                };
                window.selectActionGroup = function(id, labelText) {
                    document.getElementById('action_group_id_input').value = id;
                    document.getElementById('selectedActionGroupLabel').innerText = labelText || 'Guruhni tanlang';
                    window.toggleActionGroupDropdown();
                };
                window.filterActionGroups = function() {
                    const filter = document.getElementById('actionGroupSearchInput').value.toLowerCase();
                    const items = document.querySelectorAll('.action-group-item');
                    const noResult = document.getElementById('noActionGroupFound');
                    let hasResults = false;
                    items.forEach(item => {
                        const name = item.querySelector('.action-item-name').innerText.toLowerCase();
                        const course = item.querySelector('.action-item-course')?.innerText.toLowerCase() ||
                            '';
                        const show = name.includes(filter) || course.includes(filter);
                        item.style.display = show ? '' : 'none';
                        if (show) hasResults = true;
                    });
                    noResult.classList.toggle('hidden', hasResults);
                };
                document.addEventListener('click', function(e) {
                    const container = document.getElementById('actionGroupCustomContainer');
                    if (container && !container.contains(e.target)) {
                        document.getElementById('actionGroupMenu').classList.add('hidden');
                        document.getElementById('actionGroupArrow').classList.remove('rotate-180');
                    }
                });
            });

            /* ── Remove existing image/file helpers ──────────────────────── */
            function handleRemoveExistingImg(checkbox) {
                const note = document.getElementById('removeImgNote');
                if (note) note.classList.toggle('hidden', !checkbox.checked);
            }

            function handleRemoveExistingFile(checkbox) {
                const note = document.getElementById('removeFileNote');
                if (note) note.classList.toggle('hidden', !checkbox.checked);
            }
        </script>
    @endpush
@endsection
