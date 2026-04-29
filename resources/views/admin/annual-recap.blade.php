@extends('layouts.app')

@section('title', 'Rekap Tahunan - Admin Panel')

@section('content')
<div x-data="annualRecap()" x-init="init()">
    @include('admin.partials.sidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-72 p-4 md:p-8">
        <header class="mb-8 animate-fade-in">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Rekap Tahunan</h1>
            <p class="text-slate-400 mt-1 text-sm font-medium">Perbandingan data risiko antar tahun periode</p>
        </header>

        <!-- Yearly Comparison Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-6 mb-8 animate-fade-in-up">
            <h2 class="text-sm font-bold text-slate-800 mb-6 flex items-center uppercase tracking-wider">
                <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-amber-500/20">
                    <i data-lucide="trending-up" class="w-4 h-4 text-white"></i>
                </div>
                Tren Risiko Tahunan
            </h2>
            <div class="h-80 w-full">
                <canvas id="yearlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Detailed Yearly Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 stagger-children">
            @foreach($recapData as $year => $data)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-6 card-hover animate-fade-in-up">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-amber-50 border border-amber-200/60 rounded-xl flex items-center justify-center">
                            <span class="text-amber-700 font-extrabold text-sm">{{ substr($year, 2) }}</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800">Tahun {{ $year }}</h3>
                    </div>
                    <span class="px-3.5 py-1.5 bg-amber-100/80 text-amber-700 rounded-xl text-xs font-bold border border-amber-200/40">
                        {{ $data['total'] }} Risiko
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 bg-gradient-to-br from-slate-50/80 to-white rounded-xl border border-slate-100/60 stat-accent stat-accent-green">
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1 pl-3">Penyelesaian</p>
                        <p class="text-2xl font-extrabold text-emerald-600 pl-3">{{ $data['statuses']['Completed'] }}</p>
                        <p class="text-[10px] text-slate-400 pl-3 mt-0.5 font-medium">Selesai Mitigasi</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-slate-50/80 to-white rounded-xl border border-slate-100/60 stat-accent stat-accent-red">
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1 pl-3">Risiko Tinggi</p>
                        <p class="text-2xl font-extrabold text-red-600 pl-3">{{ $data['levels']['Kritis'] + $data['levels']['Tinggi'] }}</p>
                        <p class="text-[10px] text-slate-400 pl-3 mt-0.5 font-medium">Kritis & Tinggi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-[10px] font-bold text-slate-400 mb-3 uppercase tracking-wider">Level Risiko</h4>
                        <div class="h-48">
                            <canvas id="levelChart-{{ $year }}"></canvas>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-slate-400 mb-3 uppercase tracking-wider">Top Kategori</h4>
                        <div class="space-y-2.5">
                            @php $count = 0; @endphp
                            @foreach($data['categories']->sortDesc() as $cat => $val)
                                @if($count < 5)
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-slate-600 truncate mr-2 font-medium">{{ $cat }}</span>
                                        <span class="text-[10px] font-black px-2 py-0.5 bg-amber-100/80 rounded-md text-amber-700">{{ $val }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-amber-400 to-amber-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ ($val / $data['total']) * 100 }}%"></div>
                                    </div>
                                </div>
                                @php $count++; @endphp
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    function annualRecap() {
        return {
            init() {
                setTimeout(() => {
                    if (window.lucide) window.lucide.createIcons();
                    this.renderCharts();
                }, 100);
            },
            renderCharts() {
                const years = @json($years);
                const recapData = @json($recapData);

                // Yearly Trend Chart
                const ctxTrend = document.getElementById('yearlyTrendChart').getContext('2d');
                new Chart(ctxTrend, {
                    type: 'bar',
                    data: {
                        labels: years,
                        datasets: [
                            {
                                label: 'Total Risiko',
                                data: years.map(y => recapData[y].total),
                                backgroundColor: 'rgba(245, 158, 11, 0.15)',
                                borderColor: 'rgb(245, 158, 11)',
                                borderWidth: 2,
                                borderRadius: 10,
                            },
                            {
                                label: 'Selesai Mitigasi',
                                data: years.map(y => recapData[y].statuses.Completed),
                                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                                borderColor: 'rgb(16, 185, 129)',
                                borderWidth: 2,
                                borderRadius: 10,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'top',
                                labels: { usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 11, weight: '600' } }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)' }, ticks: { font: { size: 11 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' } } }
                        }
                    }
                });

                // Individual Year Level Charts
                years.forEach(year => {
                    const ctxLevel = document.getElementById(`levelChart-${year}`).getContext('2d');
                    new Chart(ctxLevel, {
                        type: 'doughnut',
                        data: {
                            labels: ['Kritis', 'Tinggi', 'Sedang', 'Rendah'],
                            datasets: [{
                                data: [
                                    recapData[year].levels.Kritis,
                                    recapData[year].levels.Tinggi,
                                    recapData[year].levels.Sedang,
                                    recapData[year].levels.Rendah
                                ],
                                backgroundColor: [
                                    '#dc2626',
                                    '#f97316',
                                    '#facc15',
                                    '#22c55e'
                                ],
                                borderWidth: 0,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'right', labels: { boxWidth: 10, padding: 12, font: { size: 10, weight: '500' }, usePointStyle: true, pointStyle: 'circle' } }
                            },
                            cutout: '72%'
                        }
                    });
                });
            }
        }
    }
</script>
@endpush
