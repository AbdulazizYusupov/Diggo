<div class="flex-shrink-0 bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 shadow-sm z-40">
    <div class="px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between gap-4">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="flex-shrink-0 w-7 h-7 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <p class="text-white text-xs font-medium truncate">
                <span class="font-bold">{{ session('impersonate.target_name') }}</span>
                nomidan kirgansiz
                <span class="ml-1 px-1.5 py-0.5 bg-white/20 text-[10px] font-bold rounded-full uppercase">
                    {{ session('impersonate.target_role') }}
                </span>
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('impersonate.index') }}"
                class="hidden sm:flex items-center gap-1 px-2.5 py-1 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg transition-colors border border-white/20">
                Foydalanuvchilar
            </a>
            <form method="POST" action="{{ route('impersonate.leave') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-1.5 px-3 py-1 bg-white text-orange-600 hover:bg-orange-50 text-xs font-bold rounded-lg transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Chiqish
                    <span class="hidden sm:inline">({{ session('impersonate.original_name') }})</span>
                </button>
            </form>
        </div>
    </div>
</div>
