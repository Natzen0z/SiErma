<!-- ANNOUNCEMENT MANAGEMENT MODAL -->
<div x-show="isAnnouncementModalOpen" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div x-show="isAnnouncementModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="isAnnouncementModalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div x-show="isAnnouncementModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row max-h-[90vh]">
            
            <!-- Left Side: List & Management -->
            <div class="flex-1 p-6 border-r border-slate-100 overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Manage Notifikasi</h3>
                        <p class="text-[10px] text-slate-400 font-medium">Buat dan atur pesan untuk unit kerja.</p>
                    </div>
                    <button @click="resetAnnouncementForm()" 
                        class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="item in myAnnouncements" :key="item.id">
                        <div class="p-4 rounded-2xl border border-slate-100 hover:border-indigo-200 transition-all group">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-xs font-bold text-slate-800" x-text="item.title"></h4>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="announcementForm = { ...item, duration: '1_week' }" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg"><i data-lucide="edit-2" class="w-3.5 h-3.5"></i></button>
                                    <button @click="deleteAnnouncement(item.id)" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 line-clamp-2 mb-3" x-text="item.message"></p>
                            <div class="flex items-center justify-between">
                                <div class="flex gap-2">
                                    <span class="text-[9px] px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full font-bold" x-text="item.bidang || 'Global'"></span>
                                    <span x-show="item.expires_at" class="text-[9px] px-2 py-0.5 bg-amber-50 text-amber-600 rounded-full font-bold">
                                        Exp: <span x-text="new Date(item.expires_at).toLocaleDateString()"></span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold" :class="item.is_active ? 'text-teal-600' : 'text-slate-300'" x-text="item.is_active ? 'Aktif' : 'Nonaktif'"></span>
                                    <button @click="item.is_active = !item.is_active; updateAnnouncement(item)" class="relative inline-flex h-4 w-8 items-center rounded-full transition-colors" :class="item.is_active ? 'bg-teal-500' : 'bg-slate-200'">
                                        <span class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform" :class="item.is_active ? 'translate-x-4' : 'translate-x-1'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full md:w-[380px] bg-slate-50/50 p-6 flex flex-col">
                <div class="mb-6">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider" x-text="announcementForm.id ? 'Edit Notifikasi' : 'Buat Notifikasi Baru'"></h3>
                </div>

                <div class="space-y-4 flex-1 overflow-y-auto pr-1">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Judul Notifikasi</label>
                        <input type="text" x-model="announcementForm.title" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Contoh: Pengingat Update Mitigasi">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Pesan</label>
                        <textarea x-model="announcementForm.message" rows="4" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all resize-none" placeholder="Isi pesan notifikasi..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Durasi Tampil</label>
                            <select x-model="announcementForm.duration" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                <option value="1_week">1 Minggu</option>
                                <option value="1_month">1 Bulan</option>
                                <option value="always">Selamanya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">Target Bidang</label>
                            <select x-model="announcementForm.bidang" :disabled="!isAdmin" class="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :class="!isAdmin ? 'bg-slate-100 opacity-70' : ''">
                                <option value="">Global (Semua)</option>
                                <template x-for="b in [...new Set(availableUnits.map(u => u.bidang).filter(b => b))]" :key="b">
                                    <option :value="b" x-text="b"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Target Unit Kerja</label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" @change="$event.target.checked ? announcementForm.target_units = availableUnits.filter(u => !announcementForm.bidang || u.bidang === announcementForm.bidang).map(u => u.name) : announcementForm.target_units = []" 
                                    :checked="announcementForm.target_units.length > 0 && announcementForm.target_units.length === availableUnits.filter(u => !announcementForm.bidang || u.bidang === announcementForm.bidang).length"
                                    class="w-3.5 h-3.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-[9px] font-bold text-indigo-600 uppercase">Select All</span>
                            </label>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-xl p-3 max-h-40 overflow-y-auto space-y-1">
                            <template x-for="unit in availableUnits.filter(u => !announcementForm.bidang || u.bidang === announcementForm.bidang)" :key="unit.id">
                                <label class="flex items-center gap-2 p-1.5 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">
                                    <input type="checkbox" :value="unit.name" x-model="announcementForm.target_units" class="w-3.5 h-3.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-[10px] font-medium text-slate-600" x-text="unit.name"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex gap-3">
                    <button @click="isAnnouncementModalOpen = false" class="flex-1 py-3 text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">Batal</button>
                    <button @click="saveAnnouncement()" class="flex-[2] py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all active:scale-95" x-text="announcementForm.id ? 'UPDATE' : 'SIMPAN'"></button>
                </div>
            </div>
        </div>
    </div>
</div>
