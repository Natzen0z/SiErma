<!-- 5. ASSESSMENT VIEW -->
<div x-show="activeTab === 'assessment'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    
    <!-- Assessment Controls -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Assessment & Audit Kepatuhan</h3>
                <p class="text-xs text-slate-400">Kelola self-assessment unit dan hasil audit auditor.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <select x-model="filterAsmStatus" class="p-2.5 text-xs bg-slate-50 border-[1.5px] border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold transition-all shadow-sm">
                <option value="">SEMUA STATUS</option>
                <option value="Draft">DRAFT</option>
                <option value="Submitted">SUBMITTED (UNIT)</option>
                <option value="Final">FINAL (AUDITOR)</option>
            </select>

            <select x-model="asmSortBy" class="p-2.5 text-xs bg-slate-50 border-[1.5px] border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none font-bold transition-all shadow-sm">
                <option value="date">URUT: TERBARU</option>
                <option value="score">URUT: SKOR TERTINGGI</option>
            </select>

            @if(!Auth::user()->isAuditor())
            <button @click="openAssessment()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center shadow-lg shadow-blue-500/20">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Mulai Self Assessment
            </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
        <div x-show="filteredAssessments().length === 0" class="p-12 text-center text-slate-400">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
            </div>
            <p class="font-medium text-sm">Tidak ada data assessment yang ditemukan.</p>
        </div>

        <div x-show="filteredAssessments().length > 0" class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-6 py-4">Periode & Unit</th>
                        <th class="px-6 py-4 text-center">Self Status</th>
                        <th class="px-6 py-4 text-center">Auditor Status</th>
                        <th class="px-6 py-4">Skor & Catatan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    <template x-for="asm in filteredAssessments()" :key="asm.id">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800" x-text="asm.triwulan + ' - ' + asm.period_year"></div>
                                <div class="text-[10px] text-slate-500 font-medium uppercase mt-0.5" x-text="asm.unit"></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase" 
                                    :class="asm.self_status === 'Submitted' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500'"
                                    x-text="asm.self_status || 'Draft'"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase" 
                                    :class="asm.status === 'Final' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                                    x-text="asm.status"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 mb-1" x-show="asm.status === 'Final'">
                                    <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-black" x-text="(asm.total_score || 0) + ' / 100'"></span>
                                </div>
                                <p class="text-[11px] text-slate-500 italic line-clamp-1" x-text="asm.auditor_notes || 'Belum ada catatan auditor'"></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button @click="openAssessment(asm)" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Buka Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <template x-if="asm.status === 'Final'">
                                        <a :href="'/auditor/assessment/' + asm.id + '/print'" target="_blank" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Cetak Hasil">
                                            <i data-lucide="printer" class="w-4 h-4"></i>
                                        </a>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Assessment Form Modal -->
    <div x-show="isAssessmentModalOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isAssessmentModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/90 backdrop-blur-md transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isAssessmentModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-white/20">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-8 py-6 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center border border-blue-500/30">
                            <i data-lucide="clipboard-check" class="w-6 h-6 text-blue-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white tracking-tight uppercase" x-text="'Assessment - ' + (currentAssessment.unit || userUnit)"></h3>
                            <p class="text-blue-300/60 text-[10px] font-bold uppercase tracking-widest" x-text="currentAssessment.triwulan + ' ' + currentAssessment.period_year"></p>
                        </div>
                    </div>
                    <button @click="isAssessmentModalOpen = false" class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-white/10 transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="p-8 max-h-[70vh] overflow-y-auto bg-slate-50/50">
                    <div class="grid grid-cols-1 gap-8">
                        <!-- Questions Section -->
                        <div class="space-y-6">
                            <template x-for="(q, idx) in assessmentQuestions" :key="q.id">
                                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60 flex flex-col gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center shrink-0 text-xs font-black text-slate-500" x-text="idx + 1"></div>
                                        <p class="text-sm font-bold text-slate-700 leading-relaxed" x-text="q.text"></p>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-12">
                                        <!-- Self Assessment (Unit) -->
                                        <div class="space-y-2 p-4 bg-slate-50/80 rounded-xl border border-slate-200/50">
                                            <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-widest">Self Assessment (Unit)</label>
                                            <select x-model="currentAssessment.self_answers[q.id]" :disabled="{{ Auth::user()->isAuditor() ? 'true' : 'false' }} || currentAssessment.self_status === 'Submitted'"
                                                class="w-full p-2 text-xs font-bold bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                                <option value="">Pilih Skor</option>
                                                <option value="4">4 - Sangat Baik</option>
                                                <option value="3">3 - Baik</option>
                                                <option value="2">2 - Cukup</option>
                                                <option value="1">1 - Kurang</option>
                                            </select>
                                        </div>

                                        <!-- Auditor Assessment -->
                                        <div class="space-y-2 p-4 bg-blue-50/50 rounded-xl border border-blue-100/50">
                                            <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest">Penilaian Auditor</label>
                                            <select x-model="currentAssessment.answers[q.id]" :disabled="!{{ Auth::user()->isAuditor() ? 'true' : 'false' }} || currentAssessment.status === 'Final'"
                                                class="w-full p-2 text-xs font-bold bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none disabled:opacity-60">
                                                <option value="">Pilih Skor</option>
                                                <option value="4">4 - Sangat Baik</option>
                                                <option value="3">3 - Baik</option>
                                                <option value="2">2 - Cukup</option>
                                                <option value="1">1 - Kurang</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Auditor Notes -->
                        @if(Auth::user()->isAuditor())
                        <div class="bg-blue-600 p-6 rounded-2xl shadow-xl shadow-blue-900/20 text-white">
                            <label class="block text-xs font-black uppercase tracking-widest mb-3 flex items-center">
                                <i data-lucide="message-square" class="w-4 h-4 mr-2"></i>
                                Catatan & Rekomendasi Auditor
                            </label>
                            <textarea x-model="currentAssessment.auditor_notes" :disabled="currentAssessment.status === 'Final'"
                                class="w-full p-4 bg-white/10 border border-white/20 rounded-xl text-sm placeholder-white/40 focus:ring-2 focus:ring-white/30 outline-none min-h-[100px]"
                                placeholder="Tuliskan catatan evaluasi atau saran perbaikan untuk unit kerja..."></textarea>
                        </div>
                        @else
                        <template x-if="currentAssessment.auditor_notes">
                            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                                <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Catatan Auditor</label>
                                <p class="text-sm text-slate-700 italic leading-relaxed" x-text="currentAssessment.auditor_notes"></p>
                            </div>
                        </template>
                        @endif
                    </div>
                </div>

                <div class="bg-white px-8 py-6 border-t border-slate-100 flex flex-col md:flex-row justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Status:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter" 
                            :class="currentAssessment.status === 'Final' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                            x-text="currentAssessment.status === 'Final' ? 'AUDIT SELESAI' : 'PROSES REVIEW'"></span>
                    </div>

                    <div class="flex gap-3">
                        @if(!Auth::user()->isAuditor())
                            <!-- Unit Actions -->
                            <template x-if="currentAssessment.self_status !== 'Submitted'">
                                <div class="flex gap-3">
                                    <button @click="saveAssessment('self', 'Draft')" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-all">
                                        Simpan Draft
                                    </button>
                                    <button @click="confirm('Kirim assessment ke auditor? Data tidak dapat diubah setelah dikirim.') && saveAssessment('self', 'Submitted')" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/20">
                                        Simpan & Kirim ke Auditor
                                    </button>
                                </div>
                            </template>
                        @else
                            <!-- Auditor Actions -->
                            <template x-if="currentAssessment.status !== 'Final'">
                                <div class="flex gap-3">
                                    <button @click="saveAssessment('auditor', 'Draft')" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-all">
                                        Simpan Draft Audit
                                    </button>
                                    <button @click="confirm('Selesaikan audit? Hasil akan dipublikasikan ke unit.') && saveAssessment('auditor', 'Final')" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20">
                                        Selesaikan & Publikasi Audit
                                    </button>
                                </div>
                            </template>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
