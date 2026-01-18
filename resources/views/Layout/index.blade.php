<!DOCTYPE html>
<html lang="uz" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="# csrf_token() #">
    <title>@yield('title', 'Dashboard') - Edu Center</title>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .flag-icon {
            width: 24px;
            height: 18px;
            background-size: cover;
            display: inline-block;
            border-radius: 2px;
        }

        .flag-uz {
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MDAgMzAwIj48cGF0aCBmaWxsPSIjMDA5OWQ4IiBkPSJNMCAwaDUwMHYxMDBIMHoiLz48cGF0aCBmaWxsPSIjZmZmIiBkPSJNMCAxMDBoNTAwdjEwMEgweiIvPjxwYXRoIGZpbGw9IiMxZWI1M2EiIGQ9Ik0wIDIwMGg1MDB2MTAwSDB6Ii8+PHBhdGggZmlsbD0iI2ZmMCIgZD0iTTAgOTVoNTAwdjEwSDB6TTAgMTk1aDUwMHYxMEgweiIvPjwvc3ZnPg==');
        }

        .flag-ru {
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA5MDAgNjAwIj48cGF0aCBmaWxsPSIjZmZmIiBkPSJNMCAwaDkwMHYyMDBIMHoiLz48cGF0aCBmaWxsPSIjMDA1M2E1IiBkPSJNMCAyMDBoOTAwdjIwMEgweiIvPjxwYXRoIGZpbGw9IiNkNTIwMjAiIGQ9Ik0wIDQwMGg5MDB2MjAwSDB6Ii8+PC9zdmc+');
        }

        .flag-en {
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2MCAzMCI+PGRlZnM+PGNsaXBQYXRoIGlkPSJ0Ij48cGF0aCBkPSJNMzAsMTUgaC0zMCB2LTE1IHogdi0xNSBoLTMwIHogaC0zMCB2MTUgeiBtMzAsMCBjMCwtMTUgMzAsMCAwLDE1eiIvPjwvY2xpcFBhdGg+PC9kZWZzPjxwYXRoIGQ9Ik0wLDBoNjB2MzBIMHoiIGZpbGw9IiMwMTI2OWEiLz48cGF0aCBkPSJNMCwwIGw2MCwzMCBNNjAsMCBMMCwzMCIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjYiLz48cGF0aCBkPSJNMCwwIGw2MCwzMCBNNjAsMCBMMCwzMCIgY2xpcC1wYXRoPSJ1cmwoI3QpIiBzdHJva2U9IiNjODEwMmUiIHN0cm9rZS13aWR0aD0iNCIvPjxwYXRoIGQ9Ik0zMCwwIHYzMCBNMCwxNSBoNjAiIHN0cm9rZT0iI2ZmZiIgc3Ryb2tlLXdpZHRoPSIxMCIvPjxwYXRoIGQ9Ik0zMCwwIHYzMCBNMCwxNSBoNjAiIHN0cm9rZT0iI2M4MTAyZSIgc3Ryb2tlLXdpZHRoPSI2Ii8+PC9zdmc+');
        }

        .sidebar-collapsed {
            width: 5rem;
        }

        .sidebar-expanded {
            width: 16rem;
        }

        .nav-text {
            transition: opacity 0.2s, width 0.2s;
        }

        .sidebar-collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-collapsed .badge-count {
            opacity: 0;
            width: 0;
        }

        .tooltip {
            position: absolute;
            left: 100%;
            margin-left: 0.75rem;
            padding: 0.5rem 0.75rem;
            background: rgba(17, 24, 39, 0.95);
            color: white;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 1000;
        }

        .sidebar-collapsed .nav-item:hover .tooltip {
            opacity: 1;
        }

        @media (max-width: 1024px) {
            .sidebar-collapsed .tooltip {
                display: none;
            }
        }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="h-full bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-200">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"></div>

    <div class="flex h-full overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out lg:static lg:flex lg:flex-shrink-0 sidebar-expanded">
            <div class="flex flex-col h-full w-full">
                <div
                    class="flex flex-col flex-grow bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700/50 pt-5 pb-4 overflow-y-auto transition-colors duration-200 shadow-xl lg:shadow-none">

                    <!-- Header with Logo and Toggle -->
                    <div class="flex items-center justify-between px-6 mb-8">
                        <div class="flex items-center overflow-hidden">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0 transform hover:scale-105 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span
                                class="ml-3 text-gray-900 dark:text-white text-xl font-bold tracking-tight nav-text">Diggo</span>
                        </div>

                        <!-- Toggle Button for Desktop -->
                        <button id="sidebar-toggle"
                            class="hidden lg:block p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Close Button for Mobile -->
                        <button id="close-sidebar"
                            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-3 space-y-1">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 nav-text">
                            Asosiy</p>

                        <a href="{{ route('dashboard') }}"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all 
                            {{ request()->routeIs('dashboard')
                                ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                            <svg class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>

                            <span class="ml-3 nav-text">Boshqaruv paneli</span>

                            @if (request()->routeIs('dashboard'))
                                <span
                                    class="ml-auto bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 py-0.5 px-2 text-xs font-bold rounded-full">
                                    4
                                </span>
                            @endif

                            <span class="tooltip">Boshqaruv paneli</span>
                        </a>


                        <!-- Users Dropdown -->
                        <div class="space-y-1">
                            <button
                                onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow-icon').classList.toggle('rotate-180')"
                                class="nav-item relative w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all 
                                {{ request()->is('staffs*', 'directors*', 'teachers*') ? 'bg-indigo-50 dark:bg-indigo-900/10 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="h-5 w-5 {{ request()->is('staffs*', 'directors*') ? 'text-indigo-600' : 'text-gray-400' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>

                                <span class="ml-3 nav-text">Foydalanuvchilar</span>

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor"
                                    class="arrow-icon ml-auto h-4 w-4 text-gray-400 transition-transform duration-300 {{ request()->is('staffs*', 'directors*') ? 'rotate-180' : '' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div
                                class="{{ request()->is('staffs*', 'directors*', 'teachers*') ? '' : 'hidden' }} ml-8 space-y-1 transition-all">

                                <a href="{{ route('staffs.index') }}"
                                    class="flex items-center px-3 py-2 text-sm rounded-lg transition-all 
                                        {{ request()->is('staffs*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.25 14.15v4.25c0 .414-.336.75-.75.75H4.5a.75.75 0 0 1-.75-.75v-4.25m16.5 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 14.15m16.5 0V9a2.25 2.25 0 0 0-2.25-2.25H16.5V4.5A2.25 2.25 0 0 0 14.25 2.25h-4.5A2.25 2.25 0 0 0 7.5 4.5V6.75H6.75A2.25 2.25 0 0 0 4.5 9v5.15" />
                                    </svg>
                                    Staff
                                </a>

                                <a href="{{ route('directors.index') }}"
                                    class="flex items-center px-3 py-2 text-sm rounded-lg transition-all 
                                    {{ request()->is('directors*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6.75h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                    </svg>
                                    Directors
                                </a>
                                <a href="{{ route('teachers.index') }}"
                                    class="flex items-center px-3 py-2 text-sm rounded-lg transition-all 
                                    {{ request()->is('teachers*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.425 4.732 4.125 4.125 0 0 0 4.83 5.437m15.482-10.17a50.591 50.591 0 0 1 2.425 4.732 4.125 4.125 0 0 1-4.83 5.437m-13.077-10.17a50.66 50.66 0 0 1 13.077 0ZM12 4.577a74.123 74.123 0 0 0-8.149 5.57m16.299 0A74.13 74.13 0 0 1 12 4.577Zm0 0V9" />
                                    </svg>
                                    Teachers
                                </a>

                                <a href="#"
                                    class="flex items-center px-3 py-2 text-sm rounded-lg text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0a5.995 5.995 0 0 0-4.058-2.932m0 0a3 3 0 1 0-4.681 0m0 0a5.995 5.995 0 0 0-4.058 2.932m0 0a5.971 5.971 0 0 0-.941 3.197m0 0a9.214 9.214 0 0 1 3.741.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c2.17 0 4.207-.576 5.963-1.584A6.062 6.062 0 0 1 18 18.719m-6-7.016a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm10.5-4.691a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM4.5 7.009a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                    </svg>
                                    Students
                                </a>
                            </div>
                        </div>
                        <a href="#"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-50 dark:hover:from-gray-700 dark:hover:to-gray-700/50 hover:text-gray-900 dark:hover:text-white group transition-all">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-purple-500 flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="ml-3 nav-text">Guruhlar</span>
                            <span class="tooltip">Guruhlar</span>
                        </a>

                        <a href="#"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-50 dark:hover:from-gray-700 dark:hover:to-gray-700/50 hover:text-gray-900 dark:hover:text-white group transition-all">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-blue-500 flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="ml-3 nav-text">Kurslar</span>
                            <span class="tooltip">Kurslar</span>
                        </a>

                        <a href="#"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-50 dark:hover:from-gray-700 dark:hover:to-gray-700/50 hover:text-gray-900 dark:hover:text-white group transition-all">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-green-500 flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="ml-3 nav-text">Moliya/To'lovlar</span>
                            <span class="tooltip">Moliya/To'lovlar</span>
                        </a>

                        <a href="#"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-50 dark:hover:from-gray-700 dark:hover:to-gray-700/50 hover:text-gray-900 dark:hover:text-white group transition-all">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-orange-500 flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="ml-3 nav-text">Statistika</span>
                            <span class="tooltip">Statistika</span>
                        </a>
                    </nav>

                    <!-- Active Courses -->
                    <div class="mt-8 px-4 sidebar-courses">
                        <p
                            class="px-2 text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider nav-text">
                            Faol Kurslar
                        </p>
                        <div class="mt-3 space-y-2">
                            <a href="#"
                                class="group flex items-center px-2 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100/50 dark:hover:from-blue-900/20 dark:hover:to-blue-800/20 transition-all">
                                <div
                                    class="w-9 h-9 bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-lg flex items-center justify-center font-bold flex-shrink-0 shadow-md">
                                    E</div>
                                <span class="ml-3 truncate nav-text">English IELTS 7.0</span>
                            </a>
                            <a href="#"
                                class="group flex items-center px-2 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 rounded-xl hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100/50 dark:hover:from-purple-900/20 dark:hover:to-purple-800/20 transition-all">
                                <div
                                    class="w-9 h-9 bg-gradient-to-br from-purple-400 to-purple-600 text-white rounded-lg flex items-center justify-center font-bold flex-shrink-0 shadow-md">
                                    M</div>
                                <span class="ml-3 truncate nav-text">Matematika (DTM)</span>
                            </a>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div class="mt-auto px-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div
                            class="flex items-center p-2.5 rounded-xl hover:bg-gradient-to-r hover:from-gray-100 hover:to-gray-50 dark:hover:from-gray-700 dark:hover:to-gray-700/50 transition-all cursor-pointer group">
                            <div class="relative flex-shrink-0">
                                <img class="h-10 w-10 rounded-xl object-cover ring-2 ring-indigo-100 dark:ring-gray-600 shadow-md"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff"
                                    alt="{{ Auth::user()->name }}">
                                <span
                                    class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-400 ring-2 ring-white dark:ring-gray-800"></span>
                            </div>
                            <div class="ml-3 overflow-hidden nav-text">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                    {{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Administrator</p>
                            </div>
                            <svg class="ml-auto h-4 w-4 text-gray-400 group-hover:text-indigo-500 nav-text flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden w-full">
            <header
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md shadow-sm border-b border-gray-200 dark:border-gray-700/50 transition-colors duration-200 z-30 sticky top-0">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <button type="button" id="mobile-menu-button"
                            class="lg:hidden -ml-2 mr-3 p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-all">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="flex items-center lg:hidden">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="ml-2 text-gray-900 dark:text-white text-lg font-bold">Diggo</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <button id="theme-toggle"
                            class="p-2.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all hover:scale-105">
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <button
                            class="p-2.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl relative transition-all hover:scale-105">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                            </span>
                        </button>

                        <div class="relative ml-1">
                            <button type="button" id="user-menu-button"
                                class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <img class="h-9 w-9 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700 shadow-sm"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff"
                                    alt="{{ Auth::user()->name }}">
                                <svg class="hidden sm:block h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="user-menu"
                                class="hidden absolute right-0 mt-2 w-56 rounded-xl shadow-xl bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">

                                <div
                                    class="px-3 py-3 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ collect(explode(' ', Auth::user()->name))->map(fn($name) => Str::upper(Str::substr($name, 0, 1)))->take(2)->implode('') }}
                                        </div>

                                        <div class="ml-2.5 overflow-hidden">
                                            <p
                                                class="text-xs font-bold text-gray-900 dark:text-white truncate leading-none">
                                                {{ Auth::user()->name }}
                                            </p>
                                            <p
                                                class="text-[10px] text-gray-500 dark:text-gray-400 truncate mt-1 leading-none">
                                                {{ Auth::user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-1">
                                    <a href="#"
                                        class="flex items-center px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors group">
                                        <svg class="mr-2.5 h-4 w-4 text-gray-400 group-hover:text-indigo-600"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profil
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-3 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors group">
                                        <svg class="mr-2.5 h-4 w-4 text-gray-400 group-hover:text-indigo-600"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                        Sozlamalar
                                    </a>
                                </div>

                                <div class="border-t border-gray-50 dark:border-gray-700">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors group">
                                            <svg class="mr-2.5 h-4 w-4 text-red-400 group-hover:text-red-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Chiqish
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main
                class="flex-1 relative overflow-y-auto focus:outline-none bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-200">
                <div class="py-8">
                    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">

                        {{-- Success Message --}}
                        @if (session('success'))
                            <div id="alert-success"
                                class="mb-6 flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border-l-4 border-green-500 rounded-xl shadow-lg text-green-800 dark:text-green-200 backdrop-blur-sm transition-all duration-300">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm font-medium">{{ session('success') }}</span>
                                </div>
                                {{-- Yopish tugmasi --}}
                                <button onclick="document.getElementById('alert-success').remove()"
                                    class="ml-auto p-1.5 hover:bg-green-100 dark:hover:bg-green-800/50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif

                        {{-- Error Message --}}
                        @if (session('error') || $errors->any())
                            <div id="alert-error"
                                class="mb-6 flex items-start justify-between p-4 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/30 dark:to-rose-900/30 border-l-4 border-red-500 rounded-xl shadow-lg text-red-800 dark:text-red-200 backdrop-blur-sm transition-all duration-300">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <span class="text-sm font-bold">Xatolik yuz berdi:</span>
                                        <ul class="mt-2 list-disc list-inside text-xs space-y-1">
                                            @if (session('error'))
                                                <li>{{ session('error') }}</li>
                                            @endif
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                {{-- Yopish tugmasi --}}
                                <button onclick="document.getElementById('alert-error').remove()"
                                    class="ml-auto p-1.5 hover:bg-red-100 dark:hover:bg-red-800/50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success xabari uchun
            const successAlert = document.getElementById('alert-success');
            if (successAlert) {
                setTimeout(() => {
                    fadeOutAndRemove(successAlert);
                }, 5000); // 5 sekund kutadi
            }

            // Error xabari uchun (ixtiyoriy, odatda xatolar ko'rinib turishi yaxshi)
            const errorAlert = document.getElementById('alert-error');
            if (errorAlert) {
                setTimeout(() => {
                    fadeOutAndRemove(errorAlert);
                }, 8000); // Xatolar uzunroq bo'lishi mumkinligi uchun 8 sekund
            }

            // Yo'qotish funksiyasi
            function fadeOutAndRemove(element) {
                element.style.transition = "opacity 0.5s ease";
                element.style.opacity = "0";
                setTimeout(() => {
                    element.remove();
                }, 500); // Transition tugagach o'chirib tashlaydi
            }
        });
    </script>
    <script>
        const elements = {
            themeBtn: document.getElementById('theme-toggle'),
            darkIcon: document.getElementById('theme-toggle-dark-icon'),
            lightIcon: document.getElementById('theme-toggle-light-icon'),
            mobileMenuBtn: document.getElementById('mobile-menu-button'),
            sidebar: document.getElementById('sidebar'),
            sidebarToggle: document.getElementById('sidebar-toggle'),
            closeSidebarBtn: document.getElementById('close-sidebar'),
            sidebarOverlay: document.getElementById('sidebar-overlay'),
            userBtn: document.getElementById('user-menu-button'),
            userMenu: document.getElementById('user-menu')
        };

        // Sidebar toggle state
        let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

        // Initialize sidebar state
        const initSidebar = () => {
            if (window.innerWidth >= 1024) {
                if (sidebarCollapsed) {
                    elements.sidebar.classList.remove('sidebar-expanded');
                    elements.sidebar.classList.add('sidebar-collapsed');
                } else {
                    elements.sidebar.classList.remove('sidebar-collapsed');
                    elements.sidebar.classList.add('sidebar-expanded');
                }
            }
        };

        // Desktop sidebar toggle
        elements.sidebarToggle?.addEventListener('click', () => {
            sidebarCollapsed = !sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', sidebarCollapsed);

            if (sidebarCollapsed) {
                elements.sidebar.classList.remove('sidebar-expanded');
                elements.sidebar.classList.add('sidebar-collapsed');
            } else {
                elements.sidebar.classList.remove('sidebar-collapsed');
                elements.sidebar.classList.add('sidebar-expanded');
            }
        });

        // Mobile sidebar open
        elements.mobileMenuBtn?.addEventListener('click', () => {
            elements.sidebar.classList.remove('-translate-x-full');
            elements.sidebarOverlay.classList.remove('hidden');
        });

        // Mobile sidebar close
        elements.closeSidebarBtn?.addEventListener('click', () => {
            elements.sidebar.classList.add('-translate-x-full');
            elements.sidebarOverlay.classList.add('hidden');
        });

        // Overlay click
        elements.sidebarOverlay?.addEventListener('click', () => {
            elements.sidebar.classList.add('-translate-x-full');
            elements.sidebarOverlay.classList.add('hidden');
        });

        // Dark Mode
        const updateThemeIcons = () => {
            const isDark = document.documentElement.classList.contains('dark');
            elements.darkIcon?.classList.toggle('hidden', isDark);
            elements.lightIcon?.classList.toggle('hidden', !isDark);
        };

        elements.themeBtn?.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        });

        // User menu toggle
        elements.userBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            elements.userMenu?.classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!elements.userBtn?.contains(e.target) && !elements.userMenu?.contains(e.target)) {
                elements.userMenu?.classList.add('hidden');
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                elements.sidebar.classList.remove('-translate-x-full');
                elements.sidebarOverlay.classList.add('hidden');
                initSidebar();
            } else {
                elements.sidebar.classList.remove('sidebar-collapsed');
                elements.sidebar.classList.add('sidebar-expanded');
            }
        });

        // Initialize
        updateThemeIcons();
        initSidebar();
    </script>

    @stack('scripts')
</body>

</html>
