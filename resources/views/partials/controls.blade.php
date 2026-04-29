<!-- 4. CONTROLS VIEW -->
<div x-show="activeTab === 'controls'" x-transition:enter="transition ease-out duration-300">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-8">
        <div class="flex items-center mb-6 text-amber-600 bg-gradient-to-r from-amber-50 to-orange-50/50 p-4 rounded-xl border border-amber-100/60">
           <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
               <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
           </div>
           <div>
             <h3 class="font-bold text-sm">Modul Pengendalian</h3>
             <p class="text-xs text-amber-700/70 mt-0.5">Modul ini akan aktif setelah Anda mengisi data risiko pada menu Daftar Risiko.</p>
           </div>
        </div>

        <div x-show="filteredData().length > 0">
            <h3 class="font-bold text-sm text-slate-800 mb-4 uppercase tracking-wider flex items-center">
                <div class="w-7 h-7 bg-teal-100 rounded-lg flex items-center justify-center mr-2.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-teal-600"></i>
                </div>
                Mitigasi Risiko & Status Aksi
            </h3>
            <div class="space-y-4">
                <template x-for="item in filteredData()" :key="item.id">
                    <div class="border border-slate-100 rounded-2xl p-5 hover:shadow-md transition-all duration-200 hover:border-slate-200 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-teal-500 to-blue-500 rounded-l-2xl"></div>
                        <div class="flex justify-between items-start mb-3 pl-3">
                          <div>
                              <span class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase tracking-wider mb-1.5" x-text="item.unit"></span>
                              <h4 class="font-bold text-slate-800 text-sm" x-text="item.risiko"></h4>
                          </div>
                          <span class="text-[10px] font-bold px-2.5 py-1 bg-slate-100 rounded-lg text-slate-500 uppercase tracking-wider flex-shrink-0" x-text="'Target: ' + item.triwulan"></span>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4 text-sm mt-3 pl-3">
                          <div>
                              <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-2">Aksi Mitigasi</p>
                              <div class="space-y-2">
                                  <template x-for="mit in item.mitigations" :key="mit.id || Math.random()">
                                      <div class="text-slate-700 bg-teal-50/50 p-3 rounded-xl border border-teal-100/60 flex flex-col justify-between">
                                          <div class="flex justify-between items-center mb-2">
                                              <span class="text-xs font-medium" x-text="mit.treatment"></span>
                                              <span class="text-[9px] px-2 py-0.5 rounded-full font-bold uppercase ml-2 flex-shrink-0" :class="getStatusColor(mit.status)" x-text="mit.status"></span>
                                          </div>
                                          <template x-if="mit.evidence_link">
                                              <div class="mt-1">
                                                  <a :href="mit.evidence_link" target="_blank" class="inline-flex items-center text-[10px] bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 px-2.5 py-1 rounded-md font-bold transition-all w-fit">
                                                      <i data-lucide="external-link" class="w-3 h-3 mr-1.5 text-blue-500"></i>Lihat Bukti
                                                  </a>
                                              </div>
                                          </template>
                                      </div>
                                  </template>
                              </div>
                          </div>
                          <div>
                              <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-2">Informasi Pengelola</p>
                              <div class="p-3 rounded-xl border border-blue-100/60 bg-blue-50/50 text-slate-700">
                                  <div class="font-bold text-blue-800 text-xs" x-text="'PJ: ' + (item.pj || '-')"></div>
                                  <div class="text-[11px] mt-1.5 text-slate-500" x-text="'Target: ' + (item.triwulan || '-')"></div>
                                  <div class="text-[11px] text-slate-500" x-text="'Status Risiko: ' + item.status"></div>
                              </div>
                          </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
