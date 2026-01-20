<!DOCTYPE html>
<html lang="uz" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Edu Center</title>
    <meta name="csrf-token" content="# csrf_token() #">
    <title>@yield('title', 'Dashboard') - Edu Center</title>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Flag icons */
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

        /* Sidebar transitions */
        /* Sidebar transitions */
        .sidebar {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 5rem;
        }

        .sidebar.expanded {
            width: 16rem;
        }

        /* Text transitions */
        .sidebar-text {
            white-space: nowrap;
            transition: none;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        /* Tooltip for collapsed state */
        .nav-tooltip {
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

        .sidebar.collapsed .nav-item:hover .nav-tooltip {
            opacity: 1;
        }

        /* Dropdown animations */
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-content.open {
            max-height: 500px;
        }

        .arrow-icon {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed .arrow-icon {
            display: none;
        }

        .arrow-icon.rotated {
            transform: rotate(180deg);
        }

        /* Theme switcher custom styles */
        .theme-option {
            position: relative;
            transition: all 0.2s;
        }

        .theme-option:hover {
            background: rgba(99, 102, 241, 0.1);
        }

        .theme-option.active {
            background: rgba(99, 102, 241, 0.1);
            color: rgb(99, 102, 241);
        }

        .theme-indicator {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: rgb(99, 102, 241);
            border-radius: 0 2px 2px 0;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .theme-option.active .theme-indicator {
            opacity: 1;
        }

        /* @media (max-width: 1024px) {
            .sidebar.collapsed .nav-tooltip {
                display: none;
            }
        } */

        /* Dark mode styles */
        .dark .theme-option.active {
            background: rgba(99, 102, 241, 0.2);
        }

        /* Show dropdown items centered when sidebar is collapsed */
        .sidebar.collapsed .dropdown-content.open {
            max-height: 500px;
        }

        .sidebar.collapsed .dropdown-content .ml-8 {
            margin-left: 0 !important;
        }

        .sidebar.collapsed .dropdown-content a {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar.collapsed .dropdown-content svg {
            margin-right: 0;
        }
    </style>
</head>

<body
    class="h-full bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-200">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"></div>

    <div class="flex h-full overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out lg:static lg:flex lg:flex-shrink-0">
            <div class="flex flex-col h-full w-full">
                <div
                    class="flex flex-col flex-grow bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700/50 pt-5 pb-4 overflow-y-auto transition-colors duration-200 shadow-xl lg:shadow-none">

                    <!-- Header with Logo and Toggle -->
                    <div class="flex items-center justify-between px-6 mb-8">
                        <div class="flex items-center overflow-hidden min-w-0">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0 transform hover:scale-105 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span
                                class="ml-3 text-gray-900 dark:text-white text-xl font-bold tracking-tight sidebar-text">Diggo</span>
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
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 sidebar-text">
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

                            <span class="ml-3 sidebar-text">Boshqaruv paneli</span>

                            @if (request()->routeIs('dashboard'))
                                <span
                                    class="ml-auto bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 py-0.5 px-2 text-xs font-bold rounded-full sidebar-text">4</span>
                            @endif

                            <span class="nav-tooltip">Boshqaruv paneli</span>
                        </a>
                        <div class="space-y-1">
                            <button id="roles-dropdown-btn"
                                onclick="const dropdown = this.nextElementSibling; dropdown.classList.toggle('open'); this.querySelector('.arrow-icon').classList.toggle('rotate-180');"
                                class="nav-item relative w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->is('roles*', 'permissions*','role-permissions*') ? 'bg-indigo-50 dark:bg-indigo-900/10 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="h-5 w-5 flex-shrink-0 {{ request()->is('roles*', 'permissions*', 'role-permissions*') ? 'text-indigo-600' : 'text-gray-400' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.744c0 5.621 3.401 10.449 8.243 12.446a11.95 11.95 0 008.157-12.446c0-1.302-.207-2.557-.598-3.744A11.959 11.959 0 0112 2.714z" />
                                </svg>

                                <span class="ml-3 sidebar-text">Ruxsatnomalar</span>

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor"
                                    class="arrow-icon ml-auto h-4 w-4 text-gray-400 sidebar-text transition-transform duration-300 {{ request()->is('roles*', 'permissions*','role-permissions*') ? 'rotate-180' : '' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                <span class="nav-tooltip">Ruxsatnomalar</span>
                            </button>

                            <div id="roles-dropdown"
                                class="dropdown-content {{ request()->is('roles*', 'permissions*', 'role-permissions*') ? 'open' : '' }}">

                                <div class="ml-8 space-y-1 py-2">
                                    <a href="{{ route('roles.index') }}"
                                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('roles.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                        <span class="sidebar-text">Rollar (Roles)</span>
                                    </a>

                                    <a href="{{ route('permissions.index') }}"
                                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->is('permissions*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 mr-2 flex-shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                        </svg>
                                        <span class="sidebar-text">Huquqlar (Permissions)</span>
                                    </a>

                                    <a href="{{ route('role-permissions.index') }}"
                                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->is('role-permissions*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 mr-2 flex-shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.798.425 1.081m2.75 0a.75.75 0 0 1 1.06 1.06L12 9.75l-2.75-2.75a.75.75 0 0 1 1.06-1.06l1.69 1.69 1.69-1.69a.75.75 0 0 1 1.06 0Z" />
                                        </svg>
                                        <span class="sidebar-text">Rolga biriktirish</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <button id="users-dropdown-btn" {{-- onclick="const dropdown = this.nextElementSibling; dropdown.classList.toggle('open'); this.querySelector('.arrow-icon').classList.toggle('rotate-180');" --}}
                                class="nav-item relative w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all 
                                {{ request()->is('staffs*', 'directors*', 'teachers*', 'students*')
                                    ? 'bg-indigo-50 dark:bg-indigo-900/10 text-indigo-600'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="h-5 w-5 flex-shrink-0 {{ request()->is('staffs*', 'directors*', 'teachers*', 'students*') ? 'text-indigo-600' : 'text-gray-400' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>

                                <span class="ml-3 sidebar-text">Foydalanuvchilar</span>

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor"
                                    class="arrow-icon ml-auto h-4 w-4 text-gray-400 sidebar-text transition-transform duration-300 {{ request()->is('staffs*', 'directors*', 'teachers*', 'students*') ? 'rotate-180' : '' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                <span class="nav-tooltip">Foydalanuvchilar</span>
                            </button>

                            <div id="users-dropdown"
                                class="dropdown-content {{ request()->is('staffs*', 'directors*', 'teachers*', 'students*') ? 'open' : '' }}">

                                <div class="ml-8 space-y-1 py-2">
                                    <a href="{{ route('staffs.index') }}"
                                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('staffs.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 mr-2 flex-shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 14.15v4.25c0 .414-.336.75-.75.75H4.5a.75.75 0 0 1-.75-.75v-4.25m16.5 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 14.15m16.5 0V9a2.25 2.25 0 0 0-2.25-2.25H16.5V4.5A2.25 2.25 0 0 0 14.25 2.25h-4.5A2.25 2.25 0 0 0 7.5 4.5V6.75H6.75A2.25 2.25 0 0 0 4.5 9v5.15" />
                                        </svg>
                                        <span class="sidebar-text">Staff</span>
                                    </a>

                                    <a href="{{ route('directors.index') }}"
                                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('directors.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 mr-2 flex-shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6.75h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                        </svg>
                                        <span class="sidebar-text">Directors</span>
                                    </a>

                                    <a href="{{ route('teachers.index') }}"
                                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('teachers.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 mr-2 flex-shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.425 4.732 4.125 4.125 0 0 0 4.83 5.437m15.482-10.17a50.591 50.591 0 0 1 2.425 4.732 4.125 4.125 0 0 1-4.83 5.437m-13.077-10.17a50.66 50.66 0 0 1 13.077 0ZM12 4.577a74.123 74.123 0 0 0-8.149 5.57m16.299 0A74.13 74.13 0 0 1 12 4.577Zm0 0V9" />
                                        </svg>
                                        <span class="sidebar-text">Teachers</span>
                                    </a>

                                    <a href="{{ route('students.index') }}"
                                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('students.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 mr-2 flex-shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                        <span class="sidebar-text">Students</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <a href="#"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 group transition-all">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-purple-500 flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="ml-3 sidebar-text">Guruhlar</span>
                            <span class="nav-tooltip">Guruhlar</span>
                        </a>

                        <a href="#"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 group transition-all">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-blue-500 flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="ml-3 sidebar-text">Kurslar</span>
                            <span class="nav-tooltip">Kurslar</span>
                        </a>

                        <a href="#"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 group transition-all">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-green-500 flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="ml-3 sidebar-text">Moliya/To'lovlar</span>
                            <span class="nav-tooltip">Moliya/To'lovlar</span>
                        </a>

                        <a href="#"
                            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 group transition-all">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-orange-500 flex-shrink-0 transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="ml-3 sidebar-text">Statistika</span>
                            <span class="nav-tooltip">Statistika</span>
                        </a>
                    </nav>
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
                            class="lg:hidden -ml-2 mr-3 p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all">
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
                        <!-- Theme Switcher -->
                        <div class="relative">
                            <button id="theme-toggle"
                                class="p-2.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all hover:scale-105">
                                <svg id="theme-icon-light" class="hidden w-5 h-5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <svg id="theme-icon-dark" class="hidden w-5 h-5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                                <svg id="theme-icon-system" class="hidden w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </button>

                            <!-- Theme Menu Dropdown -->
                            <div id="theme-menu"
                                class="hidden absolute right-0 mt-2 w-44 rounded-xl shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                                <div class="py-1">
                                    <button data-theme="light"
                                        class="theme-option w-full flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 transition-colors">
                                        <span class="theme-indicator"></span>
                                        <svg class="w-4 h-4 mr-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Light
                                    </button>
                                    <button data-theme="dark"
                                        class="theme-option w-full flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 transition-colors">
                                        <span class="theme-indicator"></span>
                                        <svg class="w-4 h-4 mr-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z">
                                            </path>
                                        </svg>
                                        Dark
                                    </button>
                                    <button data-theme="system"
                                        class="theme-option w-full flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 transition-colors">
                                        <span class="theme-indicator"></span>
                                        <svg class="w-4 h-4 mr-3 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        System
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Button -->
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

                        <!-- User Menu Dropdown -->
                        <div class="relative ml-1">
                            <button type="button" id="user-menu-button"
                                class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <img class="h-9 w-9 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700 shadow-sm"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name) }}&background=6366f1&color=fff"
                                    alt="User">
                                <svg class="hidden sm:block h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="user-menu"
                                class="hidden absolute right-0 mt-2 w-64 rounded-xl shadow-xl bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">

                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <img class="h-10 w-10 rounded-lg object-cover ring-2 ring-indigo-100 dark:ring-indigo-900"
                                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name) }}&background=6366f1&color=fff"
                                            alt="User">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                {{ optional(Auth::user())->full_name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ optional(Auth::user())->phone }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-1">
                                    <a href="{{ route('profile.show') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Your Profile
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Settings
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Help & Support
                                    </a>
                                </div>

                                <div class="py-1 border-t border-gray-100 dark:border-gray-700">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group">
                                            <svg class="mr-3 h-4 w-4 text-red-500 group-hover:text-red-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main
                class="flex-1 relative overflow-y-auto focus:outline-none bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-200">
                <div class="py-8">
                    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Success Alert Example -->
                        {{-- <div id="alert-success" class="mb-6 flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border-l-4 border-green-500 rounded-xl shadow-lg text-green-800 dark:text-green-200 backdrop-blur-sm transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 text-sm font-medium">Operation completed successfully!</span>
                            </div>
                            <button onclick="document.getElementById('alert-success').remove()" class="ml-auto p-1.5 hover:bg-green-100 dark:hover:bg-green-800/50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div> --}}
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
                                <button onclick="document.getElementById('alert-success').remove()"
                                    class="ml-auto p-1.5 hover:bg-green-100 dark:hover:bg-green-800/50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
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
        /**
         * Elementlarni tanlab olish
         */
        const elements = {
            themeToggle: document.getElementById('theme-toggle'),
            themeMenu: document.getElementById('theme-menu'),
            themeOptions: document.querySelectorAll('[data-theme]'),
            themeIcons: {
                light: document.getElementById('theme-icon-light'),
                dark: document.getElementById('theme-icon-dark'),
                system: document.getElementById('theme-icon-system')
            },
            sidebar: document.getElementById('sidebar'),
            sidebarToggle: document.getElementById('sidebar-toggle'),
            closeSidebarBtn: document.getElementById('close-sidebar'),
            sidebarOverlay: document.getElementById('sidebar-overlay'),
            mobileMenuBtn: document.getElementById('mobile-menu-button'),
            usersDropdownBtn: document.getElementById('users-dropdown-btn'),
            usersDropdown: document.getElementById('users-dropdown'),
            userMenuButton: document.getElementById('user-menu-button'),
            userMenu: document.getElementById('user-menu'),
            successAlert: document.getElementById('alert-success')
        };

        /**
         * 1. THEME MANAGEMENT (Mavzu boshqaruvi)
         */
        let currentTheme = localStorage.getItem('theme') || 'system';

        function applyTheme(theme) {
            currentTheme = theme;
            localStorage.setItem('theme', theme);

            // Ikonkalarni yangilash
            Object.keys(elements.themeIcons).forEach(key => {
                elements.themeIcons[key]?.classList.toggle('hidden', key !== theme);
            });

            // Active klassini yangilash
            elements.themeOptions.forEach(opt => {
                opt.classList.toggle('active', opt.dataset.theme === theme);
            });

            // HTML klassini o'zgartirish
            let isDark = false;

            if (theme === 'dark') {
                isDark = true;
            } else if (theme === 'light') {
                isDark = false;
            } else if (theme === 'system') {
                isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }

            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Tizim mavzusi o'zgarganda kuzatish
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (currentTheme === 'system') applyTheme('system');
        });

        /**
         * 2. SIDEBAR MANAGEMENT (Yon panel boshqaruvi)
         */
        let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

        function updateSidebarView() {
            const isDesktop = window.innerWidth >= 1024;
            if (isDesktop) {
                elements.sidebar.classList.toggle('collapsed', sidebarCollapsed);
                elements.sidebar.classList.toggle('expanded', !sidebarCollapsed);
                elements.sidebar.classList.remove('-translate-x-full');
                elements.sidebarOverlay.classList.add('hidden');
            } else {
                elements.sidebar.classList.add('expanded');
                elements.sidebar.classList.remove('collapsed');
            }
        }

        /**
         * 3. DROPDOWNS & UTILS (Menyular va yordamchi funksiyalar)
         */
        function closeAllDropdowns() {
            elements.themeMenu?.classList.add('hidden');
            elements.userMenu?.classList.add('hidden');
        }

        function toggleDropdown(menu) {
            const isHidden = menu.classList.contains('hidden');
            closeAllDropdowns();
            if (isHidden) menu.classList.remove('hidden');
        }

        /**
         * 4. EVENT LISTENERS (Hodisalar)
         */

        // Theme events
        elements.themeToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(elements.themeMenu);
        });

        elements.themeOptions.forEach(option => {
            option.addEventListener('click', () => {
                applyTheme(option.dataset.theme);
                elements.themeMenu.classList.add('hidden');
            });
        });

        // Sidebar events
        elements.sidebarToggle?.addEventListener('click', () => {
            sidebarCollapsed = !sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
            updateSidebarView();
        });

        elements.mobileMenuBtn?.addEventListener('click', () => {
            elements.sidebar.classList.remove('-translate-x-full');
            elements.sidebarOverlay.classList.remove('hidden');
        });

        [elements.closeSidebarBtn, elements.sidebarOverlay].forEach(btn => {
            btn?.addEventListener('click', () => {
                elements.sidebar.classList.add('-translate-x-full');
                elements.sidebarOverlay.classList.add('hidden');
            });
        });

        // Users dropdown (sidebar ichidagi)
        elements.usersDropdownBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            elements.usersDropdown.classList.toggle('open');
            elements.usersDropdownBtn.querySelector('.arrow-icon')?.classList.toggle('rotated');
        });

        // User profile menu
        elements.userMenuButton?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(elements.userMenu);
        });

        document.addEventListener('click', (e) => {
            // Check if click is outside theme menu and user menu
            if (!elements.themeToggle?.contains(e.target) && !elements.themeMenu?.contains(e.target)) {
                elements.themeMenu?.classList.add('hidden');
            }

            if (!elements.userMenuButton?.contains(e.target) && !elements.userMenu?.contains(e.target)) {
                elements.userMenu?.classList.add('hidden');
            }
        });

        window.addEventListener('resize', updateSidebarView);

        if (elements.successAlert) {
            setTimeout(() => {
                elements.successAlert.style.transition = "opacity 0.5s ease";
                elements.successAlert.style.opacity = "0";
                setTimeout(() => elements.successAlert.remove(), 500);
            }, 5000);
        }

        /**
         * INITIALIZE (Ishga tushirish)
         */
        applyTheme(currentTheme);
        updateSidebarView();
    </script>
</body>

</html>
