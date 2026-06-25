@extends('layouts.app')

@section('title', 'Kelola Unit - Admin Panel')

@section('content')
<div x-data="{ showModal: false, showSyncModal: false, syncUnit: null, selectedUsers: [] }" x-init="setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100)">
    @include('admin.partials.sidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-72 p-4 md:p-8">
        <header class="mb-8 flex justify-between items-center animate-fade-in">
            <div>
               <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Kelola Unit/Bagian</h1>
               <p class="text-slate-400 mt-1 text-sm font-medium">Tambah atau hapus unit/bagian rumah sakit</p>
            </div>
            <button @click="showModal = true" class="bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white py-2.5 px-5 rounded-xl flex items-center font-semibold text-sm transition-all shadow-lg shadow-teal-600/20 btn-shimmer">
                <i data-lucide="plus" class="w-[18px] h-[18px] mr-2"></i>
                Tambah Unit
            </button>
        </header>

        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50/80 border border-emerald-200/60 rounded-2xl flex items-center text-emerald-700 animate-fade-in-up">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2.5 flex-shrink-0"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50/80 border border-red-200/60 rounded-2xl text-red-700 animate-fade-in-up">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Units Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="p-6 border-b border-slate-100/80">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Daftar Unit ({{ $units->count() }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-6 py-4 w-16">No</th>
                            <th class="px-6 py-4">Nama Unit</th>
                            <th class="px-6 py-4">Bidang</th>
                            <th class="px-6 py-4">Ditambahkan</th>
                            <th class="px-6 py-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @forelse($units as $index => $unit)
                        <tr class="table-row-hover hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-400 text-xs font-semibold">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-xs">
                                <div class="flex items-center">
                                    <div class="p-2 bg-gradient-to-br from-teal-50 to-teal-100/60 text-teal-600 rounded-xl mr-3 border border-teal-100/60">
                                        <i data-lucide="building" class="w-4 h-4"></i>
                                    </div>
                                    {{ $unit->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider" 
                                    :class="'{{ $unit->bidang }}' === 'Pelayanan' ? 'bg-blue-50 text-blue-600' : 
                                           ('{{ $unit->bidang }}' === 'SDM & Pengembangan' ? 'bg-amber-50 text-amber-600' : 
                                           ('{{ $unit->bidang }}' === 'Umum, Anggaran dan Keuangan' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100 text-slate-500'))">
                                    {{ $unit->bidang ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs">{{ $unit->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" @click="syncUnit = {{ json_encode($unit) }}; selectedUsers = {{ json_encode($unit->users->pluck('id')) }}; showSyncModal = true" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Kelola User">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.units.destroy', $unit) }}" class="inline" onsubmit="return confirm('Hapus unit {{ $unit->name }}? Unit ini akan dihapus dari daftar pilihan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Unit">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm font-medium">
                                Belum ada unit. Klik "Tambah Unit" untuk menambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Unit Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showModal = false"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4" @click.stop
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-lg font-extrabold text-slate-800 mb-6">Tambah Unit Baru</h3>
            <form method="POST" action="{{ route('admin.units.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Nama Unit</label>
                    <input type="text" name="name" required placeholder="Contoh: Rawat Inap Mawar" class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 text-sm font-medium transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Bidang</label>
                    <select name="bidang" required class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 text-sm font-medium transition-all bg-white">
                        <option value="">Pilih Bidang</option>
                        <option value="Pelayanan">Pelayanan</option>
                        <option value="SDM & Pengembangan">SDM & Pengembangan</option>
                        <option value="Umum, Anggaran dan Keuangan">Umum, Anggaran dan Keuangan</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showModal = false" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl font-semibold text-sm transition-all">Batal</button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white py-3 rounded-xl font-semibold text-sm transition-all shadow-lg shadow-teal-600/20 btn-shimmer">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sync Users Modal -->
    <div x-show="showSyncModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showSyncModal = false"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col" @click.stop
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-lg font-extrabold text-slate-800 mb-2">Kelola User Unit: <span x-text="syncUnit ? syncUnit.name : ''" class="text-teal-600"></span></h3>
            <p class="text-sm text-slate-500 mb-6">Pilih user yang menjadi bagian dari unit ini.</p>

            <form method="POST" :action="syncUnit ? '{{ url('/admin/units') }}/' + syncUnit.id + '/users/sync' : '#'" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="overflow-y-auto flex-1 border border-slate-200 rounded-xl p-4 space-y-3 mb-6 bg-slate-50">
                    @foreach($users as $user)
                    <label class="flex items-center p-3 bg-white border border-slate-200 rounded-lg cursor-pointer hover:border-teal-400 transition-colors">
                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" x-model="selectedUsers" class="w-5 h-5 text-teal-600 bg-slate-100 border-slate-300 rounded focus:ring-teal-500 focus:ring-2">
                        <div class="ml-3 flex flex-col">
                            <span class="text-sm font-semibold text-slate-800">{{ $user->name }}</span>
                            <span class="text-xs text-slate-500">{{ $user->email }} - {{ ucfirst($user->role) }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="flex gap-3 mt-auto">
                    <button type="button" @click="showSyncModal = false" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl font-semibold text-sm transition-all">Batal</button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white py-3 rounded-xl font-semibold text-sm transition-all shadow-lg shadow-teal-600/20 btn-shimmer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
