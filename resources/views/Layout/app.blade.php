<!DOCTYPE html>
<html lang="uz" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Dashboard - Edu Center</title> --}}
    <meta name="description" content="@yield('meta_description', 'Diggo Edu Center — zamonaviy ta\'lim boshqaruv tizimi')">
    <meta name="keywords" content="@yield('meta_keywords', 'edu center, ta\'lim, kurslar, o\'quvchilar')">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('Icon/graduation.png') }}">
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "EducationalOrganization",
        "name": "Diggo Edu Center",
        "url": "{{ config('app.url') }}",
        "logo": "@yield('schema_logo', asset('img/logo.png'))",
        "description": "Zamonaviy ta'lim boshqaruv tizimi",
        "address": { 
            "@@type": "PostalAddress", 
            "addressCountry": "UZ" 
    }
    }
    </script>
    <title>@yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

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

    {{-- <script>
        (function() {
            try {
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script> --}}
    <script>
        // ✅ DARK MODE FLASH OLDINI OLISH (BIRINCHI!)
        (function() {
            try {
                // Dark mode
                const theme = localStorage.getItem('theme') || 'system';
                const isDark = theme === 'dark' ||
                    (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

                if (isDark) {
                    document.documentElement.classList.add('dark');
                }

                // Sidebar state
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {
                console.error('Theme init error:', e);
            }
        })();
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
            class="sidebar fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out lg:static lg:flex lg:flex-shrink-0">
            <div class="flex flex-col h-full w-full">
                <div
                    class="flex flex-col flex-grow bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700/50 pt-5 pb-4 overflow-y-auto overflow-x-hidden transition-colors duration-200 shadow-xl lg:shadow-none">

                    <!-- Header with Logo and Toggle -->
                    <div class="flex items-center justify-between px-6 mb-8">
                        <div class="flex items-center overflow-hidden min-w-0 group select-none">
                            <!-- Logo box -->
                            <div
                                class="relative w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 shadow-lg shadow-indigo-500/30 dark:shadow-indigo-900/40 border border-white/20 dark:border-white/10 transform group-hover:scale-105 group-hover:rotate-[1deg] transition-all duration-300">

                                <!-- subtle glow -->
                                <div
                                    class="absolute inset-0 rounded-2xl bg-indigo-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                <svg class="relative w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>

                            <!-- Text -->
                            <div class="ml-3 flex flex-col sidebar-text">
                                <span
                                    class="text-gray-900 dark:text-white text-xl font-extrabold tracking-tight leading-none">
                                    Diggo<span
                                        class="bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">.</span>
                                </span>

                                <span
                                    class="text-[10px] uppercase tracking-[0.25em] font-semibold text-gray-400 dark:text-gray-500 mt-1">
                                    Admin Panel
                                </span>
                            </div>
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
                    @include('Layout.navigation')
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden w-full">
            @if (session('impersonate.active'))
                @include('Layout.impersonate')
            @endif
            @include('Layout.header')
            <main
                class="flex-1 relative overflow-y-auto focus:outline-none bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-200">
                <div class="py-8">
                    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
                        @include('Layout.alerts')
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        /**
         * DOM Elementlari
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
            langToggle: document.getElementById('lang-toggle'),
            langMenu: document.getElementById('lang-menu'),
            langArrow: document.getElementById('lang-arrow'),
            notificationToggle: document.getElementById('notification-toggle'),
            notificationDropdown: document.getElementById('notification-dropdown'),
            userMenuBtn: document.getElementById('user-menu-button'),
            userMenu: document.getElementById('user-menu'),
            usersDropdownBtn: document.getElementById('users-dropdown-btn'),
            usersDropdown: document.getElementById('users-dropdown'),
        };

        /**
         * 1. THEME MANAGEMENT
         */
        let currentTheme = localStorage.getItem('theme') || 'system';

        function applyTheme(theme) {
            currentTheme = theme;
            localStorage.setItem('theme', theme);

            // Iconlarni yangilash
            Object.entries(elements.themeIcons).forEach(([key, icon]) => {
                icon?.classList.toggle('hidden', key !== theme);
            });

            // Active class-ni yangilash
            elements.themeOptions.forEach(opt => {
                opt.classList.toggle('active', opt.dataset.theme === theme);
            });

            // Dark rejimni aniqlash
            const isDark = theme === 'dark' ||
                (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

            document.documentElement.classList.toggle('dark', isDark);
        }

        // Tizim o'zgarganda avtomatik moslashish
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (currentTheme === 'system') applyTheme('system');
        });

        /**
         * 2. SIDEBAR MANAGEMENT
         */
        let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

        function updateSidebarView() {
            const isDesktop = window.innerWidth >= 1024;

            if (isDesktop) {
                elements.sidebar?.classList.toggle('collapsed', sidebarCollapsed);
                elements.sidebar?.classList.toggle('expanded', !sidebarCollapsed);
                elements.sidebar?.classList.remove('-translate-x-full');
                elements.sidebarOverlay?.classList.add('hidden');
            } else {
                elements.sidebar?.classList.add('expanded');
                elements.sidebar?.classList.remove('collapsed');
            }
        }

        /**
         * 3. DROPDOWN LOGIC (Unified)
         */
        const dropdowns = [{
                btn: elements.themeToggle,
                menu: elements.themeMenu
            },
            {
                btn: elements.langToggle,
                menu: elements.langMenu,
                arrow: elements.langArrow
            },
            {
                btn: elements.notificationToggle,
                menu: elements.notificationDropdown
            },
            {
                btn: elements.userMenuBtn,
                menu: elements.userMenu
            }
        ];

        function closeAllDropdowns() {
            dropdowns.forEach(({
                menu,
                arrow,
                btn
            }) => {
                menu?.classList.add('hidden');
                arrow?.classList.remove('rotate-180');
                btn?.setAttribute('aria-expanded', 'false');
            });
        }

        function toggleDropdown(e, menu, arrow, btn) {
            e.stopPropagation();
            const isHidden = menu?.classList.contains('hidden');
            closeAllDropdowns();
            if (isHidden) {
                menu?.classList.remove('hidden');
                arrow?.classList.add('rotate-180');
                btn?.setAttribute('aria-expanded', 'true');
            }
        }

        /**
         * 4. EVENT LISTENERS
         */

        // Dropdownlarni ulash
        dropdowns.forEach(({
            btn,
            menu,
            arrow
        }) => {
            btn?.addEventListener('click', (e) => toggleDropdown(e, menu, arrow, btn));
        });

        // Tashqariga bosilganda hamma dropdownlarni yopish
        document.addEventListener('click', closeAllDropdowns);

        // Sidebar toggle
        elements.sidebarToggle?.addEventListener('click', () => {
            sidebarCollapsed = !sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
            updateSidebarView();
        });

        // Mobile sidebar ochish
        elements.mobileMenuBtn?.addEventListener('click', () => {
            elements.sidebar?.classList.remove('-translate-x-full');
            elements.sidebarOverlay?.classList.remove('hidden');
        });

        // Sidebar yopish (X tugmasi va Overlay)
        [elements.closeSidebarBtn, elements.sidebarOverlay].forEach(el => {
            el?.addEventListener('click', () => {
                elements.sidebar?.classList.add('-translate-x-full');
                elements.sidebarOverlay?.classList.add('hidden');
            });
        });

        // Theme tanlash
        elements.themeOptions.forEach(opt => {
            opt.addEventListener('click', () => applyTheme(opt.dataset.theme));
        });

        // Sub-dropdown (Sidebar ichidagi Users kabi)
        elements.usersDropdownBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            elements.usersDropdown?.classList.toggle('open');
            elements.usersDropdownBtn.querySelector('.arrow-icon')?.classList.toggle('rotated');
        });

        window.addEventListener('resize', updateSidebarView);

        /**
         * 5. AUTO-HIDE ALERTS
         */
        const initAlerts = () => {
            ['alert-success', 'alert-error'].forEach(id => {
                const alert = document.getElementById(id);
                if (alert) {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => alert.remove(), 500);
                    }, 5000);
                }
            });
        };

        /**
         * INITIALIZE
         */
        applyTheme(currentTheme);
        updateSidebarView();
        initAlerts();
    </script>
    @stack('scripts')
</body>

</html>
