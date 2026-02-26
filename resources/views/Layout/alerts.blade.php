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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif
