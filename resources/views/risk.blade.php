@extends('layouts.app')

@section('title', 'SI ERMa - RSUD dr. Murjani')

@section('content')
<div x-data="riskApp()" x-init="init()">

    <!-- MOBILE HEADER -->
    <div class="md:hidden fixed top-0 w-full bg-slate-950/95 backdrop-blur-xl text-white z-50 px-4 py-3 flex justify-between items-center shadow-lg border-b border-white/5">
        <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 bg-gradient-to-br from-teal-400 to-teal-600 rounded-lg flex items-center justify-center">
                <i data-lucide="activity" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-bold text-sm tracking-tight">SI ERMa</span>
        </div>
        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
            <i data-lucide="menu" class="w-5 h-5 text-white"></i>
        </button>
    </div>

    <!-- MOBILE MENU DROPDOWN -->
    <div x-show="isMobileMenuOpen" x-cloak 
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="md:hidden fixed top-[52px] left-0 w-full bg-slate-900/98 backdrop-blur-xl text-white z-40 p-3 space-y-1 shadow-2xl border-b border-white/5">
        <button @click="setActiveTab('dashboard'); isMobileMenuOpen = false" class="block w-full text-left py-2.5 px-4 hover:bg-white/10 rounded-xl text-sm font-medium transition-colors">
            <i data-lucide="layout-dashboard" class="w-4 h-4 inline mr-2 opacity-60"></i>Dashboard
        </button>
        <button @click="setActiveTab('register'); isMobileMenuOpen = false" class="block w-full text-left py-2.5 px-4 hover:bg-white/10 rounded-xl text-sm font-medium transition-colors">
            <i data-lucide="file-text" class="w-4 h-4 inline mr-2 opacity-60"></i>Daftar Risiko
        </button>
        <button @click="setActiveTab('matrix'); isMobileMenuOpen = false" class="block w-full text-left py-2.5 px-4 hover:bg-white/10 rounded-xl text-sm font-medium transition-colors">
            <i data-lucide="target" class="w-4 h-4 inline mr-2 opacity-60"></i>Matriks Risiko
        </button>
        <button @click="setActiveTab('controls'); isMobileMenuOpen = false" class="block w-full text-left py-2.5 px-4 hover:bg-white/10 rounded-xl text-sm font-medium transition-colors">
            <i data-lucide="shield-check" class="w-4 h-4 inline mr-2 opacity-60"></i>Pengendalian
        </button>
        <hr class="border-white/10 my-2">
        <button @click="exportToExcel(); isMobileMenuOpen = false" class="block w-full text-left py-2.5 px-4 bg-emerald-600/90 hover:bg-emerald-600 rounded-xl text-sm font-semibold transition-colors">
            <i data-lucide="file-spreadsheet" class="w-4 h-4 inline mr-2"></i>Download Excel
        </button>
    </div>

    <!-- SIDEBAR (DESKTOP) -->
    <div :class="isSidebarCollapsed ? 'w-20' : 'w-72'" class="sidebar-gradient text-white h-screen fixed left-0 top-0 flex flex-col shadow-2xl shadow-black/30 z-50 hidden md:flex border-r border-white/5 transition-all duration-300 ease-in-out group/sidebar">
        
        <!-- Toggle Button -->
        <button @click="isSidebarCollapsed = !isSidebarCollapsed" 
            class="absolute -right-3 top-10 w-7 h-7 bg-teal-500 rounded-full flex items-center justify-center text-white border-4 border-slate-900 shadow-xl hover:bg-teal-400 transition-all z-[60] hover:scale-110 active:scale-95 group/toggle">
            <svg x-show="!isSidebarCollapsed" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover/toggle:-translate-x-0.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            <svg x-show="isSidebarCollapsed" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover/toggle:translate-x-0.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>

        <!-- Brand -->
        <div class="mb-10 flex items-center px-5 pt-8 transition-all duration-300" :class="isSidebarCollapsed ? 'justify-center' : 'space-x-4'">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/20 shrink-0">
                <i data-lucide="activity" class="w-5 h-5 text-white"></i>
            </div>
            <div x-show="!isSidebarCollapsed" x-transition.opacity.duration.300ms class="animate-fade-in">
                <h1 class="text-lg font-black tracking-tight whitespace-nowrap text-white">SI ERMa</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest whitespace-nowrap">RSUD dr. Murjani</p>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4 space-y-1.5">
            <!-- Navigation Items Macro-like Structure -->
            <template x-for="item in [
                { id: 'dashboard', icon: 'layout-dashboard', label: 'Dashboard' },
                { id: 'register', icon: 'file-text', label: 'Daftar Risiko' },
                { id: 'matrix', icon: 'target', label: 'Matriks Risiko' },
                { id: 'controls', icon: 'shield-check', label: 'Pengendalian' },
                { id: 'assessment', icon: 'clipboard-check', label: 'Assessment' }
            ]" :key="item.id">
                <button @click="setActiveTab(item.id)" 
                    class="w-full flex items-center rounded-xl transition-all duration-200 py-3 group/nav relative"
                    :class="[
                        activeTab === item.id ? 'text-teal-400 bg-white/5 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200',
                        isSidebarCollapsed ? 'justify-center px-0' : 'px-4 space-x-4'
                    ]">
                    <!-- Active Indicator Line -->
                    <div x-show="activeTab === item.id" class="absolute left-0 w-1 h-6 bg-teal-500 rounded-r-full"></div>
                    
                    <div class="w-6 h-6 flex items-center justify-center shrink-0">
                        <i :data-lucide="item.icon" class="w-5 h-5 transition-transform group-hover/nav:scale-110"></i>
                    </div>
                    <span x-show="!isSidebarCollapsed" x-transition.opacity.duration.300ms class="font-bold text-xs uppercase tracking-wider whitespace-nowrap transition-colors"
                        :class="activeTab === item.id ? 'text-teal-400' : 'text-slate-400 group-hover/nav:text-slate-200'"
                        x-text="item.label"></span>
                </button>
            </template>

            <!-- Bottom Section Separator -->
            <div class="pt-4 mt-4 border-t border-white/5 space-y-1.5" x-show="isAdmin || isWadir">
                <button @click="isContextModalOpen = true" 
                    class="w-full flex items-center rounded-xl text-slate-400 hover:bg-white/5 hover:text-slate-200 transition-all duration-200 py-3 group/nav"
                    :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-4 space-x-4'">
                    <div class="w-6 h-6 flex items-center justify-center shrink-0 text-teal-500/80">
                        <i data-lucide="{{ Auth::user()->isAdmin() ? 'megaphone' : 'target' }}" class="w-5 h-5 transition-transform group-hover/nav:scale-110"></i>
                    </div>
                    <span x-show="!isSidebarCollapsed" x-transition.opacity.duration.300ms class="font-bold text-xs uppercase tracking-wider whitespace-nowrap text-left text-teal-400/80">{{ Auth::user()->isAdmin() ? 'Notify Users' : 'Konteks & Strategis' }}</span>
                </button>
            </div>
        </nav>

        <!-- User Info Card -->
        <div class="px-4 mt-auto mb-4 overflow-hidden">
            <div class="rounded-xl transition-all duration-300 flex flex-col overflow-hidden" :class="isSidebarCollapsed ? 'p-2 items-center' : 'bg-white/5 border border-white/5 p-4 items-start'">
                <div class="flex items-center transition-all duration-300" :class="isSidebarCollapsed ? 'gap-0 mb-0 justify-center' : 'gap-3 mb-3'">
                    <!-- Avatar (Always Visible, Scales Smoothly) -->
                    <div class="bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-black text-xs shadow-lg shadow-teal-500/20 shrink-0 transition-all duration-300" 
                        :class="isSidebarCollapsed ? 'w-9 h-9' : 'w-10 h-10'" 
                        title="{{ Auth::user()->name }} ({{ Auth::user()->role }})">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                    </div>
                    <!-- Text Info (Fades in/out) -->
                    <div x-show="!isSidebarCollapsed" x-transition.opacity.duration.300ms class="min-w-0" style="display: none;">
                        <p class="text-white font-bold text-xs truncate leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-slate-400 text-[10px] truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <!-- Badges (Fades in/out) -->
                <div x-show="!isSidebarCollapsed" x-transition.opacity.duration.300ms class="flex flex-wrap gap-1.5" style="display: none;">
                    @if(Auth::user()->isAdmin())
                        <span class="px-2 py-0.5 bg-amber-500/20 text-amber-400 text-[9px] font-black uppercase tracking-wider rounded-md border border-amber-500/20">Super Admin</span>
                    @elseif(Auth::user()->isUnitAdmin())
                        <span class="px-2 py-0.5 bg-teal-500/20 text-teal-400 text-[9px] font-black uppercase tracking-wider rounded-md border border-teal-500/20">Unit Admin</span>
                    @elseif(Auth::user()->isAuditor())
                        <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 text-[9px] font-black uppercase tracking-wider rounded-md border border-blue-500/20">Auditor</span>
                    @else
                        <span class="px-2 py-0.5 bg-slate-500/20 text-slate-400 text-[9px] font-black uppercase tracking-wider rounded-md border border-slate-500/20">{{ ucfirst(Auth::user()->role) }}</span>
                    @endif
                    @if(Auth::user()->unit)
                        <span class="px-2 py-0.5 bg-white/5 text-slate-400 text-[9px] font-bold rounded-md border border-white/5 truncate max-w-[120px]">{{ Auth::user()->unit }}</span>
                    @endif
                    @if(Auth::user()->bidang)
                        <span class="px-2 py-0.5 bg-white/5 text-slate-500 text-[9px] font-medium rounded-md border border-white/5 truncate max-w-[120px]">{{ Auth::user()->bidang }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Section -->
        <div class="px-4 space-y-2 pb-8">
            <button @click="isExportModalOpen = true; isSidebarCollapsed = true" 
                class="w-full text-slate-400 hover:bg-emerald-500/10 hover:text-emerald-400 rounded-xl flex items-center transition-all py-3 group/nav"
                :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-4 space-x-4'">
                <div class="w-6 h-6 flex items-center justify-center shrink-0 text-emerald-500">
                    <i data-lucide="file-spreadsheet" class="w-5 h-5 transition-transform group-hover/nav:scale-110"></i>
                </div>
                <span x-show="!isSidebarCollapsed" x-transition.opacity.duration.300ms class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Export Excel</span>
            </button>
            
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" 
                class="w-full text-slate-400 hover:bg-amber-500/10 hover:text-amber-400 rounded-xl flex items-center transition-all py-3 group/nav"
                :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-4 space-x-4'">
                <div class="w-6 h-6 flex items-center justify-center shrink-0 text-amber-500/80">
                    <i data-lucide="shield-check" class="w-5 h-5 transition-transform group-hover/nav:scale-110"></i>
                </div>
                <span x-show="!isSidebarCollapsed" x-transition.opacity.duration.300ms class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Admin Panel</span>
            </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" 
                    class="w-full text-slate-400 hover:bg-red-500/10 hover:text-red-400 rounded-xl flex items-center transition-all py-3 group/nav"
                    :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-4 space-x-4'">
                    <div class="w-6 h-6 flex items-center justify-center shrink-0 text-red-500">
                        <i data-lucide="log-out" class="w-5 h-5 transition-transform group-hover/nav:scale-110"></i>
                    </div>
                    <span x-show="!isSidebarCollapsed" x-transition.opacity.duration.300ms class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main :class="isSidebarCollapsed ? 'md:ml-20' : 'md:ml-72'" class="flex-1 p-4 md:p-8 mt-14 md:mt-0 transition-all duration-300 ease-in-out">
        
        <!-- HEADER -->
        <header class="mb-8 animate-fade-in">
            <div>
               <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight" x-text="getHeaderTitle()"></h1>
                <p class="text-slate-400 mt-1 text-sm font-medium">Sistem Informasi Enterprise Risk Management (SI ERMa) RSUD dr. Murjani</p>
            </div>
        </header>



        @include('partials.dashboard')
        @include('partials.register')
        @include('partials.matrix')
        @include('partials.controls')
        @include('partials.assessment')
        @include('partials.risk_form_modal')

        <!-- App Context Modal -->
        <div x-show="isContextModalOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isContextModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="isContextModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-br from-teal-100 to-teal-50 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="info" class="h-5 w-5 text-teal-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                                    PENETAPAN KONTEKS DAN RUANG LINGKUP
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tahun</label>
                                            <input type="number" x-model="contextForm.year" :disabled="!(isAdmin || isWadir)" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-teal-500 focus:outline-none disabled:opacity-60 text-sm font-medium transition-all">
                                        </div>
                                        <template x-if="isAdmin || isWadir">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Bidang (Kosongkan jika Direktur)</label>
                                                <select x-model="contextForm.bidang" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-teal-500 focus:outline-none text-sm font-medium">
                                                    <option value="">Direktur (Global)</option>
                                                    <template x-for="unit in availableUnits" :key="unit.id">
                                                        <option :value="unit.name" x-text="unit.name"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </template>
                                    </div>
                                    <template x-if="isAdmin || isWadir">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Notifikasi</label>
                                                <div class="flex gap-2">
                                                    <select x-model="contextForm.notify_days" class="flex-1 p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-teal-500 focus:outline-none text-sm font-medium">
                                                        <option value="0">Jangan Notifikasi</option>
                                                        <option value="7">1 Minggu</option>
                                                        <option value="30">1 Bulan</option>
                                                        <option value="-1">Selamanya (Penting)</option>
                                                        <option value="custom">Kustom (Hari)</option>
                                                    </select>
                                                    <template x-if="contextForm.notify_days === 'custom'">
                                                        <input type="number" x-model="contextForm.custom_days" placeholder="Hari" class="w-20 p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-teal-500 focus:outline-none text-sm">
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="isAdmin || isWadir">
                                        <div x-data="{ search: '' }">
                                            <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Target Notifikasi (Centang User)</label>
                                            <div class="bg-slate-50 rounded-xl p-4 max-h-48 overflow-y-auto border border-slate-200">
                                                <input type="text" x-model="search" placeholder="Cari user..." class="w-full p-2 mb-3 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none bg-white sticky top-0">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    <template x-for="u in availableUsers.filter(u => u.name.toLowerCase().includes(search.toLowerCase()) && (!contextForm.bidang || u.bidang === contextForm.bidang))" :key="u.id">
                                                        <label class="flex items-center gap-2 p-2 hover:bg-white rounded-lg transition-colors cursor-pointer border border-transparent hover:border-slate-200">
                                                            <input type="checkbox" :value="u.name" x-model="contextForm.notify_targets" 
                                                                class="rounded text-teal-600 focus:ring-teal-500">
                                                            <div class="flex flex-col">
                                                                <span class="text-xs font-bold text-slate-700" x-text="u.name"></span>
                                                                <span class="text-[9px] text-slate-400" x-text="u.unit || u.bidang || 'Admin'"></span>
                                                            </div>
                                                        </label>
                                                    </template>
                                                </div>
                                            </div>
                                            <p class="mt-1.5 text-[10px] text-slate-400 font-medium">Kosongkan untuk mengirim ke semua user di bidang tersebut.</p>
                                        </div>
                                    </template>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Urusan Pemerintahan</label>
                                        <input type="text" x-model="contextForm.urusan" :disabled="!(isAdmin || isWadir)" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-teal-500 focus:outline-none disabled:opacity-60 text-sm font-medium transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">OPD yang Dinilai</label>
                                        <input type="text" x-model="contextForm.opd" :disabled="!(isAdmin || isWadir)" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-teal-500 focus:outline-none disabled:opacity-60 text-sm font-medium transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Sasaran</label>
                                        <div x-show="isAdmin || isWadir" class="text-[10px] text-slate-400 mb-1.5 italic">
                                            Panduan SMART: Specific (jelas), Measureable (terukur), Achievable (dapat diraih), Relevant (sesuai bisnis), Time-Bound (ada batas waktu)
                                        </div>
                                        <textarea x-model="contextForm.sasaran" :disabled="!(isAdmin || isWadir)" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-teal-500 focus:outline-none disabled:opacity-60 text-sm font-medium transition-all" rows="3"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Indikator</label>
                                        <textarea x-model="contextForm.indikator" :disabled="!(isAdmin || isWadir)" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-teal-500 focus:outline-none disabled:opacity-60 text-sm font-medium transition-all" rows="3" placeholder="Gunakan baris baru untuk setiap indikator"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2">
                        <template x-if="isAdmin || isWadir">
                            <button @click="saveContext()" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 text-sm font-bold text-white hover:from-teal-700 hover:to-teal-600 focus:outline-none sm:w-auto transition-all btn-shimmer">
                                Simpan Konteks
                            </button>
                        </template>
                        <button @click="isContextModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-6 py-2.5 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:w-auto transition-all">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Export Excel Modal -->
        <div x-show="isExportModalOpen" class="fixed inset-0 z-[110] overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isExportModalOpen" @click="isExportModalOpen = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="isExportModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-100">
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="file-spreadsheet" class="h-5 w-5 text-emerald-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-900">Export Laporan ke Excel</h3>
                                <div class="mt-4 space-y-4">
                                    <p class="text-sm text-slate-500 mb-2">Tentukan rentang tanggal laporan risiko yang ingin Anda cetak. Biarkan kosong jika ingin mencetak semua data.</p>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tanggal Mulai</label>
                                        <input type="date" x-model="exportStartDate" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Tanggal Akhir</label>
                                        <input type="date" x-model="exportEndDate" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl bg-slate-50/80 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm font-medium">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2">
                        <button @click="exportToExcel(); isExportModalOpen = false;" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-500 text-sm font-bold text-white hover:from-emerald-700 hover:to-emerald-600 focus:outline-none sm:w-auto transition-all">
                            Generate Excel
                        </button>
                        <button @click="isExportModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-6 py-2.5 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:w-auto transition-all">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('partials.announcement_modal')
</div>
@endsection

@push('scripts')
<script>
    function riskApp() {
        return {
            activeTab: 'dashboard',
            isMobileMenuOpen: false,
            riskData: @json($risks),
            availableUnits: @json($units),
            availableSubUnits: @json($subUnits ?? []),
            availableUsers: @json($users ?? []),
            availableCategories: @json($categories),
            searchTerm: '',
            filterUnit: '', 
            filterType: 'all',
            sortBy: 'time',
            filterTriwulan: '',
            startDate: '',
            endDate: '',

            // Dashboard Eksekutif filters (separate from other views)
            dashTriwulan: '',
            isAssessmentModalOpen: false,
            currentAssessment: { id: null, unit: '', triwulan: '', self_answers: {}, answers: {}, self_status: 'Draft', status: 'Draft', auditor_notes: '' },
            filterAsmStatus: '',
            asmSortBy: 'date',
            assessmentQuestions: [
                { id: 1, text: 'Apakah seluruh risiko di unit kerja telah teridentifikasi dengan benar?' },
                { id: 2, text: 'Apakah analisis penyebab dan dampak risiko sudah dilakukan secara mendalam?' },
                { id: 3, text: 'Apakah rencana mitigasi yang disusun sudah relevan dan dapat dilaksanakan?' },
                { id: 4, text: 'Apakah terdapat bukti pendukung (evidence) yang valid untuk setiap mitigasi?' },
                { id: 5, text: 'Apakah monitoring dan evaluasi dilakukan secara berkala terhadap risiko tersebut?' }
            ],
            isSidebarCollapsed: false,
            
            // Route URLs from Laravel
            storeUrl: '{{ route("risk.store") }}',
            baseRiskUrl: '{{ url("/risks") }}',
            
            // User unit info from server
            userUnit: '{{ Auth::user()->unit ?? "" }}',
            userEmail: '{{ Auth::user()->email }}',
            userName: '{{ Auth::user()->name }}',
            userBidang: '{{ Auth::user()->bidang }}',
            isRestricted: {{ Auth::user()->isRestrictedToUnit() ? 'true' : 'false' }},
            isUnitAdmin: {{ Auth::user()->isUnitAdmin() ? 'true' : 'false' }},
            isAdmin: {{ Auth::user()->isAdmin() ? 'true' : 'false' }},
            isWadir: {{ Auth::user()->isWadir() ? 'true' : 'false' }},
            
            // Context Data
            contexts: @json($contexts),
            assessmentsData: @json($assessments),
            isContextModalOpen: false,
            isExportModalOpen: false,
            exportStartDate: '',
            exportEndDate: '',
            contextForm: {
                year: new Date().getFullYear(),
                urusan: 'Wajib - Kesehatan',
                opd: 'RSUD dr. Murjani',
                sasaran: '',
                indikator: '',
                notify_days: 7, 
                custom_days: 14,
                notify_targets: []
            },

            // Announcement Data
            announcements: @json($announcements ?? []),
            myAnnouncements: @json($myAnnouncements ?? []),
            isAnnouncementModalOpen: false,
            announcementForm: {
                id: null,
                title: '',
                message: '',
                duration: '1_week',
                target_units: [],
                target_users: [],
                bidang: ''
            },
            
            resetAnnouncementForm() {
                this.announcementForm = {
                    id: null,
                    title: '',
                    message: '',
                    duration: '1_week',
                    target_units: [],
                    target_users: [],
                    bidang: this.isAdmin ? '' : this.userBidang
                };
            },

            async saveAnnouncement() {
                try {
                    const isEdit = !!this.announcementForm.id;
                    const url = isEdit ? `/announcements/${this.announcementForm.id}` : '/announcements';
                    const method = isEdit ? 'PUT' : 'POST';
                    
                    const response = await fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.announcementForm)
                    });
                    const data = await response.json();
                    if (data.success) {
                        if (isEdit) {
                            const idx = this.myAnnouncements.findIndex(a => a.id === data.announcement.id);
                            if (idx !== -1) this.myAnnouncements[idx] = data.announcement;
                        } else {
                            this.myAnnouncements.unshift(data.announcement);
                        }
                        this.resetAnnouncementForm();
                        this.isAnnouncementModalOpen = false;
                        setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100);
                    } else {
                        this.notify(data.message || 'Gagal menyimpan notifikasi', 'error');
                    }
                } catch (error) { console.error(error); }
            },

            async deleteAnnouncement(id) {
                const result = await Swal.fire({
                    title: 'Hapus notifikasi ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });
                if (!result.isConfirmed) return;
                try {
                    const response = await fetch(`/announcements/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.myAnnouncements = this.myAnnouncements.filter(a => a.id !== id);
                    }
                } catch (error) { console.error(error); }
            },

            async updateAnnouncement(item) {
                try {
                    await fetch(`/announcements/${item.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ is_active: item.is_active })
                    });
                } catch (error) { console.error(error); }
            },

            filteredData() {
                let data = this.riskData.filter(item => {
                    const matchesSearch = (item.risiko && item.risiko.toLowerCase().includes(this.searchTerm.toLowerCase())) ||
                                        (item.kategori && item.kategori.toLowerCase().includes(this.searchTerm.toLowerCase()));
                    
                    let matchesType = true;
                    if (this.isRestricted) {
                        if (this.filterType === 'own') {
                            matchesType = item.unit === this.userUnit;
                        } else if (this.filterType === 'shared') {
                            matchesType = item.unit !== this.userUnit;
                        }
                    } else {
                        // For Admin, filterUnit still works as before or we use filterType
                        if (this.filterUnit !== '') {
                            matchesType = item.unit === this.filterUnit;
                        }
                    }

                    const matchesYear = !this.periodYear || String(item.period_year) === String(this.periodYear);
                    
                    // Triwulan Filter
                    let matchesTriwulan = true;
                    if (this.filterTriwulan) {
                        matchesTriwulan = item.triwulan === this.filterTriwulan;
                    }

                    return matchesSearch && matchesType && matchesYear && matchesTriwulan;
                });

                // Sorting logic
                data.sort((a, b) => {
                    if (this.sortBy === 'score') {
                        return b.awal_skor - a.awal_skor;
                    } else if (this.sortBy === 'status') {
                        const statusOrder = { 'Valid': 0, 'Menunggu': 1, 'Revisi': 2 };
                        const orderA = statusOrder[a.validasi] ?? 9;
                        const orderB = statusOrder[b.validasi] ?? 9;
                        return orderA - orderB;
                    } else { // time
                        return new Date(b.created_at) - new Date(a.created_at);
                    }
                });

                return data;
            },

            // Base data for matrix/register — filters by year + unit only
            getYearlyData() {
                return this.riskData.filter(item => {
                    const matchesYear = !this.periodYear || String(item.period_year) === String(this.periodYear);
                    const matchesUnit = this.filterUnit === '' || item.unit === this.filterUnit || (item.shared_with && item.shared_with.includes(this.filterUnit));
                    return matchesYear && matchesUnit;
                });
            },

            // Dashboard Eksekutif data — adds triwulan filter on top of yearly data
            getDashboardData() {
                return this.getYearlyData().filter(item => {
                    const matchesTriwulan = !this.dashTriwulan || item.triwulan === this.dashTriwulan;
                    return matchesTriwulan;
                });
            },

            getHighRiskCount() {
                return this.getDashboardData().filter(r => ['Tinggi', 'Kritis'].includes(r.awal_level)).length;
            },

            // Matrix Data — adds triwulan filter on top of yearly data
            getMatrixData() {
                return this.getYearlyData().filter(item => {
                    const matchesTriwulan = !this.filterTriwulan || item.triwulan === this.filterTriwulan;
                    return matchesTriwulan;
                });
            },

            getMatrixCount(p, d) {
                return this.getMatrixData().filter(r => r.awal_p === p && r.awal_d === d).length;
            },

            // Edit Risk State
            isEditMode: false,
            isAddRiskModalOpen: false,
            editingRiskId: null,
            
            periodYear: new Date().getFullYear(),
            globalPeriod: '',
            
            // Notification State
            visibleContexts: [],
            activeContextIndex: 0,
            contextInterval: null,
            
            startContextSlider() {
                if (this.contextInterval) clearInterval(this.contextInterval);
                this.contextInterval = setInterval(() => {
                    if (this.visibleContexts.length > 1) {
                        this.activeContextIndex = (this.activeContextIndex + 1) % this.visibleContexts.length;
                    }
                }, 5000);
            },
            
            dismissNotification(id) {
                localStorage.setItem('dismissed_smart_' + id, 'true');
                this.visibleContexts = this.visibleContexts.filter(c => c.id !== id);
            },

            newRisk: {
                risiko: '', dampakDeskripsi: '', kategori: '', unit: '', penyebab: '',
                awalD: 1, awalP: 1, mitigations: [{ treatment: '', status: 'Not Started', evidence_link: '' }], 
                evaluasi: 'Dibagi',
                shared_with: [],
                escalated_to: '',
                residualD: 1, residualP: 1, pj: '', status: 'Not Started',
                is_active: true,
                tanggal: new Date().toISOString().split('T')[0],
                triwulan: 'Triwulan ' + Math.ceil((new Date().getMonth() + 1) / 3),
                sub_unit: '',
                validator: ''
            },
            
            resetNewRisk() {
                const defaultCat = this.availableCategories.length > 0 ? this.availableCategories[0].name : '';
                const currentTriwulan = 'Triwulan ' + Math.ceil((new Date().getMonth() + 1) / 3);
                
                this.newRisk = { 
                    risiko: '', 
                    dampakDeskripsi: '', 
                    kategori: defaultCat, 
                    unit: this.isRestricted ? this.userUnit : '', 
                    sub_unit: '', 
                    validator: '', 
                    penyebab: '', 
                    awalD: 1, 
                    awalP: 1, 
                    mitigations: [{ treatment: '', status: 'Not Started', evidence_link: '' }], 
                    evaluasi: 'Dibagi', 
                    shared_with: [], 
                    escalated_to: '', 
                    residualD: 1, 
                    residualP: 1, 
                    pj: '', 
                    status: 'Not Started', 
                    is_active: true, 
                    tanggal: new Date().toISOString().split('T')[0],
                    triwulan: currentTriwulan
                };
                this.isEditMode = false;
                this.editingRiskId = null;
            },

            // Utility for themed notifications
            notify(title, icon = 'success', text = '') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                Toast.fire({ icon, title, text });
            },

            showNewSubUnitInput: false,
            newSubUnitName: '',

            catChartInstance: null,
            statChartInstance: null,

            init() {
                this.updatePeriod();
                
                if (this.isRestricted && this.userUnit) {
                    this.newRisk.unit = this.userUnit;
                    this.filterUnit = this.userUnit;
                }
                
                if (this.availableCategories.length > 0) {
                    this.newRisk.kategori = this.availableCategories[0].name;
                }
                
                this.$watch('activeTab', (value) => {
                    if (value === 'dashboard') setTimeout(() => this.updateCharts(), 100);
                    setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 50);
                });

                this.$watch('visibleContexts', () => {
                    setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 50);
                });

                this.$watch('activeContextIndex', () => {
                    setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 50);
                });

                if (this.announcements && this.announcements.length > 0) {
                    this.announcements.forEach(ctx => {
                        if (this.visibleContexts.length >= 10) return; // UI limit
                        
                        const isDismissed = localStorage.getItem('dismissed_smart_' + ctx.id);
                        if (!isDismissed) {
                            let show = true;
                            
                            // Check expiry (Strategic Context or Regular Announcement)
                            const expiryDate = ctx.notify_until || ctx.expires_at;
                            if (expiryDate && new Date() >= new Date(expiryDate)) {
                                show = false;
                            }
                            
                            if (show) {
                                if (ctx.is_context) {
                                    if (!ctx.notify_targets || ctx.notify_targets.length === 0 || ctx.notify_targets.includes(this.userName)) {
                                        this.visibleContexts.push(ctx);
                                    }
                                } else {
                                    // Only show URGENT announcements in the top slider (expires_at is null / 'Selamanya')
                                    if (!ctx.expires_at) {
                                        this.visibleContexts.push(ctx);
                                    }
                                }
                            }
                        }
                    });
                    
                    // Set contextForm for UI state
                    if (this.announcements.length > 0) {
                        this.contextForm = { ...this.announcements[0] };
                        if (!this.contextForm.notify_targets) this.contextForm.notify_targets = [];
                    }
                    
                    this.startContextSlider();
                }

                if (!this.announcements.length && this.isAdmin) {
                    this.isContextModalOpen = true;
                }

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('notify') === '1' && this.isAdmin) {
                    this.isContextModalOpen = true;
                }

                this.$watch('riskData', () => {
                    if (this.activeTab === 'dashboard') this.updateCharts();
                    setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 50);
                });
                this.$watch('dashTriwulan', () => {
                    if (this.activeTab === 'dashboard') this.updateCharts();
                });
                this.$watch('dashStartDate', () => {
                    if (this.activeTab === 'dashboard') this.updateCharts();
                });
                this.$watch('dashEndDate', () => {
                    if (this.activeTab === 'dashboard') this.updateCharts();
                });
                this.$watch('filterUnit', () => {
                    if (this.activeTab === 'dashboard') this.updateCharts();
                });
                setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100);
                if (this.activeTab === 'dashboard') setTimeout(() => this.updateCharts(), 100);
            },

            setActiveTab(tab) { this.activeTab = tab; },
            
            getHeaderTitle() {
                if (this.activeTab === 'dashboard') return 'Dashboard Eksekutif';
                if (this.activeTab === 'register') return 'Daftar Risiko & Validasi Unit';
                if (this.activeTab === 'matrix') return 'Analisis Matriks Risiko';
                if (this.activeTab === 'controls') return 'Pengendalian & Evaluasi';
                if (this.activeTab === 'assessment') return 'Hasil Assessment Auditor';
                return '';
            },

            updatePeriod() {
                this.globalPeriod = this.periodYear.toString();
            },

            addMitigation() {
                this.newRisk.mitigations.push({ treatment: '', status: 'Not Started', evidence_link: '' });
            },

            removeMitigation(index) {
                this.newRisk.mitigations.splice(index, 1);
            },

            calculateLevel(score) {
                if (score >= 15) return 'Kritis';
                if (score >= 10) return 'Tinggi';
                if (score >= 5) return 'Sedang';
                return 'Rendah';
            },

            getRiskColor(level) {
                const colors = { 'Kritis': 'bg-red-600 text-white', 'Tinggi': 'bg-orange-500 text-white', 'Sedang': 'bg-yellow-400 text-black', 'Rendah': 'bg-green-500 text-white' };
                return colors[level] || 'bg-slate-200 text-slate-800';
            },

            getStatusColor(status) {
                const colors = { 'Completed': 'bg-emerald-100 text-emerald-800 border-emerald-200', 'In-Progress': 'bg-blue-100 text-blue-800 border-blue-200', 'Not Started': 'bg-gray-100 text-gray-800 border-gray-200' };
                return colors[status] || 'bg-gray-100 text-gray-800';
            },

            getValidationColor(status) {
                const colors = { 'Valid': 'bg-teal-100 text-teal-800 border-teal-200', 'Revisi': 'bg-red-100 text-red-800 border-red-200' };
                return colors[status] || 'bg-slate-100 text-slate-600 border-slate-200';
            },

            async addRisk() {
                if (!this.newRisk.risiko) { this.notify("Uraian Risiko harus diisi!", "warning"); return; }
                if (!this.newRisk.unit) { this.notify("Nama Unit harus diisi!", "warning"); return; }
                
                // Validate mitigations
                for (let i = 0; i < this.newRisk.mitigations.length; i++) {
                    const mit = this.newRisk.mitigations[i];
                    if (!mit.treatment) { this.notify(`Uraian Tindakan pada Mitigasi #${i+1} harus diisi!`, "warning"); return; }
                    if (mit.status === 'Completed' && !mit.evidence_link) { 
                        this.notify(`Link G-Drive Bukti pada Mitigasi #${i+1} wajib diisi sebagai bukti penyelesaian.`, "warning"); 
                        return; 
                    }
                }

                const formData = {
                    unit: this.newRisk.unit, kategori: this.newRisk.kategori, risiko: this.newRisk.risiko,
                    dampak_deskripsi: this.newRisk.dampakDeskripsi, penyebab: this.newRisk.penyebab,
                    awal_d: parseInt(this.newRisk.awalD) || 1, awal_p: parseInt(this.newRisk.awalP) || 1,
                    mitigations: this.newRisk.mitigations, evaluasi: this.newRisk.evaluasi,
                    shared_with: this.newRisk.shared_with,
                    escalated_to: this.newRisk.escalated_to,
                    residual_d: parseInt(this.newRisk.residualD) || 1, residual_p: parseInt(this.newRisk.residualP) || 1,
                    pj: this.newRisk.pj, status: this.newRisk.status,
                    is_active: this.newRisk.is_active,
                    tanggal: this.newRisk.tanggal,
                    triwulan: this.globalPeriod || '',
                    period_year: this.periodYear,
                    sub_unit: this.newRisk.sub_unit,
                    validator: this.newRisk.validator
                };

                const url = this.isEditMode ? `${this.baseRiskUrl}/${this.editingRiskId}` : this.storeUrl;
                const method = this.isEditMode ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: { 
                            'Content-Type': 'application/json', 
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify(formData)
                    });

                    if (!response.ok && response.status !== 422) {
                        const errorText = await response.text();
                        console.error('Server Error:', errorText);
                        this.notify(`Terjadi kesalahan pada server (Status: ${response.status}). Hubungi Admin.`, "error");
                        return;
                    }

                    const data = await response.json();
                    if (data.success) {
                        // Dynamically update availableSubUnits if a new one was created
                        if (data.sub_unit) {
                            const exists = this.availableSubUnits.some(s => s.name === data.sub_unit.name && s.unit_name === data.sub_unit.unit_name);
                            if (!exists) {
                                this.availableSubUnits.push(data.sub_unit);
                            }
                        }

                        if (this.isEditMode) {
                            this.riskData = this.riskData.map(item => item.id === this.editingRiskId ? data.risk : item);
                            this.cancelEdit();
                        } else {
                            this.riskData.unshift(data.risk);
                            this.isAddRiskModalOpen = false;
                            this.resetNewRisk();
                        }
                        this.notify(data.message || 'Data berhasil disimpan!');
                    } else {
                        // Handle validation errors if present
                        let errorMsg = data.message || 'Terjadi kesalahan saat menyimpan data.';
                        if (data.errors) {
                            errorMsg += '\n\nDetail:\n' + Object.values(data.errors).flat().join('\n');
                        }
                        this.notify(errorMsg, "error");
                    }
                } catch (error) { 
                    console.error('Error:', error);
                    this.notify("Gagal menghubungi server. Pastikan koneksi internet aktif.", "error");
                }
            },

            editRisk(risk) {
                this.isEditMode = true;
                this.editingRiskId = risk.id;
                this.isAddRiskModalOpen = true;
                
                this.newRisk = {
                    risiko: risk.risiko,
                    dampakDeskripsi: risk.dampak_deskripsi,
                    kategori: risk.kategori,
                    unit: risk.unit,
                    penyebab: risk.penyebab,
                    awalD: risk.awal_d,
                    awalP: risk.awal_p,
                    mitigations: risk.mitigations && risk.mitigations.length > 0 ? risk.mitigations.map(m => ({ treatment: m.treatment, status: m.status, id: m.id, evidence_link: m.evidence_link || '' })) : [{ treatment: '', status: 'Not Started', evidence_link: '' }],
                    evaluasi: risk.evaluasi || 'Diturunkan',
                    shared_with: Array.isArray(risk.shared_with) ? risk.shared_with : [],
                    escalated_to: risk.escalated_to || '',
                    residualD: risk.residual_d,
                    residualP: risk.residual_p,
                    pj: risk.pj,
                    status: risk.status,
                    is_active: risk.is_active,
                    tanggal: risk.tanggal || (risk.created_at ? risk.created_at.split('T')[0] : new Date().toISOString().split('T')[0]),
                    triwulan: risk.triwulan || ('Triwulan ' + Math.ceil((new Date().getMonth() + 1) / 3)),
                    sub_unit: risk.sub_unit || '',
                    validator: risk.validator || ''
                };
            },

            cancelEdit() {
                this.resetNewRisk();
                this.isAddRiskModalOpen = false;
            },

            async saveContext() {
                try {
                    const dataToSave = { ...this.contextForm };
                    if (dataToSave.notify_days === 'custom') {
                        dataToSave.notify_days = parseInt(this.contextForm.custom_days) || 0;
                    }
                    if (this.isWadir) {
                        dataToSave.bidang = this.userBidang;
                    }

                    const response = await fetch('{{ route("risk.context.store") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(dataToSave)
                    });
                    const data = await response.json();
                    if (data.success) {
                        // Refresh contexts array
                        this.contexts = this.contexts.map(c => c.id === data.context.id ? data.context : c);
                        if (!this.contexts.find(c => c.id === data.context.id)) {
                            this.contexts.push(data.context);
                        }
                        this.isContextModalOpen = false;
                        this.notify('Konteks berhasil disimpan');
                        setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100);
                    }
                } catch (error) { console.error('Error:', error); }
            },

            async deleteRisk(id) {
                const result = await Swal.fire({
                    title: 'Hapus data risiko?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`${this.baseRiskUrl}/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.riskData = this.riskData.filter(item => item.id !== id);
                            this.notify("Data berhasil dihapus");
                        } else {
                            this.notify(data.message || "Gagal menghapus data", "error");
                        }
                    } catch (error) { 
                        console.error('Error:', error);
                        this.notify("Terjadi kesalahan sistem", "error");
                    }
                }
            },

            async updateRisk(risk) {
                try {
                    const response = await fetch(`${this.baseRiskUrl}/${risk.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ 
                            validasi: risk.validasi, 
                            validator: risk.validator,
                            is_active: risk.is_active 
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.riskData = this.riskData.map(item => item.id === risk.id ? data.risk : item);
                        // Optional: Show toast or small notification instead of alert for seamless experience
                        console.log('Update success');
                    } else {
                        this.notify(data.message || 'Gagal memperbarui data', "error");
                    }
                } catch (error) { 
                    console.error('Error:', error);
                    this.notify('Terjadi kesalahan koneksi ke server.', "error");
                }
            },

            async updateRiskStatus(risk) {
                try {
                    const response = await fetch(`${this.baseRiskUrl}/${risk.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ status: risk.status })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.riskData = this.riskData.map(item => item.id === risk.id ? data.risk : item);
                    } else {
                        this.notify(data.message || 'Gagal memperbarui status', "error");
                    }
                } catch (error) { 
                    console.error('Error:', error);
                    this.notify('Terjadi kesalahan koneksi ke server.', "error");
                }
            },

            filteredAssessments() {
                let data = [...this.assessmentsData];
                if (this.filterAsmStatus) data = data.filter(a => a.status === this.filterAsmStatus || a.self_status === this.filterAsmStatus);
                
                data.sort((a, b) => {
                    if (this.asmSortBy === 'date') return new Date(b.created_at) - new Date(a.created_at);
                    if (this.asmSortBy === 'score') return (b.total_score || 0) - (a.total_score || 0);
                    return 0;
                });
                return data;
            },

            openAssessment(asm = null) {
                if (asm) {
                    this.currentAssessment = { ...asm, self_answers: asm.self_answers || {}, answers: asm.answers || {} };
                } else {
                    this.currentAssessment = { 
                        id: null, 
                        unit: this.userUnit, 
                        triwulan: this.filterTriwulan || 'Triwulan 1', 
                        self_answers: {}, 
                        answers: {}, 
                        self_status: 'Draft', 
                        status: 'Draft', 
                        auditor_notes: '',
                        period_year: this.periodYear
                    };
                }
                this.isAssessmentModalOpen = true;
            },

            async confirmAssessment(target, status) {
                const text = status === 'Submitted' 
                    ? 'Kirim assessment ke auditor? Data tidak dapat diubah setelah dikirim.' 
                    : 'Selesaikan audit? Hasil akan dipublikasikan ke unit.';
                
                const result = await Swal.fire({
                    title: 'Konfirmasi',
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: status === 'Submitted' ? '#4f46e5' : '#059669',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    this.saveAssessment(target, status);
                }
            },

            async saveAssessment(target = 'self', status = 'Draft') {
                const isAuditor = {{ Auth::user()->isAuditor() ? 'true' : 'false' }};
                const payload = {
                    unit: this.currentAssessment.unit,
                    period_year: this.currentAssessment.period_year || this.periodYear,
                    triwulan: this.currentAssessment.triwulan,
                    audit_date: new Date().toISOString().split('T')[0]
                };

                if (target === 'self') {
                    payload.self_answers = this.currentAssessment.self_answers;
                    payload.self_status = status;
                } else {
                    payload.answers = this.currentAssessment.answers;
                    payload.status = status;
                    payload.auditor_notes = this.currentAssessment.auditor_notes;
                }

                try {
                    const response = await fetch('{{ route("auditor.assessment.store") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();
                    if (data.success) {
                        // Refresh data
                        const idx = this.assessmentsData.findIndex(a => a.unit === data.assessment.unit && a.triwulan === data.assessment.triwulan);
                        if (idx !== -1) this.assessmentsData[idx] = data.assessment;
                        else this.assessmentsData.unshift(data.assessment);
                        
                        this.isAssessmentModalOpen = false;
                        this.notify(data.message);
                    }
                } catch (error) { console.error(error); }
            },

            get userUnitAssessment() {
                if (this.isUnitAdmin && this.assessmentsData.length > 0) {
                    return this.assessmentsData.find(a => a.unit === this.userUnit);
                }
                return null;
            },

            updateCharts() {
                if (typeof Chart === 'undefined') return;
                const canvasCat = document.getElementById('categoryChart');
                const canvasStat = document.getElementById('statusChart');
                if (!canvasCat || !canvasStat) return;

                const yearlyRisks = this.getYearlyData();
                const counts = {};
                yearlyRisks.forEach(d => { counts[d.kategori] = (counts[d.kategori] || 0) + 1; });
                const catLabels = Object.keys(counts);
                const catValues = Object.values(counts);

                const completed = yearlyRisks.reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === 'Completed').length : 0), 0);
                const active = yearlyRisks.reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === 'In-Progress').length : 0), 0);
                const notStarted = yearlyRisks.reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === 'Not Started').length : 0), 0);

                if (this.catChartInstance) this.catChartInstance.destroy();
                this.catChartInstance = new Chart(canvasCat.getContext('2d'), {
                    type: 'bar',
                    data: { 
                        labels: catLabels.length > 0 ? catLabels : ['Belum ada data'], 
                        datasets: [{ 
                            label: 'Jumlah Risiko', 
                            data: catValues.length > 0 ? catValues : [0], 
                            backgroundColor: 'rgba(13, 148, 136, 0.8)', 
                            borderRadius: 8, 
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }] 
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        plugins: { legend: { display: false } }, 
                        scales: { 
                            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.04)' } }, 
                            x: { offset: true, grid: { display: false }, ticks: { font: { size: 10 } } } 
                        } 
                    }
                });

                if (this.statChartInstance) this.statChartInstance.destroy();
                this.statChartInstance = new Chart(canvasStat.getContext('2d'), {
                    type: 'doughnut',
                    data: { labels: ['Aksi Selesai', 'Aksi Berjalan', 'Aksi Belum Mulai'], datasets: [{ data: (completed + active + notStarted) > 0 ? [completed, active, notStarted] : [0, 0, 1], backgroundColor: (completed + active + notStarted) > 0 ? ['#10b981', '#3b82f6', '#94a3b8'] : ['#e2e8f0', '#e2e8f0', '#e2e8f0'], borderWidth: 0, hoverOffset: 8 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 11, weight: '500' } } } } }
                });
            },

            async exportToExcel() {
                let dataToExport = this.riskData;
                
                if (this.exportStartDate) {
                    dataToExport = dataToExport.filter(r => {
                        const rDate = r.tanggal || (r.created_at ? r.created_at.split('T')[0] : null);
                        return rDate && rDate >= this.exportStartDate;
                    });
                }
                
                if (this.exportEndDate) {
                    dataToExport = dataToExport.filter(r => {
                        const rDate = r.tanggal || (r.created_at ? r.created_at.split('T')[0] : null);
                        return rDate && rDate <= this.exportEndDate;
                    });
                }
                
                if (dataToExport.length === 0) { this.notify("Belum ada data untuk rentang tanggal tersebut!", "warning"); return; }
                
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'SI ERMa - RSUD dr. Murjani';
                workbook.created = new Date();

                // ============ SHEET 1: DASHBOARD ============
                const dashSheet = workbook.addWorksheet('Dashboard');
                dashSheet.mergeCells('A1:E1');
                dashSheet.getCell('A1').value = 'DASHBOARD SI ERMa';
                dashSheet.getCell('A1').font = { size: 16, bold: true, color: { argb: 'FF0D9488' } };
                dashSheet.getCell('A2').value = `Periode: ${this.globalPeriod}`;
                dashSheet.getCell('A2').font = { italic: true, color: { argb: 'FF64748B' } };
                dashSheet.getCell('A4').value = 'RINGKASAN STATISTIK';
                dashSheet.getCell('A4').font = { bold: true, size: 12 };
                
                const completed = dataToExport.reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === 'Completed').length : 0), 0);
                const inProgress = dataToExport.reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === 'In-Progress').length : 0), 0);
                const notStarted = dataToExport.reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === 'Not Started').length : 0), 0);
                const highRisk = dataToExport.filter(r => r.residual_level === 'Tinggi' || r.residual_level === 'Kritis').length;
                
                const stats = [['Total Risiko', dataToExport.length],['Risiko Tinggi/Kritis', highRisk],['Aksi Mitigasi Selesai', completed],['Aksi Mitigasi Berjalan', inProgress],['Belum Dimulai', notStarted]];
                stats.forEach((stat, index) => { dashSheet.getCell(`A${6 + index}`).value = stat[0]; dashSheet.getCell(`B${6 + index}`).value = stat[1]; dashSheet.getCell(`B${6 + index}`).font = { bold: true }; });
                
                dashSheet.getCell('A13').value = 'DISTRIBUSI KATEGORI';
                dashSheet.getCell('A13').font = { bold: true, size: 12 };
                const categoryCounts = {};
                dataToExport.forEach(d => { categoryCounts[d.kategori] = (categoryCounts[d.kategori] || 0) + 1; });
                let catRow = 14;
                Object.entries(categoryCounts).forEach(([cat, count]) => { dashSheet.getCell(`A${catRow}`).value = cat; dashSheet.getCell(`B${catRow}`).value = count; catRow++; });
                dashSheet.getColumn(1).width = 25;
                dashSheet.getColumn(2).width = 15;

                // ============ SHEET 2: MITIGASI ============
                const ctrlSheet = workbook.addWorksheet('Mitigasi Risiko');
                ctrlSheet.mergeCells('A1:F1');
                ctrlSheet.getCell('A1').value = 'GAP ANALYSIS & MITIGASI RISIKO';
                ctrlSheet.getCell('A1').font = { size: 14, bold: true };
                ctrlSheet.getCell('A2').value = 'Status aksi mitigasi untuk risiko aktif';
                ctrlSheet.getCell('A2').font = { italic: true, color: { argb: 'FF64748B' } };
                ctrlSheet.getRow(4).values = ['Kode', 'Unit', 'Uraian Risiko', 'Aksi Mitigasi', 'Target Periode', 'Status Aksi', 'PJ'];
                ctrlSheet.getRow(4).font = { bold: true, color: { argb: 'FFFFFFFF' } };
                ctrlSheet.getRow(4).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF3B82F6' } };
                dataToExport.filter(r => r.status !== 'Completed').forEach(item => {
                    const mitigationsText = item.mitigations ? item.mitigations.map(m => m.treatment).join('\n') : '-';
                    const mitigationStatus = item.mitigations ? item.mitigations.map(m => m.status).join('\n') : '-';
                    ctrlSheet.addRow([item.kode, item.unit, item.risiko, mitigationsText, item.triwulan || '-', mitigationStatus, item.pj || '-']);
                });
                [10,15,40,40,15,15,20].forEach((w, i) => { ctrlSheet.getColumn(i+1).width = w; });

                // ============ SHEET 3: RISK REGISTER ============
                const regSheet = workbook.addWorksheet('Risk Register');
                regSheet.mergeCells('A1:S1');
                regSheet.getCell('A1').value = 'DAFTAR RISIKO TERINTEGRASI';
                regSheet.getCell('A1').font = { size: 14, bold: true };
                regSheet.getCell('A2').value = `Periode: ${this.globalPeriod} | Diekspor: ${new Date().toLocaleDateString('id-ID')}`;
                const headers = ['Kode', 'Unit', 'Kategori', 'Uraian Risiko', 'Penyebab', 'Deskripsi Dampak', 'Dampak (D)', 'Mitigasi Risiko (Aksi)', 'Status Aksi', 'Evaluasi', 'Probabilitas (P)', 'Skor Awal', 'Level Awal', 'Dampak Sisa', 'Prob Sisa', 'Skor Sisa', 'Level Sisa', 'Penanggung Jawab', 'Status Risiko', 'Validasi', 'Validator', 'Tahun'];
                regSheet.getRow(4).values = headers;
                regSheet.getRow(4).font = { bold: true, color: { argb: 'FFFFFFFF' } };
                regSheet.getRow(4).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0D9488' } };
                regSheet.getRow(4).alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
                regSheet.getRow(4).height = 30;
                
                dataToExport.forEach((item) => {
                    const mitigationsText = item.mitigations ? item.mitigations.map(m => m.treatment).join('\n') : '';
                    const mitigationStatus = item.mitigations ? item.mitigations.map(m => m.status).join('\n') : '';
                    const row = regSheet.addRow([item.kode, item.unit, item.kategori, item.risiko, item.penyebab, item.dampak_deskripsi, item.awal_d, mitigationsText, mitigationStatus, item.evaluasi, item.awal_p, item.awal_skor, item.awal_level, item.residual_d, item.residual_p, item.residual_skor, item.residual_level, item.pj, item.status, item.validasi, item.validator || '', item.period_year]);
                    const levelColors = { 'Kritis': 'FFDC2626', 'Tinggi': 'FFF97316', 'Sedang': 'FFFACC15', 'Rendah': 'FF22C55E' };
                    row.getCell(13).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: levelColors[item.awal_level] || 'FFE2E8F0' } };
                    row.getCell(13).font = { bold: true, color: { argb: item.awal_level === 'Sedang' ? 'FF000000' : 'FFFFFFFF' } };
                    row.getCell(17).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: levelColors[item.residual_level] || 'FFE2E8F0' } };
                    row.getCell(17).font = { bold: true, color: { argb: item.residual_level === 'Sedang' ? 'FF000000' : 'FFFFFFFF' } };
                    row.alignment = { vertical: 'top', wrapText: true };
                });
                
                [10,15,15,35,25,25,8,35,15,15,8,8,10,8,8,8,10,20,12,12,15,10].forEach((w, i) => { regSheet.getColumn(i+1).width = w; });

                const lastDataRow = regSheet.lastRow.number;
                const signatureStartRow = lastDataRow + 4;
                regSheet.addRow([]); regSheet.addRow([]); regSheet.addRow([]);
                regSheet.mergeCells(signatureStartRow, 8, signatureStartRow, 12);
                regSheet.getCell(signatureStartRow, 8).value = 'Sampit, ' + new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                regSheet.getCell(signatureStartRow, 8).alignment = { horizontal: 'center' };
                regSheet.mergeCells(signatureStartRow + 1, 8, signatureStartRow + 1, 12);
                regSheet.getCell(signatureStartRow + 1, 8).value = 'Risk Owner (Pemilik Risiko)';
                regSheet.getCell(signatureStartRow + 1, 8).font = { bold: true, size: 11 };
                regSheet.getCell(signatureStartRow + 1, 8).alignment = { horizontal: 'center' };
                regSheet.mergeCells(signatureStartRow + 5, 8, signatureStartRow + 5, 12);
                regSheet.getCell(signatureStartRow + 5, 8).value = '________________________';
                regSheet.getCell(signatureStartRow + 5, 8).alignment = { horizontal: 'center' };
                regSheet.mergeCells(signatureStartRow + 6, 8, signatureStartRow + 6, 12);
                regSheet.getCell(signatureStartRow + 6, 8).value = '(                                        )';
                regSheet.getCell(signatureStartRow + 6, 8).alignment = { horizontal: 'center' };

                // ============ SHEET 4: MATRIX ============
                const matSheet = workbook.addWorksheet('Matriks Risiko');
                matSheet.mergeCells('A1:G1');
                matSheet.getCell('A1').value = 'MATRIKS ANALISIS RISIKO (HEATMAP)';
                matSheet.getCell('A1').font = { size: 14, bold: true };
                matSheet.getCell('B3').value = 'DAMPAK →';
                matSheet.getCell('B3').font = { bold: true };
                for(let i = 1; i <= 5; i++) { matSheet.getCell(3, i + 2).value = i; matSheet.getCell(3, i + 2).font = { bold: true }; matSheet.getCell(3, i + 2).alignment = { horizontal: 'center' }; }
                matSheet.getCell('A4').value = 'PROBABILITAS';
                matSheet.getCell('A4').font = { bold: true };
                matSheet.getCell('A4').alignment = { textRotation: 90, vertical: 'middle' };
                matSheet.mergeCells('A4:A8');
                const levelColors = { 'Rendah': 'FF22C55E', 'Sedang': 'FFFACC15', 'Tinggi': 'FFF97316', 'Kritis': 'FFDC2626' };
                for(let p = 5; p >= 1; p--) {
                    const rowNum = 9 - p;
                    matSheet.getCell(rowNum, 2).value = p; matSheet.getCell(rowNum, 2).font = { bold: true }; matSheet.getCell(rowNum, 2).alignment = { horizontal: 'center' };
                    for(let d = 1; d <= 5; d++) {
                        const count = dataToExport.filter(r => r.awal_p === p && r.awal_d === d).length;
                        const score = p * d;
                        const level = this.calculateLevel(score);
                        const cell = matSheet.getCell(rowNum, d + 2);
                        cell.value = count > 0 ? count : '';
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: levelColors[level] } };
                        cell.font = { bold: true, color: { argb: level === 'Sedang' ? 'FF000000' : 'FFFFFFFF' }, size: 14 };
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        cell.border = { top: {style:'thin'}, left: {style:'thin'}, bottom: {style:'thin'}, right: {style:'thin'} };
                    }
                }
                matSheet.getCell('A11').value = 'KETERANGAN:';
                matSheet.getCell('A11').font = { bold: true };
                const legend = [['Rendah', '1-4', 'FF22C55E'], ['Sedang', '5-9', 'FFFACC15'], ['Tinggi', '10-14', 'FFF97316'], ['Kritis', '15-25', 'FFDC2626']];
                legend.forEach((item, idx) => { matSheet.getCell(12 + idx, 1).value = item[0]; matSheet.getCell(12 + idx, 2).value = `(Skor ${item[1]})`; matSheet.getCell(12 + idx, 1).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: item[2] } }; matSheet.getCell(12 + idx, 1).font = { color: { argb: item[0] === 'Sedang' ? 'FF000000' : 'FFFFFFFF' } }; });
                for(let i = 1; i <= 7; i++) matSheet.getColumn(i).width = 12;

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                saveAs(blob, `Risk_Register_RSUD_${this.periodDate ? new Date(this.periodDate).getFullYear() : new Date().getFullYear()}_${new Date().toISOString().slice(0,10)}.xlsx`);
            }
        }
    }
</script>
@endpush
