@extends('Layout.app')

@section('title', 'Ruxsatlarni boshqarish - ' . $role->name)

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('role-permissions.index') }}"
                    class="p-2.5 inline-flex items-center justify-center
           /* Light mode: oq emas, och kulrang/slate fon */
           bg-slate-100 border border-slate-200 text-slate-600
           hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300
           
           /* Dark mode: o'z holicha qoldi, chunki u yerda ko'rinishi yaxshi edi */
           dark:bg-slate-800 dark:border-slate-700 dark:text-gray-400
           dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 dark:hover:border-indigo-500/30
           
           rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div
                    class="hidden sm:flex p-3 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        {{ $role->name }}
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ruxsatlarni boshqarish
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="selectAll()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 
               bg-emerald-100/80 hover:bg-emerald-200/80 border border-emerald-200 
               dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 dark:border-emerald-500/40
               text-emerald-800 dark:text-emerald-300 font-semibold rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Hammasini tanlash
                </button>

                <button type="button" onclick="deselectAll()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 
               bg-slate-200/60 hover:bg-slate-200 border border-slate-300
               dark:bg-slate-800 dark:hover:bg-slate-700 dark:border-slate-600
               text-slate-800 dark:text-slate-300 font-semibold rounded-xl transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Bekor qilish
                </button>
            </div>
        </div>

        <form action="{{ route('role-permissions.update', $role->id) }}" method="POST" id="permissionsForm"
            class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Statistics Bar --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-6">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div
                        class="text-center p-4 bg-indigo-50/80 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-xl shadow-sm">
                        <p class="text-xs uppercase tracking-wider text-indigo-600 dark:text-indigo-400 font-bold mb-1">Jami
                            ruxsatlar</p>
                        <p class="text-3xl font-black text-indigo-900 dark:text-indigo-100" id="totalPermissions">
                            {{ $groupedPermissions->flatten()->count() }}
                        </p>
                    </div>

                    <div
                        class="text-center p-4 bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl shadow-sm">
                        <p class="text-xs uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-bold mb-1">
                            Tanlangan</p>
                        <p class="text-3xl font-black text-emerald-900 dark:text-emerald-100" id="selectedCount">
                            {{ count($assignedPermissions) }}
                        </p>
                    </div>

                    <div
                        class="text-center p-4 bg-sky-50/80 dark:bg-sky-500/10 border border-sky-100 dark:border-sky-500/20 rounded-xl shadow-sm">
                        <p class="text-xs uppercase tracking-wider text-sky-600 dark:text-sky-400 font-bold mb-1">Modullar
                        </p>
                        <p class="text-3xl font-black text-sky-900 dark:text-sky-100">
                            {{ $groupedPermissions->count() }}
                        </p>
                    </div>

                    <div
                        class="text-center p-4 bg-amber-50/80 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-xl shadow-sm">
                        <p class="text-xs uppercase tracking-wider text-amber-600 dark:text-amber-400 font-bold mb-1">
                            Tanlanmagan</p>
                        <p class="text-3xl font-black text-amber-900 dark:text-amber-100" id="unselectedCount">
                            {{ $groupedPermissions->flatten()->count() - count($assignedPermissions) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Quick Search --}}
            {{-- Quick Search --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-4">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 dark:text-indigo-400/50" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input type="text" id="searchInput" placeholder="Ruxsatlarni qidiring..."
                        class="block w-full pl-11 pr-12 py-2.5 
            bg-gray-50 dark:bg-slate-800/50 
            border border-gray-300 dark:border-indigo-500/30 
            rounded-xl text-gray-900 dark:text-white 
            placeholder-gray-400 dark:placeholder-gray-500 
            outline-none transition-all duration-200
            focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 
            dark:focus:ring-indigo-500/20 dark:focus:border-indigo-400">

                    <button type="button" id="clearSearch"
                        class="hidden absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Permissions Grid --}}
            <div class="space-y-4">
                @foreach ($groupedPermissions as $module => $permissions)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden permission-module"
                        data-module="{{ $module }}">
                        <div
                            class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-gray-200 dark:border-indigo-500/20 p-6 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="p-2.5 bg-indigo-100 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-500/40 rounded-xl shadow-sm text-indigo-700 dark:text-indigo-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <h3
                                            class="text-lg font-bold text-gray-900 dark:text-white capitalize tracking-tight">
                                            {{ str_replace('-', ' ', $module) }}
                                        </h3>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400 mt-0.5 flex items-center gap-1.5">
                                            <span
                                                class="px-1.5 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-md font-bold text-xs border border-indigo-100 dark:border-indigo-500/20">
                                                <span
                                                    class="module-selected-count">{{ $permissions->whereIn('id', $assignedPermissions)->count() }}</span>
                                                / {{ $permissions->count() }}
                                            </span>
                                            <span class="font-medium">tanlangan</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="selectModule('{{ $module }}')"
                                        class="p-2.5 bg-emerald-100/80 hover:bg-emerald-200/80 border border-emerald-200 dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 dark:border-emerald-500/40 text-emerald-700 dark:text-emerald-400 font-medium rounded-xl transition-all active:scale-95 shadow-sm"
                                        title="Modulni hammasini tanlash">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>

                                    <button type="button" onclick="deselectModule('{{ $module }}')"
                                        class="p-2.5 bg-slate-200/70 hover:bg-slate-300/70 border border-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 dark:border-slate-500 text-slate-700 dark:text-slate-300 font-medium rounded-xl transition-all active:scale-95 shadow-sm"
                                        title="Modulni bekor qilish">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                @foreach ($permissions as $permission)
                                    <label
                                        class="permission-item relative flex items-start gap-3 p-4 
                       bg-white dark:bg-slate-800/40 
                       border-2 border-gray-100 dark:border-slate-700/50
                       rounded-xl cursor-pointer transition-all duration-200 group
                       hover:bg-indigo-50/50 dark:hover:bg-indigo-500/10 
                       hover:border-indigo-200 dark:hover:border-indigo-500/30 shadow-sm"
                                        data-permission="{{ strtolower($permission->name) }}"
                                        data-module="{{ $module }}">

                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}
                                                class="permission-checkbox w-5 h-5 
                               text-indigo-600 bg-white dark:bg-slate-900 
                               border-gray-300 dark:border-slate-600 
                               rounded focus:ring-offset-0 focus:ring-4 focus:ring-indigo-500/10 
                               dark:focus:ring-indigo-500/20 transition-all cursor-pointer">
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <span
                                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 
                               group-hover:text-indigo-700 dark:group-hover:text-indigo-300 
                               transition-colors block truncate"
                                                title="{{ $permission->name }}">
                                                {{ $permission->name }}
                                            </span>
                                        </div>

                                        <div
                                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4 text-indigo-400 dark:text-indigo-500/50" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div id="noResults"
                    class="hidden bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hech narsa topilmadi</h3>
                    <p class="text-gray-500 dark:text-gray-400">Boshqa kalit so'z bilan qidirib ko'ring</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div
                class="sticky bottom-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-gray-200 dark:border-indigo-500/20 p-6 mt-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center px-3 py-1 bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 rounded-lg font-bold border border-indigo-200 dark:border-indigo-500/30"
                            id="selectedCountBottom">
                            {{ count($assignedPermissions) }}
                        </span>
                        <span>ta ruxsat tanlandi</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('role-permissions.index') }}"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 
                       bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 
                       border border-slate-200 dark:border-slate-700
                       text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-all active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Bekor qilish
                        </a>

                        <button type="submit"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-8 py-3 
           bg-indigo-500 hover:bg-indigo-600 text-white 
           dark:bg-indigo-600 dark:hover:bg-indigo-700
           font-semibold rounded-xl shadow-md shadow-indigo-100 dark:shadow-none 
           active:scale-95 transition-all duration-200">
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

    <script>
        // Update counts
        function updateCounts() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const checked = document.querySelectorAll('.permission-checkbox:checked');

            document.getElementById('selectedCount').textContent = checked.length;
            document.getElementById('selectedCountBottom').textContent = checked.length;
            document.getElementById('unselectedCount').textContent = checkboxes.length - checked.length;

            // Update module counts
            document.querySelectorAll('.permission-module').forEach(module => {
                const moduleCheckboxes = module.querySelectorAll('.permission-checkbox');
                const moduleChecked = module.querySelectorAll('.permission-checkbox:checked');
                module.querySelector('.module-selected-count').textContent = moduleChecked.length;
            });
        }

        // Select all
        function selectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
            updateCounts();
        }

        // Deselect all
        function deselectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
            updateCounts();
        }

        // Select module
        function selectModule(module) {
            document.querySelectorAll(`[data-module="${module}"] .permission-checkbox`).forEach(cb => cb.checked = true);
            updateCounts();
        }

        // Deselect module
        function deselectModule(module) {
            document.querySelectorAll(`[data-module="${module}"] .permission-checkbox`).forEach(cb => cb.checked = false);
            updateCounts();
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const modules = document.querySelectorAll('.permission-module');
            let visibleCount = 0;

            modules.forEach(module => {
                const items = module.querySelectorAll('.permission-item');
                let moduleHasVisible = false;

                items.forEach(item => {
                    const permissionName = item.dataset.permission;
                    if (permissionName.includes(searchTerm)) {
                        item.style.display = '';
                        moduleHasVisible = true;
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                module.style.display = moduleHasVisible ? '' : 'none';
            });

            document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
        });

        // Update counts on checkbox change
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateCounts);
        });

        // Initial count
        updateCounts();


        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');

        // Qidiruv funksiyasini alohida nom bilan yozib olamiz qayta ishlatish uchun
        function performSearch(term) {
            const modules = document.querySelectorAll('.permission-module');
            let visibleCount = 0;

            modules.forEach(module => {
                const items = module.querySelectorAll('.permission-item');
                let moduleHasVisible = false;

                items.forEach(item => {
                    const permissionName = item.dataset.permission;
                    if (permissionName.includes(term.toLowerCase())) {
                        item.style.display = '';
                        moduleHasVisible = true;
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                module.style.display = moduleHasVisible ? '' : 'none';
            });

            document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';

            // Tugmani ko'rsatish yoki yashirish
            clearBtn.classList.toggle('hidden', term.length === 0);
        }

        // Inputga yozilganda
        searchInput.addEventListener('input', function(e) {
            performSearch(e.target.value);
        });

        // Tozalash tugmasi bosilganda
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            performSearch('');
            searchInput.focus();
        });
    </script>
@endsection
