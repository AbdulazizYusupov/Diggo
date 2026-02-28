<nav class="flex-1 px-3 space-y-1">
    @role('student')
        <a href="{{ route('student.dashboard') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('student.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('student.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.dashboard') }}</span>

            @if (request()->routeIs('student'))
                <span
                    class="ml-auto bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 py-0.5 px-2 text-xs font-bold rounded-full sidebar-text">4</span>
            @endif

            <span class="nav-tooltip">{{ __('navigation.dashboard') }}</span>
        </a>
        <a href="{{ route('student.groups') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('student.groups*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">


            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('student.groups*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.my_groups') }}</span>

            <span class="nav-tooltip">{{ __('navigation.my_groups') }}</span>
        </a>
        <a href="{{ route('student.payments') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('student.payments*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('student.payments*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
            </svg>
            <span class="ml-3 sidebar-text">{{ __('navigation.my_payments') }}</span>

            <span class="nav-tooltip">{{ __('navigation.my_payments') }}</span>
        </a>
        <a href="{{ route('student.attendance') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('student.attendance*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">


            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('student.attendance*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
            </svg>
            <span class="ml-3 sidebar-text">{{ __('navigation.my_indicators') }}</span>

            <span class="nav-tooltip">{{ __('navigation.my_indicators') }}</span>
        </a>
        <a href="{{ route('student.coins') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('student.coins') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('student.coins') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.my_coins') }}</span>

            <span class="nav-tooltip">{{ __('navigation.my_coins') }}</span>
        </a>
        <a href="{{ route('student.schedule') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('student.schedule') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('student.schedule') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.schedule') }}</span>

            <span class="nav-tooltip">{{ __('navigation.schedule') }}</span>
        </a>
        <a href="{{ route('student.leaderboard') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('student.leaderboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('student.leaderboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.leaderboard') }}</span>

            <span class="nav-tooltip">{{ __('navigation.leaderboard') }}</span>
        </a>
        <a href="{{ route('student.shop.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('student.shop.index') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('student.shop.index') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>


            <span class="ml-3 sidebar-text">{{ __('navigation.shop') }}</span>

            <span class="nav-tooltip">{{ __('navigation.shop') }}</span>
        </a>
    @endrole
    @role('director')
        <a href="{{ route('director.dashboard') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs(['director.dashboard', 'director.school.show']) ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg class="h-5 w-5 flex-shrink-0 {{ request()->routeIs(['director.dashboard', 'director.school.show']) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.dashboard') }}</span>

            @if (request()->routeIs('director'))
                <span
                    class="ml-auto bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 py-0.5 px-2 text-xs font-bold rounded-full sidebar-text">4</span>
            @endif

            <span class="nav-tooltip">{{ __('navigation.dashboard') }}</span>
        </a>
        <a href="{{ route('director.students') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('director.students*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('director.students*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.students') }}</span>

            <span class="nav-tooltip">{{ __('navigation.students') }}</span>
        </a>
        <a href="{{ route('director.commissions') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('director.commissions') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('director.commissions') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m8.99 14.993 6-6m6 3.001c0 1.268-.63 2.39-1.593 3.069a3.746 3.746 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043 3.745 3.745 0 0 1-3.068 1.593c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 0 1-3.296-1.043 3.746 3.746 0 0 1-1.043-3.297 3.746 3.746 0 0 1-1.593-3.068c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 0 1 1.043-3.297 3.745 3.745 0 0 1 3.296-1.042 3.745 3.745 0 0 1 3.068-1.594c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.297 3.746 3.746 0 0 1 1.593 3.068ZM9.74 9.743h.008v.007H9.74v-.007Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 4.5h.008v.008h-.008v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.commissions') }}</span>

            <span class="nav-tooltip">{{ __('navigation.commissions') }}</span>
        </a>
        <a href="{{ route('director.groups') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('director.groups*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('director.groups*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
            </svg>
            <span class="ml-3 sidebar-text">{{ __('navigation.groups') }}</span>
            <span class="nav-tooltip">{{ __('navigation.groups') }}</span>
        </a>
        <a href="{{ route('director.reports') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('director.reports*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('director.reports*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.reports') }}</span>

            <span class="nav-tooltip">{{ __('navigation.reports') }}</span>
        </a>
    @endrole
    @role('teacher')
        <a href="{{ route('teacher.dashboard') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('teacher.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.dashboard') }}</span>
            <span class="nav-tooltip">{{ __('navigation.dashboard') }}</span>
        </a>
        <a href="{{ route('teacher.groups.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs(['teacher.groups*', 'teacher.attendance.*']) ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 {{ request()->routeIs(['teacher.groups*', 'teacher.attendance.*']) ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
            </svg>
            <span class="ml-3 sidebar-text">{{ __('navigation.groups') }}</span>
            <span class="nav-tooltip">{{ __('navigation.groups') }}</span>
        </a>
    @endrole
    @role('admin|staff')
        <a href="{{ route('dashboard') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="ml-3 sidebar-text">{{ __('navigation.dashboard') }}</span>
            <span class="nav-tooltip">{{ __('navigation.dashboard') }}</span>
        </a>
        <a href="{{ route('statistics.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('statistics.index') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('statistics.index') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
            </svg>
            <span class="ml-3 sidebar-text">{{ __('navigation.statistics') }}</span>
            <span class="nav-tooltip">{{ __('navigation.statistics') }}</span>
        </a>
    @endrole
    @role('admin')
        <div class="space-y-1">
            <button id="roles-dropdown-btn"
                onclick="const dropdown = this.nextElementSibling; dropdown.classList.toggle('open'); this.querySelector('.arrow-icon').classList.toggle('rotate-180');"
                class="nav-item relative w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->is('roles*', 'permissions*', 'role-permissions*') ? 'bg-indigo-50 dark:bg-indigo-900/10 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5 flex-shrink-0 {{ request()->is('roles*', 'permissions*', 'role-permissions*') ? 'text-indigo-600' : 'text-gray-400' }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.744c0 5.621 3.401 10.449 8.243 12.446a11.95 11.95 0 008.157-12.446c0-1.302-.207-2.557-.598-3.744A11.959 11.959 0 0112 2.714z" />
                </svg>

                <span class="ml-3 sidebar-text">{{ __('navigation.permissions') }}</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor"
                    class="arrow-icon ml-auto h-4 w-4 text-gray-400 sidebar-text transition-transform duration-300 {{ request()->is('roles*', 'permissions*', 'role-permissions*') ? 'rotate-180' : '' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
                <span class="nav-tooltip">{{ __('navigation.permissions') }}</span>
            </button>

            <div id="roles-dropdown"
                class="dropdown-content {{ request()->is('roles*', 'permissions*', 'role-permissions*') ? 'open' : '' }}">

                <div class="ml-8 space-y-1 py-2">
                    <a href="{{ route('roles.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('roles.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="sidebar-text">{{ __('navigation.roles') }}</span>
                    </a>

                    <a href="{{ route('permissions.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->is('permissions*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                        </svg>
                        <span class="sidebar-text">{{ __('navigation.permissions') }}</span>
                    </a>

                    <a href="{{ route('role-permissions.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->is('role-permissions*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <span class="sidebar-text">{{ __('navigation.assign_role') }}</span>
                    </a>
                </div>
            </div>
        </div>
        <a href="{{ route('impersonate.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('impersonate.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('impersonate.index') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.impersonate') }}</span>
            <span class="nav-tooltip">{{ __('navigation.impersonate') }}</span>
        </a>
    @endrole
    @role('admin|staff')
        <div class="space-y-1">
            <button id="users-dropdown-btn"
                class="nav-item relative w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->is('staffs*', 'directors*', 'teachers*', 'students*') ? 'bg-indigo-50 dark:bg-indigo-900/10 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5 flex-shrink-0 {{ request()->is('staffs*', 'directors*', 'teachers*', 'students*') ? 'text-indigo-600' : 'text-gray-400' }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>

                <span class="ml-3 sidebar-text">{{ __('navigation.users_group') }}</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor"
                    class="arrow-icon ml-auto h-4 w-4 text-gray-400 sidebar-text transition-transform duration-300 {{ request()->is('staffs*', 'directors*', 'teachers*', 'students*') ? 'rotate-180' : '' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
                <span class="nav-tooltip">{{ __('navigation.users_group') }}</span>
            </button>

            <div id="users-dropdown"
                class="dropdown-content {{ request()->is('staffs*', 'directors*', 'teachers*', 'students*') ? 'open' : '' }}">

                <div class="ml-8 space-y-1 py-2">
                    <a href="{{ route('staffs.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('staffs.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg>

                        <span class="sidebar-text">{{ __('navigation.staff') }}</span>
                    </a>

                    <a href="{{ route('directors.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('directors.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6.75h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                        </svg>
                        <span class="sidebar-text">{{ __('navigation.directors') }}</span>
                    </a>

                    <a href="{{ route('teachers.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('teachers.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>

                        <span class="sidebar-text">{{ __('navigation.teachers') }}</span>
                    </a>

                    <a href="{{ route('students.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('students.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="sidebar-text">{{ __('navigation.students') }}</span>
                    </a>
                </div>
            </div>
        </div>
        <a href="{{ route('schools.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('schools.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('schools.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.schools') }}</span>
            <span class="nav-tooltip">{{ __('navigation.schools') }}</span>
        </a>
        <a href="{{ route('courses.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('courses.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('courses.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-blue-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.courses') }}</span>
            <span class="nav-tooltip">{{ __('navigation.courses') }}</span>
        </a>
        <a href="{{ route('groups.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('groups.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('groups.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0 L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0 -5.571 3-5.571-3" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.groups') }}</span>
            <span class="nav-tooltip">{{ __('navigation.groups') }}</span>
        </a>

        <a href="{{ route('lessons.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('lessons.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('lessons.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.lessons') }}</span>
            <span class="nav-tooltip">{{ __('navigation.lessons') }}</span>
        </a>

        <a href="{{ route('activities.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('activities.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('activities.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.63 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.activities') }}</span>
            <span class="nav-tooltip">{{ __('navigation.activities') }}</span>
        </a>
        <a href="{{ route('activityresults.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('activityresults.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('activityresults.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
            </svg>


            <span class="ml-3 sidebar-text">{{ __('navigation.activity_results') }}</span>
            <span class="nav-tooltip">{{ __('navigation.activity_results') }}</span>
        </a>
        <a href="{{ route('coin-transactions.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('coin-transactions.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('coin-transactions.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
            </svg>


            <span class="ml-3 sidebar-text">{{ __('navigation.coin_transactions') }}</span>
            <span class="nav-tooltip">{{ __('navigation.coin_transactions') }}</span>
        </a>
        <a href="{{ route('attendance.index') }}"
            class="nav-item relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all group {{ request()->routeIs('attendance.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('attendance.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-purple-500' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
            </svg>

            <span class="ml-3 sidebar-text">{{ __('navigation.attendance') }}</span>
            <span class="nav-tooltip">{{ __('navigation.attendance') }}</span>
        </a>
        <div class="space-y-1">
            <button id="finance-dropdown-btn"
                onclick="const dropdown = this.nextElementSibling; dropdown.classList.toggle('open'); this.querySelector('.arrow-icon').classList.toggle('rotate-180');"
                class="nav-item relative w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->is('payments*', 'transactions*') ? 'bg-indigo-50 dark:bg-indigo-900/10 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                <svg class="h-5 w-5 flex-shrink-0 {{ request()->is('payments*', 'transactions*') ? 'text-indigo-600' : 'text-gray-400' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <span class="ml-3 sidebar-text">{{ __('navigation.finance_group') }}</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor"
                    class="arrow-icon ml-auto h-4 w-4 text-gray-400 sidebar-text transition-transform duration-300 {{ request()->is('payments*', 'transactions*') ? 'rotate-180' : '' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
                <span class="nav-tooltip">{{ __('navigation.finance_group') }}</span>
            </button>

            <div id="finance-dropdown"
                class="dropdown-content {{ request()->is('payments*', 'transactions*') ? 'open' : '' }}">

                <div class="ml-8 space-y-1 py-2">
                    <a href="{{ route('payments.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('payments.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="sidebar-text">{{ __('navigation.payments') }}</span>
                    </a>

                    <a href="{{ route('transactions.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('transactions.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <span class="sidebar-text">{{ __('navigation.transactions') }}</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="space-y-1">
            <button id="shop-dropdown-btn"
                onclick="const dropdown = this.nextElementSibling; dropdown.classList.toggle('open'); this.querySelector('.arrow-icon').classList.toggle('rotate-180');"
                class="nav-item relative w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl group transition-all {{ request()->routeIs('admin.shop*') ? 'bg-indigo-50 dark:bg-indigo-900/10 text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.shop*') ? 'text-indigo-600' : 'text-gray-400' }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>


                <span class="ml-3 sidebar-text">{{ __('navigation.shop_management') }}</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor"
                    class="arrow-icon ml-auto h-4 w-4 text-gray-400 sidebar-text transition-transform duration-300 {{ request()->routeIs('admin.shop*') ? 'rotate-180' : '' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>

                <span class="nav-tooltip">{{ __('navigation.shop_management') }}</span>
            </button>

            <div id="shop-dropdown" class="dropdown-content {{ request()->routeIs('admin.shop*') ? 'open' : '' }}">

                <div class="ml-8 space-y-1 py-2">
                    <a href="{{ route('admin.shop.categories.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('admin.shop.categories.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>

                        <span class="sidebar-text">{{ __('navigation.categories') }}</span>
                    </a>

                    <a href="{{ route('admin.shop.products.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('admin.shop.products.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>

                        <span class="sidebar-text">{{ __('navigation.products') }}</span>
                    </a>
                    <a href="{{ route('admin.shop.orders.index') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('admin.shop.orders.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 hover:text-indigo-600' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>



                        <span class="sidebar-text">{{ __('navigation.orders') }}</span>
                    </a>
                </div>
            </div>
        </div>
    @endrole
</nav>
