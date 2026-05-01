@extends('Layout.app')

@section('title', 'Guruh Tafsilotlari')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('director.groups') }}"
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    {{ $group->name }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $group->course->title ?? 'N/A' }}
                </p>
            </div>
            <span
                class="px-4 py-2 rounded-lg text-sm font-bold {{ $group->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                {{ $group->is_active ? 'Faol' : 'Faol emas' }}
            </span>
        </div>

        {{-- Group Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @if ($group->start_time)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dars Vaqti</p>
                        <p class="font-bold text-gray-900 dark:text-white text-lg">
                            {{ $group->start_time }}{{ $group->end_time ? ' - ' . $group->end_time : '' }}
                        </p>
                    </div>
                @endif

                @if ($group->days_of_week)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dars Kunlari</p>
                        <p class="font-bold text-gray-900 dark:text-white">
                            @php
                                $days = is_array($group->days_of_week)
                                    ? $group->days_of_week
                                    : json_decode($group->days_of_week, true) ?? [];
                            @endphp
                            {{ implode(', ', $days) }}
                        </p>
                    </div>
                @endif

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sig'im</p>
                    <p class="font-bold text-gray-900 dark:text-white text-lg">{{ $group->capacity }}</p>
                </div>

                @if ($group->start_date)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Boshlangan</p>
                        <p class="font-bold text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($group->start_date)->format('d.m.Y') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200/60 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-2xl p-6 border border-indigo-200 dark:border-indigo-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-indigo-700 dark:text-indigo-400 uppercase">Jami O'quvchilar</p>
                        <p class="text-3xl font-extrabold text-indigo-900 dark:text-indigo-50 mt-1">
                            {{ $stats['total_students'] }}
                        </p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                            {{ $stats['capacity_percentage'] }}% to'ldirilgan
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200/60 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-purple-700 dark:text-purple-400 uppercase">Sizning O'quvchilaringiz
                        </p>
                        <p class="text-3xl font-extrabold text-purple-900 dark:text-purple-50 mt-1">
                            {{ $stats['director_students'] }}
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            {{ $stats['total_students'] > 0 ? round(($stats['director_students'] / $stats['total_students']) * 100, 1) : 0 }}%
                            dan
                        </p>
                    </div>
                    <div class="p-3 bg-purple-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-emerald-100 to-emerald-200/60 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400 uppercase">Komissiya Daromadingiz
                        </p>
                        <p class="text-3xl font-extrabold text-emerald-900 dark:text-emerald-50 mt-1">
                            {{ number_format($commissionStats['total_commission'], 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ $commissionStats['transaction_count'] }} ta to'lov ·
                            {{ $commissionStats['commission_percentage'] }}%
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-600 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Teachers --}}
        @if ($group->teachers->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">O'qituvchilar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($group->teachers as $teacher)
                        <div
                            class="flex items-center gap-3 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-xl border border-blue-200 dark:border-blue-800/30">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                                {{ substr($teacher->user->name, 0, 1) }}{{ substr($teacher->user->surname ?? '', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white">
                                    {{ $teacher->user->name }} {{ $teacher->user->surname }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $teacher->user->phone }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Director's Students in this Group --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Sizning O'quvchilaringiz</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($directorStudents as $groupStudent)
                    @php $student = $groupStudent->student; @endphp
                    <a href="{{ route('director.students.show', $student->id) }}"
                        class="group flex items-center gap-3 p-4 bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/10 dark:to-indigo-900/10 rounded-xl border border-purple-200 dark:border-purple-800/30 hover:shadow-lg transition-all">
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                            {{ substr($student->user->name, 0, 1) }}{{ substr($student->user->surname ?? '', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p
                                class="font-bold text-gray-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $student->user->name }} {{ $student->user->surname }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->user->phone }}</p>
                            <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                Qo'shilgan: {{ \Carbon\Carbon::parse($groupStudent->joined_at)->format('d.m.Y') }}
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-3 text-center text-gray-500 dark:text-gray-400 py-8">Bu guruhda sizning
                        o'quvchilaringiz yo'q</p>
                @endforelse
            </div>
        </div>

        {{-- Payments from Director's Students --}}
        {{-- Commission Transactions from Director's Students --}}
        @if ($transactions->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Komissiya Tranzaksiyalari</h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $commissionStats['transaction_count'] }} ta tranzaksiya
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach ($transactions as $transaction)
                        @php $student = $transaction->source?->payable; @endphp
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($student?->user?->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $student?->user?->name }} {{ $student?->user?->surname }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->transaction_date
                                            ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d.m.Y H:i')
                                            : '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-indigo-600 dark:text-indigo-400">
                                    +{{ number_format($transaction->amount, 0, '.', ' ') }} so'm
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $commissionStats['commission_percentage'] }}% komissiya
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
