@extends('layouts.app')

@section('title', 'Kelola User - Admin Panel')

@section('content')
<div x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }}, editUser: null, roleType: '{{ old('role', 'user') }}' }" x-init="setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100)">
    @include('admin.partials.sidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-72 p-4 md:p-8">
        <header class="mb-8 flex justify-between items-center animate-fade-in">
            <div>
               <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Kelola User</h1>
               <p class="text-slate-400 mt-1 text-sm font-medium">Lihat dan kelola pengguna sistem</p>
            </div>
            <button @click="showModal = true; editUser = null; roleType = 'user'" class="bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white py-2.5 px-5 rounded-xl flex items-center font-semibold text-sm transition-all shadow-lg shadow-teal-600/20 btn-shimmer">
                <i data-lucide="user-plus" class="w-[18px] h-[18px] mr-2"></i>
                Tambah User
            </button>
        </header>

        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50/80 border border-emerald-200/60 rounded-2xl flex items-center text-emerald-700 animate-fade-in-up">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2.5 flex-shrink-0"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50/80 border border-red-200/60 rounded-2xl flex items-start text-red-700 animate-fade-in-up">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-2.5 flex-shrink-0 mt-0.5"></i>
            <div class="text-sm font-medium">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-4">Nama</th>
                            <th class="px-5 py-4">Email</th>
                            <th class="px-5 py-4">Unit</th>
                            <th class="px-5 py-4">Bidang</th>
                            <th class="px-5 py-4">Role</th>
                            <th class="px-5 py-4">Password</th>
                            <th class="px-5 py-4 text-center">Risiko</th>
                            <th class="px-5 py-4">Dibuat</th>
                            <th class="px-5 py-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @foreach($users as $user)
                        <tr class="table-row-hover hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-900 text-xs">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $user->unit ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-500 text-xs font-bold uppercase">{{ $user->bidang ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-700' : ($user->role === 'auditor' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <code class="text-[11px] bg-slate-100 px-2 py-0.5 rounded-md font-mono font-medium text-slate-600">{{ $user->password_plain ?? '********' }}</code>
                            </td>
                            <td class="px-5 py-4 text-center text-slate-700 font-bold text-xs">{{ $user->risks_count }}</td>
                            <td class="px-5 py-4 text-slate-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-center flex items-center justify-center space-x-1">
                                <button @click="editUser = {{ json_encode($user) }}; roleType = editUser.role; showModal = true" class="p-2 text-slate-400 hover:text-teal-500 hover:bg-teal-50 rounded-lg transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($user->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- User Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showModal = false"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4" @click.stop
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-lg font-extrabold text-slate-800 mb-6" x-text="editUser ? 'Edit User' : 'Tambah User Baru'"></h3>
            
            <form :action="editUser ? '{{ url('/admin/users') }}/' + editUser.id : '{{ route('admin.users.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editUser">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Nama</label>
                    <input type="text" name="name" required :value="editUser ? editUser.name : ''" class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 text-sm font-medium transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Email</label>
                    <input type="email" name="email" required :value="editUser ? editUser.email : ''" class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 text-sm font-medium transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider" x-text="editUser ? 'Password (Kosongkan jika tidak ubah)' : 'Password'"></label>
                    <input type="text" name="password" :required="!editUser" class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 text-sm font-medium transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Role</label>
                    <select name="role" x-model="roleType" class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 text-sm font-medium transition-all">
                        <option value="user" :selected="editUser && editUser.role === 'user'">User</option>
                        <option value="admin" :selected="editUser && editUser.role === 'admin'">Admin</option>
                        <option value="auditor" :selected="editUser && editUser.role === 'auditor'">Auditor</option>
                    </select>
                </div>
                <div x-show="roleType !== 'auditor'">
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Unit/Bagian</label>
                    <select name="unit" class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 text-sm font-medium transition-all" x-bind:disabled="roleType === 'auditor'">
                        <option value="">-- Tidak Ada Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->name }}" :selected="editUser && editUser.unit === '{{ $unit->name }}'">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="roleType === 'auditor'" x-cloak>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Unit/Bagian</label>
                    <div class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl bg-slate-50 text-slate-400 text-sm font-medium flex items-center justify-between">
                        <span>Akses Semua Unit</span>
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Bidang</label>
                    <select name="bidang" class="w-full p-3 border-[1.5px] border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none focus:border-teal-500 text-sm font-medium transition-all bg-white">
                        <option value="">-- Tanpa Bidang --</option>
                        <option value="Pelayanan" :selected="editUser && editUser.bidang === 'Pelayanan'">Pelayanan</option>
                        <option value="SDM & Pengembangan" :selected="editUser && editUser.bidang === 'SDM & Pengembangan'">SDM & Pengembangan</option>
                        <option value="Umum, Anggaran dan Keuangan" :selected="editUser && editUser.bidang === 'Umum, Anggaran dan Keuangan'">Umum, Anggaran dan Keuangan</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showModal = false" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl font-semibold text-sm transition-all">Batal</button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white py-3 rounded-xl font-semibold text-sm transition-all shadow-lg shadow-teal-600/20 btn-shimmer" x-text="editUser ? 'Update' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
