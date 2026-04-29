<!-- 1. DASHBOARD VIEW -->
<div x-show="activeTab === 'dashboard'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="flex flex-col lg:flex-row gap-6">
    
    <!-- Main Dashboard Content -->
    <div class="flex-1">
        <!-- Context & Strategy Slider -->
    <template x-if="visibleContexts.length > 0">
        <div class="mb-8 relative group">
            <div class="overflow-hidden rounded-3xl">
                <div class="flex transition-transform duration-700 ease-in-out" 
                    :style="`transform: translateX(-${activeContextIndex * 100}%)`">
                    <template x-for="(ctx, index) in visibleContexts" :key="ctx.id">
                        <div class="w-full flex-shrink-0 grid grid-cols-1 md:grid-cols-2 gap-6 p-1">
                            <!-- Left Card: Sasaran or Title -->
                            <div class="bg-gradient-to-br from-teal-50/80 to-white p-6 rounded-2xl border border-teal-100/60 shadow-sm relative overflow-hidden card-hover">
                                <div class="absolute top-0 right-0 p-3 opacity-[0.06]">
                                    <i :data-lucide="ctx.is_context ? 'target' : 'bell'" class="w-20 h-20 text-teal-600"></i>
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-3 border-b border-teal-100/60 pb-2">
                                        <div class="flex items-center gap-2 text-teal-700 font-bold">
                                            <div class="w-7 h-7 bg-teal-600 rounded-lg flex items-center justify-center">
                                                <i :data-lucide="ctx.is_context ? 'crosshair' : 'megaphone'" class="w-3.5 h-3.5 text-white"></i>
                                            </div>
                                            <span class="text-xs uppercase tracking-wider" x-text="ctx.is_context ? 'Sasaran Strategis' : 'Notifikasi Pengumuman'"></span>
                                        </div>
                                        <span class="text-[9px] font-black px-2 py-0.5 bg-teal-600 text-white rounded-md uppercase tracking-tighter" x-text="ctx.is_context ? (ctx.bidang ? ctx.bidang : 'DIREKTUR') : (ctx.bidang ? ctx.bidang : 'GLOBAL')"></span>
                                    </div>
                                    <h4 class="text-xs font-black text-slate-800 mb-1 uppercase" x-show="!ctx.is_context" x-text="ctx.title"></h4>
                                    <p class="text-sm text-slate-700 font-medium leading-relaxed" :class="ctx.is_context ? 'italic' : ''" x-text="ctx.is_context ? ctx.sasaran : ctx.message"></p>
                                </div>
                            </div>
                            <!-- Right Card: Indikator or Message/Details -->
                            <div class="bg-gradient-to-br from-indigo-50/80 to-white p-6 rounded-2xl border border-indigo-100/60 shadow-sm relative overflow-hidden card-hover">
                                <div class="absolute top-0 right-0 p-3 opacity-[0.06]">
                                    <i :data-lucide="ctx.is_context ? 'list-checks' : 'info'" class="w-20 h-20 text-indigo-600"></i>
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-3 border-b border-indigo-100/60 pb-2">
                                        <div class="flex items-center gap-2 text-indigo-700 font-bold">
                                            <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                                                <i :data-lucide="ctx.is_context ? 'activity' : 'calendar'" class="w-3.5 h-3.5 text-white"></i>
                                            </div>
                                            <span class="text-xs uppercase tracking-wider" x-text="ctx.is_context ? 'Indikator Kinerja' : 'Waktu Terbit'"></span>
                                        </div>
                                        <button @click="dismissNotification(ctx.id)" class="text-slate-300 hover:text-slate-500 transition-colors">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                    <div x-show="ctx.is_context" class="text-sm text-slate-700 font-medium leading-relaxed" style="white-space: pre-line" x-text="ctx.indikator"></div>
                                    <div x-show="!ctx.is_context" class="flex flex-col h-full justify-center">
                                        <div class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-widest">Informasi Tambahan</div>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-2 py-1 bg-white border border-slate-100 rounded-lg text-[10px] font-bold text-slate-600 flex items-center gap-1.5">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                Diterbitkan: <span x-text="new Date(ctx.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})"></span>
                                            </span>
                                            <template x-if="ctx.user">
                                                <span class="px-2 py-1 bg-white border border-slate-100 rounded-lg text-[10px] font-bold text-slate-600 flex items-center gap-1.5">
                                                    <i data-lucide="user" class="w-3 h-3"></i>
                                                    Oleh: <span x-text="ctx.user.name"></span>
                                                </span>
                                            </template>
                                        </div>
                                        <p class="mt-4 text-[11px] text-slate-400 italic">Klik tombol (x) di pojok kanan untuk menyembunyikan notifikasi ini dari dashboard Anda.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Slider Dots -->
            <div x-show="visibleContexts.length > 1" class="flex justify-center gap-1.5 mt-4">
                <template x-for="(ctx, index) in visibleContexts" :key="index">
                    <button @click="activeContextIndex = index" 
                        class="h-1.5 rounded-full transition-all duration-300"
                        :class="activeContextIndex === index ? 'w-8 bg-teal-500' : 'w-2 bg-slate-200 hover:bg-slate-300'"></button>
                </template>
            </div>
        </div>
    </template>

    <!-- Header with Action -->
        <div class="flex justify-end items-center mb-6">
            <div class="flex gap-3">
                <template x-if="isAdmin || isUnitAdmin">
                    <button @click="resetAnnouncementForm(); isAnnouncementModalOpen = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                        <i data-lucide="bell-plus" class="w-3.5 h-3.5"></i>
                        MANAGE NOTIFIKASI
                    </button>
                </template>
                <button @click="isExportModalOpen = true" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg shadow-teal-200 transition-all active:scale-95">
                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                    EXPORT LAPORAN
                </button>
            </div>
        </div>



    <!-- Audit Feedback Alert (If Exists) -->
    <template x-if="userUnitAssessment">
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 shadow-sm relative overflow-hidden animate-fade-in-up">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500 rounded-full opacity-5 blur-2xl"></div>
            <div class="flex items-start gap-4 relative z-10">
                <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center flex-shrink-0 border border-blue-100">
                    <i data-lucide="clipboard-check" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-800 mb-1 tracking-tight">Laporan Audit Kepatuhan Unit Anda Telah Tersedia!</h3>
                    <p class="text-xs text-slate-600 mb-3 max-w-2xl leading-relaxed">Auditor telah melakukan evaluasi terhadap laporan risk register dan upaya mitigasi unit Anda. Silakan unduh skor dan umpan balik kelayakan mitigasi sebagai evaluasi perbaikan kedepannya.</p>
                    <a :href="'/auditor/assessment/' + userUnitAssessment.id + '/print'" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-lg shadow-blue-500/30">
                        <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Cetak Hasil Audit
                    </a>
                </div>
            </div>
        </div>
    </template>

    <!-- Dashboard Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 stagger-children">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/20">
                <i data-lucide="filter" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Filter Dashboard Eksekutif</h3>
                <p class="text-xs text-slate-400">Sesuaikan data statistik berdasarkan periode.</p>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <!-- Triwulan Filter -->
            <div class="flex items-center gap-2">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Periode:</span>
                <select x-model="dashTriwulan" class="p-2.5 text-xs bg-slate-50 border-[1.5px] border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-teal-500 focus:outline-none font-bold transition-all shadow-sm">
                    <option value="">SEMUA TRIWULAN</option>
                    <option value="Triwulan 1">TRIWULAN 1 (JAN-MAR)</option>
                    <option value="Triwulan 2">TRIWULAN 2 (APR-JUN)</option>
                    <option value="Triwulan 3">TRIWULAN 3 (JUL-SEP)</option>
                    <option value="Triwulan 4">TRIWULAN 4 (OKT-DES)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="getYearlyData().length === 0" class="flex flex-col items-center justify-center h-96 text-center bg-white rounded-2xl border-2 border-dashed border-slate-200 p-8">
        <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mb-5">
            <i data-lucide="file-text" class="w-10 h-10 text-slate-300"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Data Risiko</h3>
        <p class="text-slate-400 max-w-md text-sm leading-relaxed">Silakan masuk ke menu "Daftar Risiko" untuk mulai menambahkan data risiko secara manual.</p>
    </div>

    <!-- Stats & Charts -->
    <div x-show="getYearlyData().length > 0" class="space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 stagger-children">
            <!-- Total -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100/80 flex items-center justify-between card-hover stat-accent stat-accent-blue animate-fade-in-up">
                <div class="pl-3">
                    <p class="text-xs font-semibold text-slate-400 mb-1 uppercase tracking-wider">Total Risiko</p>
                    <h3 class="text-3xl font-extrabold text-slate-800" x-text="getYearlyData().length"></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                </div>
            </div>
            <!-- Kritis/Tinggi -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100/80 flex items-center justify-between card-hover stat-accent stat-accent-red animate-fade-in-up">
                <div class="pl-3">
                    <p class="text-xs font-semibold text-slate-400 mb-1 uppercase tracking-wider">Tinggi/Kritis</p>
                    <h3 class="text-3xl font-extrabold text-slate-800" x-text="getHighRiskCount()"></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                    <i data-lucide="alert-octagon" class="w-5 h-5 text-white"></i>
                </div>
            </div>
             <!-- Selesai -->
             <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100/80 flex items-center justify-between card-hover stat-accent stat-accent-green animate-fade-in-up">
                <div class="pl-3">
                    <p class="text-xs font-semibold text-slate-400 mb-1 uppercase tracking-wider">Mitigasi Selesai</p>
                    <h3 class="text-3xl font-extrabold text-slate-800" x-text="getYearlyData().reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === 'Completed').length : 0), 0)"></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i data-lucide="check-circle" class="w-5 h-5 text-white"></i>
                </div>
            </div>
             <!-- Berjalan -->
             <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100/80 flex items-center justify-between card-hover stat-accent stat-accent-amber animate-fade-in-up">
                <div class="pl-3">
                    <p class="text-xs font-semibold text-slate-400 mb-1 uppercase tracking-wider">Mitigasi Berjalan</p>
                    <h3 class="text-3xl font-extrabold text-slate-800" x-text="getYearlyData().reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === 'In-Progress').length : 0), 0)"></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <i data-lucide="clock" class="w-5 h-5 text-white"></i>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Bar Chart -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100/80 card-hover">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center uppercase tracking-wider">
                    <div class="w-7 h-7 bg-teal-100 rounded-lg flex items-center justify-center mr-2.5">
                        <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-teal-600"></i>
                    </div>
                    Distribusi Kategori Risiko
                </h3>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <!-- Pie Chart -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100/80 card-hover">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center uppercase tracking-wider">
                    <div class="w-7 h-7 bg-teal-100 rounded-lg flex items-center justify-center mr-2.5">
                        <i data-lucide="activity" class="w-3.5 h-3.5 text-teal-600"></i>
                    </div>
                    Status Mitigasi
                </h3>
                <div class="h-64 flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Notification Sidebar -->
    <div class="w-full lg:w-80 space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100/80 sticky top-6">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="bell" class="w-3.5 h-3.5 text-teal-600"></i>
                    Notification Center
                </h3>
                <span class="px-2 py-0.5 bg-teal-100 text-teal-600 text-[10px] font-black rounded-full" x-text="announcements.length"></span>
            </div>
            
            <div class="space-y-4 max-h-[calc(100vh-200px)] overflow-y-auto pr-1 custom-scrollbar">
                <template x-for="note in announcements" :key="note.id">
                    <div class="p-4 rounded-2xl border relative group hover:shadow-md transition-all animate-fade-in-up"
                        :class="note.is_context ? 'bg-amber-50/50 border-amber-200/60' : 'bg-slate-50/50 border-slate-100'">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl flex-shrink-0 flex items-center justify-center" 
                                :class="note.is_context ? 'bg-amber-500 text-white shadow-lg shadow-amber-200' : (note.bidang ? 'bg-indigo-100 text-indigo-600' : 'bg-teal-100 text-teal-600')">
                                <i :data-lucide="note.is_context ? (note.title.includes('SASARAN') ? 'target' : 'bar-chart-2') : (note.bidang ? 'shield' : 'megaphone')" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold mb-1 leading-tight" 
                                    :class="note.is_context ? 'text-amber-800' : 'text-slate-800'"
                                    x-text="note.title"></h4>
                                <p class="text-[11px] leading-relaxed mb-2" 
                                    :class="note.is_context ? 'text-amber-700 font-medium' : 'text-slate-500'"
                                    style="white-space: pre-line"
                                    x-text="note.message"></p>
                                <div class="flex items-center justify-between text-[9px]">
                                    <span class="text-slate-400 font-medium" x-text="new Date(note.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'short'})"></span>
                                    <span class="font-bold" :class="note.is_context ? 'text-amber-500' : 'text-slate-400'" x-text="note.is_context ? 'STRATEGIS' : (note.user ? note.user.name.split(' ')[0] : 'System')"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                
                <template x-if="announcements.length === 0">
                    <div class="py-12 text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="bell-off" class="w-6 h-6 text-slate-200"></i>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium italic">Tidak ada notifikasi aktif</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
