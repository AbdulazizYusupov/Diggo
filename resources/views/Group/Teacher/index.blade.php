@extends('Layout.app')

@section('title', $group->name . ' - O\'qituvchilar')

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
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">O'qituvchilar boshqaruvi</p>
                </div>
            </div>

            <button type="button" onclick="document.getElementById('add-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                O'qituvchi qo'shish
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-500/5 rounded-2xl p-6 border border-blue-200 dark:border-blue-500/20">
                <p class="text-xs uppercase tracking-wider font-bold text-blue-600 dark:text-blue-400 mb-2">Jami
                    o'qituvchilar</p>
                <p class="text-4xl font-black text-blue-600 dark:text-blue-400">{{ $group->teachers->count() }}</p>
            </div>
            <div
                class="bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-500/10 dark:to-amber-500/5 rounded-2xl p-6 border border-amber-200 dark:border-amber-500/20">
                <p class="text-xs uppercase tracking-wider font-bold text-amber-600 dark:text-amber-400 mb-2">Asosiy
                    o'qituvchi</p>
                <p class="text-lg font-black text-amber-600 dark:text-amber-400">
                    {{ $group->teachers->where('pivot.is_primary', true)->first()?->user->name ?? 'Yo\'q' }}
                </p>
            </div>
        </div>

        <!-- Teachers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($group->teachers as $teacher)
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-6 hover:shadow-lg transition-all">
                    <!-- Teacher Avatar -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none">
                                <span
                                    class="text-white font-bold text-lg">{{ substr($teacher->user->name ?? 'N', 0, 2) }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white">
                                    {{ $teacher->user->name ?? 'Noma\'lum' }}</h3>
                                @if ($teacher->pivot->is_primary)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Asosiy
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Info -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $teacher->user->email ?? 'Email yo\'q' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>{{ $teacher->user->phone ?? 'Telefon yo\'q' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $teacher->pivot->assigned_at ? \Carbon\Carbon::parse($teacher->pivot->assigned_at)->format('d.m.Y') : 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-800">
                        @if (!$teacher->pivot->is_primary)
                            <form action="{{ route('groups.teachers.primary', [$group->id, $teacher->id]) }}" method="POST"
                                class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full px-3 py-2 bg-amber-100 hover:bg-amber-200 dark:bg-amber-500/20 dark:hover:bg-amber-500/30 text-amber-700 dark:text-amber-400 text-sm font-bold rounded-lg transition-all">
                                    Asosiy qilish
                                </button>
                            </form>
                        @endif
                        <button type="button"
                            onclick="openDeleteModal('{{ route('groups.teachers.destroy', [$group->id, $teacher->id]) }}')"
                            class="flex-1 w-full px-3 py-2 bg-rose-100 hover:bg-rose-200 dark:bg-rose-500/20 dark:hover:bg-rose-500/30 text-rose-700 dark:text-rose-400 text-sm font-bold rounded-lg transition-all">
                            O'chirish
                        </button>

                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-blue-500/20 p-12 text-center">
                        <div
                            class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">O'qituvchilar yo'q</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Bu guruhda hali o'qituvchilar yo'q</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div id="add-modal" onclick="document.getElementById('add-modal').classList.add('hidden')"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

        <div onclick="event.stopPropagation()"
            class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-white/5 max-h-[90vh] flex flex-col">

            <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">O'qituvchi qo'shish</h3>
                    <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition-all">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('groups.teachers.store', $group->id) }}" method="POST"
                class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 flex-1 overflow-y-auto">
                    <!-- Search -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="modalSearch" placeholder="O'qituvchi qidirish..."
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Teachers List -->
                    <div class="space-y-2" id="teachersList">
                        @forelse($availableTeachers as $teacher)
                            <label
                                class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-xl cursor-pointer transition-all teacher-item">
                                <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}"
                                    class="w-5 h-5 text-blue-600 bg-white dark:bg-slate-900 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white teacher-item-name">
                                        {{ $teacher->user->name ?? 'Noma\'lum' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $teacher->user->email ?? 'Email yo\'q' }}</p>
                                </div>
                                <label class="flex items-center gap-2" onclick="event.stopPropagation()">
                                    <input type="radio" name="primary_teacher_id" value="{{ $teacher->id }}"
                                        class="w-4 h-4 text-amber-600 bg-white dark:bg-slate-900 border-gray-300 dark:border-gray-600 rounded focus:ring-amber-500">
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400">Asosiy</span>
                                </label>
                            </label>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Qo'shish uchun o'qituvchilar yo'q</p>
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
                            class="flex-1 px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 dark:shadow-none transition-all">
                            Qo'shish
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" onclick="closeDeleteModal()"
        class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

        <div onclick="event.stopPropagation()"
            class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-white/5 overflow-hidden">

            <div class="p-6 text-center">
                <div
                    class="w-16 h-16 bg-rose-100 dark:bg-rose-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">
                    Tasdiqlang
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Siz haqiqatdan ham
                    <span id="delete-teacher-name" class="font-bold text-gray-900 dark:text-white"></span>
                    ni guruhdan chiqarmoqchimisiz?
                </p>

                <form id="delete-form" method="POST" class="flex gap-3">
                    @csrf
                    @method('DELETE')

                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-5 py-3 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all">
                        Bekor qilish
                    </button>

                    <button type="submit"
                        class="flex-1 px-5 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-200 dark:shadow-none transition-all active:scale-95">
                        O'chirish
                    </button>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // DELETE MODAL
            function openDeleteModal(action) {
                const modal = document.getElementById('delete-modal');
                const form = document.getElementById('delete-form');

                if (!modal || !form) return;

                form.action = action;
                modal.classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('delete-modal')?.classList.add('hidden');
            }

            // MODAL SEARCH
            document.getElementById('modalSearch')?.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const items = document.querySelectorAll('.teacher-item');

                items.forEach(item => {
                    const name =
                        item.querySelector('.teacher-item-name')?.textContent.toLowerCase() || '';
                    item.style.display = name.includes(searchTerm) ? '' : 'none';
                });
            });
        </script>
    @endpush
@endsection
