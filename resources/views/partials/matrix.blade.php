<!-- 3. MATRIX VIEW -->
<div x-show="activeTab === 'matrix'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100/80">
        <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center uppercase tracking-wider">
            <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-teal-500/20">
                <i data-lucide="target" class="w-4 h-4 text-white"></i>
            </div>
            Peta Panas Risiko (Risk Heatmap)
        </h2>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8 bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <i data-lucide="filter" class="w-5 h-5 text-teal-600"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-tight">Filter Analisis</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Saring visualisasi berdasarkan waktu & unit.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <select x-model="filterTriwulan" class="p-2.5 text-xs bg-white border-[1.5px] border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-teal-500 focus:outline-none font-bold transition-all shadow-sm">
                    <option value="">SEMUA TRIWULAN</option>
                    <option value="Triwulan 1">TRIWULAN 1 (JAN-MAR)</option>
                    <option value="Triwulan 2">TRIWULAN 2 (APR-JUN)</option>
                    <option value="Triwulan 3">TRIWULAN 3 (JUL-SEP)</option>
                    <option value="Triwulan 4">TRIWULAN 4 (OKT-DES)</option>
                </select>



                <select x-model="filterUnit" class="p-2.5 text-xs bg-white border-[1.5px] border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-teal-500 focus:outline-none font-bold transition-all shadow-sm" :disabled="isRestricted">
                    <option value="">SEMUA UNIT</option>
                    <template x-for="unit in availableUnits" :key="unit.id">
                        <option :value="unit.name" x-text="unit.name"></option>
                    </template>
                </select>
            </div>
        </div>

        <div x-show="getMatrixData().length === 0" class="text-center py-12 text-slate-400 border-2 border-dashed border-slate-200 rounded-xl">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="grid-3x3" class="w-8 h-8 text-slate-300"></i>
            </div>
            <p class="font-medium">Tidak ada data untuk filter yang dipilih.</p>
        </div>

        <div x-show="getMatrixData().length > 0">
            <div class="flex flex-col items-center overflow-x-auto">
                <div class="flex items-center">
                    <!-- Y Axis Label -->
                    <div class="-rotate-90 font-bold text-slate-400 tracking-widest text-[10px] uppercase w-8 text-center mr-4">Probabilitas</div>
                    
                    <!-- Y Labels -->
                    <div class="grid grid-rows-5 gap-1.5">
                        <div class="h-20 flex items-center justify-end pr-3 font-semibold text-slate-400 text-xs">Sgt Tinggi</div>
                        <div class="h-20 flex items-center justify-end pr-3 font-semibold text-slate-400 text-xs">Tinggi</div>
                        <div class="h-20 flex items-center justify-end pr-3 font-semibold text-slate-400 text-xs">Sedang</div>
                        <div class="h-20 flex items-center justify-end pr-3 font-semibold text-slate-400 text-xs">Rendah</div>
                        <div class="h-20 flex items-center justify-end pr-3 font-semibold text-slate-400 text-xs">Sgt Rendah</div>
                    </div>

                    <!-- The Matrix Grid -->
                    <div class="grid grid-cols-5 grid-rows-5 gap-1.5 ml-2">
                        <!-- Row 5 (Prob 5) -->
                        <div class="w-24 h-20 bg-yellow-400 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:5 D:1">
                            <span x-text="getMatrixCount(5,1)" x-show="getMatrixCount(5,1) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:5 D:2">
                            <span x-text="getMatrixCount(5,2)" x-show="getMatrixCount(5,2) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:5 D:3">
                            <span x-text="getMatrixCount(5,3)" x-show="getMatrixCount(5,3) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:5 D:4">
                            <span x-text="getMatrixCount(5,4)" x-show="getMatrixCount(5,4) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:5 D:5">
                            <span x-text="getMatrixCount(5,5)" x-show="getMatrixCount(5,5) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>

                        <!-- Row 4 (Prob 4) -->
                        <div class="w-24 h-20 bg-yellow-400 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:4 D:1">
                            <span x-text="getMatrixCount(4,1)" x-show="getMatrixCount(4,1) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:4 D:2">
                            <span x-text="getMatrixCount(4,2)" x-show="getMatrixCount(4,2) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:4 D:3">
                            <span x-text="getMatrixCount(4,3)" x-show="getMatrixCount(4,3) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:4 D:4">
                            <span x-text="getMatrixCount(4,4)" x-show="getMatrixCount(4,4) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:4 D:5">
                            <span x-text="getMatrixCount(4,5)" x-show="getMatrixCount(4,5) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>

                        <!-- Row 3 (Prob 3) -->
                        <div class="w-24 h-20 bg-green-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:3 D:1">
                            <span x-text="getMatrixCount(3,1)" x-show="getMatrixCount(3,1) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-yellow-400 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:3 D:2">
                            <span x-text="getMatrixCount(3,2)" x-show="getMatrixCount(3,2) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:3 D:3">
                            <span x-text="getMatrixCount(3,3)" x-show="getMatrixCount(3,3) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:3 D:4">
                            <span x-text="getMatrixCount(3,4)" x-show="getMatrixCount(3,4) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-red-600 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:3 D:5">
                            <span x-text="getMatrixCount(3,5)" x-show="getMatrixCount(3,5) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>

                        <!-- Row 2 (Prob 2) -->
                        <div class="w-24 h-20 bg-green-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:2 D:1">
                            <span x-text="getMatrixCount(2,1)" x-show="getMatrixCount(2,1) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-green-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:2 D:2">
                            <span x-text="getMatrixCount(2,2)" x-show="getMatrixCount(2,2) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-yellow-400 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:2 D:3">
                            <span x-text="getMatrixCount(2,3)" x-show="getMatrixCount(2,3) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:2 D:4">
                            <span x-text="getMatrixCount(2,4)" x-show="getMatrixCount(2,4) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:2 D:5">
                            <span x-text="getMatrixCount(2,5)" x-show="getMatrixCount(2,5) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>

                        <!-- Row 1 (Prob 1) -->
                        <div class="w-24 h-20 bg-green-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:1 D:1">
                            <span x-text="getMatrixCount(1,1)" x-show="getMatrixCount(1,1) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-green-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:1 D:2">
                            <span x-text="getMatrixCount(1,2)" x-show="getMatrixCount(1,2) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-yellow-400 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:1 D:3">
                            <span x-text="getMatrixCount(1,3)" x-show="getMatrixCount(1,3) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-yellow-400 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:1 D:4">
                            <span x-text="getMatrixCount(1,4)" x-show="getMatrixCount(1,4) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                        <div class="w-24 h-20 bg-orange-500 rounded-xl flex items-center justify-center relative shadow-sm hover:scale-105 transition-transform cursor-default" title="P:1 D:5">
                            <span x-text="getMatrixCount(1,5)" x-show="getMatrixCount(1,5) > 0" class="text-2xl font-extrabold text-white drop-shadow-md"></span>
                        </div>
                    </div>
                </div>

                 <!-- X Axis -->
                 <div class="flex mt-2 ml-24">
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-xs">Sgt Ringan</div>
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-xs">Ringan</div>
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-xs">Sedang</div>
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-xs">Berat</div>
                    <div class="w-24 text-center pt-2 font-semibold text-slate-400 text-xs">Sgt Berat</div>
                </div>
                <div class="mt-2 font-bold text-slate-400 tracking-widest text-[10px] uppercase ml-24">Dampak</div>
            </div>
            
            <!-- Legend -->
            <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-3 max-w-2xl mx-auto">
                <div class="flex items-center p-2.5 bg-green-50 rounded-xl border border-green-100">
                    <div class="w-4 h-4 bg-green-500 rounded-md mr-2.5 flex-shrink-0"></div>
                    <span class="text-xs font-semibold text-slate-600">Rendah (1-4)</span>
                </div>
                <div class="flex items-center p-2.5 bg-yellow-50 rounded-xl border border-yellow-100">
                    <div class="w-4 h-4 bg-yellow-400 rounded-md mr-2.5 flex-shrink-0"></div>
                    <span class="text-xs font-semibold text-slate-600">Sedang (5-9)</span>
                </div>
                <div class="flex items-center p-2.5 bg-orange-50 rounded-xl border border-orange-100">
                    <div class="w-4 h-4 bg-orange-500 rounded-md mr-2.5 flex-shrink-0"></div>
                    <span class="text-xs font-semibold text-slate-600">Tinggi (10-14)</span>
                </div>
                <div class="flex items-center p-2.5 bg-red-50 rounded-xl border border-red-100">
                    <div class="w-4 h-4 bg-red-600 rounded-md mr-2.5 flex-shrink-0"></div>
                    <span class="text-xs font-semibold text-slate-600">Kritis (15-25)</span>
                </div>
            </div>
        </div>
    </div>
</div>
