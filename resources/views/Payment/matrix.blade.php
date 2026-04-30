@extends('Layout.app')
@section('title', "To'lov Matritsasi $year")
@section('content')

{{-- ============================================================
     PAYMENT MATRIX — Refactored (Paginated + Optimized)
     O'zgarishlar:
       - $matrix array o'rniga $paginator (LengthAwarePaginator)
       - $student->month_breakdown, paid_months_count, total_debt_count
       - withQueryString() orqali filterlar saqlanadi
     ============================================================ --}}

<div class="space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex p-3 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20 rounded-2xl shadow-sm">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">To'lov Matritsasi</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20">
                        {{ $year }}-yil
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Jami <strong class="text-gray-700 dark:text-gray-200 ml-1">{{ $paginator->total() }}</strong> ta o'quvchi
                    · Sahifada {{ $paginator->perPage() }} ta
                </p>
            </div>
        </div>
        <a href="{{ route('payments.matrix.export', request()->all()) }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-xl transition-all shadow-sm text-sm active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Excelga yuklash
        </a>
        <a href="{{ route('payments.create') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all shadow-sm text-sm active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            To'lov qabul qilish
        </a>
    </div>

    {{-- ===== LEGEND ===== --}}
    <div class="flex flex-wrap gap-3">
        @foreach([
            ['paid',    'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-700', 'bg-emerald-500', 'text-emerald-700 dark:text-emerald-400', "To'langan"],
            ['partial', 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-700',         'bg-amber-500',   'text-amber-700 dark:text-amber-400',   'Qisman'],
            ['unpaid',  'bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-700',             'bg-rose-500',    'text-rose-700 dark:text-rose-400',     'Qarzdor'],
            ['future',  'bg-gray-50 dark:bg-slate-800 border-gray-200 dark:border-gray-700',              'bg-gray-300 dark:bg-gray-600', 'text-gray-500 dark:text-gray-400', 'Hali kelmagan'],
        ] as [$key, $bg, $dot, $text, $label])
            <span class="inline-flex items-center gap-2 px-3 py-1.5 {{ $bg }} border rounded-lg text-xs font-semibold {{ $text }}">
                <span class="w-2.5 h-2.5 rounded-full {{ $dot }} inline-block flex-shrink-0"></span>
                {{ $label }}
            </span>
        @endforeach
    </div>

    {{-- ===== FILTERS ===== --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 p-4 lg:p-5">
        <form method="GET" action="{{ route('payments.matrix') }}" class="flex flex-wrap gap-3 items-end">

            {{-- Yil --}}
            <div class="min-w-[130px]">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Yil</label>
                <select name="year" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none cursor-pointer">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}-yil</option>
                    @endforeach
                </select>
            </div>

            {{-- Guruh --}}
            <div class="min-w-[180px] flex-1">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Guruh</label>
                <select name="group_id" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none cursor-pointer">
                    <option value="">Barcha guruhlar</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ $groupId == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sahifadagi yozuvlar soni --}}
            <div class="min-w-[110px]">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Sahifada</label>
                <select name="per_page" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none cursor-pointer">
                    @foreach([25, 50, 100] as $n)
                        <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }} ta</option>
                    @endforeach
                </select>
            </div>

            {{-- Tugmalar --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition-all shadow-sm flex items-center gap-2 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('payments.matrix') }}"
                    class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-500 dark:text-gray-400 rounded-xl transition-all flex items-center justify-center active:scale-95"
                    title="Filterlarni tozalash">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </a>
            </div>

        </form>
    </div>

    {{-- ===== MATRIX TABLE ===== --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 overflow-hidden">

        @if($paginator->isEmpty())
            <div class="py-16 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                </svg>
                <p class="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">O'quvchilar topilmadi</p>
            </div>
        @else

        {{--
            RESPONSIVE SCROLL STRATEGY:
            - Tashqi div: overflow-x-auto → gorizontal scroll faqat table uchun
            - sticky left-0 → O'quvchi ustuni har doim ko'rinadi
            - min-w-[72px] → oylik hujayralar siqilmaydi
            - Table-layout: fixed + explicit min-width → 1000+ student'da layout shift yo'q
        --}}
        <div class="overflow-x-auto overscroll-x-contain" style="scrollbar-width: thin;">
            <table class="w-full border-collapse" style="min-width: 1100px;">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-800/80 border-b border-gray-200 dark:border-gray-700">

                        {{-- O'quvchi ustuni — sticky --}}
                        <th class="sticky left-0 z-20 bg-gray-50 dark:bg-slate-800 px-4 py-3.5 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-r border-gray-200 dark:border-gray-700"
                            style="min-width: 230px; max-width: 250px;">
                            O'quvchi
                        </th>

                        {{-- 12 oylik ustunlar --}}
                        @foreach($months as $num => $name)
                            <th class="px-1.5 py-3.5 text-center text-[11px] font-bold uppercase tracking-wide"
                                style="min-width: 72px;"
                                :class="{{ $num == $currentMonth ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
                                <span class="{{ $num == $currentMonth
                                    ? 'inline-flex items-center justify-center px-2 py-0.5 bg-indigo-100 dark:bg-indigo-800/40 text-indigo-700 dark:text-indigo-300 rounded-full'
                                    : '' }}">
                                    {{ Str::limit($name, 3, '') }}
                                </span>
                            </th>
                        @endforeach

                        {{-- Jami ustuni --}}
                        <th class="px-3 py-3.5 text-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider"
                            style="min-width: 90px;">
                            Jami
                        </th>

                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @foreach($paginator as $student)
                        {{-- $student->month_breakdown, paid_months_count, total_debt_count
                             — attachMonthlyBreakdown() tomonidan biriktilgan --}}
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-800/40 transition-colors group
                            {{ ($student->total_debt_count ?? 0) >= 3 ? 'bg-rose-50/20 dark:bg-rose-900/5' : '' }}">

                            {{-- O'quvchi — sticky --}}
                            <td class="sticky left-0 z-10 bg-white dark:bg-slate-900 group-hover:bg-gray-50/80 dark:group-hover:bg-slate-800/40 transition-colors px-4 py-3 border-r border-gray-100 dark:border-gray-800"
                                style="min-width: 230px; max-width: 250px;">
                                <div class="flex items-center gap-2.5">
                                    {{-- Avatar --}}
                                    <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br
                                        {{ ($student->total_debt_count ?? 0) >= 3
                                            ? 'from-rose-400 to-rose-600'
                                            : 'from-indigo-400 to-indigo-600' }}
                                        flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                        {{ substr($student->user?->name ?? '', 0, 1) }}{{ substr($student->user?->surname ?? '', 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('payments.student-card', $student->id) }}"
                                           class="text-sm font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors block truncate"
                                           title="{{ $student->user?->full_name }}">
                                            {{ $student->user?->full_name ?? '—' }}
                                        </a>
                                        <a href="tel:{{ $student->user?->phone }}"
                                           class="text-xs text-gray-400 dark:text-gray-500 hover:text-indigo-500 transition-colors flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $student->user?->phone ?? '—' }}
                                        </a>
                                    </div>
                                </div>
                            </td>

                            {{-- 12 oylik hujayralar --}}
                            @foreach($months as $num => $name)
                                @php
                                    $cell   = $student->month_breakdown[$num] ?? ['status' => 'future'];
                                    $status = $cell['status'];
                                @endphp
                                <td class="px-1 py-2.5 text-center {{ $num == $currentMonth ? 'bg-indigo-50/20 dark:bg-indigo-900/5' : '' }}">

                                    @if($status === 'future')
                                        <span class="flex items-center justify-center h-9 text-gray-200 dark:text-gray-800 text-xs select-none">—</span>

                                    @elseif($status === 'paid')
                                        <div class="flex flex-col items-center justify-center min-h-[36px] px-1 py-1.5 mx-auto
                                                    bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/40
                                                    rounded-lg cursor-default"
                                             style="max-width: 68px;"
                                             title="{{ $name }}: {{ number_format($cell['amount'] ?? 0, 0, '.', ' ') }} so'm">
                                            <svg class="w-3.5 h-3.5 text-emerald-500 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-[9px] font-bold text-emerald-700 dark:text-emerald-400 leading-none mt-0.5 tabular-nums">
                                                {{ number_format(($cell['amount'] ?? 0) / 1000, 0) }}k
                                            </span>
                                        </div>

                                    @elseif($status === 'partial')
                                        <div class="flex flex-col items-center justify-center min-h-[36px] px-1 py-1.5 mx-auto
                                                    bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/40
                                                    rounded-lg cursor-default"
                                             style="max-width: 68px;"
                                             title="{{ $name }}: Qisman to'langan {{ number_format($cell['amount'] ?? 0, 0, '.', ' ') }} so'm">
                                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01"/>
                                            </svg>
                                            <span class="text-[9px] font-bold text-amber-700 dark:text-amber-400 leading-none mt-0.5 tabular-nums">
                                                {{ number_format(($cell['amount'] ?? 0) / 1000, 0) }}k
                                            </span>
                                        </div>

                                    @else {{-- unpaid --}}
                                        <div class="flex flex-col items-center justify-center min-h-[36px] px-1 py-1.5 mx-auto
                                                    bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-700/40
                                                    rounded-lg cursor-default"
                                             style="max-width: 68px;"
                                             title="{{ $name }}: To'lanmagan">
                                            <svg class="w-3.5 h-3.5 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span class="text-[9px] font-semibold text-rose-600 dark:text-rose-400 leading-none mt-0.5">Qarz</span>
                                        </div>
                                    @endif

                                </td>
                            @endforeach

                            {{-- Jami --}}
                            <td class="px-3 py-2.5 text-center">
                                <div class="flex flex-col items-center gap-1.5">
                                    {{-- To'langan / jami oy --}}
                                    <span class="text-xs font-black text-gray-800 dark:text-gray-200 tabular-nums">
                                        {{ $student->paid_months_count }}<span class="text-gray-400 font-normal">/12</span>
                                    </span>

                                    {{-- Qarz badge --}}
                                    @if(($student->total_debt_count ?? 0) > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold
                                            {{ ($student->total_debt_count ?? 0) >= 3
                                                ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-700/40'
                                                : 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-700/40' }}
                                            tabular-nums">
                                            {{ $student->total_debt_count }} qarz
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                            Toza ✓
                                        </span>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        @endif

    </div>

    {{-- ===== PAGINATION ===== --}}
    {{--
        withQueryString() controller'da chaqirilganligi sababli,
        year, group_id, per_page parametrlari sahifalash havolalarida
        avtomatik saqlanadi. Qo'shimcha hech narsa kerak emas.
    --}}
    @if($paginator->hasPages())
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-indigo-500/20 px-5 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

                {{-- Info matni --}}
                <p class="text-sm text-gray-500 dark:text-gray-400 order-2 sm:order-1">
                    Ko'rsatilmoqda
                    <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                    —
                    <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                    / jami
                    <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
                    ta o'quvchi
                </p>

                {{-- Laravel default paginator (Tailwind) --}}
                <div class="order-1 sm:order-2">
                    {{ $paginator->links() }}
                </div>

            </div>
        </div>
    @endif

</div>
@endsection