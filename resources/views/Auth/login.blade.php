<!DOCTYPE html>
<html lang="uz" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish - Diggo O'quv Markazi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Background */
        .bg-image {
            background-image: url('{{ $randomImage }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        @media (max-width: 768px) {
            .bg-image {
                background-attachment: scroll;
            }
        }

        /* Overlay */
        .overlay {
            background: rgba(0, 0, 0, 0.2);
        }

        .dark .overlay {
            background: rgba(0, 0, 0, 0.7);
        }

        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .dark .glass {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .glass {
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }
        }

        /* Input Fields */
        .input-field {
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .input-wrapper:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .input-field:focus {
            background: rgba(255, 255, 255, 1);
        }


        .dark .input-field:focus {
            background: rgba(30, 41, 59, 1);
        }

        .dark .input-field::placeholder {
            color: #94a3b8;
        }

        .dark label {
            color: #e2e8f0;
        }

        /* Button Gradient */
        .btn-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            transition: all 0.4s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.6;
                transform: scale(1.1);
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-out forwards;
            opacity: 0;
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .logo-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @media (max-width: 768px) {

            .float-animation,
            .logo-pulse {
                animation: none;
            }
        }

        /* Theme Toggle Button - sahifa yuklanganda ko'rinishi */
        #theme-toggle {
            opacity: 0;
            animation: fadeIn 1s ease-out 0.3s forwards;
        }

        .input-wrapper:focus-within svg {
            color: #6366f1;
            /* indigo */
        }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px white inset !important;
            -webkit-text-fill-color: #1f2937 !important;
        }

        .dark input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #1e293b inset !important;
            -webkit-text-fill-color: #fff !important;
        }

        .input-error {
            border: 2px solid #ef4444 !important;
        }

        .input-error::placeholder {
            color: #fca5a5;
        }

        .input-wrapper {
            will-change: transform;
        }
    </style>
</head>

<body class="h-full bg-image">
    <!-- Overlay -->
    <div class="overlay fixed inset-0"></div>

    <!-- Theme Toggle Button -->
    <div class="fixed top-12 right-5 z-50">
        <button id="theme-toggle"
            class="bg-white/20 backdrop-blur-md p-3 rounded-full border border-white/30 text-white hover:bg-white/40 transition-all">
            <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z">
                </path>
            </svg>
            <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full space-y-8 fade-in">
            <!-- Logo Section -->
            <div class="text-center">
                <div class="flex justify-center mb-3">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/30 rounded-full blur-xl logo-pulse"></div>
                        <div
                            class="relative bg-white/20 backdrop-blur-md p-4 rounded-full border-2 border-white/30 float-animation">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                </div>
                <h1 class="text-4xl font-extrabold text-white tracking-tight">Diggo Portal</h1>
            </div>

            <!-- Login Card -->
            <div class="glass rounded-3xl p-8 space-y-6">
                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- ID/Email Input -->
                    <div>
                        <label
                            class="block font-semibold mb-2 text-sm {{ $errors->has('phone') ? 'text-red-400' : 'text-white' }}">
                            ID raqam yoki Email</label>
                        <div class="relative input-wrapper transition-all duration-300">

                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                placeholder="ID raqamingizni kiriting"
                                class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-white/50 text-gray-800 placeholder-gray-500 {{ $errors->has('phone') ? 'input-error' : '' }}" />
                            @error('phone')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- Password Input -->
                    <div>
                        <label class="block text-white font-semibold mb-2 text-sm">Maxfiy parol</label>
                        <div class="relative input-wrapper transition-all duration-300">

                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                class="input-field w-full pl-12 pr-12 py-3.5 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-white/50 text-gray-800 placeholder-gray-500" />

                            <button type="button" id="togglePassword" tabindex="-1"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700 transition-colors">

                                <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 rounded border-white/30 text-indigo-600 focus:ring-2 focus:ring-white/50 bg-white/20 cursor-pointer" />
                        <label for="remember" class="ml-2 text-sm text-white font-medium cursor-pointer">Eslab
                            qolish</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="btn-gradient w-full py-4 px-6 rounded-xl text-white font-bold text-base shadow-lg">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Tizimga kirish
                        </span>
                    </button>
                </form>

                <!-- Footer -->
                <div class="pt-4 border-t border-white/20">
                    <p class="text-center text-xs text-white/70">
                        © 2024 Diggo O'quv Markazi. Barcha huquqlar himoyalangan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            if (type === 'text') {
                eyeIcon.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.29-3.902M6.172 6.172A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.563 2.773M2 2l20 20"/>`;
            } else {
                eyeIcon.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        });

        // Theme Toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const html = document.documentElement;

        // Initialize theme
        function initTheme() {
            const savedTheme = localStorage.getItem('color-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                html.classList.add('dark');
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                html.classList.remove('dark');
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleLightIcon.classList.add('hidden');
            }
        }

        // Toggle theme
        themeToggleBtn.addEventListener('click', function() {
            html.classList.toggle('dark');

            if (html.classList.contains('dark')) {
                localStorage.setItem('color-theme', 'dark');
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                localStorage.setItem('color-theme', 'light');
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleLightIcon.classList.add('hidden');
            }
        });

        // Initialize on page load
        initTheme();
    </script>
</body>

</html>
