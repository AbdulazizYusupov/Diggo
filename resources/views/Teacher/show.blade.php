@extends('Layout.app')

@section('title', "O'qituvchi profili")

@section('content')
    <div class="w-full space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-start gap-6">
                <!-- Profile Image -->
                <div class="relative group">
                    @if (optional($teacher->user)->photo)
                        <img src="{{ asset('storage/' . optional($teacher->user)->photo) }}"
                            alt="{{ optional($teacher->user)->full_name }}"
                            class="w-32 h-32 rounded-3xl object-cover ring-4 ring-blue-100 dark:ring-blue-900/50 shadow-xl">
                    @else
                        <div
                            class="w-32 h-32 rounded-3xl bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-4xl shadow-xl ring-4 ring-blue-100 dark:ring-blue-900/50">
                            {{ substr(optional($teacher->user)->name, 0, 1) }}{{ substr(optional($teacher->user)->surname, 0, 1) }}
                        </div>
                    @endif

                    @if ($teacher->is_active)
                        <div
                            class="absolute -bottom-2 -right-2 p-2 bg-green-500 rounded-full ring-4 ring-white dark:ring-gray-800 shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    @else
                        <div
                            class="absolute -bottom-2 -right-2 p-2 bg-gray-400 rounded-full ring-4 ring-white dark:ring-gray-800 shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="flex-1 space-y-3">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white">
                                {{ optional($teacher->user)->full_name }}
                            </h1>
                            @if (optional(optional($teacher->user))->is_active)
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    Faol
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                    Nofaol
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="font-semibold">{{ optional($teacher->user)->phone }}</span>
                            </div>

                            @if (optional($teacher->user)->city)
                                <span class="text-gray-300 dark:text-gray-600">•</span>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ optional($teacher->user)->city }}</span>
                                </div>
                            @endif

                            <span class="text-gray-300 dark:text-gray-600">•</span>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                ID: #{{ $teacher->id }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('teachers.index') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Orqaga
                </a>
                <a href="{{ route('teachers.edit', $teacher->id) }}"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-lg shadow-blue-200 dark:shadow-none transform hover:scale-105 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Tahrirlash
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-100 dark:border-blue-800/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Guruhlar
                            soni</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">{{ $teacher->groups_count ?? 0 }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-2xl p-6 border border-emerald-100 dark:border-emerald-800/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                            Maosh turi</p>
                        <p class="text-lg font-black text-gray-900 dark:text-white mt-2">
                            @if ($teacher->salary_type === 'fixed')
                                Qat'iy
                            @elseif($teacher->salary_type === 'hourly')
                                Soatlik
                            @elseif($teacher->salary_type === 'per_group')
                                Guruh uchun
                            @else
                                Foiz
                            @endif
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/40 rounded-xl">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl p-6 border border-purple-100 dark:border-purple-800/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider">
                            Maosh miqdori</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-2">
                            {{ number_format($teacher->salary_amount, 0, '.', ' ') }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/40 rounded-xl">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 rounded-2xl p-6 border border-orange-100 dark:border-orange-800/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-orange-600 dark:text-orange-400 uppercase tracking-wider">
                            Talaba foizi</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">
                            {{ $teacher->percent_per_student }}%</p>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/40 rounded-xl">
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Shaxsiy ma'lumotlar
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Familiya</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->surname }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ism</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->name }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Otasining ismi</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->middle_name ?? '-' }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tug'ilgan sana</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->birth_date ? optional($teacher->user)->birth_date->format('d.m.Y') : '-' }}
                                </dd>
                            </div>
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jinsi</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->gender === 'male' ? 'Erkak' : 'Ayol' }}
                                </dd>
                            </div>
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Telefon</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->phone }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Address Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Manzil ma'lumotlari
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Viloyat</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->region ?? '-' }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Shahar/Tuman</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->city ?? '-' }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Manzil</dt>
                                <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ optional($teacher->user)->address ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Groups Table -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Biriktirilgan guruhlar
                            </h2>
                            <span
                                class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                {{ $teacher->groups_count ?? 0 }} ta
                            </span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        @if ($teacher->groups && $teacher->groups->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Guruh nomi</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Kurs</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Talabalar</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach (optional($teacher)->groups ?? [] as $group)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $group->name }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ optional($group->course)->name ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                                    {{ $group->students_count ?? 0 }} talaba
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($group->is_active)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                        Faol
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                                        Nofaol
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Guruhlar mavjud emas
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bu o'qituvchi hali hech qanday
                                    guruhga biriktirilmagan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="space-y-6">
                <!-- Salary Details -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Maosh tafsilotlari
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Maosh turi</dt>
                            <dd class="text-sm font-bold text-gray-900 dark:text-white">
                                @if ($teacher->salary_type === 'fixed')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        Qat'iy maosh
                                    </span>
                                @elseif($teacher->salary_type === 'hourly')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                        Soatlik to'lov
                                    </span>
                                @elseif($teacher->salary_type === 'per_group')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        Guruh uchun
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Foiz asosida
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Maosh miqdori</dt>
                            <dd class="text-2xl font-black text-gray-900 dark:text-white">
                                {{ number_format($teacher->salary_amount, 0, '.', ' ') }} <span
                                    class="text-sm text-gray-500">so'm</span>
                            </dd>
                        </div>

                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Talaba uchun foiz</dt>
                            <dd class="text-xl font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $teacher->percent_per_student }}%
                            </dd>
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Qo'shimcha ma'lumot
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Holat</dt>
                            <dd>
                                @if (optional(optional($teacher->user))->is_active)
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 font-semibold">
                                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                        Faol o'qituvchi
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 font-semibold">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                        Nofaol
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Ro'yxatdan o'tgan</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $teacher->created_at->format('d.m.Y H:i') }}
                            </dd>
                        </div>

                        <div class="space-y-1">
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Oxirgi yangilanish</dt>
                            <dd class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $teacher->updated_at->format('d.m.Y H:i') }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
