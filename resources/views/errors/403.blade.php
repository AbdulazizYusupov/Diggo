@extends('Layout.app')

@section('title', 'Ruxsat yo\'q')

@section('content')

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center">
                <div class="inline-flex p-4 bg-red-100 dark:bg-red-900/30 rounded-full mb-6">
                    <svg class="w-16 h-16 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">403</h1>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Ruxsat yo'q</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    {{ $exception->getMessage() ?? 'Sizda bu sahifaga kirish huquqi yo\'q. Administrator bilan bog\'laning.' }}
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Bosh sahifa
                    </a>

                    <button onclick="window.history.back()"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Orqaga
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
