<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Audit Kepatuhan Risiko - {{ $assessment->unit }}</title>
    <!-- Tailwind CSS (compiled ideally, via CDN here for standalone print view) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white !important; }
            .no-print { display: none !important; }
            @page { size: A4 portrait; margin: 1.5cm; }
        }
        body { font-family: 'Times New Roman', Times, serif; background-color: #f8fafc; }
        .page-container { max-w-[21cm] mx-auto bg-white p-8 md:p-12 shadow-lg min-h-[29.7cm]; }
        @media print { .page-container { max-width: 100%; margin: 0; p-0; shadow: none; } }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px 12px; text-align: left; vertical-align: top; font-size: 11pt; }
        th { background-color: #f1f5f9; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        
        .header-title { font-size: 14pt; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 5px; }
        .header-subtitle { font-size: 12pt; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body class="py-8">

    <div class="fixed top-4 right-4 no-print flex gap-2">
        <button onclick="window.close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-lg text-sm font-bold shadow-sm transition-colors">Tutup</button>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Dokumen
        </button>
    </div>

    <div class="max-w-[21cm] mx-auto bg-white p-8 shadow-sm print:shadow-none print:p-0">
        
        <!-- Header / KOP Surat Sederhana -->
        <div class="border-b-2 border-slate-800 pb-4 mb-6 text-center">
            <div class="header-title">RSUD dr. Murjani Sampit</div>
            <div class="header-subtitle">Laporan Pemantauan & Audit Kepatuhan Evaluasi Risiko (SI ERMa)</div>
        </div>

        <!-- Identitas Audit -->
        <div class="mb-6 space-y-1">
            <div class="flex"><div class="w-40 font-bold">Unit / Bagian</div><div>: {{ $assessment->unit }}</div></div>
            <div class="flex"><div class="w-40 font-bold">Tahun / Periode</div><div>: {{ $assessment->period_year }}</div></div>
            <div class="flex"><div class="w-40 font-bold">Tanggal Penilaian</div><div>: {{ optional($assessment->audit_date)->translatedFormat('d F Y') ?? $assessment->created_at->translatedFormat('d F Y') }}</div></div>
            <div class="flex"><div class="w-40 font-bold">Auditor (Penilai)</div><div>: {{ $assessment->auditor->name ?? 'Tim Auditor' }}</div></div>
        </div>

        <!-- Tabel Hasil Audit -->
        @php
            $weights = ['A'=>100, 'B'=>80, 'C'=>60, 'D'=>40, 'E'=>20];
            $totalSkor = 0;
            $answers = $assessment->answers;
        @endphp

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Parameter Kepatuhan & Mitigasi</th>
                    <th style="width: 10%;">Skor Kelayakan</th>
                    <th style="width: 45%;">Deskripsi & Catatan Evaluasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answers as $index => $answer)
                    @php $totalSkor += $weights[$answer['score'] ?? 'E']; @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $answer['question'] }}</td>
                        <td class="text-center font-bold text-lg">{{ $answer['score'] }}</td>
                        <td class="text-sm">{{ $answer['notes'] ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Kesimpulan / Total Score -->
        @php
            $persentase = ($totalSkor / (count($answers) * 100)) * 100;
            $predikat = 'Tidak Memadai';
            if ($persentase >= 90) $predikat = 'Sangat Memadai (Exceeds Expectation)';
            elseif ($persentase >= 75) $predikat = 'Memadai (Meets Expectation)';
            elseif ($persentase >= 60) $predikat = 'Cukup (Needs Improvement)';
            elseif ($persentase >= 40) $predikat = 'Kurang Memadai';
        @endphp

        <div class="mt-6 border border-slate-800 p-4 bg-slate-50">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Hasil Evaluasi Keseluruhan</div>
            <div class="flex items-end gap-4">
                <div class="text-3xl font-black">{{ number_format($persentase, 1) }}%</div>
                <div class="text-lg font-bold pb-0.5 text-slate-800">({{ $predikat }})</div>
            </div>
        </div>

        <!-- Signature -->
        <div class="mt-20 flex justify-end">
            <div class="text-center w-64">
                <p class="mb-20">Sampit, {{ date('d F Y') }}<br><strong>Auditor Kepolisian/Manajemen Risiko</strong></p>
                <p class="underline font-bold">{{ $assessment->auditor->name ?? '_____________________' }}</p>
                <p class="text-sm mt-1">NIP. {{ $assessment->auditor->nip ?? '........................' }}</p>
            </div>
        </div>

    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            setTimeout(() => {
                // window.print();
            }, 500);
        }
    </script>
</body>
</html>
