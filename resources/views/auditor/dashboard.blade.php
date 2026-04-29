@extends('layouts.app')

@section('title', 'Auditor Dashboard - SI ERMa')

@push('styles')
<style>
    /* Custom Scrollbar for a more modern Millennial feel */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 5px;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 5px;
        border: 2px solid #f1f5f9;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Ensure the modal body has a visible scrollbar */
    .overflow-y-auto {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 font-sans" x-data="auditorApp()" x-init="init()">
    <!-- Top Navigation Bar -->
    <nav class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 shadow-xl">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Branding -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <i data-lucide="eye" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg leading-tight tracking-tight">SI ERMa <span class="text-indigo-400">Auditor</span></h1>
                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-widest">RSUD dr. Murjani</p>
                    </div>
                </div>
                
                <!-- Right Action -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col items-end mr-2">
                        <span class="text-sm font-bold text-white">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-slate-400 font-medium bg-slate-800 px-2 py-0.5 rounded-full mt-0.5">Auditor Access</span>
                    </div>
                    <div class="h-8 w-px bg-slate-700 hidden md:block"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-4 py-2 rounded-lg text-sm font-bold transition-all border border-red-500/20 hover:border-red-500">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Stream -->
    <main class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 pb-32">
        
        <!-- Header Section -->
        <header class="animate-fade-in-up">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Executive Summary</h2>
            <p class="text-slate-500 mt-1.5 font-medium">Tinjauan menyeluruh terhadap profil risiko enterprise.</p>
        </header>

        <!-- SECTION 1: STATISTICS -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 animate-fade-in-up" style="animation-delay: 0.1s;">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group hover:-translate-y-1 transition-transform">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Risiko</p>
                        <h3 class="text-3xl font-black text-slate-800" x-text="riskData.length">0</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                        <i data-lucide="file-stack" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group hover:-translate-y-1 transition-transform">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Risiko Kritis/Tinggi</p>
                        <h3 class="text-3xl font-black text-red-600 drop-shadow-sm" x-text="getHighRiskCount()">0</h3>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600 shadow-inner">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group hover:-translate-y-1 transition-transform">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Aksi Mitigasi (Aktif)</p>
                        <h3 class="text-3xl font-black text-indigo-600" x-text="getMitigationCount('In-Progress')">0</h3>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group hover:-translate-y-1 transition-transform">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Risiko Terselesaikan</p>
                        <h3 class="text-3xl font-black text-emerald-600" x-text="getCompletedRisks()">0</h3>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- GRID FOR REGISTER AND HEATMAP -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- LEFT: THE READ ONLY REGISTER (8/12) -->
            <section class="lg:col-span-8 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
                    <!-- Table Header & Controls -->
                    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center">
                            <i data-lucide="database" class="w-4 h-4 mr-2 text-indigo-500"></i>
                            Master Risiko
                        </h2>
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <div class="relative flex-1 md:w-48">
                                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                                <input x-model="searchTerm" type="text" placeholder="Cari..." class="w-full pl-9 pr-3 py-2 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 text-xs font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- Scrollable Table -->
                    <div class="overflow-x-auto overflow-y-auto flex-1 max-h-[1000px]">
                        <table class="w-full text-left text-[11px] relative">
                            <thead class="bg-slate-100/80 text-slate-500 font-bold uppercase tracking-wider text-[9px] sticky top-0 z-20 backdrop-blur-md">
                                <tr>
                                    <th class="px-4 py-3 w-16">ID</th>
                                    <th class="px-4 py-3 min-w-[200px]">Risiko</th>
                                    <th class="px-4 py-3 w-20 text-center">Score</th>
                                    <th class="px-4 py-3 min-w-[150px]">Mitigasi</th>
                                    <th class="px-4 py-3 w-24">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="item in filteredData()" :key="item.id">
                                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                                        <td class="px-4 py-4 font-bold text-slate-900" x-text="item.kode"></td>
                                        <td class="px-4 py-4">
                                            <div class="font-bold text-slate-800 mb-1" x-text="item.risiko"></div>
                                            <div class="text-[9px] text-indigo-600 font-medium bg-indigo-50 px-1.5 py-0.5 rounded-md inline-block" x-text="item.unit"></div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="px-2 py-1 rounded-md text-[10px] font-black" :class="getRiskColor(item.awal_level)" x-text="item.awal_skor"></span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <template x-for="mit in item.mitigations" :key="mit.id">
                                                <div class="text-[10px] mb-1 line-clamp-1 border-l-2 border-indigo-200 pl-2" x-text="mit.treatment"></div>
                                            </template>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="text-[9px] px-2 py-0.5 rounded font-bold uppercase" :class="getRiskStatusColor(item.status)" x-text="item.status"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- RIGHT: HEATMAP (4/12) -->
            <section class="lg:col-span-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 sticky top-24 h-fit">
                    <h2 class="text-sm font-bold text-slate-800 flex items-center uppercase tracking-wider mb-6">
                        <i data-lucide="target" class="w-4 h-4 mr-2 text-indigo-600"></i>
                        Risk Heatmap
                    </h2>
                    
                    <div class="flex flex-col items-center">
                        <div class="flex items-center select-none scale-90 origin-top">
                            <!-- Y Labels -->
                            <div class="grid grid-rows-5 gap-1 border-r-2 border-slate-100 pr-2">
                                <template x-for="label in ['5','4','3','2','1']">
                                    <div class="h-12 flex items-center justify-end font-bold text-slate-400 text-[10px]" x-text="label"></div>
                                </template>
                            </div>

                            <!-- Grid -->
                            <div class="grid grid-cols-5 grid-rows-5 gap-1 ml-1">
                                <template x-for="p in [5,4,3,2,1]">
                                    <template x-for="d in [1,2,3,4,5]">
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center relative shadow-sm border border-white/20 transition-transform hover:scale-105"
                                             :class="getMatrixColorCode(p, d)">
                                            <span x-text="getMatrixCount(p,d)" x-show="getMatrixCount(p,d) > 0" class="text-xs font-black drop-shadow-md z-10" :class="isDarkText(p, d) ? 'text-black/80' : 'text-white'"></span>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                        <div class="flex mt-1 ml-4 border-t-2 border-slate-100 pt-1 scale-90 origin-top">
                            <template x-for="label in ['1','2','3','4','5']">
                                <div class="w-12 text-center font-bold text-slate-400 text-[10px]" x-text="label"></div>
                            </template>
                        </div>
                        <div class="mt-4 flex gap-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                            <span class="flex items-center"><div class="w-2 h-2 bg-red-600 rounded mr-1"></div> Kritis</span>
                            <span class="flex items-center"><div class="w-2 h-2 bg-orange-500 rounded mr-1"></div> Tinggi</span>
                            <span class="flex items-center"><div class="w-2 h-2 bg-yellow-400 rounded mr-1"></div> Sedang</span>
                            <span class="flex items-center"><div class="w-2 h-2 bg-emerald-500 rounded mr-1"></div> Rendah</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- SECTION 3: AUDIT KEPATUHAN UNIT (BACK TO BOTTOM) -->
        <section class="animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 flex items-center uppercase tracking-wider">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 text-blue-600">
                                <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                            </div>
                            Audit Kepatuhan & Feedback Unit
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Beri nilai komprehensif atas kepatuhan mitigasi & risk register unit.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="unit in availableUnits" :key="unit.id">
                        <div class="border border-slate-200 rounded-xl p-4 hover:shadow-md transition-all relative bg-slate-50/30">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-bold text-slate-800 text-xs truncate mr-2" x-text="unit.name"></h3>
                                <template x-if="getAssessmentForUnit(unit.name)">
                                    <span class="bg-emerald-100 text-emerald-700 text-[9px] px-2 py-0.5 rounded-md font-bold flex-shrink-0">Telah Dinilai</span>
                                </template>
                                <template x-if="!getAssessmentForUnit(unit.name)">
                                    <span class="bg-slate-100 text-slate-500 text-[9px] px-2 py-0.5 rounded-md font-bold flex-shrink-0">Belum Dinilai</span>
                                </template>
                            </div>
                            
                            <div class="flex gap-2">
                                <button @click="openAuditModal(unit.name)" class="flex-1 bg-white hover:bg-indigo-50 text-indigo-700 py-1.5 rounded-lg text-[10px] font-bold transition-all border border-indigo-100 shadow-sm">
                                    <span x-text="getAssessmentForUnit(unit.name) ? 'Edit Penilaian' : 'Mulai'"></span>
                                </button>
                                <template x-if="getAssessmentForUnit(unit.name)">
                                    <a :href="'/auditor/assessment/' + getAssessmentForUnit(unit.name).id + '/print'" target="_blank" class="w-8 h-8 flex items-center justify-center bg-white hover:bg-slate-100 text-slate-600 border border-slate-200 rounded-lg transition-all" title="Print Laporan">
                                        <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        <div class="text-center pt-8 text-slate-400 text-xs font-medium">
            <p>SI ERMa Auditor Panel &copy; {{ date('Y') }} RSUD dr. Murjani</p>
        </div>
    </main>

    <!-- AUDIT MODAL (FULL PAGE BLUR) -->
    <div x-show="showAuditModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center overflow-hidden bg-slate-900/60 backdrop-blur-xl" 
         x-cloak
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0">
        <div @click.away="showAuditModal = false" 
             class="bg-white w-full max-w-4xl max-h-[90vh] rounded-2xl shadow-2xl flex flex-col relative"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-90 translate-y-12" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
             x-transition:leave-end="opacity-0 scale-95 translate-y-8">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 rounded-t-2xl">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-800" x-text="'Lembar Penilaian Kepatuhan: ' + currentAuditUnit"></h3>
                    <div class="mt-2 flex items-center gap-4">
                        <div class="flex flex-col">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Audit</label>
                            <input type="date" x-model="auditDate" class="p-1 px-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none font-bold text-slate-700">
                        </div>
                        <div class="pt-4">
                            <p class="text-[10px] text-slate-500">Skor (A-E) untuk kriteria. A = Sangat Memadai</p>
                        </div>
                    </div>
                </div>
                <button @click="showAuditModal = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 flex-1 overflow-y-auto space-y-6">
                <template x-for="(q, index) in auditQuestions" :key="index">
                    <div class="p-4 bg-slate-50/50 rounded-xl border border-slate-200/60">
                        <h4 class="text-sm font-bold text-slate-800 mb-3" x-text="(index+1) + '. ' + q.question"></h4>
                        
                        <div class="grid md:grid-cols-12 gap-4">
                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Skor Kelayakan (A-E)</label>
                                <select x-model="q.score" class="w-full p-2 text-sm border-2 border-slate-200 rounded-lg focus:ring-0 focus:border-indigo-500 font-bold text-slate-700 bg-white">
                                    <option value="A">A - Sangat Memadai (100%)</option>
                                    <option value="B">B - Memadai (80%)</option>
                                    <option value="C">C - Cukup (60%)</option>
                                    <option value="D">D - Kurang (40%)</option>
                                    <option value="E">E - Tidak Memadai (< 20%)</option>
                                </select>
                            </div>
                            <div class="md:col-span-8">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi / Catatan Auditor</label>
                                <textarea x-model="q.notes" rows="2" class="w-full p-2 text-xs border border-slate-200 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none" placeholder="Isikan catatan evaluasi untuk unit..."></textarea>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-b-2xl">
                <span class="text-xs font-bold text-slate-500" x-text="'Total Poin: ' + calculateAuditTotal()"></span>
                <div class="flex gap-3">
                    <button @click="showAuditModal = false" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-200 text-slate-700 hover:bg-slate-300 transition-all">Batal</button>
                    <button @click="submitAudit()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-indigo-600 text-white hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Penilaian
                    </button>
                </div>
            </div>
        </div>
    </div>
        
        <div class="text-center pt-8 text-slate-400 text-xs font-medium">
            <p>SI ERMa Auditor Panel &copy; {{ date('Y') }} RSUD dr. Murjani</p>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    function auditorApp() {
        return {
            riskData: @json($risks),
            availableUnits: @json($units),
            assessmentsData: @json($assessments),
            searchTerm: '',
            filterUnit: '', 
            
            showAuditModal: false,
            currentAuditUnit: null,
            auditDate: new Date().toISOString().split('T')[0],
            auditQuestions: [],
            baseQuestions: [
                "Apakah sasaran sudah disampaikan dengan SMART?",
                "Apakah risiko Strategis sudah dieksplorasi dan dimitigasi?",
                "Apakah risiko operasional sudah dieksplorasi dan dimitigasi?",
                "Apakah risiko fraud sudah dieksplorasi dan dimitigasi?",
                "Apakah risiko SDM sudah dieksplorasi dan dimitigasi?",
                "Apakah risiko klinis dan keselamatan pasien sudah dieksplorasi dan dimitigasi?",
                "Apakah risiko teknologi informasi sudah dieksplorasi dan dimitigasi?",
                "Apakah risiko keuangan sudah dieksploitasi dan dimitigasi?",
                "Apakah risk register sudah dikomunikasikan dengan pimpinan?",
                "Apakah risk register sudah disosialisasikan dengan seluruh pegawai di Unit?",
                "Apakah risk register sudah direview dalam jangka waktu tertentu?"
            ],

            init() {
                setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100);
                this.resetAuditForm();
            },

            filteredData() {
                return this.riskData.filter(item => {
                    const matchesSearch = (item.risiko && item.risiko.toLowerCase().includes(this.searchTerm.toLowerCase())) ||
                                        (item.kode && item.kode.toLowerCase().includes(this.searchTerm.toLowerCase())) ||
                                        (item.kategori && item.kategori.toLowerCase().includes(this.searchTerm.toLowerCase()));
                    const matchesUnit = this.filterUnit === '' || item.unit === this.filterUnit;
                    return matchesSearch && matchesUnit;
                });
            },

            // Stats Helpers
            getHighRiskCount() { 
                return this.riskData.filter(d => d.awal_level === 'Tinggi' || d.awal_level === 'Kritis').length; 
            },
            
            getMitigationCount(status) {
                return this.riskData.reduce((acc, r) => acc + (r.mitigations ? r.mitigations.filter(m => m.status === status).length : 0), 0);
            },
            
            getCompletedRisks() {
                return this.riskData.filter(d => d.status === 'Completed').length;
            },

            // Matrix Logic
            getMatrixCount(p, d) { 
                return this.riskData.filter(item => item.awal_p === p && item.awal_d === d).length; 
            },

            calculateLevel(score) {
                if (score >= 15) return 'Kritis';
                if (score >= 10) return 'Tinggi';
                if (score >= 5) return 'Sedang';
                return 'Rendah';
            },

            getMatrixColorCode(p, d) {
                const score = p * d;
                const level = this.calculateLevel(score);
                const colors = { 
                    'Kritis': 'bg-red-600', 
                    'Tinggi': 'bg-orange-500', 
                    'Sedang': 'bg-yellow-400', 
                    'Rendah': 'bg-emerald-500' 
                };
                return colors[level] || 'bg-slate-200';
            },

            isDarkText(p, d) {
                const score = p * d;
                const level = this.calculateLevel(score);
                return level === 'Sedang';
            },

            // Table Styling Helpers
            getRiskColor(level) {
                const colors = { 
                    'Kritis': 'bg-red-600 text-white', 
                    'Tinggi': 'bg-orange-500 text-white', 
                    'Sedang': 'bg-yellow-400 text-black', 
                    'Rendah': 'bg-emerald-500 text-white' 
                };
                return colors[level] || 'bg-slate-200 text-slate-800';
            },

            getStatusBadge(status) {
                const colors = { 
                    'Completed': 'bg-emerald-500', 
                    'In-Progress': 'bg-indigo-500', 
                    'Not Started': 'bg-slate-400' 
                };
                return colors[status] || 'bg-slate-500';
            },
            
            getRiskStatusColor(status) {
                const colors = { 
                    'Completed': 'bg-emerald-50 text-emerald-600 border-emerald-200', 
                    'In-Progress': 'bg-indigo-50 text-indigo-600 border-indigo-200', 
                    'Not Started': 'bg-slate-100 text-slate-500 border-slate-200' 
                };
                return colors[status] || 'bg-slate-100 text-slate-800';
            },

            // Audit Modal Logic
            getAssessmentForUnit(unitName) {
                return this.assessmentsData.find(a => a.unit === unitName);
            },

            resetAuditForm() {
                this.auditQuestions = this.baseQuestions.map((q, i) => ({
                    id: i + 1,
                    question: q,
                    score: 'C',
                    notes: ''
                }));
            },

            openAuditModal(unitName) {
                this.currentAuditUnit = unitName;
                const existing = this.getAssessmentForUnit(unitName);
                if (existing) {
                    this.auditQuestions = JSON.parse(JSON.stringify(existing.answers));
                    this.auditDate = existing.audit_date || new Date().toISOString().split('T')[0];
                } else {
                    this.resetAuditForm();
                    this.auditDate = new Date().toISOString().split('T')[0];
                }
                this.showAuditModal = true;
                setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 50);
            },

            calculateAuditTotal() {
                const weights = { 'A': 100, 'B': 80, 'C': 60, 'D': 40, 'E': 20 };
                let total = 0;
                this.auditQuestions.forEach(q => { total += weights[q.score]; });
                return (total / (this.auditQuestions.length * 100) * 100).toFixed(1) + '%';
            },

            async submitAudit() {
                try {
                    const response = await fetch('/auditor/assessment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            unit: this.currentAuditUnit,
                            period_year: '{{ date("Y") }}',
                            audit_date: this.auditDate,
                            answers: this.auditQuestions,
                            status: 'Final'
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        // Update local assessment array
                        const exIdx = this.assessmentsData.findIndex(a => a.unit === this.currentAuditUnit);
                        if (exIdx >= 0) {
                            this.assessmentsData[exIdx] = data.assessment;
                        } else {
                            this.assessmentsData.unshift(data.assessment);
                        }
                        this.showAuditModal = false;
                    } else {
                        alert('Gagal menyimpan penilaian');
                    }
                } catch (e) {
                    console.error(e);
                    alert('Terjadi kesalahan jaringan.');
                }
            }
        }
    }
</script>
@endpush
