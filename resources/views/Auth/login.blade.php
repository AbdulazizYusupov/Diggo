<!DOCTYPE html>
<html lang="uz" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish - Diggo Uquv Markazi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="h-full flex items-center justify-center p-4 transition-colors duration-500"
    style="
        background-image: url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1740&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    ">

    <!-- Login Card -->
    <div class="w-full max-w-md z-10">
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-white/80 dark:bg-gray-800/60 rounded-2xl shadow-xl mb-4 border border-gray-100 dark:border-gray-700 backdrop-blur-lg">
                <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                    </path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                Diggo <span class="text-purple-600 font-medium">Portal</span>
            </h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400 font-medium italic">Bilimga yo'l shu yerdan boshlanadi</p>
        </div>

        <div
            class="bg-white/90 dark:bg-gray-800/80 backdrop-blur-lg border border-gray-200 dark:border-gray-700 rounded-3xl shadow-2xl p-8 sm:p-10 transition-all duration-300">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <!-- ID / Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">ID raqam yoki
                        Email</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input name="phone" type="text" required placeholder="ID raqam yoki email"
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm placeholder:text-gray-400">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 ml-1">Maxfiy
                        parol</label>
                    <div class="relative group">
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="block w-full pl-11 pr-10 py-3.5 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all shadow-sm placeholder:text-gray-400">
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-purple-600 transition-colors">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.29-3.902M6.172 6.172A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.563 2.773M2 2l20 20" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember_me"
                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Eslab qolish</span>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full py-4 px-6 text-white font-bold rounded-xl shadow-lg bg-gradient-to-r from-purple-500 to-indigo-500 hover:shadow-xl hover:-translate-y-1 transition-all active:scale-[0.98]">
                    Tizimga kirish
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            const eye = document.getElementById('eyeIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                eye.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12c1.273-4.057 5.064-7 9.542-7s8.269 2.943 9.542 7c-1.273 4.057-5.064 7-9.542 7s-8.269-2.943-9.542-7z"/><line x1="3" y1="3" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>`;
            } else {
                pw.type = 'password';
                eye.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>

</body>

</html>
