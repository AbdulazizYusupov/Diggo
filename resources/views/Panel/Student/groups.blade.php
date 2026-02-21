@extends('Layout.app')

@section('title', 'Mening Guruhlarim')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Mening Guruhlarim
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Barcha guruhlaringiz va ularning statistikasi
                </p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-2 sm:space-x-8 overflow-x-auto" id="groupTabs">
                <button data-tab="active"
                    class="tab-btn active-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600 dark:text-indigo-400">
                    Faol guruhlar ({{ $activeGroups->count() }})
                </button>
                <button data-tab="completed"
                    class="tab-btn whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400">
                    Tugatilgan ({{ $completedGroups->count() }})
                </button>
                <button data-tab="transferred"
                    class="tab-btn whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400">
                    O'tkazilgan ({{ $transferredGroups->count() }})
                </button>
                <button data-tab="dropped"
                    class="tab-btn whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400">
                    Tark etilgan ({{ $droppedGroups->count() }})
                </button>
            </nav>
        </div>

        {{-- Tab Contents --}}

        {{-- Faol Guruhlar --}}
        <div id="tab-active" class="tab-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($activeGroups as $group)
                <a href="{{ route('student.groups.show', $group['id']) }}"
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-indigo-400 dark:hover:border-indigo-500 transition-all">

                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 min-w-0 pr-2">
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate">
                                {{ $group['name'] }}
                            </h3>
                            <p class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold truncate">
                                {{ $group['course_name'] }}
                            </p>
                        </div>
                        <span class="flex-shrink-0 px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded-lg">
                            Faol
                        </span>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $group['lesson_days'] ?: '—' }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $group['lesson_time'] ?: '—' }}</span>
                        </div>

                        @if(!empty($group['teachers']) && $group['teachers']->count())
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="truncate">{{ $group['teachers']->map(fn($t) => $t->user->name ?? '')->join(', ') }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Darslar</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $group['total_lessons'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Davomat</p>
                            <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $group['attendance_rate'] }}%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">O'rinlar</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $group['capacity'] }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Faol guruhlar mavjud emas</p>
                </div>
            @endforelse
        </div>

        {{-- Tugatilgan Guruhlar --}}
        <div id="tab-completed" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($completedGroups as $group)
                <a href="{{ route('student.groups.show', $group['id']) }}"
                    class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all">

                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 min-w-0 pr-2">
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg truncate">{{ $group['name'] }}</h3>
                            <p class="text-sm text-green-600 dark:text-green-400 font-semibold truncate">{{ $group['course_name'] }}</p>
                        </div>
                        <span class="flex-shrink-0 px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded-lg">
                            Tugallangan
                        </span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold">Boshlangan:</span>
                            {{ \Carbon\Carbon::parse($group['start_date'])->format('d.m.Y') }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold">Tugagan:</span>
                            {{ $group['end_date'] ? \Carbon\Carbon::parse($group['end_date'])->format('d.m.Y') : 'N/A' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jami darslar</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $group['total_lessons'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Davomat</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ $group['attendance_rate'] }}%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Qatnashdi</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $group['present_lessons'] }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-16">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Tugatilgan guruhlar mavjud emas</p>
                </div>
            @endforelse
        </div>

        {{-- O'tkazilgan Guruhlar --}}
        <div id="tab-transferred" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($transferredGroups as $group)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 min-w-0 pr-2">
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg truncate">{{ $group['name'] }}</h3>
                            <p class="text-sm text-amber-600 dark:text-amber-400 font-semibold truncate">{{ $group['course_name'] }}</p>
                        </div>
                        <span class="flex-shrink-0 px-3 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 text-xs font-bold rounded-lg">
                            O'tkazilgan
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <p><span class="font-semibold">Qo'shilgan:</span> {{ \Carbon\Carbon::parse($group['joined_at'])->format('d.m.Y') }}</p>
                        @if($group['left_at'])
                            <p><span class="font-semibold">Tark etgan:</span> {{ \Carbon\Carbon::parse($group['left_at'])->format('d.m.Y') }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">O'tkazilgan guruhlar mavjud emas</p>
                </div>
            @endforelse
        </div>

        {{-- Tark etilgan Guruhlar --}}
        <div id="tab-dropped" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($droppedGroups as $group)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 min-w-0 pr-2">
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg truncate">{{ $group['name'] }}</h3>
                            <p class="text-sm text-red-600 dark:text-red-400 font-semibold truncate">{{ $group['course_name'] }}</p>
                        </div>
                        <span class="flex-shrink-0 px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs font-bold rounded-lg">
                            Tark etilgan
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <p><span class="font-semibold">Qo'shilgan:</span> {{ \Carbon\Carbon::parse($group['joined_at'])->format('d.m.Y') }}</p>
                        @if($group['left_at'])
                            <p><span class="font-semibold">Tark etgan:</span> {{ \Carbon\Carbon::parse($group['left_at'])->format('d.m.Y') }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Tark etilgan guruhlar mavjud emas</p>
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const tabColors = {
                active:      { border: 'border-indigo-500', text: 'text-indigo-600 dark:text-indigo-400' },
                completed:   { border: 'border-green-500',  text: 'text-green-600 dark:text-green-400'  },
                transferred: { border: 'border-amber-500',  text: 'text-amber-600 dark:text-amber-400'  },
                dropped:     { border: 'border-red-500',    text: 'text-red-600 dark:text-red-400'      },
            };

            function activateTab(tabName) {
                // Hide all content panels
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

                // Show selected panel
                const panel = document.getElementById('tab-' + tabName);
                if (panel) panel.classList.remove('hidden');

                // Reset all buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove(
                        'border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400',
                        'border-green-500',  'text-green-600',  'dark:text-green-400',
                        'border-amber-500',  'text-amber-600',  'dark:text-amber-400',
                        'border-red-500',    'text-red-600',    'dark:text-red-400'
                    );
                    btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                });

                // Style active button
                const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
                if (activeBtn) {
                    activeBtn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    const colors = tabColors[tabName];
                    activeBtn.classList.add(...colors.border.split(' '), ...colors.text.split(' '));
                }

                // Persist in session
                try { sessionStorage.setItem('groupsActiveTab', tabName); } catch(e) {}
            }

            // Attach click listeners
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', () => activateTab(btn.dataset.tab));
            });

            // Restore last active tab
            let defaultTab = 'active';
            try {
                const saved = sessionStorage.getItem('groupsActiveTab');
                if (saved && tabColors[saved]) defaultTab = saved;
            } catch(e) {}

            activateTab(defaultTab);
        })();
    </script>
    @endpush
@endsection