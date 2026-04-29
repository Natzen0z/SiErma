@extends('layouts.app')

@section('title', 'Semua Risiko - Admin Panel')

@section('content')
<div x-data="{ searchTerm: '', filterUnit: '' }" x-init="setTimeout(() => { if (window.lucide) window.lucide.createIcons(); }, 100)">
    @include('admin.partials.sidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-72 p-4 md:p-8">
        <header class="mb-8 animate-fade-in">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Semua Risiko</h1>
            <p class="text-slate-400 mt-1 text-sm font-medium">Data risiko dari semua user dan unit</p>
        </header>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-4 mb-6 flex flex-wrap gap-4 animate-fade-in-up">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input x-model="searchTerm" type="text" placeholder="Cari risiko..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50/80 border-[1.5px] border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-medium transition-all">
                </div>
            </div>
            <div>
                <select x-model="filterUnit" class="p-2.5 bg-slate-50/80 border-[1.5px] border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Risks Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-4 py-4">Kode</th>
                            <th class="px-4 py-4">User</th>
                            <th class="px-4 py-4">Unit</th>
                            <th class="px-4 py-4">Kategori</th>
                            <th class="px-4 py-4">Risiko</th>
                            <th class="px-4 py-4 text-center">Level Awal</th>
                            <th class="px-4 py-4 text-center">Level Sisa</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @foreach($risks as $risk)
                        <tr class="table-row-hover hover:bg-slate-50/50 transition-colors" 
                            x-show="(searchTerm === '' || '{{ strtolower($risk->risiko) }}'.includes(searchTerm.toLowerCase())) && (filterUnit === '' || '{{ $risk->unit }}' === filterUnit)">
                            <td class="px-4 py-4 font-bold text-slate-900 text-xs">{{ $risk->kode }}</td>
                            <td class="px-4 py-4 text-slate-500 text-xs">{{ $risk->user ? $risk->user->name : 'N/A' }}</td>
                            <td class="px-4 py-4 text-slate-500 text-xs">{{ $risk->unit }}</td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md text-[10px] font-semibold">{{ $risk->kategori }}</span>
                            </td>
                            <td class="px-4 py-4 text-slate-700 max-w-xs text-xs font-medium">{{ Str::limit($risk->risiko, 50) }}</td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $levelColors = [
                                        'Kritis' => 'bg-red-600 text-white',
                                        'Tinggi' => 'bg-orange-500 text-white',
                                        'Sedang' => 'bg-yellow-400 text-black',
                                        'Rendah' => 'bg-green-500 text-white',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-lg text-[10px] font-extrabold {{ $levelColors[$risk->awal_level] ?? 'bg-slate-200' }}">
                                    {{ $risk->awal_skor }} ({{ $risk->awal_level }})
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-extrabold {{ $levelColors[$risk->residual_level] ?? 'bg-slate-200' }}">
                                    {{ $risk->residual_skor }} ({{ $risk->residual_level }})
                                </span>
                            </td>
                            <td class="px-4 py-4">
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
                            <td class="px-4 py-4">
                                @php
                                    $validasiColors = [
                                        'Valid' => 'bg-teal-100 text-teal-700',
                                        'Revisi' => 'bg-red-100 text-red-700',
                                        'Menunggu' => 'bg-slate-100 text-slate-500',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $validasiColors[$risk->validasi] ?? 'bg-slate-100' }}">
                                    {{ $risk->validasi }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
