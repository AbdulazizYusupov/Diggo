@extends('Layout.app')

@section('title', 'Davomat - ' . $group->name)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('attendance.index') }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div class="space-y-1">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        {{ $group->name }}
                    </h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $group->course->title ?? 'Kurs mavjud emas' }} • {{ $students->count() }} o'quvchi
                    </p>
                </div>
            </div>

            <!-- Month/Year Selector -->
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('attendance.show', $group->id) }}" class="flex items-center gap-2">
                    <select name="month"
                        class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->locale('uz')->monthName }}
                            </option>
                        @endfor
                    </select>

                    <select name="year"
                        class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                        @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>

                <a href="{{ route('attendance.statistics', $group->id) }}"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="hidden sm:inline">Statistika</span>
                </a>
            </div>
        </div>

        <!-- Coin Info Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center border border-amber-100 dark:border-amber-500/20">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Coin tizimi qoidalari</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg border border-emerald-100 dark:border-emerald-500/20">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Keldi</p>
                                <p class="text-sm font-bold text-emerald-700 dark:text-emerald-300">+2 coin</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-amber-50 dark:bg-amber-500/10 rounded-lg border border-amber-100 dark:border-amber-500/20">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-xs text-amber-600 dark:text-amber-400 font-medium">Kechikdi</p>
                                <p class="text-sm font-bold text-amber-700 dark:text-amber-300">+1 coin</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-500/10 rounded-lg border border-blue-100 dark:border-blue-500/20">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div>
                                <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Uy vazifa</p>
                                <p class="text-sm font-bold text-blue-700 dark:text-blue-300">0-2 coin</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-500/10 rounded-lg border border-purple-100 dark:border-purple-500/20">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <div>
                                <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Faollik</p>
                                <p class="text-sm font-bold text-purple-700 dark:text-purple-300">0-2 coin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Form -->
        <form method="POST" action="{{ route('attendance.bulk-store') }}">
            @csrf
            <input type="hidden" name="group_id" value="{{ $group->id }}">

            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-blue-600 to-blue-700 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider sticky left-0 bg-blue-600 z-20 min-w-[200px]">
                                    O'quvchi
                                </th>
                                @foreach ($lessonDays as $day)
                                    <th class="px-2 py-3 text-center text-xs font-bold text-white uppercase min-w-[150px]">
                                        <div class="flex flex-col items-center gap-1">
                                            <span>{{ $day->format('d') }}</span>
                                            <span class="text-blue-200 text-[10px]">{{ $day->locale('uz')->dayName }}</span>
                                        </div>
                                    </th>
                                @endforeach
                                <th class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wider min-w-[100px] bg-purple-600">
                                    Jami Coin
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($students as $studentIndex => $student)
                                @php
                                    $totalCoins = 0;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap sticky left-0 bg-white dark:bg-slate-800 z-10 border-r border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                {{ substr($student->user->surname ?? 'N', 0, 1) }}{{ substr($student->user->name ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $student->user->surname ?? '' }} {{ $student->user->name ?? '' }}
                                                </div>
                                                <div class="text-xs text-amber-600 dark:text-amber-400 font-semibold flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $student->coins ?? 0 }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    @foreach ($lessonDays as $dayIndex => $day)
                                        @php
                                            $key = $student->id . '_' . $day->format('Y-m-d');
                                            $attendanceCollection = $attendances[$key] ?? collect();
                                            $attendance = $attendanceCollection->first();
                                            
                                            $status = $attendance->status ?? '';
                                            $notes = $attendance->notes ?? '';
                                            
                                            $dailyCoins = 0;
                                            if ($status === 'present') $dailyCoins = 2;
                                            elseif ($status === 'late') $dailyCoins = 1;
                                            $totalCoins += $dailyCoins;
                                        @endphp
                                        
                                        <td class="px-2 py-2 text-center">
                                            <input type="hidden" name="attendances[{{ $studentIndex }}][{{ $dayIndex }}][student_id]" value="{{ $student->id }}">
                                            <input type="hidden" name="attendances[{{ $studentIndex }}][{{ $dayIndex }}][date]" value="{{ $day->format('Y-m-d') }}">
                                            
                                            <div class="space-y-2">
                                                <!-- Status Buttons -->
                                                <div class="flex justify-center gap-1">
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" 
                                                               name="attendances[{{ $studentIndex }}][{{ $dayIndex }}][status]" 
                                                               value="present" 
                                                               {{ $status === 'present' ? 'checked' : '' }}
                                                               class="peer sr-only">
                                                        <div class="w-9 h-9 rounded-lg transition-all flex items-center justify-center bg-gray-100 dark:bg-slate-700 text-gray-400 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </label>
                                                    
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" 
                                                               name="attendances[{{ $studentIndex }}][{{ $dayIndex }}][status]" 
                                                               value="late" 
                                                               {{ $status === 'late' ? 'checked' : '' }}
                                                               class="peer sr-only">
                                                        <div class="w-9 h-9 rounded-lg transition-all flex items-center justify-center bg-gray-100 dark:bg-slate-700 text-gray-400 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/30">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                    </label>
                                                    
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" 
                                                               name="attendances[{{ $studentIndex }}][{{ $dayIndex }}][status]" 
                                                               value="absent" 
                                                               {{ $status === 'absent' ? 'checked' : '' }}
                                                               class="peer sr-only">
                                                        <div class="w-9 h-9 rounded-lg transition-all flex items-center justify-center bg-gray-100 dark:bg-slate-700 text-gray-400 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-rose-300 hover:bg-rose-50 dark:hover:bg-rose-900/30">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </div>
                                                    </label>
                                                </div>

                                                @if ($dailyCoins > 0)
                                                    <div class="text-xs font-bold text-amber-600 dark:text-amber-400">
                                                        +{{ $dailyCoins }} coin
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 text-center bg-purple-50 dark:bg-purple-900/10 sticky right-0">
                                        <div class="text-xl font-black text-purple-600 dark:text-purple-400">
                                            {{ $totalCoins }}
                                        </div>
                                        <div class="text-[10px] text-purple-500 dark:text-purple-500 font-semibold">
                                            coins
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Save Button -->
            <div class="sticky bottom-6 z-50 flex justify-center mt-6">
                <button type="submit"
                    class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-2xl shadow-xl shadow-emerald-500/30 transition-all transform hover:scale-105 active:scale-95 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-lg">Saqlash</span>
                </button>
            </div>
        </form>
    </div>
@endsection