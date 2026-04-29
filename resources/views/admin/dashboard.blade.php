@extends('layouts.app')

@section('title', 'Admin Dashboard - Risk Management System')

@section('content')
<div x-data="adminDashboard()" x-init="init()">
    @include('admin.partials.sidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-72 p-4 md:p-8 transition-all duration-300 ease-in-out">
        
        <!-- HEADER -->
        <header class="mb-8 animate-fade-in">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">{{ Auth::user()->isAuditor() ? 'Dashboard Auditor' : 'Dashboard Admin' }}</h1>
            <p class="text-slate-400 mt-1 text-sm font-medium">Selamat datang, {{ Auth::user()->name }}</p>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 stagger-children">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 card-hover stat-accent stat-accent-blue animate-fade-in-up">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <i data-lucide="users" class="w-5 h-5 text-white"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800 pl-3">{{ $totalUsers }}</h3>
                <p class="text-slate-400 text-xs font-semibold pl-3 mt-0.5 uppercase tracking-wider">Total User</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 card-hover stat-accent stat-accent-teal animate-fade-in-up">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-500/20">
                        <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800 pl-3">{{ $totalRisks }}</h3>
                <p class="text-slate-400 text-xs font-semibold pl-3 mt-0.5 uppercase tracking-wider">Total Risiko</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 card-hover stat-accent stat-accent-red animate-fade-in-up">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-white"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800 pl-3">{{ $highRisks }}</h3>
                <p class="text-slate-400 text-xs font-semibold pl-3 mt-0.5 uppercase tracking-wider">Risiko Tinggi/Kritis</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 card-hover stat-accent stat-accent-green animate-fade-in-up">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <i data-lucide="check-circle" class="w-5 h-5 text-white"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800 pl-3">{{ $completedRisks }}</h3>
                <p class="text-slate-400 text-xs font-semibold pl-3 mt-0.5 uppercase tracking-wider">Mitigasi Selesai</p>
            </div>
        </div>

        <!-- Recent Risks Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="p-6 border-b border-slate-100/80">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Risiko Terbaru (Semua Unit)</h2>
                <p class="text-slate-400 text-xs mt-1">Menampilkan 10 risiko terbaru dari semua user</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-5 py-4">Kode</th>
                            <th class="px-5 py-4">User</th>
                            <th class="px-5 py-4">Unit</th>
                            <th class="px-5 py-4">Risiko</th>
                            <th class="px-5 py-4 text-center">Level</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @forelse($recentRisks as $risk)
                        <tr class="table-row-hover hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-900 text-xs">{{ $risk->kode }}</td>
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $risk->user ? $risk->user->name : 'N/A' }}</td>
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $risk->unit }}</td>
                            <td class="px-5 py-4 text-slate-700 max-w-xs truncate text-xs font-medium">{{ $risk->risiko }}</td>
                            <td class="px-5 py-4 text-center">
                                @php
                                    $levelColors = [
                                        'Kritis' => 'bg-red-600 text-white',
                                        'Tinggi' => 'bg-orange-500 text-white',
                                        'Sedang' => 'bg-yellow-400 text-black',
                                        'Rendah' => 'bg-green-500 text-white',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold {{ $levelColors[$risk->awal_level] ?? 'bg-slate-200' }}">
                                    {{ $risk->awal_level }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusColors = [
                                        'Completed' => 'bg-emerald-100 text-emerald-700',
                                        'In-Progress' => 'bg-blue-100 text-blue-700',
                                        'Not Started' => 'bg-gray-100 text-gray-600',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $statusColors[$risk->status] ?? 'bg-gray-100' }}">
                                    {{ $risk->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-sm font-medium">
                                Belum ada data risiko.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 stagger-children">
            @if(!Auth::user()->isAuditor())
            <a href="{{ route('admin.users') }}" class="bg-gradient-to-br from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white rounded-2xl p-6 flex items-center transition-all card-hover shadow-lg shadow-blue-600/20 btn-shimmer animate-fade-in-up">
                <div class="w-12 h-12 bg-white/15 rounded-xl flex items-center justify-center mr-4">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Kelola User</h3>
                    <p class="text-blue-200 text-xs mt-0.5">Lihat dan kelola semua pengguna</p>
                </div>
            </a>
            @endif
            <a href="{{ route('admin.risks') }}" class="bg-gradient-to-br from-teal-600 to-teal-700 hover:from-teal-500 hover:to-teal-600 text-white rounded-2xl p-6 flex items-center transition-all card-hover shadow-lg shadow-teal-600/20 btn-shimmer animate-fade-in-up">
                <div class="w-12 h-12 bg-white/15 rounded-xl flex items-center justify-center mr-4">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Lihat Semua Risiko</h3>
                    <p class="text-teal-200 text-xs mt-0.5">Akses data risiko semua unit</p>
                </div>
            </a>
            <a href="{{ route('risk.index') }}" class="bg-gradient-to-br from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600 text-white rounded-2xl p-6 flex items-center transition-all card-hover shadow-lg shadow-purple-600/20 btn-shimmer animate-fade-in-up">
                <div class="w-12 h-12 bg-white/15 rounded-xl flex items-center justify-center mr-4">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Risk Dashboard</h3>
                    <p class="text-purple-200 text-xs mt-0.5">Kembali ke dashboard risiko</p>
                </div>
            </a>
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script>
    function adminDashboard() {
        return {
            init() {
                setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100);
            }
        }
    }
</script>
@endpush
