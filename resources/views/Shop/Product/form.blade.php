@extends('Layout.app')

@section('title', isset($product) ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.shop.products.index') }}"
                class="p-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                    {{ isset($product) ? 'Mahsulotni tahrirlash' : 'Yangi mahsulot qo\'shish' }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ isset($product) ? $product->name : 'Do\'kon uchun yangi mahsulot' }}</p>
            </div>
        </div>

        <form method="POST"
            action="{{ isset($product) ? route('admin.shop.products.update', $product) : route('admin.shop.products.store') }}"
            enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-500/30 rounded-2xl p-4">
                    <ul class="list-disc list-inside text-sm text-rose-700 dark:text-rose-400 space-y-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Asosiy ma'lumotlar --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-5">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Asosiy ma'lumotlar
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Mahsulot nomi <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                            placeholder="Masalan: AirPods Pro"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kategoriya <span
                                class="text-rose-500">*</span></label>
                        <select name="shop_category_id" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer">
                            <option value="">Kategoriya tanlang</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('shop_category_id', $product->shop_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon ?? '' }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Brend</label>
                        <input type="text" name="brand" value="{{ old('brand', $product->brand ?? '') }}"
                            placeholder="Apple, Samsung..."
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Qisqacha
                            tavsif</label>
                        <input type="text" name="short_description"
                            value="{{ old('short_description', $product->short_description ?? '') }}"
                            placeholder="Karta ustida ko'rinadigan qisqa tavsif"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">To'liq
                            tavsif</label>
                        <textarea name="description" rows="4" placeholder="Mahsulot haqida to'liq ma'lumot..."
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-none">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Narx va Zaxira --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-5">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Narx va Zaxira</h2>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Coin narxi <span
                                class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-amber-500 font-bold text-sm">🪙</span>
                            <input type="number" name="coin_price"
                                value="{{ old('coin_price', $product->coin_price ?? '') }}" required min="1"
                                placeholder="500"
                                class="w-full pl-9 pr-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">SKU (Mahsulot
                            kodi)</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
                            placeholder="Avtomatik yaratiladi"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white font-mono text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Zaxira
                            miqdori</label>
                        <input type="number" name="stock_quantity"
                            value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" min="0"
                            id="stockInput"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>

                    <div class="sm:col-span-2 flex flex-col justify-center">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Zaxira turi</label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_unlimited" value="0">
                            <input type="checkbox" name="is_unlimited" value="1" id="unlimitedCheck"
                                {{ old('is_unlimited', $product->is_unlimited ?? false) ? 'checked' : '' }}
                                onchange="document.getElementById('stockInput').disabled = this.checked"
                                class="w-5 h-5 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Cheksiz zaxira</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Max (1
                            student)</label>
                        <input type="number" name="max_per_student"
                            value="{{ old('max_per_student', $product->max_per_student ?? 1) }}" min="1"
                            max="99"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tartib</label>
                        <input type="number" name="sort_order"
                            value="{{ old('sort_order', $product->sort_order ?? 0) }}" min="0"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            {{-- Rasm --}}
            {{-- Rasm --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Rasmlar</h2>

                {{-- Asosiy rasm --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Asosiy rasm</label>
                    @if (isset($product) && $product->image)
                        <div class="mb-3" id="mainImagePreview">
                            <div class="relative inline-block">
                                <img src="{{ $product->image_url }}" id="mainImgTag"
                                    class="h-32 w-32 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700">
                            </div>
                        </div>
                    @else
                        <div id="mainImagePreview" class="mb-3 hidden">
                            <div class="relative inline-block">
                                <img id="mainImgTag" src=""
                                    class="h-32 w-32 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700">
                                <button type="button" id="removeMainImg"
                                    class="absolute -top-1 -right-1 bg-rose-500 hover:bg-rose-600 text-white rounded-full w-6 h-6 text-sm font-bold flex items-center justify-center shadow">×</button>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="image" id="mainImageInput" accept="image/*"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 4MB</p>
                </div>

                {{-- Qo'shimcha rasmlar --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Qo'shimcha
                        rasmlar</label>

                    {{-- Mavjud rasmlar (edit rejimida) --}}
                    @if (isset($product) && $product->images)
                        <div class="flex gap-2 mb-3 flex-wrap" id="existingImages">
                            @foreach ($product->images as $index => $img)
                                <div class="relative" data-existing="{{ $img }}">
                                    <img src="{{ asset('storage/' . $img) }}"
                                        class="h-20 w-20 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700">
                                    <button type="button"
                                        class="remove-existing absolute -top-1 -right-1 bg-rose-500 hover:bg-rose-600 text-white rounded-full w-6 h-6 text-sm font-bold flex items-center justify-center shadow">×</button>
                                    <input type="hidden" name="existing_images[]" value="{{ $img }}">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Yangi tanlangan rasmlar preview --}}
                    <div id="imagePreview" class="flex gap-2 mb-3 flex-wrap"></div>

                    <input type="file" id="imagesInput" accept="image/*" multiple
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">

                    {{-- Yig'ilgan fayllar shu yerga hidden input sifatida qo'shiladi --}}
                    <div id="hiddenInputs"></div>

                    <p class="text-xs text-gray-400 mt-1">Bir necha marta bosib ko'proq rasm qo'shish mumkin</p>
                </div>
            </div>

            {{-- Holat --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-4">Holat</h2>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                            class="w-5 h-5 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Sotuvda (faol)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1"
                            {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                            class="w-5 h-5 rounded text-amber-500 border-gray-300 focus:ring-amber-500">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">⭐ Featured (maxsus)</span>
                    </label>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="flex-1 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                    {{ isset($product) ? 'Saqlash' : 'Qo\'shish' }}
                </button>
                <a href="{{ route('admin.shop.products.index') }}"
                    class="px-6 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all active:scale-95">
                    Bekor qilish
                </a>
            </div>
        </form>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // ── Cheksiz zaxira toggle ──
                const unlimitedCheck = document.getElementById('unlimitedCheck');
                const stockInput = document.getElementById('stockInput');
                if (unlimitedCheck && stockInput) {
                    if (unlimitedCheck.checked) stockInput.disabled = true;
                    unlimitedCheck.addEventListener('change', () => {
                        stockInput.disabled = unlimitedCheck.checked;
                    });
                }

                // ── Asosiy rasm preview ──
                const mainInput = document.getElementById('mainImageInput');
                const mainPreview = document.getElementById('mainImagePreview');
                const mainImgTag = document.getElementById('mainImgTag');
                const removeMain = document.getElementById('removeMainImg');

                if (mainInput) {
                    mainInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (!file) return;
                        mainImgTag.src = URL.createObjectURL(file);
                        mainPreview.classList.remove('hidden');

                        // O'chirish tugmasini qo'shish (agar hali yo'q bo'lsa)
                        if (removeMain) {
                            removeMain.classList.remove('hidden');
                            removeMain.onclick = () => {
                                mainInput.value = '';
                                mainPreview.classList.add('hidden');
                                mainImgTag.src = '';
                            };
                        }
                    });
                }

                // ── Mavjud rasmlarni o'chirish (edit rejimi) ──
                document.querySelectorAll('.remove-existing').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.closest('[data-existing]').remove();
                    });
                });

                // ── Qo'shimcha rasmlar — yig'ib borish ──
                const imagesInput = document.getElementById('imagesInput');
                const imagePreview = document.getElementById('imagePreview');
                const hiddenInputs = document.getElementById('hiddenInputs');

                if (!imagesInput) return;

                const dt = new DataTransfer(); // Yig'ilgan fayllar

                imagesInput.addEventListener('change', function() {
                    const MAX = 8;

                    Array.from(this.files).forEach(file => {
                        // Har iteratsiyada qayta hisoblanadi — shu to'g'ri
                        const existingCount = document.querySelectorAll('[data-existing]').length;
                        const totalNow = existingCount + dt.files.length;

                        if (totalNow >= MAX) {
                            alert(`Maksimal ${MAX} ta qo'shimcha rasm yuklash mumkin!`);
                            return;
                        }

                        const exists = Array.from(dt.files).some(
                            f => f.name === file.name && f.size === file.size && f.lastModified ===
                            file.lastModified
                        );
                        if (exists) return;

                        dt.items.add(file);
                        addPreviewCard(file);
                    });

                    syncFiles();
                    this.value = ''; // Inputni tozalash — keyingi safar ham change ishlashi uchun
                });

                function addPreviewCard(file) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative group';

                    const img = document.createElement('img');
                    img.className = 'h-20 w-20 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700';
                    img.src = URL.createObjectURL(file);

                    // Fayl nomi tooltip
                    img.title = file.name;

                    const del = document.createElement('button');
                    del.type = 'button';
                    del.className =
                        'absolute -top-1 -right-1 bg-rose-500 hover:bg-rose-600 text-white rounded-full w-6 h-6 text-sm font-bold flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity';
                    del.innerHTML = '×';
                    del.onclick = () => {
                        // dt dan o'chirish
                        const newDt = new DataTransfer();
                        Array.from(dt.files).forEach(f => {
                            if (!(f.name === file.name && f.size === file.size && f.lastModified === file
                                    .lastModified)) {
                                newDt.items.add(f);
                            }
                        });
                        dt.items.clear();
                        Array.from(newDt.files).forEach(f => dt.items.add(f));

                        URL.revokeObjectURL(img.src); // Memory leak oldini olish
                        wrapper.remove();
                        syncFiles();
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(del);
                    imagePreview.appendChild(wrapper);
                }

                function syncFiles() {
                    // Hidden inputlarni tozalab qaytadan qurish
                    hiddenInputs.innerHTML = '';

                    Array.from(dt.files).forEach(file => {
                        const singleDt = new DataTransfer();
                        singleDt.items.add(file);

                        const inp = document.createElement('input');
                        inp.type = 'file';
                        inp.name = 'images[]';
                        inp.style.display = 'none';
                        inp.files = singleDt.files;

                        hiddenInputs.appendChild(inp); // ← BU YETISHMAYOTGAN ENG MUHIM QATOR
                    });
                }
            });
        </script>
    @endpush
@endsection
