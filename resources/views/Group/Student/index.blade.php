@extends('Layout.app')

@section('title', $group->name . ' - O\'quvchilar')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('groups.show', $group->id) }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white">{{ $group->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">O'quvchilar boshqaruvi</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('add-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-200 dark:shadow-none transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                O'quvchi qo'shish
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100/50 dark:from-purple-500/10 dark:to-purple-500/5 rounded-2xl p-6 border border-purple-200 dark:border-purple-500/20">
                <p class="text-xs uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400 mb-2">Jami
                    o'quvchi</p>
                <p class="text-4xl font-black text-purple-600 dark:text-purple-400">{{ $group->activeStudents->count() }}
                </p>
            </div>
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-500/5 rounded-2xl p-6 border border-blue-200 dark:border-blue-500/20">
                <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400 mb-2">Sig'im</p>
                <p class="text-4xl font-black text-blue-600 dark:text-blue-400">{{ $group->capacity }}</p>
            </div>
            <div
                class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-500/5 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-500/20">
                <p class="text-xs uppercase tracking-wider font-bold text-emerald-600 dark:text-emerald-400 mb-2">Bo'sh joy
                </p>
                <p class="text-4xl font-black text-emerald-600 dark:text-emerald-400">
                    {{ $group->capacity - $group->activeStudents->count() }}</p>
            </div>
        </div>

        <!-- Students Table -->
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-purple-500/20 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white">O'quvchilar ro'yxati</h2>
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Qidirish..."
                            class="pl-10 pr-4 py-2 bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-slate-400 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="studentsTable">
                    <thead class="bg-gray-50 dark:bg-slate-800">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                O'quvchi</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Telefon</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Qo'shilgan sana</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Holat</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($group->activeStudents as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors student-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                                            <span
                                                class="text-white font-bold text-sm">{{ substr($student->user->name ?? 'N/A', 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white student-name">
                                                {{ $student->user->full_name ?? 'Noma\'lum' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $student->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $student->user->phone ?? 'Telefon yo\'q' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $student->pivot->joined_at ? \Carbon\Carbon::parse($student->pivot->joined_at)->format('d.m.Y') : 'N/A' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                        Faol
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('students.show', $student->id) }}"
                                            class="p-2 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-all group">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('groups.students.destroy', [$group->id, $student->id]) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('O\'quvchini guruhdan chiqarmoqchimisiz?')"
                                                class="p-2 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all group">
                                                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">O'quvchilar yo'q
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Bu guruhda hali o'quvchilar
                                            yo'q
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Students Modal -->
    <div id="add-modal" onclick="closeAddModal()"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

        <div onclick="event.stopPropagation()"
            class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-white/5 max-h-[90vh] flex flex-col">

            <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">O'quvchi qo'shish</h3>
                    <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <form action="{{ route('groups.students.store', $group->id) }}" method="POST"
                class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 flex-1 overflow-y-auto">
                    <!-- Search -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="modalSearch" placeholder="o'quvchini qidirish..."
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-slate-400 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Students List -->
                    <div class="space-y-2" id="studentsList">
                        @forelse($availableStudents as $student)
                            <label
                                class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-purple-500/10 rounded-xl cursor-pointer transition-all student-item">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                    class="w-5 h-5 text-purple-600 bg-white dark:bg-slate-900 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-500">
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white student-item-name">
                                        {{ $student->user->full_name ?? 'Noma\'lum' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $student->user->phone ?? 'Telefon yo\'q' }}</p>
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Qo'shish uchun o'quvchilar yo'q</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-slate-800">
                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')"
                            class="flex-1 px-5 py-3 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all">
                            Bekor qilish
                        </button>
                        <button type="submit"
                            class="flex-1 px-5 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-200 dark:shadow-none transition-all">
                            Qo'shish
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            // Table search
            document.getElementById('searchInput')?.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.student-row');

                rows.forEach(row => {
                    const name = row.querySelector('.student-name')?.textContent.toLowerCase() || '';
                    row.style.display = name.includes(searchTerm) ? '' : 'none';
                });
            });

            // Modal search
            document.getElementById('modalSearch')?.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const items = document.querySelectorAll('.student-item');

                items.forEach(item => {
                    const name = item.querySelector('.student-item-name')?.textContent.toLowerCase() || '';
                    item.style.display = name.includes(searchTerm) ? '' : 'none';
                });
            });

            function closeAddModal() {
                document.getElementById('add-modal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection
