@extends('Layout.app')

@section('title', "Yordam & Qo'llab-quvvatlash")

@section('content')
<div class="space-y-6">

    {{-- ═══════════════════════════════════════════════
         HERO HEADER
    ═══════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-3xl shadow-xl">
        {{-- Decorative blobs --}}
        <div class="absolute top-0 right-0 w-72 h-72 bg-amber-500/10 rounded-full -translate-y-1/2 translate-x-1/4 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-56 h-56 bg-blue-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-3xl pointer-events-none"></div>

        <div class="relative px-6 py-10 lg:px-12 lg:py-14 flex flex-col lg:flex-row items-center gap-8">
            {{-- Icon --}}
            <div class="flex-shrink-0 w-20 h-20 lg:w-24 lg:h-24 bg-amber-500 rounded-3xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            {{-- Text --}}
            <div class="text-center lg:text-left flex-1">
                <h1 class="text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                    Yordam & Qo'llab-quvvatlash
                </h1>
                <p class="mt-2 text-base text-slate-400 max-w-xl">
                    Savollaringizga javob toping, qo'llanmani yuklab oling yoki administrator bilan bog'laning
                </p>
                {{-- Quick stats --}}
                <div class="flex flex-wrap gap-4 mt-5 justify-center lg:justify-start">
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-slate-300">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        Administrator online
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-slate-300">
                        ⚡ Javob vaqti: 30 daqiqa
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-slate-300">
                        📞 9:00 – 20:00
                    </div>
                </div>
            </div>
            {{-- PDF Download button --}}
            <div class="flex-shrink-0">
                <a href="{{ route('help.download-pdf') }}"
                   class="group inline-flex flex-col items-center gap-2 px-6 py-5 bg-amber-500 hover:bg-amber-400 rounded-2xl transition-all duration-200 shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-8 h-8 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm font-bold text-white leading-tight text-center">PDF<br>Qo'llanma</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         CONTACT CARDS
    ═══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Phone --}}
        <a href="tel:+998881156101"
           class="group bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-0.5">Telefon</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">+998 88 115 61 01</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Darhol javob</p>
            </div>
        </a>

        {{-- Email --}}
        <a href="mailto:xoliqulovmuhammadnabi@gmail.com"
           class="group bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-0.5">Email</p>
                <p class="text-xs font-bold text-gray-900 dark:text-white truncate">xoliqulovmuhammadnabi<br>@gmail.com</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">1–2 soat ichida</p>
            </div>
        </a>

        {{-- Working hours --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-amber-100 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-0.5">Ish vaqti</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">9:00 – 20:00</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Dushanba – Shanba</p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         ADMIN PROFILE CARD
    ═══════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row items-stretch">
            {{-- Left accent --}}
            <div class="sm:w-2 bg-amber-500 flex-shrink-0"></div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-5 lg:p-6 flex-1">
                {{-- Avatar --}}
                <div class="flex-shrink-0 relative">
                    <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center shadow-md">
                        <span class="text-3xl font-black text-amber-400">MX</span>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 border-2 border-white dark:border-slate-900 rounded-full"></span>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-lg font-extrabold text-gray-900 dark:text-white">Muhammadnabi Xoliqulov</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30">
                            Administrator
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-x-5 gap-y-1.5 mt-2">
                        <a href="tel:+998881156101" class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="font-medium">+998 88 115 61 01</span>
                        </a>
                        <a href="mailto:xoliqulovmuhammadnabi@gmail.com" class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">xoliqulovmuhammadnabi@gmail.com</span>
                        </a>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="flex flex-col sm:items-end gap-2">
                    <a href="tel:+998881156101"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-emerald-200 dark:shadow-none active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Qo'ng'iroq qilish
                    </a>
                    <a href="mailto:xoliqulovmuhammadnabi@gmail.com"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email yuborish
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         MUROJAAT FORMASI + QUICK LINKS (2 col)
    ═══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Contact Form --}}
        <div class="lg:col-span-3 bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 bg-purple-100 dark:bg-purple-500/20 border border-purple-200 dark:border-purple-500/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Murojaat qoldiring</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Savol yoki muammoingizni yozing</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-4 flex items-start gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/30 rounded-xl">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
            @endif

            <form action="{{ route('help.send') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Ismingiz</label>
                        <input type="text" name="name" value="{{ auth()->user()->name ?? old('name') }}" readonly
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all cursor-not-allowed opacity-70">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Telefon</label>
                        <input type="tel" name="phone" value="{{ auth()->user()->phone ?? old('phone') }}" placeholder="+998..."
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Murojaat turi</label>
                    <select name="type"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all cursor-pointer">
                        <option value="general">Umumiy savol</option>
                        <option value="technical">Texnik muammo</option>
                        <option value="payment">To'lov masalasi</option>
                        <option value="shop">Do'kon masalasi</option>
                        <option value="account">Hisob muammosi</option>
                        <option value="other">Boshqa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Mavzu</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Murojaat mavzusi..."
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('subject') border-rose-500 @enderror">
                    @error('subject')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Xabar</label>
                    <textarea name="message" rows="5" placeholder="Muammo yoki savolingizni batafsil yozing..."
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all resize-none @error('message') border-rose-500 @enderror">{{ old('message') }}</textarea>
                    @error('message')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm rounded-xl transition-all shadow-sm shadow-purple-200 dark:shadow-none active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Yuborish
                </button>
            </form>
        </div>

        {{-- Right column --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- PDF Download card --}}
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg shadow-amber-200 dark:shadow-none p-5">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-white">PDF Qo'llanma</h3>
                        <p class="text-xs text-amber-100 mt-1">Platforma bo'yicha to'liq yo'riqnoma. Oflayn ham o'qing!</p>
                        <div class="flex items-center gap-2 mt-1.5 text-xs text-amber-200">
                            <span>📄 6 bo'lim</span>
                            <span>•</span>
                            <span>{{ number_format(file_exists(public_path('guides/qollanma.pdf')) ? filesize(public_path('guides/qollanma.pdf')) / 1024 : 17, 0) }} KB</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('help.download-pdf') }}"
                   class="mt-4 flex items-center justify-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white font-bold text-sm rounded-xl transition-all active:scale-95 border border-white/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Yuklab olish
                </a>
            </div>

            {{-- Quick links --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Tezkor havolalar
                </h3>
                <div class="space-y-2">
                    @php
                    $links = [
                        ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Bosh sahifa', 'route' => 'student.dashboard', 'color' => 'purple'],
                        ['icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'label' => "Do'kon", 'route' => 'student.shop.index', 'color' => 'amber'],
                        ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Buyurtmalarim', 'route' => 'student.shop.orders', 'color' => 'blue'],
                        ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Profilim', 'route' => 'student.profile', 'color' => 'emerald'],
                    ];
                    $colorMap = ['purple' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-100 dark:border-purple-500/20', 'amber' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-500/20', 'blue' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/20', 'emerald' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20'];
                    @endphp
                    @foreach($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors group">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg border {{ $colorMap[$link['color']] }} flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ $link['label'] }}</span>
                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 ml-auto group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         FAQ ACCORDION
    ═══════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-amber-100 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Ko'p so'raladigan savollar</h2>
                <p class="text-xs text-gray-400 dark:text-gray-500">Tez-tez uchraydigan savollar va javoblar</p>
            </div>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700/50" id="faqAccordion">
            @foreach($faqs as $i => $faq)
            <div class="faq-item">
                <button type="button"
                    onclick="toggleFaq({{ $i }})"
                    class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                    <span class="flex items-center gap-3 flex-1 min-w-0">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-full text-xs font-black">{{ $i + 1 }}</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $faq['question'] }}</span>
                    </span>
                    <svg id="faq-icon-{{ $i }}" class="flex-shrink-0 w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-answer-{{ $i }}" class="hidden px-6 pb-4">
                    <div class="ml-9 text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-l-2 border-amber-200 dark:border-amber-500/30 pl-4">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
<script>
function toggleFaq(index) {
    const answer = document.getElementById('faq-answer-' + index);
    const icon   = document.getElementById('faq-icon-' + index);
    const isOpen = !answer.classList.contains('hidden');

    // Close all
    document.querySelectorAll('[id^="faq-answer-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id^="faq-icon-"]').forEach(el => el.style.transform = '');

    if (!isOpen) {
        answer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>
@endpush
@endsection