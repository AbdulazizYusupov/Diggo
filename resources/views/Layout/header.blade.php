<header
    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md shadow-sm border-b border-gray-200 dark:border-gray-700/50 transition-colors duration-200 z-30 sticky top-0">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center">
            <button type="button" id="mobile-menu-button"
                class="lg:hidden -ml-2 mr-3 p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="flex items-center lg:hidden">
                <div
                    class="w-9 h-9 bg-indigo-500 dark:bg-indigo-600 rounded-xl flex items-center justify-center shadow-sm border border-indigo-400/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>

                <span class="ml-3 text-gray-900 dark:text-white text-xl font-black tracking-tight">
                    Diggo<span class="text-indigo-500">.</span>
                </span>
            </div>
        </div>

        <div class="flex items-center space-x-2 sm:space-x-3">
            <!-- Theme Switcher -->
            <div class="relative">
                <button id="theme-toggle"
                    class="p-2.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all hover:scale-105">
                    <svg id="theme-icon-light" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <svg id="theme-icon-dark" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-icon-system" class="hidden w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
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
                            <svg class="w-4 h-4 mr-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    @if (Auth::user()->photo)
                        <img class="h-10 w-10 rounded-lg object-cover ring-2 ring-indigo-100 dark:ring-indigo-900"
                            src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->full_name }}">
                    @else
                        <img class="h-10 w-10 rounded-lg object-cover ring-2 ring-indigo-100 dark:ring-indigo-900"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name) }}&background=6366f1&color=fff"
                            alt="User">
                    @endif
                    <svg class="hidden sm:block h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="user-menu"
                    class="hidden absolute right-0 mt-2 w-64 rounded-xl shadow-xl bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">

                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            @if (Auth::user()->photo)
                                <img class="h-10 w-10 rounded-lg object-cover ring-2 ring-indigo-100 dark:ring-indigo-900"
                                    src="{{ asset('storage/' . Auth::user()->photo) }}"
                                    alt="{{ Auth::user()->full_name }}">
                            @else
                                <img class="h-10 w-10 rounded-lg object-cover ring-2 ring-indigo-100 dark:ring-indigo-900"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name) }}&background=6366f1&color=fff"
                                    alt="User">
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ Auth::user()->full_name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ Auth::user()->phone }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile.show') }}"
                            class="flex items-center px-4 py-2.5 text-sm transition-colors group {{ request()->routeIs('profile.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <svg class="mr-3 h-4 w-4 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 {{ request()->routeIs('profile.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Your Profile
                        </a>
                        @role('admin')
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center px-4 py-2.5 text-sm transition-colors group {{ request()->routeIs('settings.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                <svg class="mr-3 h-4 w-4 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 {{ request()->routeIs('settings.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Settings
                            </a>
                        @endrole
                        <a href="{{ route('help.index') }}"
                            class="flex items-center px-4 py-2.5 text-sm transition-colors group {{ request()->routeIs('help.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <svg class="mr-3 h-4 w-4 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 {{ request()->routeIs('help.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400' }}"
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
                                <svg class="mr-3 h-4 w-4 text-red-500 group-hover:text-red-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
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
