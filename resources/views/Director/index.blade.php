@extends('Layout.index')

@section('title', 'Direktorlar')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex p-3 bg-purple-100 dark:bg-purple-900/40 rounded-2xl">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Direktorlar
                        </h1>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                            {{ $totalDirectors }} ta direktor
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Direktorlarni boshqarish va nazorat qilish
                    </p>
                </div>
            </div>
            <a href="{{ route('directors.create') }}"
                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
                <span class="hidden sm:inline">Direktor qo'shish</span>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Jami direktorlar</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $totalDirectors }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Umumiy balans</p>
                        <p class="text-2xl sm:text-3xl font-bold text-green-600 dark:text-green-400 mt-2 truncate">
                            {{ number_format($totalBalance, 0, '.', ' ') }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">O'rtacha komissiya</p>
                        <p class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                            {{ number_format($avgCommission, 1) }}%
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Maktabli direktorlar</p>
                        <p class="text-2xl sm:text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">
                            {{ $directorsWithSchools }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 lg:p-6">
            <form method="GET" action="{{ route('directors.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="relative sm:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ism, telefon, manzil yoki maktab nomi..."
                            class="w-full pr-10 px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">

                        @if (request('search'))
                            <a href="{{ route('directors.index', request()->except('search')) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition"
                                title="Qidiruvni tozalash">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    <select name="school_id"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-white text-sm">
                        <option value="">Barcha maktablar</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="commission_range"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-white text-sm">
                        <option value="">Barcha komissiyalar</option>
                        <option value="low" {{ request('commission_range') === 'low' ? 'selected' : '' }}>Past (&lt;10%)
                        </option>
                        <option value="medium" {{ request('commission_range') === 'medium' ? 'selected' : '' }}>O'rtacha
                            (10-20%)</option>
                        <option value="high" {{ request('commission_range') === 'high' ? 'selected' : '' }}>Yuqori
                            (&gt;20%)</option>
                    </select>

                    <select name="balance_range"
                        class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-white text-sm">
                        <option value="">Barcha balanslar</option>
                        <option value="zero" {{ request('balance_range') === 'zero' ? 'selected' : '' }}>Nol</option>
                        <option value="low" {{ request('balance_range') === 'low' ? 'selected' : '' }}>Past (&lt;1M)
                        </option>
                        <option value="medium" {{ request('balance_range') === 'medium' ? 'selected' : '' }}>O'rtacha
                            (1M-5M)</option>
                        <option value="high" {{ request('balance_range') === 'high' ? 'selected' : '' }}>Yuqori (&gt;5M)
                        </option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <span class="hidden sm:inline">Qidirish</span>
                        </button>

                        <a href="{{ route('directors.index') }}"
                            class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all flex items-center justify-center"
                            title="Tozalash">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modern Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Direktor</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Telefon</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Maktab</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Komissiya</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Balans</th>
                            <th
                                class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($directors as $director)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 lg:h-12 lg:w-12">
                                            @if ($director->user->photo)
                                                <img src="{{ asset('storage/' . $director->user->photo) }}"
                                                    alt="{{ $director->user->full_name }}"
                                                    class="h-10 w-10 lg:h-12 lg:w-12 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700">
                                            @else
                                                <div
                                                    class="h-10 w-10 lg:h-12 lg:w-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm lg:text-base">
                                                    {{ substr($director->user->name, 0, 1) }}{{ substr($director->user->surname, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3 lg:ml-4 min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $director->user->full_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $director->user->gender === 'male' ? 'Erkak' : 'Ayol' }}
                                                @if ($director->user->birth_date)
                                                    • {{ $director->user->birth_date->age }} yosh
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $director->user->phone }}</div>
                                    @if ($director->user->city)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[150px]">
                                            {{ $director->user->city }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    @if($director->school)
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $director->school->name }}
                                        </div>
                                        @if($director->school->address)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]">
                                                {{ $director->school->address }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Maktab yo'q</span>
                                    @endif
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg
                                    {{ $director->commission_percentage < 10 ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                    {{ $director->commission_percentage >= 10 && $director->commission_percentage <= 20 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $director->commission_percentage > 20 ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : '' }}">
                                        {{ number_format($director->commission_percentage, 1) }}%
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-bold {{ $director->balance > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ number_format($director->balance, 0, '.', ' ') }} so'm
                                    </div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-1 lg:gap-2">
                                        <a href="{{ route('directors.show', $director->id) }}" title="Ko'rish"
                                            class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('directors.edit', $director->id) }}" title="Tahrirlash"
                                            class="p-2 text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                            onclick="document.getElementById('delete-modal-{{ $director->id }}').classList.remove('hidden')"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        <!-- Delete Modal -->
                                        <div id="delete-modal-{{ $director->id }}"
                                            class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
                                            <div
                                                class="relative w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border-2 border-gray-300 dark:border-gray-600 overflow-hidden">
                                                <div class="text-center">
                                                    <div
                                                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                                                        <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                    </div>

                                                    <h3
                                                        class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                                                        Tasdiqlang</h3>

                                                    <p
                                                        class="text-sm text-gray-500 dark:text-gray-400 mb-6 px-2 break-words leading-relaxed">
                                                        Rostdan ham <span
                                                            class="font-bold text-gray-900 dark:text-white break-words">{{ $director->user->full_name }}</span>
                                                        ni o'chirmoqchimisiz?
                                                    </p>

                                                    <div class="flex flex-wrap sm:flex-nowrap gap-3">
                                                        <button type="button"
                                                            onclick="document.getElementById('delete-modal-{{ $director->id }}').classList.add('hidden')"
                                                            class="w-full sm:flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-2xl transition-all">
                                                            Bekor qilish
                                                        </button>

                                                        <form action="{{ route('directors.destroy', $director->id) }}"
                                                            method="POST" class="w-full sm:flex-1 m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl shadow-lg transition-all transform active:scale-95">
                                                                O'chirilsin
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Direktorlar
                                        topilmadi</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Yangi direktor qo'shish uchun
                                        yuqoridagi tugmani bosing.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($directors->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $directors->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection