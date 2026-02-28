<script>
        /**
         * Elementlarni tanlab olish
         * successAlert va errorAlert bu yerdan OLIB TASHLANDI (pastda alohida)
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

            langToggle: document.getElementById('lang-toggle'),
            langMenu: document.getElementById('lang-menu'),
            langArrow: document.getElementById('lang-arrow'),

            notificationToggle: document.getElementById('notification-toggle'),
            notificationDropdown: document.getElementById('notification-dropdown'),
            notificationWrapper: document.getElementById('notification-wrapper'),


        };

        /**
         * 1. THEME MANAGEMENT (Mavzu boshqaruvi)
         */
        let currentTheme = localStorage.getItem('theme') || 'system';

        function applyTheme(theme) {
            currentTheme = theme;
            localStorage.setItem('theme', theme);

            Object.keys(elements.themeIcons).forEach(key => {
                elements.themeIcons[key]?.classList.toggle('hidden', key !== theme);
            });

            elements.themeOptions.forEach(opt => {
                opt.classList.toggle('active', opt.dataset.theme === theme);
            });

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
                // ✅ FIX: faqat desktop ga o'tganda overlay yashirilsin
                // mobile da sidebar ochiq tursa overlay saqlansin
                if (!elements.sidebarOverlay.classList.contains('hidden')) {
                    elements.sidebarOverlay.classList.add('hidden');
                }
            } else {
                elements.sidebar.classList.add('expanded');
                elements.sidebar.classList.remove('collapsed');
            }
        }


        /**
         * 3. DROPDOWNS & UTILS
         */
        function closeAllDropdowns() {
            elements.themeMenu?.classList.add('hidden');
            elements.userMenu?.classList.add('hidden');
            elements.langMenu?.classList.add('hidden');
            elements.langArrow?.classList.remove('rotate-180');
            elements.notificationDropdown?.classList.add('hidden');
            elements.notificationToggle?.setAttribute('aria-expanded', 'false');
        }

        function toggleDropdown(menu) {
            const isHidden = menu.classList.contains('hidden');
            closeAllDropdowns();
            if (isHidden) menu.classList.remove('hidden');
        }

        /**
         * 4. EVENT LISTENERS
         */
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

        elements.langToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = elements.langMenu.classList.contains('hidden');
            toggleDropdown(elements.langMenu);
            // Rotate arrow when open
            if (isHidden) {
                elements.langArrow?.classList.add('rotate-180');
            }
        });


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

        elements.usersDropdownBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            elements.usersDropdown.classList.toggle('open');
            elements.usersDropdownBtn.querySelector('.arrow-icon')?.classList.toggle('rotated');
        });

        elements.notificationToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(elements.notificationDropdown);

            const isHidden = elements.notificationDropdown.classList.contains('hidden');
            elements.notificationToggle?.setAttribute('aria-expanded', String(!isHidden));
        });

        elements.userMenuButton?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(elements.userMenu);
        });

        document.addEventListener('click', (e) => {
            if (!elements.themeToggle?.contains(e.target) && !elements.themeMenu?.contains(e.target)) {
                elements.themeMenu?.classList.add('hidden');
            }
            if (!elements.userMenuButton?.contains(e.target) && !elements.userMenu?.contains(e.target)) {
                elements.userMenu?.classList.add('hidden');
            }
            if (!elements.langToggle?.contains(e.target) && !elements.langMenu?.contains(e.target)) {
                elements.langMenu?.classList.add('hidden');
                elements.langArrow?.classList.remove('rotate-180');
            }
            if (!elements.notificationToggle?.contains(e.target) &&
                !elements.notificationDropdown?.contains(e.target)) {
                elements.notificationDropdown?.classList.add('hidden');
                elements.notificationToggle?.setAttribute('aria-expanded', 'false');
            }
        });

        window.addEventListener('resize', updateSidebarView);

        /**
         * ✅ FIX: Alert auto-hide — elements dan ALOHIDA, null xavfsiz
         */
        (function() {
            const alerts = [
                document.getElementById('alert-success'),
                document.getElementById('alert-error'),
            ];
            alerts.forEach(function(alert) {
                if (!alert) return; // session bo'sh bo'lsa — skip
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        })();

        /**
         * INITIALIZE
         */
        applyTheme(currentTheme);
        updateSidebarView();
    </script>
    {{-- <script>
        // ── Notification Bell ────────────────────────────────────────
        (function() {
            const toggle = document.getElementById('notification-toggle');
            const dropdown = document.getElementById('notification-dropdown');
            const wrapper = document.getElementById('notification-wrapper');

            if (!toggle || !dropdown || !wrapper) return;

            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = dropdown.classList.contains('hidden');
                dropdown.classList.toggle('hidden', !isHidden);
                toggle.setAttribute('aria-expanded', String(isHidden));
            });

            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdown.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        })();
        // ── End Notification Bell ────────────────────────────────────
    </script> --}}