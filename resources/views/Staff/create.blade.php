@extends('Layout.index')

@section('title', "Yangi xodim qo'shish")

@section('content')
<div class="w-full">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Yangi xodim</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Tizimga yangi mutaxassisni qo'shish va maosh turini belgilash
            </p>
        </div>
        <a href="{{ route('staffs.index') }}" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Orqaga qaytish
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('staffs.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf

            <div class="space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                    <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Shaxsiy ma'lumotlar</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Ism <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Masalan: Azizbek"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Familiya <span class="text-red-500">*</span></label>
                        <input type="text" name="surname" required placeholder="Masalan: Karimov"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Telefon raqami <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">+998</span>
                            <input type="text" name="phone" required placeholder=" (90) 123-45-67"
                                   class="w-full pl-14 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white transition-all outline-none">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Jinsi <span class="text-red-500">*</span></label>
                        <select name="gender" required 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 dark:text-white outline-none">
                            <option value="male">Erkak</option>
                            <option value="female">Ayol</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                    <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Ish va Maosh shartlari</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Maosh turi <span class="text-red-500">*</span></label>
                        <select name="salary_type" id="salary_type_select" required onchange="updateSalaryFields()"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-indigo-500 dark:text-white outline-none">
                            <option value="fixed">Qat'iy (Fixed)</option>
                            <option value="percent">Foizli (Percentage)</option>
                            <option value="per_student">Har bir talaba uchun</option>
                        </select>
                    </div>

                    <div id="salary_input_container" class="space-y-2 animate-fadeIn">
                        <label id="salary_label" class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Maosh miqdori (so'mda)</label>
                        <input type="number" name="salary_amount" placeholder="0"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:text-white transition-all outline-none">
                    </div>
                </div>

                <div id="percent_fields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6 animate-slideDown">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Talaba uchun (%)</label>
                        <input type="number" name="percent_per_student" placeholder="Masalan: 40"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">To'lovdan (%)</label>
                        <input type="number" name="percent_per_payment" placeholder="Masalan: 10"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 pb-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Hisob ma'lumotlari</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Xodim surati</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 dark:hover:bg-gray-700/50 dark:bg-gray-700 dark:border-gray-600 hover:bg-gray-100 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG (Max. 2MB)</p>
                                </div>
                                <input type="file" name="photo" class="hidden" accept="image/*" />
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-1">Tizimga kirish paroli <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <p class="text-[11px] text-gray-400 px-1 italic">Xodim ushbu parol orqali o'z profiliga kiradi.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="reset" class="px-6 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 transition-colors">
                    Tozalash
                </button>
                <button type="submit" 
                        class="px-10 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-none transform hover:scale-[1.02] active:scale-95 transition-all">
                    Saqlash va qo'shish
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
    .animate-slideDown { animation: slideDown 0.3s ease-out; }
</style>

<script>
    function updateSalaryFields() {
        const type = document.getElementById('salary_type_select').value;
        const container = document.getElementById('salary_input_container');
        const label = document.getElementById('salary_label');
        const percentFields = document.getElementById('percent_fields');

        if (type === 'percent') {
            container.classList.add('hidden');
            percentFields.classList.remove('hidden');
        } else {
            container.classList.remove('hidden');
            percentFields.classList.add('hidden');
            
            if (type === 'fixed') {
                label.innerText = "Qat'iy maosh miqdori (so'm)";
            } else {
                label.innerText = "Har bir talaba uchun to'lov (so'm)";
            }
        }
    }
</script>
@endsection