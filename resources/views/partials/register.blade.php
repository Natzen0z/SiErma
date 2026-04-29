<!-- 2. REGISTER VIEW -->
<div x-show="activeTab === 'register'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    
    <!-- Period Settings -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <i data-lucide="calendar" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Pengaturan Periode Laporan</h3>
                <p class="text-xs text-slate-400">Tentukan periode laporan dokumen ini.</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <input type="number" x-model="periodYear" @change="updatePeriod()" min="2020" max="2100"
                class="w-24 p-2.5 bg-slate-50/80 border-[1.5px] border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none focus:border-indigo-500 text-center transition-all">
            <span class="text-xs text-slate-500 font-semibold px-3 py-1.5 bg-indigo-50 rounded-lg" x-text="globalPeriod"></span>
        </div>
    </div>

    <!-- Header with Plus Button -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-6 mb-6 flex flex-col md:flex-row justify-between items-center gap-6 stagger-children">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-xl shadow-teal-500/20 animate-fade-in-up">
                <i data-lucide="file-text" class="w-7 h-7 text-white"></i>
            </div>
            <div class="animate-fade-in-up" style="animation-delay: 0.1s">
                <h2 class="text-base font-black text-slate-800 uppercase tracking-tight">Daftar Risiko & Validasi Unit</h2>
                <p class="text-[10px] text-slate-400 mt-0.5 font-medium italic">Klik tombol tambah untuk mencatat kejadian risiko baru.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 animate-fade-in-up" style="animation-delay: 0.2s">
            <button @click="resetNewRisk(); isAddRiskModalOpen = true;" class="bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white px-5 py-2.5 rounded-2xl flex items-center gap-2.5 font-black text-xs shadow-xl shadow-teal-600/20 transition-all hover:-translate-y-1 active:scale-95 btn-shimmer">
                <div class="w-5 h-5 bg-white/20 rounded-lg flex items-center justify-center">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                </div>
                TAMBAH RISIKO BARU
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Daftar Risiko & Validasi</h2>
                <div class="flex flex-wrap items-center mt-2.5 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tampilkan:</span>
                        <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200 shadow-inner">
                            <button @click="filterType = 'own'" 
                                :class="filterType === 'own' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-3 py-1.5 text-[10px] font-bold rounded-lg transition-all flex items-center gap-1.5">
                                <i data-lucide="user" class="w-3 h-3"></i> UNIT SENDIRI
                            </button>
                            <button @click="filterType = 'shared'" 
                                :class="filterType === 'shared' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-3 py-1.5 text-[10px] font-bold rounded-lg transition-all flex items-center gap-1.5">
                                <i data-lucide="share-2" class="w-3 h-3"></i> RISIKO GABUNGAN
                            </button>
                            <button @click="filterType = 'all'" 
                                :class="filterType === 'all' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-3 py-1.5 text-[10px] font-bold rounded-lg transition-all flex items-center gap-1.5">
                                SEMUA
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Urutkan:</span>
                        <select x-model="sortBy" class="p-2 text-[10px] bg-white border-[1.5px] border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-teal-500 focus:outline-none font-bold transition-all shadow-sm">
                            <option value="time">WAKTU TERBARU</option>
                            <option value="score">SKOR TERTINGGI</option>
                            <option value="status">STATUS VALIDASI</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Triwulan:</span>
                        <select x-model="filterTriwulan" class="p-2 text-[10px] bg-white border-[1.5px] border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-teal-500 focus:outline-none font-bold transition-all shadow-sm">
                            <option value="">SEMUA</option>
                            <option value="Triwulan 1">TRIWULAN 1</option>
                            <option value="Triwulan 2">TRIWULAN 2</option>
                            <option value="Triwulan 3">TRIWULAN 3</option>
                            <option value="Triwulan 4">TRIWULAN 4</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="relative w-full md:w-72">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                <input x-model="searchTerm" type="text" placeholder="Cari risiko..." class="w-full pl-9 pr-4 py-2 bg-slate-50/80 border-[1.5px] border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-xs font-medium transition-all shadow-inner">
            </div>
        </div>

        <div x-show="riskData.length === 0" class="p-12 text-center text-slate-400">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
            </div>
            <p class="font-medium text-xs">Belum ada data yang tersimpan. Gunakan formulir di atas untuk menambahkan risiko.</p>
        </div>

        <div x-show="riskData.length > 0" class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-4 py-4 w-20">Kode</th>
                        <th class="px-4 py-4 w-32">Unit & Kategori</th>
                        <th class="px-4 py-4 w-64">Risiko, Penyebab & Dampak</th>
                        <th class="px-4 py-4 w-24 text-center">Skor Awal</th>
                        <th class="px-4 py-4 w-48">Pengendalian</th>
                        <th class="px-4 py-4 w-24 text-center">Skor Sisa</th>
                        <th class="px-4 py-4 w-32">Penanggung Jawab</th>
                        <th class="px-4 py-4 w-32 text-center bg-indigo-50/80 text-indigo-600 border-l border-indigo-100/60">Status Aktif</th>
                        <th class="px-4 py-4 w-32 text-center bg-indigo-50/80 text-indigo-600 border-l border-indigo-100/60">Status Risiko</th>
                        <th class="px-4 py-4 w-40 text-center bg-indigo-50/80 text-indigo-600 border-l border-indigo-100/60">Validasi Unit</th>
                        <th class="px-4 py-4 w-32 text-center bg-indigo-50/80 text-indigo-600 border-l border-indigo-100/60">Validator</th>
                        <th class="px-4 py-4 w-16 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    <template x-for="item in filteredData()" :key="item.id">
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-3 py-4 font-bold text-slate-900 align-top text-[10px]">
                                <div x-text="item.kode"></div>
                                <div class="text-[8px] text-slate-400 font-medium mt-1 uppercase" x-text="item.tanggal || item.created_at.split('T')[0]"></div>
                            </td>
                            <td class="px-3 py-4 align-top">
                                <div class="font-bold text-slate-700 text-[10px]" x-text="item.unit"></div>
                                <div x-show="item.sub_unit" class="text-[9px] text-teal-600 font-bold" x-text="'[' + item.sub_unit + ']'"></div>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[8px] font-semibold bg-slate-100 text-slate-500 mt-1" x-text="item.kategori"></span>
                            </td>
                            <td class="px-3 py-4 align-top">
                                <div class="font-bold text-slate-800 text-[10px] mb-1" x-text="item.risiko"></div>
                                <div class="text-[9px] text-slate-400 mb-0.5"><span class="font-bold text-slate-500">Penyebab:</span> <span x-text="item.penyebab"></span></div>
                                <div class="text-[9px] text-slate-400 mb-0.5" x-show="item.dampak_deskripsi"><span class="font-bold text-slate-500">Dampak:</span> <span x-text="item.dampak_deskripsi"></span></div>
                                <div class="mt-1.5 text-[9px] space-y-1">
                                    <div class="px-1.5 py-0.5 bg-indigo-50 text-indigo-600 border border-indigo-100/60 rounded-md inline-block font-bold" x-show="item.evaluasi">
                                        <span>Evaluasi:</span> <span x-text="item.evaluasi"></span>
                                    </div>
                                    <template x-if="item.shared_with && Array.isArray(item.shared_with)">
                                        <div class="mt-1 flex flex-wrap gap-1">
                                            <template x-for="shared in item.shared_with" :key="shared">
                                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 border border-blue-100/60 rounded text-[9px] font-bold" x-text="shared"></span>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="item.escalated_to">
                                        <div class="px-1.5 py-0.5 bg-amber-50 text-amber-600 border border-amber-100/60 rounded-md inline-block ml-1 font-semibold">
                                            <span class="font-bold">Eskalasi ke:</span> <span x-text="item.escalated_to"></span>
                                        </div>
                                    </template>
                                </div>
                                <div class="mt-2 pt-2 border-t border-slate-100/60 flex flex-wrap gap-x-3 gap-y-1">
                                    <template x-if="item.created_by_name">
                                        <div class="flex items-center text-[9px] text-slate-400">
                                            <i data-lucide="user" class="w-2.5 h-2.5 mr-1"></i>
                                            <span>Input: </span><span class="ml-1 font-medium" x-text="item.created_by_name"></span>
                                        </div>
                                    </template>
                                    <template x-if="item.updated_by_name">
                                        <div class="flex items-center text-[9px] text-slate-400">
                                            <i data-lucide="edit-2" class="w-2.5 h-2.5 mr-1"></i>
                                            <span>Edit: </span><span class="ml-1 font-medium" x-text="item.updated_by_name"></span>
                                        </div>
                                    </template>
                                </div>
                            </td>
                            <td class="px-3 py-4 text-center align-top">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-black shadow-sm" :class="getRiskColor(item.awal_level)" x-text="item.awal_skor"></span>
                                    <div class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter" x-text="item.awal_level"></div>
                                </div>
                            </td>
                            <td class="px-3 py-4 align-top">
                                <template x-for="mit in item.mitigations" :key="mit.id || Math.random()">
                                    <div class="mb-1.5 p-2 bg-slate-50/80 rounded-lg border border-slate-100/60">
                                        <div class="text-[9px] text-slate-700 font-medium leading-relaxed" x-text="mit.treatment"></div>
                                        <div class="mt-1 flex justify-end">
                                            <span class="text-[7px] px-1.5 py-0.5 rounded-full font-black uppercase shadow-sm" :class="getStatusColor(mit.status)" x-text="mit.status"></span>
                                        </div>
                                    </div>
                                </template>
                            </td>
                            <td class="px-3 py-4 text-center align-top">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-black shadow-sm" :class="getRiskColor(item.residual_level)" x-text="item.residual_skor"></span>
                                    <div class="text-[8px] text-slate-400 font-bold uppercase tracking-tighter" x-text="item.residual_level"></div>
                                </div>
                            </td>
                            <td class="px-3 py-4 align-top">
                                <div class="text-slate-900 font-bold text-[10px]" x-text="item.pj || '-'"></div>
                            </td>
                            <!-- Kolom Status Aktif -->
                            <td class="px-4 py-4 text-center align-top border-l border-slate-100/60 bg-slate-50/30">
                                <button @click="if(userEmail !== 'direktur@rsudmurjani.id' && (isAdmin || userBidang === item.bidang)) { item.is_active = !item.is_active; updateRisk(item); }" 
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    :class="item.is_active ? 'bg-teal-600' : 'bg-slate-300'"
                                    :disabled="userEmail === 'direktur@rsudmurjani.id' || (!isAdmin && userBidang !== item.bidang && userUnit !== item.unit)"
                                    :class="{'opacity-50 cursor-not-allowed': userEmail === 'direktur@rsudmurjani.id' || (!isAdmin && userBidang !== item.bidang && userUnit !== item.unit)}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm"
                                        :class="item.is_active ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                                <div class="mt-1 text-[10px] font-bold" :class="item.is_active ? 'text-teal-600' : 'text-slate-400'" x-text="item.is_active ? 'Aktif' : 'Deaktif'"></div>
                            </td>
                            <td class="px-3 py-4 text-center align-top border-l border-slate-100/60 bg-slate-50/30">
                                <select x-model="item.status" @change="updateRiskStatus(item)"
                                    class="w-full text-[9px] p-1.5 rounded-lg border-[1.5px] cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-300 font-black transition-all"
                                    :class="getStatusColor(item.status)"
                                    :disabled="userEmail === 'direktur@rsudmurjani.id' || (!isAdmin && userBidang !== item.bidang && userUnit !== item.unit)">
                                    <option value="Not Started">Belum Mulai</option>
                                    <option value="In-Progress">Berjalan</option>
                                    <option value="Completed">Selesai</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 text-center align-top border-l border-slate-100/60 bg-slate-50/30">
                                <select x-model="item.validasi" @change="updateRisk(item)"
                                    class="w-full text-[9px] p-1.5 rounded-lg border-[1.5px] cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-300 font-black transition-all"
                                    :class="getValidationColor(item.validasi)"
                                    :disabled="!(isAdmin || isUnitAdmin)">
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Valid">Valid</option>
                                    <option value="Revisi">Revisi</option>
                                </select>
                                <div x-show="item.validasi === 'Valid'" class="mt-1 text-[8px] text-teal-600 flex items-center justify-center gap-1 font-black uppercase">
                                    <i data-lucide="check-check" class="w-2.5 h-2.5"></i> Terverifikasi
                                </div>
                            </td>
                            <td class="px-3 py-4 text-center align-top border-l border-slate-100/60 bg-slate-50/30">
                                <select x-model="item.validator" @change="updateRisk(item)" 
                                    class="w-full text-[10px] p-1.5 rounded-lg border-[1.5px] border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-300 font-bold transition-all bg-white" 
                                    :disabled="!(isAdmin || isUnitAdmin)">
                                    <option value="">- Pilih Validator -</option>
                                    <option value="Kusnadi Jaya">Kusnadi Jaya</option>
                                    <option value="Direktur">Direktur</option>
                                    <option value="Wakil Direktur Umum, Anggaran dan Keuangan">Wakil Direktur Umum, Anggaran dan Keuangan</option>
                                    <option value="Wakil Direktur SDM & Pengembangan">Wakil Direktur SDM & Pengembangan</option>
                                    <option value="Wakil Direktur Pelayanan">Wakil Direktur Pelayanan</option>
                                    <option value="Komite Mutu">Komite Mutu</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 text-center align-top">
                                <div class="flex items-center justify-center gap-1" x-show="userEmail !== 'direktur@rsudmurjani.id' && (isAdmin || userBidang === item.bidang || userUnit === item.unit)">
                                    <button @click="editRisk(item)" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button @click="deleteRisk(item.id)" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                                <div x-show="userEmail === 'direktur@rsudmurjani.id' || (!isAdmin && userBidang !== item.bidang && userUnit !== item.unit)" class="text-[9px] text-slate-400 italic font-medium">
                                    Read Only
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
