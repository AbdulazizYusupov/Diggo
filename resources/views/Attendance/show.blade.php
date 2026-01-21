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
        <div class="bg-gradient-to-r from-amber-500 to-yellow-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-1">Coin tizimi</h3>
                    <p class="text-sm text-white/90">✅ Keldi: <strong>2 coin</strong> • ⏰ Kechikdi: <strong>1 coin</strong>
                        • 📝 Uy vazifa: <strong>0-2 coin</strong> • 🎯 Faollik: <strong>0-2 coin</strong></p>
                </div>
            </div>
        </div>

        <!-- Attendance Table (Excel style) -->
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 overflow-hidden">
            <div class="overflow-x-auto">
                <form id="attendanceForm" method="POST" action="{{ route('attendance.bulk-store') }}">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ $group->id }}">

                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-blue-600 to-blue-700 sticky top-0 z-10">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider sticky left-0 bg-blue-600 z-20 min-w-[200px]">
                                    O'quvchi
                                </th>
                                @foreach ($lessonDays as $day)
                                    <th class="px-2 py-3 text-center text-xs font-bold text-white uppercase min-w-[140px]">
                                        <div class="flex flex-col items-center gap-1">
                                            <span>{{ $day->format('d') }}</span>
                                            <span class="text-blue-200">{{ $day->locale('uz')->dayName }}</span>
                                        </div>
                                    </th>
                                @endforeach
                                <th
                                    class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wider min-w-[100px] bg-purple-600">
                                    Jami Coin
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($students as $student)
                                @php
                                    $totalCoins = 0;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td
                                        class="px-4 py-3 whitespace-nowrap sticky left-0 bg-white dark:bg-slate-800 z-10 border-r border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                {{ substr($student->user->name ?? 'N', 0, 1) }}{{ substr($student->user->surname ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $student->user->surname ?? '' }} {{ $student->user->name ?? '' }}
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-amber-500" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                                                    </svg>
                                                    {{ $student->coins ?? 0 }} coin
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    @foreach ($lessonDays as $day)
                                        @php
                                            $key = $student->id . '_' . $day->format('Y-m-d');
                                            $attendanceCollection = $attendances[$key] ?? collect();
                                            $attendance = $attendanceCollection->first();

                                            $status = $attendance->status ?? '';
                                            $hwScore = $attendance->homework_score ?? 0;
                                            $partScore = $attendance->participation_score ?? 0;

                                            $dailyCoins = 0;
                                            if ($status === 'present') {
                                                $dailyCoins += 2;
                                            } elseif ($status === 'late') {
                                                $dailyCoins += 1;
                                            }

                                            $dailyCoins += $hwScore + $partScore;
                                            $totalCoins += $dailyCoins;
                                        @endphp

                                        <td class="px-2 py-2 text-center attendance-cell"
                                            data-student="{{ $student->id }}" data-date="{{ $day->format('Y-m-d') }}">
                                            <div class="space-y-1">
                                                <!-- Status Buttons -->
                                                <div class="flex justify-center gap-1">
                                                    <button type="button"
                                                        onclick="setAttendance({{ $student->id }}, '{{ $day->format('Y-m-d') }}', 'present', this)"
                                                        class="status-btn w-8 h-8 rounded-lg transition-all {{ $status === 'present' ? 'bg-emerald-500 text-white shadow-lg scale-110' : 'bg-gray-100 dark:bg-slate-700 text-gray-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/30' }}"
                                                        title="Keldi (2 coin)">
                                                        ✓
                                                    </button>
                                                    <button type="button"
                                                        onclick="setAttendance({{ $student->id }}, '{{ $day->format('Y-m-d') }}', 'late', this)"
                                                        class="status-btn w-8 h-8 rounded-lg transition-all {{ $status === 'late' ? 'bg-amber-500 text-white shadow-lg scale-110' : 'bg-gray-100 dark:bg-slate-700 text-gray-400 hover:bg-amber-100 dark:hover:bg-amber-900/30' }}"
                                                        title="Kechikdi (1 coin)">
                                                        ⏰
                                                    </button>
                                                    <button type="button"
                                                        onclick="setAttendance({{ $student->id }}, '{{ $day->format('Y-m-d') }}', 'absent', this)"
                                                        class="status-btn w-8 h-8 rounded-lg transition-all {{ $status === 'absent' ? 'bg-rose-500 text-white shadow-lg scale-110' : 'bg-gray-100 dark:bg-slate-700 text-gray-400 hover:bg-rose-100 dark:hover:bg-rose-900/30' }}"
                                                        title="Kelmadi">
                                                        ✗
                                                    </button>
                                                </div>

                                                <!-- Scores -->
                                                <div class="flex justify-center gap-1">
                                                    <select
                                                        class="hw-score w-12 h-7 text-xs rounded bg-blue-50 dark:bg-slate-700 border-0 text-center font-bold text-blue-600 dark:text-blue-400 outline-none cursor-pointer"
                                                        data-student="{{ $student->id }}"
                                                        data-date="{{ $day->format('Y-m-d') }}" title="Uy vazifa">
                                                        <option value="0" {{ $hwScore == 0 ? 'selected' : '' }}>📝0
                                                        </option>
                                                        <option value="1" {{ $hwScore == 1 ? 'selected' : '' }}>📝1
                                                        </option>
                                                        <option value="2" {{ $hwScore == 2 ? 'selected' : '' }}>📝2
                                                        </option>
                                                    </select>
                                                    <select
                                                        class="part-score w-12 h-7 text-xs rounded bg-purple-50 dark:bg-slate-700 border-0 text-center font-bold text-purple-600 dark:text-purple-400 outline-none cursor-pointer"
                                                        data-student="{{ $student->id }}"
                                                        data-date="{{ $day->format('Y-m-d') }}" title="Faollik">
                                                        <option value="0" {{ $partScore == 0 ? 'selected' : '' }}>🎯0
                                                        </option>
                                                        <option value="1" {{ $partScore == 1 ? 'selected' : '' }}>🎯1
                                                        </option>
                                                        <option value="2" {{ $partScore == 2 ? 'selected' : '' }}>🎯2
                                                        </option>
                                                    </select>
                                                </div>

                                                @if ($dailyCoins > 0)
                                                    <div class="text-xs font-bold text-amber-600 dark:text-amber-400">
                                                        +{{ $dailyCoins }} 🪙
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 text-center bg-purple-50 dark:bg-purple-900/10">
                                        <div class="text-lg font-black text-purple-600 dark:text-purple-400">
                                            {{ $totalCoins }}
                                        </div>
                                        <div class="text-xs text-purple-500 dark:text-purple-500">
                                            🪙 coins
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <!-- Save Button -->
        <div class="sticky bottom-6 z-50 flex justify-center">
            <button type="button" onclick="saveAllAttendance()"
                class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-2xl shadow-2xl shadow-emerald-500/50 transition-all transform hover:scale-105 active:scale-95 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-lg">Barcha davomatlarni saqlash</span>
            </button>
        </div>
    </div>

    {{-- @push('scripts') --}}
        <script>
            let attendanceData = {};

            function setAttendance(studentId, date, status, button) {
                const cell = button.closest('.attendance-cell');
                const buttons = cell.querySelectorAll('.status-btn');

                // Reset all buttons in this cell
                buttons.forEach(btn => {
                    btn.classList.remove('bg-emerald-500', 'bg-amber-500', 'bg-rose-500', 'text-white', 'shadow-lg',
                        'scale-110');
                    btn.classList.add('bg-gray-100', 'dark:bg-slate-700', 'text-gray-400');
                });

                // Highlight selected button
                button.classList.remove('bg-gray-100', 'dark:bg-slate-700', 'text-gray-400');
                button.classList.add('text-white', 'shadow-lg', 'scale-110');

                if (status === 'present') button.classList.add('bg-emerald-500');
                else if (status === 'late') button.classList.add('bg-amber-500');
                else button.classList.add('bg-rose-500');

                // Store data
                const key = `${studentId}_${date}`;
                if (!attendanceData[key]) attendanceData[key] = {};
                attendanceData[key].status = status;
                attendanceData[key].student_id = studentId;
                attendanceData[key].date = date;
            }

            // Auto-save scores
            document.querySelectorAll('.hw-score, .part-score').forEach(select => {
                select.addEventListener('change', function() {
                    const studentId = this.dataset.student;
                    const date = this.dataset.date;
                    const key = `${studentId}_${date}`;

                    if (!attendanceData[key]) attendanceData[key] = {};
                    attendanceData[key].student_id = studentId;
                    attendanceData[key].date = date;

                    const cell = this.closest('.attendance-cell');
                    const hwScore = parseInt(cell.querySelector('.hw-score').value);
                    const partScore = parseInt(cell.querySelector('.part-score').value);

                    attendanceData[key].homework_score = hwScore;
                    attendanceData[key].participation_score = partScore;
                });
            });

            async function saveAllAttendance() {
                const data = Object.values(attendanceData);

                if (data.length === 0) {
                    alert('Davomat ma\'lumotlari kiritilmagan!');
                    return;
                }

                // Prepare payload
                const lessonDays = @json($lessonDays->map(fn($d) => $d->format('Y-m-d')));
                const payload = {
                    group_id: {{ $group->id }},
                    attendances: []
                };

                // Process each day
                lessonDays.forEach(date => {
                    const dayData = data.filter(d => d.date === date);
                    if (dayData.length > 0) {
                        payload.attendances.push({
                            attendance_date: date,
                            students: dayData
                        });
                    }
                });

                try {
                    const response = await fetch('{{ route('attendance.bulk-store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            group_id: {{ $group->id }},
                            attendances: data.map(item => ({
                                student_id: item.student_id,
                                status: item.status || 'absent',
                                homework_score: item.homework_score || 0,
                                participation_score: item.participation_score || 0,
                                attendance_date: item.date
                            }))
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert(`✅ Saqlandi! Jami ${result.total_coins} coin berildi.`);
                        location.reload();
                    } else {
                        alert('❌ Xatolik: ' + result.message);
                    }
                } catch (error) {
                    console.error(error);
                    alert('❌ Xatolik yuz berdi!');
                }
            }
        </script>
    {{-- @endpush --}}
@endsection
