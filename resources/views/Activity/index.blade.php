@extends('Layout.app')

@section('title', 'Faoliyatlar')

@section('content')
    <div class="space-y-6 mb-4">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Icon Box --}}
                <div
                    class="hidden sm:flex p-3 bg-purple-50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 rounded-2xl shadow-sm">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Faoliyatlar
                        </h1>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400 border border-purple-100 dark:border-purple-500/20 shadow-sm">
                            {{ $totalActivities }} ta faoliyat
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Uy ishi, imtihon va boshqa faoliyatlarni boshqarish
                    </p>
                </div>
            </div>

            {{-- Create Button --}}
            <a href="{{ route('activities.create') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3
               bg-purple-500 hover:bg-purple-600 text-white
               dark:bg-purple-600 dark:hover:bg-purple-700
               border border-purple-400/50 shadow-sm shadow-purple-100 dark:shadow-none
               font-semibold rounded-xl transition-all duration-200
               transform hover:scale-[1.02] active:scale-[0.98] group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5 text-white group-hover:rotate-12 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="font-bold">Faoliyat qo'shish</span>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-5">
            {{-- Jami --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-5 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400">Jami</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $totalActivities }}</p>
                    </div>
                    <div class="w-11 h-11 bg-purple-50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Faol --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-emerald-500/20 p-5 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400">Faol</p>
                        <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $activeCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Uy ishlari --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-5 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400">Uy ishlari</p>
                        <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ $homeworkCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Imtihonlar --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-red-500/20 p-5 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-red-600 dark:text-red-400">Imtihonlar</p>
                        <p class="text-3xl font-black text-red-600 dark:text-red-400 mt-1">{{ $examCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- O'rtacha coin --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-amber-500/20 p-5 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400">O'rtacha coin</p>
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ round($avgCoins) }}</p>
                    </div>
                    <div class="w-11 h-11 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-4 lg:p-6">
            <form method="GET" action="{{ route('activities.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

                    {{-- Search --}}
                    <div class="relative lg:col-span-4 sm:col-span-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Faoliyat nomi..."
                            class="w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition-all outline-none text-sm">
                        @if(request('search'))
                            <a href="{{ route('activities.index', request()->except('search')) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    {{-- Type Filter --}}
                    <div class="lg:col-span-2">
                        <select name="type"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">Turi (Barchasi)</option>
                            @foreach($types as $type)
                                @php
                                    $typeLabels = ['homework'=>'Uyga vazifa','exam'=>'Imtihon','competition'=>'Musobaqa','project'=>'Loyiha','other'=>'Boshqa'];
                                @endphp
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ $typeLabels[$type] ?? ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div class="lg:col-span-2">
                        <select name="status"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white text-sm outline-none transition-all cursor-pointer">
                            <option value="">Holat (Barchasi)</option>
                            @foreach($statuses as $status)
                                @php
                                    $statusLabels = ['draft'=>'Qoralama','active'=>'Faol','completed'=>'Tugallangan','cancelled'=>'Bekor'];
                                @endphp
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Group Filter --}}
                    <div class="lg:col-span-4">
                        <div class="relative" id="filterGroupCustomContainer">
                            <div onclick="toggleFilterGroupDropdown()"
                                class="w-full flex items-center justify-between px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer group hover:border-purple-500 transition-all">
                                <span id="selectedFilterGroupLabel" class="text-sm text-gray-600 dark:text-gray-300">
                                    @php $reqGroup = $groups->firstWhere('id', request('group_id')); @endphp
                                    {{ $reqGroup ? $reqGroup->name : 'Guruh (Barchasi)' }}
                                </span>
                                <input type="hidden" name="group_id" id="filter_group_id_input" value="{{ request('group_id') }}">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-500 transition-transform" id="filterGroupArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div id="filterGroupMenu" class="hidden absolute z-[140] w-full mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                <div class="p-2 border-b dark:border-gray-700">
                                    <input type="text" id="filterGroupSearchInput" oninput="filterFilterGroups()" placeholder="Guruhni qidirish..."
                                        class="w-full px-3 py-2 bg-gray-100 dark:bg-slate-900 dark:text-white border-none rounded-lg text-xs focus:ring-1 focus:ring-purple-500 outline-none">
                                </div>
                                <div class="max-h-56 overflow-y-auto custom-scrollbar">
                                    <div onclick="selectFilterGroup('', 'Guruh (Barchasi)')"
                                        class="px-4 py-2.5 text-xs text-gray-500 hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer border-b border-gray-50 dark:border-gray-700/50">
                                        Barchasi
                                    </div>
                                    @foreach($groups as $group)
                                        <div onclick="selectFilterGroup('{{ $group->id }}', '{{ $group->name }}')"
                                            class="filter-group-item px-4 py-2.5 text-xs text-gray-700 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-purple-500/10 cursor-pointer flex flex-col border-b border-gray-50 dark:border-gray-700/50 last:border-none">
                                            <span class="font-bold filter-item-name">{{ $group->name }}</span>
                                            <span class="text-[10px] text-gray-400 filter-item-course">{{ $group->course->title ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="noFilterGroupFound" class="hidden p-4 text-center text-xs text-gray-500">Topilmadi...</div>
                            </div>
                        </div>
                    </div>

                    {{-- Date From --}}
                    <div class="lg:col-span-3">
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    {{-- Date To --}}
                    <div class="lg:col-span-3">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 text-gray-900 dark:text-white text-sm outline-none transition-all">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2 lg:col-span-6 sm:col-span-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('activities.index') }}"
                            class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-500 dark:text-gray-400 rounded-xl transition-all flex items-center justify-center active:scale-95"
                            title="Tozalash">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Faoliyat</th>
                            <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Turi</th>
                            <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Holat</th>
                            <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Guruh</th>
                            <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Sana</th>
                            <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Max Coin</th>
                            <th class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Amallar</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($activities as $activity)
                            @php
                                $typeColors = [
                                    'exam'        => 'red',
                                    'homework'    => 'blue',
                                    'competition' => 'purple',
                                    'project'     => 'green',
                                    'other'       => 'gray',
                                ];
                                $color = $typeColors[$activity->type] ?? 'gray';

                                $statusConfig = [
                                    'draft'     => ['label' => 'Qoralama',    'class' => 'bg-gray-100 text-gray-700 dark:bg-gray-700/40 dark:text-gray-300'],
                                    'active'    => ['label' => 'Faol',        'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'],
                                    'completed' => ['label' => 'Tugallangan', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'],
                                    'cancelled' => ['label' => 'Bekor',       'class' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400'],
                                ];
                                $sc = $statusConfig[$activity->status] ?? ['label' => ucfirst($activity->status), 'class' => 'bg-gray-100 text-gray-700'];

                                $typeLabels = ['homework'=>'Uyga vazifa','exam'=>'Imtihon','competition'=>'Musobaqa','project'=>'Loyiha','other'=>'Boshqa'];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                {{-- Faoliyat nomi --}}
                                <td class="px-4 lg:px-6 py-4">
                                    <div class="flex items-center group">
                                        <div class="flex-shrink-0 h-10 w-10 lg:h-12 lg:w-12">
                                            <div class="h-10 w-10 lg:h-12 lg:w-12 rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-500/20 border border-{{ $color }}-200 dark:border-{{ $color }}-500/30 flex items-center justify-center transition-transform group-hover:scale-105">
                                                @if($activity->image)
                                                    <img src="{{ Storage::url($activity->image) }}" alt="{{ $activity->name }}" class="w-full h-full object-cover rounded-xl">
                                                @else
                                                    <svg class="w-6 h-6 text-{{ $color }}-700 dark:text-{{ $color }}-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-3 lg:ml-4 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                    {{ $activity->name }}
                                                </span>
                                                @if($activity->is_required)
                                                    <span class="flex-shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400">
                                                        Majburiy
                                                    </span>
                                                @endif
                                                @if($activity->file)
                                                    <span class="flex-shrink-0" title="Fayl biriktrilgan">
                                                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                            @if($activity->description)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate max-w-xs">
                                                    {{ Str::limit($activity->description, 50) }}
                                                </div>
                                            @endif
                                            @if($activity->due_time)
                                                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                                    Muddat: {{ \Carbon\Carbon::parse($activity->due_time)->format('H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Type --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg bg-{{ $color }}-100 text-{{ $color }}-700 dark:bg-{{ $color }}-900/30 dark:text-{{ $color }}-400">
                                        {{ $typeLabels[$activity->type] ?? ucfirst($activity->type) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg {{ $sc['class'] }}">
                                        {{ $sc['label'] }}
                                    </span>
                                </td>

                                {{-- Group --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    @if($activity->group)
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->group->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->group->course->title ?? '' }}</div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Guruh yo'q</span>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($activity->date)->format('d.m.Y') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Max Coin --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $activity->max_coins }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('activities.show', $activity->id) }}"
                                            class="p-2 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl transition-all duration-200 group/btn" title="Ko'rish">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('activities.edit', $activity->id) }}"
                                            class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-xl transition-all duration-200 group/btn" title="Tahrirlash">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                            onclick="document.getElementById('delete-modal-{{ $activity->id }}').classList.remove('hidden')"
                                            class="p-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all duration-200 group/btn" title="O'chirish">
                                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        {{-- Delete Modal --}}
                                        <div id="delete-modal-{{ $activity->id }}"
                                            class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                                            <div class="relative w-full max-w-sm p-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5">
                                                <div class="text-center">
                                                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 dark:bg-rose-500/10 mb-6 rotate-3">
                                                        <svg class="h-10 w-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">O'chirishni tasdiqlang</h3>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                                                        Rostdan ham <span class="font-bold text-slate-900 dark:text-white underline decoration-rose-500/30">{{ $activity->name }}</span> faoliyatini o'chirmoqchimisiz?
                                                    </p>
                                                    <div class="grid grid-cols-2 gap-3">
                                                        <button type="button"
                                                            onclick="document.getElementById('delete-modal-{{ $activity->id }}').classList.add('hidden')"
                                                            class="px-5 py-3.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-2xl transition-all active:scale-95">
                                                            Bekor qilish
                                                        </button>
                                                        <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full px-5 py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-200 dark:shadow-none transition-all active:scale-95">
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
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Hech qanday faoliyat topilmadi</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Qidiruv shartlarini o'zgartirib ko'ring yoki yangi faoliyat qo'shing.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 p-4">
            {{ $activities->links() }}
        </div>
    @endif

    @push('scripts')
        <script>
            function toggleFilterGroupDropdown() {
                const menu  = document.getElementById('filterGroupMenu');
                const arrow = document.getElementById('filterGroupArrow');
                const isHidden = menu.classList.contains('hidden');
                menu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
                if (isHidden) document.getElementById('filterGroupSearchInput').focus();
            }

            function selectFilterGroup(id, name) {
                document.getElementById('filter_group_id_input').value = id;
                document.getElementById('selectedFilterGroupLabel').innerText = name;
                toggleFilterGroupDropdown();
            }

            function filterFilterGroups() {
                const filter  = document.getElementById('filterGroupSearchInput').value.toLowerCase();
                const items   = document.querySelectorAll('.filter-group-item');
                const noResult = document.getElementById('noFilterGroupFound');
                let hasMatch  = false;
                items.forEach(item => {
                    const name   = item.querySelector('.filter-item-name').innerText.toLowerCase();
                    const course = item.querySelector('.filter-item-course').innerText.toLowerCase();
                    const show   = name.includes(filter) || course.includes(filter);
                    item.style.display = show ? '' : 'none';
                    if (show) hasMatch = true;
                });
                noResult.classList.toggle('hidden', hasMatch);
            }

            document.addEventListener('click', function (e) {
                const container = document.getElementById('filterGroupCustomContainer');
                if (container && !container.contains(e.target)) {
                    document.getElementById('filterGroupMenu').classList.add('hidden');
                    document.getElementById('filterGroupArrow').classList.remove('rotate-180');
                }
            });
        </script>
    @endpush
@endsection