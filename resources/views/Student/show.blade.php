@extends('Layout.index')

@section('title', "O'quvchi ma'lumotlari")

@section('content')
    <div class="w-full space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="hidden sm:flex p-3.5 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-2xl shadow-sm border border-blue-200 dark:border-blue-800/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>

                <div class="space-y-1.5">
                    <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                        {{ $student->user->full_name }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $student->user->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                            <span
                                class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $student->user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $student->user->is_active ? 'Faol' : 'Nofaol' }}
                        </span>
                        <p class="text-xs lg:text-sm font-semibold text-blue-500 dark:text-blue-400 uppercase tracking-widest">
                            ID: #{{ $student->id }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('students.edit', $student) }}"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transform hover:scale-105 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Tahrirlash
                </a>
                <a href="{{ route('students.index') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Orqaga
                </a>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Profile Card --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Profile Photo Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="relative h-32 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600">
                        <div class="absolute inset-0 bg-black/10"></div>
                    </div>
                    <div class="relative px-6 pb-6">
                        <div class="flex justify-center -mt-16 mb-4">
                            @if ($student->user->photo)
                                <img src="{{ asset('storage/' . $student->user->photo) }}" alt="{{ $student->user->full_name }}"
                                    class="w-32 h-32 rounded-2xl object-cover border-4 border-white dark:border-gray-800 shadow-xl">
                            @else
                                <div
                                    class="w-32 h-32 rounded-2xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 border-4 border-white dark:border-gray-800 shadow-xl flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="text-center space-y-3">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $student->user->full_name }}</h2>
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                +998 {{ $student->user->phone }}
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div
                                class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800/30">
                                <div class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mb-1">Balans</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ number_format($student->balance, 0, ',', ' ') }} so'm
                                </div>
                            </div>
                            <div
                                class="bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-800/30">
                                <div class="text-xs font-medium text-amber-600 dark:text-amber-400 mb-1">Tangalar</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $student->coins }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Info Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tezkor ma'lumot
                    </h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Jinsi</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $student->user->gender == 'male' ? "O'g'il bola" : 'Qiz bola' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tug'ilgan sana</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $student->user->birth_date?->format('d.m.Y') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Ro'yxatdan o'tdi</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $student->enrollment_date->format('d.m.Y') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Maktab</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $student->school->name ?? 'Belgilanmagan' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Personal Information --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-500 to-rose-500 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Shaxsiy ma'lumotlar
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Familiya</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $student->user->surname }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ism</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $student->user->name }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Otasining
                                    ismi</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $student->user->middle_name ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telefon</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">+998 {{ $student->user->phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address Information --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Yashash manzili
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Viloyat</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $student->user->region ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Shahar /
                                    Tuman</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $student->user->city ?? '-' }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ko'cha va
                                    uy</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $student->user->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Parent & Emergency Contact --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-violet-500 to-purple-500 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Ota-ona va favqulodda aloqa
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ota-ona
                                    ismi</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $student->parent_name ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ota-ona
                                    telefoni</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $student->parent_phone ? '+998 ' . $student->parent_phone : '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Favqulodda
                                    aloqa</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $student->emergency_contact_name ?? '-' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Favqulodda
                                    telefon</label>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $student->emergency_contact_phone ? '+998 ' . $student->emergency_contact_phone : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Groups --}}
                @if ($student->groups->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-blue-500 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Guruhlar ({{ $student->groups->count() }})
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($student->groups as $group)
                                    <div
                                        class="p-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-600/50 rounded-xl border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $group->name }}</h4>
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-lg
                                                {{ $group->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                                {{ $group->is_active ? 'Faol' : 'Nofaol' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                            Fan: {{ $group->subject->name ?? 'Belgilanmagan' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            O'qituvchi: {{ $group->teachers->first()?->user->full_name ?? 'Belgilanmagan' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if ($student->notes)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Qo'shimcha izoh
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $student->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection