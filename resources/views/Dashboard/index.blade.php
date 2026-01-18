@extends('Layout.index')
@section('title', 'Dashboard')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                🎓 O'quv Markazi Boshqaruvi
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Faol kurslar va talabalar holati</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all shadow-md flex items-center gap-2">
            <span>+</span> Yangi Kurs Qo'shish
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
            <span class="text-gray-500 dark:text-gray-400 text-sm">Jami o'quvchilar</span>
            <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">1,284</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
            <span class="text-gray-500 dark:text-gray-400 text-sm">Aktiv kurslar</span>
            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">24 ta</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
            <span class="text-gray-500 dark:text-gray-400 text-sm">Oylik tushum</span>
            <div class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">$4,250</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 shadow-xl dark:shadow-black/40 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kurs Nomi</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Oqituvchi</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vaqti</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">O'quvchilar</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Holat</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Amallar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">JS</div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">JavaScript FullStack</div>
                                    <div class="text-xs text-gray-500">Intensive Course</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Anvar Salimov</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">14:00 - 16:00</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-white font-medium">18 / 20</div>
                            <div class="w-24 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mt-1.5 overflow-hidden">
                                <div class="bg-indigo-500 h-full w-[90%]"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                Faol
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors px-2">
                                ✏️
                            </button>
                            <button class="text-gray-400 hover:text-red-600 transition-colors px-2">
                                🗑️
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center text-purple-600 dark:text-purple-400 font-bold">UX</div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">Graphic Design</div>
                                    <div class="text-xs text-gray-500">UI/UX Foundations</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Lola Karimova</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">09:00 - 11:00</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-white font-medium">12 / 15</div>
                            <div class="w-24 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mt-1.5 overflow-hidden">
                                <div class="bg-purple-500 h-full w-[80%]"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Guruh to'lmoqda
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors px-2">
                                ✏️
                            </button>
                            <button class="text-gray-400 hover:text-red-600 transition-colors px-2">
                                🗑️
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <span class="text-sm text-gray-500">2 tadan 10 ta kurs ko'rsatilyapti</span>
            <div class="flex gap-2">
                <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm hover:bg-white dark:hover:bg-gray-700 transition-colors">Oldingi</button>
                <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm hover:bg-white dark:hover:bg-gray-700 transition-colors">Keyingi</button>
            </div>
        </div>
    </div>
</div>
@endsection
