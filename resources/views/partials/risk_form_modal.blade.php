<!-- RISK INPUT/EDIT MODAL -->
<div x-show="isAddRiskModalOpen" x-cloak class="fixed inset-0 z-[110] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div x-show="isAddRiskModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/90 backdrop-blur-md transition-opacity" aria-hidden="true" @click="cancelEdit()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Content -->
        <div x-show="isAddRiskModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full border border-white/20">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-500 px-8 py-6 flex justify-between items-center shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i data-lucide="shield-plus" class="w-24 h-24 text-white"></i>
                </div>
                <div class="relative z-10">
                    <h3 class="text-xl font-black text-white uppercase tracking-tight flex items-center">
                        <i :data-lucide="isEditMode ? 'edit-3' : 'plus-circle'" class="w-6 h-6 mr-3"></i>
                        <span x-text="isEditMode ? 'Edit Register Risiko' : 'Tambah Risiko Baru'"></span>
                    </h3>
                    <p class="text-teal-50 text-xs mt-1 font-medium opacity-80" x-text="isEditMode ? 'Perbarui data risiko #' + editingRiskId : 'Lengkapi formulir di bawah untuk mencatat risiko baru ke sistem.'"></p>
                </div>
                <button @click="cancelEdit()" class="text-white/70 hover:text-white transition-colors relative z-10 bg-white/10 p-2 rounded-xl">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="px-8 py-8 bg-slate-50/30">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- Basic Info -->
                    <div class="md:col-span-4 space-y-5">
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                            <h4 class="text-[10px] font-black text-teal-600 uppercase tracking-widest mb-4 flex items-center">
                                <i data-lucide="info" class="w-3.5 h-3.5 mr-2"></i> Identifikasi Dasar
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Unit / Bagian</label>
                                    <select x-model="newRisk.unit" @change="newRisk.sub_unit = ''" class="w-full p-2.5 border-[1.5px] rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 bg-slate-50/50 border-slate-200 text-slate-700 text-sm font-bold transition-all" :disabled="isRestricted">
                                        <option value="">-- Pilih Unit --</option>
                                        <template x-for="unit in availableUnits" :key="unit.id">
                                            <option :value="unit.name" x-text="unit.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Sub-Unit / Ruangan (Wajib)</label>
                                    <select x-model="newRisk.sub_unit" 
                                        @change="if($el.value === 'ADD_NEW') { showNewSubUnitInput = true; newRisk.sub_unit = ''; } else { showNewSubUnitInput = false; }"
                                        class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 bg-slate-50/50 text-slate-700 text-sm font-bold transition-all" required>
                                        <option value="">-- Pilih Sub-Unit --</option>
                                        <template x-for="sub in availableSubUnits.filter(s => s.unit_name === newRisk.unit)" :key="sub.id">
                                            <option :value="sub.name" x-text="sub.name"></option>
                                        </template>
                                        <option value="ADD_NEW" class="text-indigo-600 font-black">+ TAMBAHKAN UNIT/RUANGAN</option>
                                    </select>
                                    
                                    <!-- Dynamic Sub-Unit Input -->
                                    <div x-show="showNewSubUnitInput" x-transition class="mt-3 p-3 bg-indigo-50 rounded-2xl border border-indigo-100 animate-pulse-subtle">
                                        <label class="block text-[9px] font-bold text-indigo-400 mb-1 uppercase tracking-wider">Nama Unit/Ruangan Baru</label>
                                        <div class="flex gap-2">
                                            <input type="text" x-model="newSubUnitName" placeholder="Masukkan nama ruangan..." 
                                                class="flex-1 p-2 bg-white border border-indigo-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                                            <button @click="if(newSubUnitName) { newRisk.sub_unit = newSubUnitName; showNewSubUnitInput = false; } else { notify('Nama ruangan tidak boleh kosong', 'warning'); }" 
                                                type="button" class="px-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase">SET</button>
                                        </div>
                                    </div>

                                    <template x-if="newRisk.sub_unit && !showNewSubUnitInput">
                                        <div class="mt-2 flex items-center gap-1.5 text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg w-fit">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            TERPILIH: <span x-text="newRisk.sub_unit"></span>
                                            <button @click="newRisk.sub_unit = ''; showNewSubUnitInput = true;" type="button" class="ml-1 text-indigo-400 hover:text-indigo-600 uppercase underline">Ubah</button>
                                        </div>
                                    </template>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Kategori Risiko</label>
                                    <select x-model="newRisk.kategori" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 bg-slate-50/50 text-sm font-bold transition-all">
                                        <template x-for="cat in availableCategories" :key="cat.id">
                                            <option :value="cat.name" x-text="cat.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Tanggal Identifikasi</label>
                                    <input type="date" x-model="newRisk.tanggal" @change="newRisk.triwulan = 'Triwulan ' + Math.ceil((new Date(newRisk.tanggal).getMonth() + 1) / 3)" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 bg-slate-50/50 text-slate-700 text-sm font-bold transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Target Triwulan</label>
                                    <select x-model="newRisk.triwulan" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 bg-slate-50/50 text-slate-700 text-sm font-bold transition-all">
                                        <option value="Triwulan 1">Triwulan 1 (Jan-Mar)</option>
                                        <option value="Triwulan 2">Triwulan 2 (Apr-Jun)</option>
                                        <option value="Triwulan 3">Triwulan 3 (Jul-Sep)</option>
                                        <option value="Triwulan 4">Triwulan 4 (Okt-Des)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Status Aktif</label>
                                    <div class="flex items-center gap-2">
                                        <button @click="newRisk.is_active = true" :class="newRisk.is_active ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-2 text-[10px] font-bold rounded-lg border-[1.5px] transition-all">AKTIF</button>
                                        <button @click="newRisk.is_active = false" :class="!newRisk.is_active ? 'bg-slate-600 text-white border-slate-600' : 'bg-white text-slate-400 border-slate-200'" class="flex-1 py-2 text-[10px] font-bold rounded-lg border-[1.5px] transition-all">DEAKTIF</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                            <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center">
                                <i data-lucide="zap" class="w-3.5 h-3.5 mr-2"></i> Analisis Skor
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2 text-center py-2 bg-slate-50 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Skor Inheren (Awal)</span>
                                    <div class="flex items-center justify-center gap-2">
                                        <input type="number" min="1" max="5" x-model="newRisk.awalD" class="w-12 p-1.5 bg-white border-[1.5px] border-slate-200 rounded-lg text-center text-sm font-black focus:ring-2 focus:ring-teal-500 outline-none">
                                        <span class="text-slate-300 font-bold">×</span>
                                        <input type="number" min="1" max="5" x-model="newRisk.awalP" class="w-12 p-1.5 bg-white border-[1.5px] border-slate-200 rounded-lg text-center text-sm font-black focus:ring-2 focus:ring-teal-500 outline-none">
                                        <span class="mx-1 text-slate-300 font-bold">=</span>
                                        <span class="w-10 h-10 flex items-center justify-center rounded-xl text-white font-black text-sm shadow-sm" :class="getRiskColor(calculateLevel((newRisk.awalD || 1) * (newRisk.awalP || 1)))" x-text="(newRisk.awalD || 1) * (newRisk.awalP || 1)"></span>
                                    </div>
                                </div>
                                <div class="col-span-2 text-center py-2 bg-slate-50 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Skor Residual (Target)</span>
                                    <div class="flex items-center justify-center gap-2">
                                        <input type="number" min="1" max="5" x-model="newRisk.residualD" class="w-12 p-1.5 bg-white border-[1.5px] border-slate-200 rounded-lg text-center text-sm font-black focus:ring-2 focus:ring-teal-500 outline-none">
                                        <span class="text-slate-300 font-bold">×</span>
                                        <input type="number" min="1" max="5" x-model="newRisk.residualP" class="w-12 p-1.5 bg-white border-[1.5px] border-slate-200 rounded-lg text-center text-sm font-black focus:ring-2 focus:ring-teal-500 outline-none">
                                        <span class="mx-1 text-slate-300 font-bold">=</span>
                                        <span class="w-10 h-10 flex items-center justify-center rounded-xl text-white font-black text-sm shadow-sm" :class="getRiskColor(calculateLevel((newRisk.residualD || 1) * (newRisk.residualP || 1)))" x-text="(newRisk.residualD || 1) * (newRisk.residualP || 1)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Detail -->
                    <div class="md:col-span-8 space-y-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-5">
                             <h4 class="text-[10px] font-black text-teal-600 uppercase tracking-widest flex items-center">
                                <i data-lucide="align-left" class="w-3.5 h-3.5 mr-2"></i> Deskripsi & Analisis Risiko
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Kejadian Risiko</label>
                                    <textarea x-model="newRisk.risiko" class="w-full p-3 border-[1.5px] border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all text-sm font-medium" rows="2" placeholder="Apa kejadian risiko yang mungkin terjadi?"></textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Akar Penyebab</label>
                                    <textarea x-model="newRisk.penyebab" class="w-full p-3 border-[1.5px] border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all text-sm font-medium" rows="3" placeholder="Mengapa hal ini bisa terjadi?"></textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Dampak Risiko</label>
                                    <textarea x-model="newRisk.dampakDeskripsi" class="w-full p-3 border-[1.5px] border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all text-sm font-medium" rows="3" placeholder="Apa konsekuensi jika risiko terjadi?"></textarea>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-slate-50">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Evaluasi Tindakan</label>
                                    <select x-model="newRisk.evaluasi" class="w-full p-3 border-[1.5px] border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:outline-none bg-slate-50/50 text-sm font-bold transition-all">
                                        <option value="Diterima">Diterima</option>
                                        <option value="Ditolak">Ditolak</option>
                                        <option value="Dibagi">Dibagi (Sharing)</option>
                                        <option value="Diturunkan">Diturunkan</option>
                                        <option value="Eskalasi">Eskalasi Ke Atasan</option>
                                        <option value="Eksploitasi">Eksploitasi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Penanggung Jawab (PJ)</label>
                                    <input type="text" x-model="newRisk.pj" class="w-full p-3 border-[1.5px] border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all text-sm font-bold" placeholder="Nama atau Jabatan">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Validator</label>
                                    <select x-model="newRisk.validator" class="w-full p-3 border-[1.5px] border-slate-200 rounded-2xl focus:ring-2 focus:ring-teal-500 focus:outline-none bg-white text-sm font-bold transition-all">
                                        <option value="">- Pilih Validator -</option>
                                        <option value="Kusnadi Jaya">Kusnadi Jaya</option>
                                        <option value="Direktur">Direktur</option>
                                        <option value="Wakil Direktur Umum, Anggaran dan Keuangan">Wakil Direktur Umum, Anggaran dan Keuangan</option>
                                        <option value="Wakil Direktur SDM & Pengembangan">Wakil Direktur SDM & Pengembangan</option>
                                        <option value="Wakil Direktur Pelayanan">Wakil Direktur Pelayanan</option>
                                        <option value="Komite Mutu">Komite Mutu</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Conditional Fields (Sharing) -->
                            <div x-show="newRisk.evaluasi === 'Dibagi'" x-transition class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100/60">
                                <label class="block text-[10px] text-blue-700 font-black uppercase tracking-wider mb-3">User Terkait (Berbagi Risiko)</label>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <template x-for="(userName, idx) in newRisk.shared_with" :key="idx">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-xl border border-blue-200 group/tag hover:bg-blue-600 hover:text-white transition-all cursor-default">
                                            <span x-text="userName"></span>
                                            <button @click="newRisk.shared_with = newRisk.shared_with.filter(u => u !== userName)" type="button" class="text-blue-400 group-hover/tag:text-blue-100 hover:text-white transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <div class="relative" x-data="{ isOpen: false, search: '' }">
                                    <button @click="isOpen = !isOpen" type="button" class="w-full p-3 bg-white border-[1.5px] border-blue-200 rounded-2xl flex justify-between items-center text-sm font-bold text-blue-700 transition-all hover:bg-blue-50">
                                        <span>+ Pilih User Tambahan</span>
                                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="isOpen ? 'rotate-180' : ''"></i>
                                    </button>
                                    <div x-show="isOpen" @click.away="isOpen = false" class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl max-h-60 overflow-y-auto p-3" x-cloak x-transition>
                                        <input type="text" x-model="search" placeholder="Cari user..." class="w-full p-2.5 mb-3 text-sm border border-slate-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none sticky top-0 bg-white">
                                        <div class="space-y-1">
                                            <template x-for="user in availableUsers.filter(u => u.name.toLowerCase().includes(search.toLowerCase()))" :key="user.id">
                                                <button @click="if (!newRisk.shared_with.includes(user.name)) newRisk.shared_with = [...newRisk.shared_with, user.name]; isOpen = false; search = ''" 
                                                    class="w-full text-left px-4 py-2.5 text-xs font-bold hover:bg-blue-50 rounded-xl transition-colors flex items-center justify-between"
                                                    :disabled="newRisk.shared_with.includes(user.name)"
                                                    :class="newRisk.shared_with.includes(user.name) ? 'text-slate-300' : 'text-slate-600'">
                                                    <div class="flex flex-col">
                                                        <span x-text="user.name"></span>
                                                        <span class="text-[9px] font-medium text-slate-400 mt-0.5" x-text="user.unit || 'Super Admin'"></span>
                                                    </div>
                                                    <i x-show="newRisk.shared_with.includes(user.name)" data-lucide="check" class="w-3.5 h-3.5 text-blue-600"></i>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mitigation Plan -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                            <div class="flex justify-between items-center">
                                <h4 class="text-[10px] font-black text-teal-600 uppercase tracking-widest flex items-center">
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-2"></i> Rencana Aksi Mitigasi
                                </h4>
                                <button @click="addMitigation()" class="px-3 py-1.5 bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white rounded-lg text-[10px] font-black transition-all flex items-center gap-1.5 border border-teal-100">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> TAMBAH AKSI
                                </button>
                            </div>
                            
                            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="(mit, index) in newRisk.mitigations" :key="index">
                                    <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-100 relative group">
                                        <div class="absolute -left-2.5 top-5 w-5 h-5 bg-white border border-slate-200 rounded-full flex items-center justify-center text-[10px] font-black text-slate-400 shadow-sm" x-text="index + 1"></div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="md:col-span-2">
                                                <label class="block text-[9px] font-bold text-slate-400 mb-1 uppercase">Uraian Tindakan</label>
                                                <textarea x-model="mit.treatment" class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all text-xs font-bold" rows="2" placeholder="Apa yang akan dilakukan untuk mengurangi risiko?"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-400 mb-1 uppercase">Status Progres</label>
                                                <select x-model="mit.status" class="w-full p-2.5 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none bg-white text-xs font-bold transition-all">
                                                    <option value="Not Started">Belum Mulai</option>
                                                    <option value="In-Progress">Sedang Berjalan</option>
                                                    <option value="Completed">Telah Selesai</option>
                                                </select>
                                            </div>
                                            <div x-show="mit.status === 'Completed'" x-transition>
                                                <label class="block text-[9px] font-bold text-slate-400 mb-1 uppercase flex items-center justify-between">
                                                    Link G-Drive Bukti
                                                    <span class="text-red-500 text-[8px] italic">*WAJIB (OPEN ACCESS)</span>
                                                </label>
                                                <div class="relative">
                                                    <i data-lucide="link" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-300"></i>
                                                    <input type="url" x-model="mit.evidence_link" placeholder="https://drive.google.com/..." 
                                                        class="w-full pl-9 pr-3 py-2.5 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all text-xs font-medium"
                                                        :required="mit.status === 'Completed'">
                                                </div>
                                            </div>
                                        </div>

                                        <button x-show="newRisk.mitigations.length > 1" @click="removeMitigation(index)" 
                                            class="absolute -right-2 -top-2 w-7 h-7 bg-white text-red-400 hover:text-red-600 border border-slate-100 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 opacity-0 group-hover:opacity-100">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-white border-t border-slate-100 flex justify-end gap-3 items-center">
                <button @click="cancelEdit()" class="px-6 py-3 text-slate-400 hover:text-slate-600 font-black text-xs uppercase tracking-widest transition-colors">
                    Batalkan
                </button>
                <button @click="addRisk()" class="px-8 py-3 bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white rounded-2xl flex items-center gap-3 font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-teal-600/20 active:scale-95">
                    <i :data-lucide="isEditMode ? 'check' : 'save'" class="w-5 h-5"></i>
                    <span x-text="isEditMode ? 'Update Data Risiko' : 'Simpan Register Risiko'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
