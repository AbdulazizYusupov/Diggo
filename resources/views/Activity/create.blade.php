@extends('Layout.app')

@section('title', 'Yangi faoliyat qo\'shish')

@section('content')
    <div class="w-full space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('activities.index') }}"
                    class="w-12 h-12 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl flex items-center justify-center transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white">Yangi faoliyat qo'shish</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Barcha majburiy maydonlarni to'ldiring</p>
                </div>
            </div>
            <div
                class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-200 dark:shadow-none transform rotate-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
        </div>

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

        <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- ── LEFT ── -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Info -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-5">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Asosiy
                            ma'lumotlar</h2>
                        <div>
                            <label for="name"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Faoliyat nomi <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('name') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium outline-none"
                                placeholder="Masalan: Python asoslari bo'yicha test">
                            @error('name')
                                <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tavsif <span
                                    class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span></label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none resize-none"
                                placeholder="Faoliyat haqida qo'shimcha ma'lumot...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Type, Status, Group -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-5">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Turkumlash
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="type"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Turi <span
                                        class="text-rose-500">*</span></label>
                                <select name="type" id="type" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('type') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all cursor-pointer outline-none">
                                    <option value="">Tanlang</option>
                                    @foreach ($types as $type)
    <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
        {{ $type->label() }}
    </option>
@endforeach
                                </select>
                                @error('type')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="status"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Holat <span
                                        class="text-rose-500">*</span></label>
                                <select name="status" id="status" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('status') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all cursor-pointer outline-none">
                                   @foreach ($statuses as $status)
    <option value="{{ $status->value }}" {{ old('status', 'active') == $status->value ? 'selected' : '' }}>
        {{ $status->label() }}
    </option>
@endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Group custom dropdown -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Guruh <span
                                    class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span></label>
                            <div class="relative" id="groupCustomSelectContainer">
                                <div onclick="toggleGroupDropdown()"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer group hover:border-purple-500 transition-all">
                                    <span id="selectedGroupLabel" class="text-sm text-gray-500 dark:text-gray-400">
                                        @php $selectedGroup = $groups->firstWhere('id', old('group_id')); @endphp
                                        {{ $selectedGroup ? $selectedGroup->name : 'Guruhni tanlang' }}
                                    </span>
                                    <input type="hidden" name="group_id" id="group_id_input"
                                        value="{{ old('group_id') }}">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-500 transition-transform"
                                        id="groupArrowIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <div id="groupDropdownMenu"
                                    class="hidden absolute z-[130] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                    <div class="p-2 border-b dark:border-gray-700">
                                        <input type="text" id="groupSearchInput" oninput="filterGroupsFunc()"
                                            placeholder="Guruh yoki kurs nomini yozing..."
                                            class="w-full px-3 py-2 bg-gray-100 dark:bg-slate-900 dark:text-white border-none rounded-lg text-sm focus:ring-1 focus:ring-purple-500 outline-none">
                                    </div>
                                    <div class="max-h-60 overflow-y-auto">
                                        <div onclick="selectGroupFunc('', 'Guruhni tanlang')"
                                            class="px-4 py-3 text-sm text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer italic">
                                            Tanlovni bekor qilish</div>
                                        @foreach ($groups as $group)
                                            <div onclick="selectGroupFunc('{{ $group->id }}', '{{ $group->name }}')"
                                                class="group-option-item px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-500/10 cursor-pointer flex flex-col border-b border-gray-50 dark:border-gray-700/50 last:border-none">
                                                <span
                                                    class="font-bold group-search-name text-purple-600 dark:text-purple-400">{{ $group->name }}</span>
                                                <span
                                                    class="text-xs text-gray-400 group-search-course">{{ $group->course->title ?? '' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="noGroupFound" class="hidden p-4 text-center text-sm text-gray-500">Guruh
                                        topilmadi...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date, Time, Coins -->
                    <!-- Date, Time, Coins -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 space-y-5">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Vaqt va
                            ball</h2>

                        {{-- grid-cols-3 → grid-cols-4 --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                            {{-- 1. Sana --}}
                            <div>
                                <label for="date"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Sana <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" name="date" id="date" value="{{ old('date') }}"
                                    required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('date') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                                @error('date')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 2. Boshlanish vaqti (YANGI) --}}
                            {{-- 2. Boshlanish vaqti --}}
                            <div>
                                <label for="start_time"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Boshlanish vaqti
                                    <span id="start_time_required" class="text-rose-500 hidden">*</span>
                                    <span id="start_time_optional"
                                        class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                                </label>
                                <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('start_time') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                                @error('start_time')
                                    <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 3. Topshirish vaqti --}}
                            {{-- 3. Topshirish vaqti --}}
                            <div>
                                <label for="due_time"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Topshirish vaqti
                                    <span id="due_time_required" class="text-rose-500 hidden">*</span>
                                    <span id="due_time_optional"
                                        class="text-gray-400 text-xs font-normal">(ixtiyoriy)</span>
                                </label>
                                <input type="time" name="due_time" id="due_time" value="{{ old('due_time') }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('due_time') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
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
                                <input type="number" name="max_coins" id="max_coins" value="{{ old('max_coins', 0) }}"
                                    required min="0" max="1000"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border @error('max_coins') border-rose-500 @else border-gray-200 dark:border-gray-700 @enderror rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none"
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

                            {{-- Preview card (hidden until file selected) --}}
                            <div id="imgPreviewWrap" class="hidden mb-3">
                                <div class="relative inline-block group/thumb">
                                    <img id="imgPreviewTag" src="" alt="rasm ko'rinishi"
                                        class="h-36 w-36 rounded-2xl object-cover ring-2 ring-purple-300 dark:ring-purple-500/50 shadow-lg shadow-purple-100 dark:shadow-none">
                                    {{-- overlay filename --}}
                                    <div
                                        class="absolute bottom-0 left-0 right-0 px-2 py-1.5 bg-black/55 backdrop-blur-sm rounded-b-2xl">
                                        <p id="imgPreviewName"
                                            class="text-[10px] text-white font-medium truncate leading-tight"></p>
                                        <p id="imgPreviewSize" class="text-[10px] text-white/70 truncate leading-tight">
                                        </p>
                                    </div>
                                    {{-- remove button --}}
                                    <button type="button" id="removeImgBtn"
                                        class="absolute -top-2 -right-2 w-7 h-7 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center shadow-lg font-bold text-lg leading-none transition-all active:scale-90 opacity-0 group-hover/thumb:opacity-100">×</button>
                                </div>
                            </div>

                            {{-- Drop zone --}}
                            <label for="imageInput" id="imageDropZone"
                                class="flex flex-col items-center justify-center gap-2 w-full h-28 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-purple-400 dark:hover:border-purple-500 bg-gray-50 dark:bg-slate-800/60 cursor-pointer transition-all group/drop">
                                <div
                                    class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center group-hover/drop:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="text-center pointer-events-none">
                                    <p
                                        class="text-sm font-semibold text-gray-600 dark:text-gray-300 group-hover/drop:text-purple-600 dark:group-hover/drop:text-purple-400 transition-colors">
                                        Rasm tanlang yoki torting</p>
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

                            {{-- Preview card (hidden until file selected) --}}
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
                                        Hujjat tanlang yoki torting</p>
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

                <!-- ── RIGHT: Settings ── -->
                <div class="lg:col-span-1 space-y-6">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-5 sticky top-6">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Sozlamalar
                        </h2>

                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800 rounded-xl">
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">Majburiy faoliyat</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Barcha talabalar uchun</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_required" value="0">
                                <input type="checkbox" name="is_required" value="1"
                                    {{ old('is_required') ? 'checked' : '' }} class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                                </div>
                            </label>
                        </div>

                        <div
                            class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs text-blue-700 dark:text-blue-400 leading-relaxed">Yaratuvchi sifatida
                                    sizning akkauntingiz avtomatik saqlanadi.</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2">
                            <button type="submit"
                                class="w-full px-6 py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-200 dark:shadow-none transition-all active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Saqlash
                            </button>
                            <a href="{{ route('activities.index') }}"
                                class="w-full px-6 py-3.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95 flex items-center justify-center">
                                Bekor qilish
                            </a>
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
                }

                function clearImg() {
                    imgInput.value = '';
                    imgTag.src = '';
                    imgWrap.classList.add('hidden');
                    imgDrop.classList.remove('hidden');
                }

                imgInput.addEventListener('change', e => {
                    if (e.target.files[0]) showImg(e.target.files[0]);
                });
                imgRemove.addEventListener('click', clearImg);

                imgDrop.addEventListener('dragover', e => {
                    e.preventDefault();
                    imgDrop.classList.add('border-purple-400', 'dark:border-purple-500');
                });
                imgDrop.addEventListener('dragleave', () => imgDrop.classList.remove('border-purple-400',
                    'dark:border-purple-500'));
                imgDrop.addEventListener('drop', e => {
                    e.preventDefault();
                    imgDrop.classList.remove('border-purple-400', 'dark:border-purple-500');
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
                }

                function clearFile() {
                    fileInput.value = '';
                    fileWrap.classList.add('hidden');
                    fileDrop.classList.remove('hidden');
                }

                fileInput.addEventListener('change', e => {
                    if (e.target.files[0]) showFile(e.target.files[0]);
                });
                fileRemove.addEventListener('click', clearFile);

                fileDrop.addEventListener('dragover', e => {
                    e.preventDefault();
                    fileDrop.classList.add('border-indigo-400', 'dark:border-indigo-500');
                });
                fileDrop.addEventListener('dragleave', () => fileDrop.classList.remove('border-indigo-400',
                    'dark:border-indigo-500'));
                fileDrop.addEventListener('drop', e => {
                    e.preventDefault();
                    fileDrop.classList.remove('border-indigo-400', 'dark:border-indigo-500');
                    const f = e.dataTransfer.files[0];
                    if (f) {
                        const dt = new DataTransfer();
                        dt.items.add(f);
                        fileInput.files = dt.files;
                        showFile(f);
                    }
                });

                /* ── GROUP DROPDOWN ──────────────────────────────────────── */
                window.toggleGroupDropdown = function() {
                    const menu = document.getElementById('groupDropdownMenu');
                    const arrow = document.getElementById('groupArrowIcon');
                    const hidden = menu.classList.contains('hidden');
                    menu.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-180');
                    if (hidden) document.getElementById('groupSearchInput').focus();
                };
                window.selectGroupFunc = function(id, name) {
                    document.getElementById('group_id_input').value = id;
                    const lbl = document.getElementById('selectedGroupLabel');
                    lbl.innerText = name;
                    lbl.classList.toggle('text-gray-500', !id);
                    lbl.classList.toggle('font-medium', !!id);
                    window.toggleGroupDropdown();
                };
                window.filterGroupsFunc = function() {
                    const q = document.getElementById('groupSearchInput').value.toLowerCase();
                    let found = false;
                    document.querySelectorAll('.group-option-item').forEach(el => {
                        const n = el.querySelector('.group-search-name').innerText.toLowerCase();
                        const c = el.querySelector('.group-search-course').innerText.toLowerCase();
                        const show = n.includes(q) || c.includes(q);
                        el.style.display = show ? '' : 'none';
                        if (show) found = true;
                    });
                    document.getElementById('noGroupFound').classList.toggle('hidden', found);
                };
                document.addEventListener('click', e => {
                    const c = document.getElementById('groupCustomSelectContainer');
                    if (c && !c.contains(e.target)) {
                        document.getElementById('groupDropdownMenu').classList.add('hidden');
                        document.getElementById('groupArrowIcon').classList.remove('rotate-180');
                    }
                });

            });
            /* ── START TIME & DUE TIME REQUIRED BY TYPE ─────────────────── */
            const requiredTypes = ['exam', 'competition'];
            const typeSelect = document.getElementById('type');
            const startInput = document.getElementById('start_time');
            const dueInput = document.getElementById('due_time');
            const startReqMark = document.getElementById('start_time_required');
            const startOptMark = document.getElementById('start_time_optional');
            const dueReqMark = document.getElementById('due_time_required');
            const dueOptMark = document.getElementById('due_time_optional');

            function updateTimeRequirements() {
                const isRequired = requiredTypes.includes(typeSelect.value);

                // start_time
                startInput.required = isRequired;
                startReqMark.classList.toggle('hidden', !isRequired);
                startOptMark.classList.toggle('hidden', isRequired);

                // due_time
                dueInput.required = isRequired;
                dueReqMark.classList.toggle('hidden', !isRequired);
                dueOptMark.classList.toggle('hidden', isRequired);
            }

            // Sahifa yuklanganda tekshir (old value bo'lishi mumkin)
            updateTimeRequirements();
            typeSelect.addEventListener('change', updateTimeRequirements);
        </script>
    @endpush
@endsection
