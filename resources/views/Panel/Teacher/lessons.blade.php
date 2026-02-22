@extends('Layout.app')

@section('title', 'Darslar — ' . $group->name)

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('teacher.groups.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Guruhlarim</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('teacher.groups.show', $group->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $group->name }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white font-medium">Darslar</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $group->name }} — Darslar</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $group->course->title ?? '' }}</p>
        </div>
        <a href="{{ route('teacher.groups.lessons.create', $group->id) }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yangi dars
        </a>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 rounded-xl px-4 py-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm font-semibold text-green-700 dark:text-green-400">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Filters --}}
    <form method="GET" action="{{ route('teacher.groups.lessons.index', $group->id) }}"
        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Status</label>
            <select name="status"
                class="px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20">
                <option value="all"         {{ $status === 'all'         ? 'selected' : '' }}>Barchasi</option>
                <option value="scheduled"   {{ $status === 'scheduled'   ? 'selected' : '' }}>Rejalashtirilgan</option>
                <option value="completed"   {{ $status === 'completed'   ? 'selected' : '' }}>Bajarildi</option>
                <option value="cancelled"   {{ $status === 'cancelled'   ? 'selected' : '' }}>Bekor qilindi</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Oy</label>
            <select name="month"
                class="px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20">
                <option value="">Barcha oylar</option>
                @foreach($months as $m => $label)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Yil</label>
            <select name="year"
                class="px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/20">
                @foreach($years as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
            Qidirish
        </button>
        <a href="{{ route('teacher.groups.lessons.index', $group->id) }}"
            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition-colors">
            Tozalash
        </a>
    </form>

    {{-- Lessons List --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 dark:text-white">Darslar ro'yxati</h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $lessons->total() }} ta dars</span>
        </div>

        @if($lessons->isEmpty())
            <div class="py-16 text-center">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Darslar mavjud emas</p>
                <a href="{{ route('teacher.groups.lessons.create', $group->id) }}"
                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Birinchi darsni qo'shish
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($lessons as $lesson)
                @php
                    $statusMap = [
                        'scheduled'   => ['label' => 'Rejalashtirilgan', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
                        'completed'   => ['label' => 'Bajarildi',        'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
                        'cancelled'   => ['label' => 'Bekor qilindi',   'class' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'],
                        'rescheduled' => ['label' => 'Siljitildi',       'class' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'],
                    ];
                    $si = $statusMap[$lesson->status] ?? ['label' => $lesson->status, 'class' => 'bg-gray-100 text-gray-600'];
                @endphp
                <div class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex items-center gap-4 min-w-0 flex-1">
                        {{-- Date block --}}
                        <div class="text-center min-w-[56px] flex-shrink-0">
                            <p class="text-xl font-extrabold text-gray-900 dark:text-white leading-none">
                                {{ \Carbon\Carbon::parse($lesson->lesson_date)->format('d') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                {{ \Carbon\Carbon::parse($lesson->lesson_date)->translatedFormat('M') }}
                            </p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($lesson->lesson_date)->format('Y') }}</p>
                        </div>
                        <div class="w-px h-12 bg-gray-200 dark:bg-gray-700 flex-shrink-0"></div>
                        {{-- Info --}}
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">
                                {{ $lesson->topic ?? 'Mavzu belgilanmagan' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $lesson->start_time }}{{ $lesson->end_time ? ' — '.$lesson->end_time : '' }}
                            </p>
                            @if($lesson->description)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 truncate">{{ $lesson->description }}</p>
                            @endif
                        </div>
                    </div>
                    {{-- Actions --}}
                    <div class="flex items-center gap-2 ml-3 flex-shrink-0">
                        <span class="hidden sm:inline-flex px-2.5 py-1 text-xs font-bold rounded-full {{ $si['class'] }}">
                            {{ $si['label'] }}
                        </span>
                        {{-- Attendance --}}
                        <a href="{{ route('teacher.attendance.show', [$group->id, $lesson->id]) }}"
                            class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition-colors"
                            title="Yuqlama">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </a>
                        {{-- Edit --}}
                        <a href="{{ route('teacher.groups.lessons.edit', [$group->id, $lesson->id]) }}"
                            class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 transition-colors"
                            title="Tahrirlash">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        {{-- Delete (only scheduled) --}}
                        @if($lesson->status === 'scheduled')
                        <form method="POST" action="{{ route('teacher.groups.lessons.destroy', [$group->id, $lesson->id]) }}"
                            onsubmit="return confirm('Bu darsni o\'chirmoqchimisiz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 transition-colors"
                                title="O'chirish">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if($lessons->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $lessons->appends(request()->query())->links() }}
            </div>
            @endif
        @endif
    </div>

</div>
@endsection