@extends('Layout.app')

@section('title', 'Foydalanuvchilar — Impersonation')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Foydalanuvchilar Paneli
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Istalgan foydalanuvchi nomidan uning kabinetiga kiring
                </p>
            </div>
            <div class="flex items-center gap-2">
                <div
                    class="flex items-center gap-1.5 px-3 py-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/30 rounded-xl">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-xs font-semibold text-orange-700 dark:text-orange-400">Faqat adminlar uchun</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            @foreach ([['label' => 'Jami', 'value' => $stats['total'], 'color' => 'indigo', 'role' => 'all'], ['label' => 'O\'qituvchi', 'value' => $stats['teachers'], 'color' => 'blue', 'role' => 'teacher'], ['label' => 'O\'quvchi', 'value' => $stats['students'], 'color' => 'green', 'role' => 'student'], ['label' => 'Direktor', 'value' => $stats['directors'], 'color' => 'purple', 'role' => 'director'], ['label' => 'Xodim', 'value' => $stats['staff'], 'color' => 'amber', 'role' => 'staff']] as $stat)
                <a href="{{ route('impersonate.index', array_merge(request()->query(), ['role' => $stat['role']])) }}"
                    class="bg-white dark:bg-gray-800 rounded-2xl border p-4 shadow-sm text-center transition-all hover:shadow-md
            {{ $role === $stat['role']
                ? 'border-' .
                    $stat['color'] .
                    '-400 dark:border-' .
                    $stat['color'] .
                    '-600 ring-2 ring-' .
                    $stat['color'] .
                    '-200 dark:ring-' .
                    $stat['color'] .
                    '-800/40'
                : 'border-gray-200 dark:border-gray-700' }}">
                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-0.5">{{ $stat['label'] }}</p>
                </a>
            @endforeach
        </div>

        {{-- Search & Filters --}}
        <form method="GET" action="{{ route('impersonate.index') }}"
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-4">
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Search --}}
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Ism, familiya, telefon yoki email..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500">
                </div>

                {{-- Role filter --}}
                <select name="role"
                    class="px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                    <option value="all" {{ $role === 'all' ? 'selected' : '' }}>Barcha rollar</option>
                    <option value="teacher" {{ $role === 'teacher' ? 'selected' : '' }}>O'qituvchi</option>
                    <option value="student" {{ $role === 'student' ? 'selected' : '' }}>O'quvchi</option>
                    <option value="director"{{ $role === 'director' ? 'selected' : '' }}>Direktor</option>
                    <option value="staff" {{ $role === 'staff' ? 'selected' : '' }}>Xodim</option>
                </select>

                {{-- Status filter --}}
                <select name="status"
                    class="px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Barcha statuslar</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Faol</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Faol emas</option>
                    <option value="deleted" {{ $status === 'deleted' ? 'selected' : '' }}>O'chirilgan</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        Qidirish
                    </button>
                    <a href="{{ route('impersonate.index') }}"
                        class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl transition-colors">
                        Tozalash
                    </a>
                </div>
            </div>
        </form>

        {{-- Users Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-bold text-gray-900 dark:text-white">
                    Foydalanuvchilar
                    @if ($search)
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">— "{{ $search }}"
                            qidiruv</span>
                    @endif
                </h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $users->total() }} ta</span>
            </div>

            @if ($users->isEmpty())
                <div class="py-20 text-center">
                    <svg class="w-14 h-14 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Foydalanuvchilar topilmadi</p>
                    <a href="{{ route('impersonate.index') }}"
                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block">
                        Filtrlarni tozalash
                    </a>
                </div>
            @else
                {{-- Desktop table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                <th
                                    class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Foydalanuvchi</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aloqa</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Rol</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Qo'shimcha</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-5 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Amal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($users as $i => $user)
                                @php
                                    $userRole = $user->roles->first()?->name ?? 'user';
                                    $isAdmin = $user->hasRole('admin');
                                    $isDeleted = $user->trashed();
                                    $canImpersonate = !$isAdmin && !$isDeleted && $user->is_active;
                                    $roleBadge = roleInfo($userRole);
                                @endphp
                                <tr
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $isDeleted ? 'opacity-50' : '' }}">
                                    {{-- # --}}
                                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $users->firstItem() + $loop->index }}
                                    </td>

                                    {{-- User info --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl flex-shrink-0 overflow-hidden
                                    {{ !$user->photo ? 'bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center' : '' }}">
                                                @if ($user->photo)
                                                    <img src="{{ asset('storage/' . $user->photo) }}" alt=""
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-white text-sm font-bold">
                                                        {{ strtoupper(mb_substr($user->surname ?? $user->name, 0, 1)) }}{{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $user->surname }} {{ $user->name }}
                                                    @if ($user->middle_name)
                                                        <span
                                                            class="font-normal text-gray-500">{{ $user->middle_name }}</span>
                                                    @endif
                                                </p>
                                                @if ($user->birth_date)
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                                        {{ \Carbon\Carbon::parse($user->birth_date)->format('d.m.Y') }}
                                                        • {{ \Carbon\Carbon::parse($user->birth_date)->age }} yosh
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Contacts --}}
                                    <td class="px-5 py-4">
                                        <div class="space-y-0.5">
                                            @if ($user->phone)
                                                <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">
                                                    {{ $user->phone }}</p>
                                            @endif
                                            @if ($user->email)
                                                <p class="text-xs text-gray-400 dark:text-gray-500 truncate max-w-[160px]">
                                                    {{ $user->email }}</p>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Role --}}
                                    <td class="px-5 py-4">
                                        @foreach ($user->roles as $r)
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full {{ $roleBadge['class'] }}">
                                                {{ $roleBadge['icon'] }} {{ $roleBadge['label'] }}
                                            </span>
                                        @endforeach
                                        @if ($user->roles->isEmpty())
                                            <span class="text-xs text-gray-400 italic">Rol yo'q</span>
                                        @endif
                                    </td>

                                    {{-- Extra info --}}
                                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if ($userRole === 'teacher' && $user->teacher)
                                            <p class="text-xs">{{ $user->teacher->groups->count() }} guruh</p>
                                            <p class="text-xs text-gray-400">{{ ucfirst($user->teacher->salary_type) }}
                                            </p>
                                        @elseif($userRole === 'student' && $user->student)
                                            @if ($user->student->school)
                                                <p class="text-xs truncate max-w-[140px]">
                                                    {{ $user->student->school->name }}</p>
                                            @endif
                                            <p class="text-xs text-amber-500 font-semibold">🪙
                                                {{ $user->student->coins ?? 0 }} coin</p>
                                        @elseif($userRole === 'director' && $user->director)
                                            @if ($user->director->school)
                                                <p class="text-xs truncate max-w-[140px]">
                                                    {{ $user->director->school->name }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400">{{ $user->director->commission_percentage }}%
                                                komissiya</p>
                                        @elseif($userRole === 'staff' && $user->staff)
                                            <p class="text-xs">{{ ucfirst($user->staff->salary_type) }}</p>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-5 py-4">
                                        @if ($isDeleted)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs font-bold rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                                O'chirilgan
                                            </span>
                                        @elseif($user->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded-full">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
                                                Faol
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 text-xs font-bold rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                                                Nofaol
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-5 py-4 text-right">
                                        @if ($canImpersonate)
                                            <form method="POST" action="{{ route('impersonate.start', $user->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm hover:shadow-md group"
                                                    title="{{ $user->surname }} {{ $user->name }} nomidan kirish">
                                                    <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Kirish
                                                </button>
                                            </form>
                                        @elseif($isAdmin)
                                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">Admin</span>
                                        @else
                                            <span class="text-xs text-red-400 dark:text-red-500 italic">Mumkin emas</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($users as $user)
                        @php
                            $userRole = $user->roles->first()?->name ?? 'user';
                            $isAdmin = $user->hasRole('admin');
                            $isDeleted = $user->trashed();
                            $canImpersonate = !$isAdmin && !$isDeleted && $user->is_active;
                            $roleBadge = roleInfo($userRole);
                        @endphp
                        <div class="p-4 {{ $isDeleted ? 'opacity-50' : '' }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div
                                        class="w-11 h-11 rounded-xl flex-shrink-0 overflow-hidden
                            {{ !$user->photo ? 'bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center' : '' }}">
                                        @if ($user->photo)
                                            <img src="{{ asset('storage/' . $user->photo) }}" alt=""
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="text-white font-bold text-sm">
                                                {{ strtoupper(mb_substr($user->surname ?? $user->name, 0, 1)) }}{{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                            {{ $user->surname }} {{ $user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->phone }}</p>
                                        <span
                                            class="inline-flex mt-1 items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-full {{ $roleBadge['class'] }}">
                                            {{ $roleBadge['icon'] }} {{ $roleBadge['label'] }}
                                        </span>
                                    </div>
                                </div>

                                @if ($canImpersonate)
                                    <form method="POST" action="{{ route('impersonate.start', $user->id) }}"
                                        class="flex-shrink-0">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-colors">
                                            Kirish
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif

            @endif
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // Impersonation confirmation
        document.querySelectorAll('form[action*="start"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                const name = this.closest('tr, .flex')?.querySelector('p.font-bold')?.textContent?.trim() ??
                    'bu foydalanuvchi';
                if (!confirm(`${name} nomidan kirishni tasdiqlaysizmi?`)) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
