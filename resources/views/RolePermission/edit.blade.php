@extends('Layout.app')

@section('title', 'Ruxsatlarni boshqarish - ' . $role->name)

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('role-permissions.index') }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div class="hidden sm:flex p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        {{ $role->name }}
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ruxsatlarni boshqarish
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="selectAll()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-semibold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Hammasini tanlash
                </button>
                <button type="button" onclick="deselectAll()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Bekor qilish
                </button>
            </div>
        </div>

        <form action="{{ route('role-permissions.update', $role->id) }}" method="POST" id="permissionsForm">
            @csrf
            @method('PUT')

            {{-- Statistics Bar --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                        <p class="text-sm text-purple-600 dark:text-purple-400 font-medium mb-1">Jami ruxsatlar</p>
                        <p class="text-3xl font-bold text-purple-900 dark:text-purple-100" id="totalPermissions">
                            {{ $groupedPermissions->flatten()->count() }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mb-1">Tanlangan</p>
                        <p class="text-3xl font-bold text-emerald-900 dark:text-emerald-100" id="selectedCount">
                            {{ count($assignedPermissions) }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-1">Modullar</p>
                        <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">
                            {{ $groupedPermissions->count() }}
                        </p>
                    </div>
                    <div class="text-center p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                        <p class="text-sm text-amber-600 dark:text-amber-400 font-medium mb-1">Tanlanmagan</p>
                        <p class="text-3xl font-bold text-amber-900 dark:text-amber-100" id="unselectedCount">
                            {{ $groupedPermissions->flatten()->count() - count($assignedPermissions) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Quick Search --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Ruxsatlarni qidiring..."
                        class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition-all">
                </div>
            </div>

            {{-- Permissions Grid --}}
            <div class="space-y-4">
                @foreach ($groupedPermissions as $module => $permissions)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden permission-module"
                        data-module="{{ $module }}">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-b border-gray-200 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white capitalize">
                                            {{ str_replace('-', ' ', $module) }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                            <span class="module-selected-count">{{ $permissions->whereIn('id', $assignedPermissions)->count() }}</span> /
                                            {{ $permissions->count() }} tanlangan
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="selectModule('{{ $module }}')"
                                        class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-medium rounded-lg transition-all text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    <button type="button" onclick="deselectModule('{{ $module }}')"
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-all text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        class="permission-item relative flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 border-2 border-transparent hover:border-purple-200 dark:hover:border-purple-800 cursor-pointer transition-all group"
                                        data-permission="{{ strtolower($permission->name) }}"
                                        data-module="{{ $module }}">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}
                                                class="permission-checkbox w-5 h-5 text-purple-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 transition-all cursor-pointer">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <span
                                                class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors block truncate"
                                                title="{{ $permission->name }}">
                                                {{ $permission->name }}
                                            </span>
                                        </div>
                                        <div
                                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
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

                <div id="noResults" class="hidden bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
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
                class="sticky bottom-0 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span id="selectedCountBottom" class="font-bold text-purple-600 dark:text-purple-400">
                            {{ count($assignedPermissions) }}
                        </span> ta ruxsat tanlandi
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('role-permissions.index') }}"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Bekor qilish
                        </a>
                        <button type="submit"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
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
    </script>
@endsection