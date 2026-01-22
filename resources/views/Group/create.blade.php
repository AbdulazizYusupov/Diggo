@extends('Layout.app')

@section('title', 'Yangi guruh qo\'shish')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Icon Box --}}
                <div
                    class="hidden sm:flex p-3 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Yangi guruh qo'shish
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Yangi o'quv guruhi yaratish
                    </p>
                </div>
            </div>

            {{-- Back Button --}}
            <a href="{{ route('groups.index') }}"
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
        <form action="{{ route('groups.store') }}" method="POST">
            @csrf

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 overflow-hidden">
                <div class="p-6 lg:p-8 space-y-6">

                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none">
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
                                <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Guruh nomi <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
                                           text-gray-900 dark:text-white placeholder-gray-400 
                                           transition-all outline-none"
                                    placeholder="Masalan: Frontend-101">
                                @error('name')
                                    <p class="mt-1.5 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Course --}}
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                    Kurs <span class="text-rose-500">*</span>
                                </label>

                                <div class="relative" id="courseCustomSelectContainer">
                                    <div onclick="toggleCourseDropdown()" id="courseSelectButton"
                                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer group hover:border-blue-500 transition-all @error('cours_id') border-rose-500 @enderror">

                                        <span id="selectedCourseLabel" class="text-sm text-gray-500 dark:text-gray-400">
                                            @php
                                                $preselected = $courses->firstWhere(
                                                    'id',
                                                    old('cours_id', $preselectedCourseId),
                                                );
                                            @endphp
                                            {{ $preselected ? $preselected->code . ' - ' . $preselected->title : 'Kursni tanlang' }}
                                        </span>

                                        <input type="hidden" name="cours_id" id="cours_id_input"
                                            value="{{ old('cours_id', $preselectedCourseId) }}" required>

                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-transform"
                                            id="courseArrowIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>

                                    <div id="courseDropdownMenu"
                                        class="hidden absolute z-[120] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                        <div class="p-2 border-b dark:border-gray-700">
                                            <input type="text" id="courseSearchInput" oninput="filterCoursesFunc()"
                                                placeholder="Kurs nomini yoki kodini yozing..."
                                                class="w-full px-3 py-2 bg-gray-100 dark:bg-slate-900 dark:text-white border-none rounded-lg text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                                        </div>

                                        <div class="max-h-60 overflow-y-auto custom-scrollbar" id="courseOptionsWrapper">
                                            <div onclick="selectCourseFunc('', 'Kursni tanlang')"
                                                class="px-4 py-3 text-sm text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer italic">
                                                Tanlovni bekor qilish
                                            </div>

                                            @foreach ($courses as $course)
                                                <div onclick="selectCourseFunc('{{ $course->id }}', '{{ $course->code }} - {{ $course->title }}')"
                                                    class="course-option-item px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-500/10 cursor-pointer flex flex-col border-b border-gray-50 dark:border-gray-700/50 last:border-none">
                                                    <span
                                                        class="font-bold course-search-text text-blue-600 dark:text-blue-400">{{ $course->code }}</span>
                                                    <span
                                                        class="text-xs lg:text-sm font-medium course-search-title">{{ $course->title }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div id="noCourseFound" class="hidden p-4 text-center text-sm text-gray-500">
                                            Bunday kurs topilmadi...
                                        </div>
                                    </div>
                                </div>

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
                                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 20) }}"
                                    min="1" max="100" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
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
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <div
                                            class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                        <span class="ms-3 text-sm font-bold text-gray-900 dark:text-gray-300">Faol</span>
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
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                <input type="time" name="start_time" id="start_time" step="60"
                                    value="{{ old('start_time', '09:00') }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
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
                                <input type="time" name="end_time" id="end_time" step="60"
                                    value="{{ old('end_time', '11:00') }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
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
                                        $oldDays = old('days_of_week', []);
                                    @endphp
                                    @foreach ($days as $value => $label)
                                        <label
                                            class="relative flex items-center justify-center px-4 py-3 bg-gray-50 dark:bg-slate-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer transition-all hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-500/10">
                                            <input type="checkbox" name="days_of_week[]" value="{{ $value }}"
                                                class="sr-only peer" {{ in_array($value, $oldDays) ? 'checked' : '' }}>
                                            <span
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 peer-checked:text-blue-600 dark:peer-checked:text-blue-400">
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
                                    value="{{ old('start_date', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
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
                                    value="{{ old('end_date', date('Y-m-d', strtotime('+6 months'))) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl 
                                           focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 
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
                        <a href="{{ route('groups.index') }}"
                            class="px-6 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 
                                   hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 
                                   font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                            Bekor qilish
                        </a>

                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 
                                   text-white font-bold rounded-xl transition-all active:scale-95 
                                   shadow-lg shadow-blue-200 dark:shadow-none
                                   flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Guruh yaratish
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        // Kurslar Dropdownini ochish/yopish
        function toggleCourseDropdown() {
            const menu = document.getElementById('courseDropdownMenu');
            const arrow = document.getElementById('courseArrowIcon');
            const isHidden = menu.classList.contains('hidden');

            // Boshqa barcha dropdownlarni yopish (agar bo'lsa)
            document.getElementById('schoolDropdown')?.classList.add('hidden');

            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');

            if (isHidden) {
                document.getElementById('courseSearchInput').focus();
            }
        }

        // Kursni tanlash
        function selectCourseFunc(id, displayText) {
            const label = document.getElementById('selectedCourseLabel');
            const input = document.getElementById('cours_id_input');

            input.value = id;
            label.innerText = displayText;

            // Dizaynni yangilash
            if (id !== "") {
                label.classList.add('text-gray-900', 'dark:text-white', 'font-medium');
                label.classList.remove('text-gray-500');
            } else {
                label.classList.remove('text-gray-900', 'dark:text-white', 'font-medium');
                label.classList.add('text-gray-500');
            }

            toggleCourseDropdown();
        }

        // Qidiruv funksiyasi
        function filterCoursesFunc() {
            const input = document.getElementById('courseSearchInput');
            const filter = input.value.toLowerCase();
            const options = document.querySelectorAll('.course-option-item');
            const noResult = document.getElementById('noCourseFound');
            let hasVisible = false;

            options.forEach(option => {
                const code = option.querySelector('.course-search-text').innerText.toLowerCase();
                const title = option.querySelector('.course-search-title').innerText.toLowerCase();

                if (code.includes(filter) || title.includes(filter)) {
                    option.style.display = "";
                    hasVisible = true;
                } else {
                    option.style.display = "none";
                }
            });

            noResult.classList.toggle('hidden', hasVisible);
        }

        // Dropdown tashqarisiga bosilganda yopish
        document.addEventListener('click', function(e) {
            const container = document.getElementById('courseCustomSelectContainer');
            if (container && !container.contains(e.target)) {
                document.getElementById('courseDropdownMenu').classList.add('hidden');
                document.getElementById('courseArrowIcon').classList.remove('rotate-180');
            }
        });
    </script>
@endsection
