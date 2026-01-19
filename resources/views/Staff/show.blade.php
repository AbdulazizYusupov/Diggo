@extends('Layout.app')

@section('title', $staff->user->name . " - Xodim ma'lumotlari")

@section('content')
<div class="w-full pb-16 animate-fadeIn">
    {{-- Breadcrumb & Quick Actions --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-6 group">
            <div class="relative">
                {{-- Profile Image with Pulse Effect --}}
                <div class="w-28 h-28 rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white dark:border-gray-800 ring-2 ring-indigo-500/20 transform transition-transform duration-500 group-hover:scale-105 group-hover:rotate-3">
                    @php $photoPath = $staff->photo ?? ($staff->user->photo ?? null); @endphp
                    @if($photoPath)
                        <img src="{{ asset('storage/' . $photoPath) }}" class="w-full h-full object-cover" alt="Staff Photo">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center text-white text-4xl font-black">
                            {{ mb_substr($staff->user->name, 0, 1) }}{{ mb_substr($staff->user->surname, 0, 1) }}
                        </div>
                    @endif
                </div>
                {{-- Status Tooltip Badge --}}
                <div class="absolute -bottom-2 -right-2 has-tooltip">
                    <span class="tooltip rounded shadow-lg p-1 bg-gray-900 text-white -mt-10 text-xs font-bold uppercase tracking-tighter">
                        {{ $staff->is_active ? 'Hozir faol' : 'Nofaol' }}
                    </span>
                    @if ($staff->is_active)
                        <span class="flex h-8 w-8 items-center justify-center rounded-2xl bg-emerald-500 ring-4 ring-white dark:ring-gray-900 animate-bounce-slow shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </span>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-2xl bg-red-500 ring-4 ring-white dark:ring-gray-900 shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </span>
                    @endif
                </div>
            </div>

            <div class="space-y-2">
                <h1 class="text-5xl font-black tracking-tighter text-gray-900 dark:text-white leading-none">
                    {{ $staff->user->surname }} <span class="text-indigo-600">{{ $staff->user->name }}</span>
                </h1>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2 px-4 py-1.5 bg-white/50 dark:bg-gray-800/50 backdrop-blur-md border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-widest">ID: #{{ $staff->id }}</span>
                    </div>
                    <span class="text-sm font-medium text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $staff->created_at->format('d.m.Y') }} da qo'shilgan
                    </span>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('staffs.index') }}" class="group flex items-center px-6 py-4 text-sm font-bold text-gray-700 bg-white border-2 border-gray-100 rounded-[1.5rem] hover:border-indigo-500 transition-all duration-300 hover:shadow-2xl active:scale-95 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Orqaga qaytish
            </a>
            <a href="{{ route('staffs.edit', $staff->id) }}" class="group flex items-center px-8 py-4 text-sm font-bold text-white bg-indigo-600 rounded-[1.5rem] shadow-[0_20px_50px_rgba(79,70,229,0.3)] hover:bg-indigo-700 hover:shadow-indigo-500/50 transition-all duration-300 active:scale-95">
                <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Tahrirlash
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Main Info - 8 Columns --}}
        <div class="lg:col-span-8 space-y-8">
            
            {{-- Personal Details Card --}}
            <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-[3rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 hover:border-indigo-200 transition-colors duration-500 group">
                <div class="absolute top-0 right-0 p-10 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity duration-700">
                    <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                
                <div class="p-10 relative z-10">
                    <div class="flex items-center gap-5 mb-10">
                        <div class="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl shadow-lg shadow-indigo-200">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white">Asosiy Profil</h2>
                            <p class="text-gray-400 text-sm font-medium italic">Shaxsiy va biometrik ma'lumotlar</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                        @php
                            $details = [
                                ['label' => 'To\'liq sharif', 'val' => $staff->user->surname . ' ' . $staff->user->name . ' ' . $staff->user->middle_name, 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                ['label' => 'Aloqa raqami', 'val' => '+998 ' . $staff->user->phone, 'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                                ['label' => 'Tug\'ilgan kun', 'val' => $staff->user->birth_date ? \Carbon\Carbon::parse($staff->user->birth_date)->format('d F, Y') : 'Kiritilmagan', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                ['label' => 'Jinsi', 'val' => $staff->user->gender == 'male' ? 'Erkak' : 'Ayol', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                            ];
                        @endphp

                        @foreach($details as $item)
                        <div class="group/item flex items-start gap-4 transition-transform duration-300 hover:translate-x-2">
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 text-gray-400 group-hover/item:text-indigo-600 group-hover/item:bg-indigo-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500/60 mb-1">{{ $item['label'] }}</p>
                                <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $item['val'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Address Section with Glass Effect --}}
            <div class="bg-gradient-to-br from-amber-500/5 to-orange-500/5 dark:from-amber-500/10 dark:to-transparent rounded-[3rem] p-1 border border-amber-100 dark:border-amber-900/30">
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-2xl rounded-[2.9rem] p-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-4 bg-amber-500 rounded-2xl shadow-lg shadow-amber-200 dark:shadow-none">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Yashash manzili</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-amber-600 uppercase">Viloyat</p>
                            <p class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $staff->user->region ?? '---' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-amber-600 uppercase">Shahar / Tuman</p>
                            <p class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $staff->user->city ?? '---' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-amber-600 uppercase">Ko'cha, uy</p>
                            <p class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $staff->user->address ?? '---' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side Column - 4 Columns --}}
        <div class="lg:col-span-4 space-y-8">
            {{-- Premium Salary Card --}}
            <div class="group relative bg-gray-900 dark:bg-black rounded-[3rem] p-10 text-white overflow-hidden shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/20 rounded-full blur-[80px] group-hover:bg-indigo-600/40 transition-all duration-700"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-600/10 rounded-full blur-[80px]"></div>

                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-12">
                            <div class="p-4 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/10 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-[10px] font-black bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full border border-emerald-500/30 uppercase tracking-tighter">Moliyaviy ma'lumot</span>
                        </div>

                        <div class="space-y-8">
                            <div>
                                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-2">To'lov shakli</p>
                                <h3 class="text-3xl font-black italic tracking-tighter">
                                    @if($staff->salary_type == 'fixed') Fixed Salary
                                    @elseif($staff->salary_type == 'percent') Revenue Share
                                    @else Per Student Basis @endif
                                </h3>
                            </div>

                            @if($staff->salary_type != 'percent')
                                <div class="p-6 bg-white/5 rounded-3xl border border-white/5 group-hover:border-white/20 transition-all">
                                    <p class="text-emerald-400 text-xs font-bold mb-1 italic">Joriy stavka</p>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-black">{{ number_format($staff->salary_amount, 0, ',', ' ') }}</span>
                                        <span class="text-sm text-gray-500">UZS</span>
                                    </div>
                                </div>
                            @endif

                            @if($staff->salary_type == 'percent')
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-indigo-500/10 p-5 rounded-3xl border border-indigo-500/20 text-center">
                                        <p class="text-[9px] font-bold text-indigo-400 uppercase mb-1 text-center">Student %</p>
                                        <p class="text-3xl font-black text-white">{{ $staff->percent_per_student }}<span class="text-sm">%</span></p>
                                    </div>
                                    <div class="bg-purple-500/10 p-5 rounded-3xl border border-purple-500/20 text-center">
                                        <p class="text-[9px] font-bold text-purple-400 uppercase mb-1 text-center">Payment %</p>
                                        <p class="text-3xl font-black text-white">{{ $staff->percent_per_payment }}<span class="text-sm">%</span></p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity & Account Status --}}
            <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 border border-gray-100 dark:border-gray-700 shadow-xl group/acc overflow-hidden relative">
                <div class="absolute top-0 right-0 w-2 h-full bg-indigo-600 transition-all duration-500 transform translate-x-full group-hover/acc:translate-x-0"></div>
                
                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    Hisob holati
                </h2>

                <div class="space-y-5">
                    <div class="flex items-center justify-between p-5 bg-gray-50 dark:bg-gray-700/50 rounded-[2rem] border border-gray-100 dark:border-gray-600 transition-transform group-hover/acc:scale-[1.02]">
                        <span class="text-sm font-bold text-gray-500">Status:</span>
                        @if($staff->is_active)
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-600 text-[10px] font-black rounded-lg">BLOCK</span>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2 p-5 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-[2rem] text-center">
                        <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Oxirgi yangilanish</span>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $staff->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes bounce-slow { 0%, 100% { transform: translateY(-5%); } 50% { transform: translateY(0); } }
    
    .animate-fadeIn { animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-bounce-slow { animation: bounce-slow 3s infinite ease-in-out; }

    /* Custom Tooltip */
    .has-tooltip { position: relative; }
    .tooltip {
        visibility: hidden;
        z-index: 50;
        position: absolute;
        width: 80px;
        text-align: center;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(10px);
    }
    .has-tooltip:hover .tooltip {
        visibility: visible;
        opacity: 1;
        transform: translateY(0px);
    }
</style>
@endsection