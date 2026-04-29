<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use App\Models\Risk;
use App\Models\Mitigation;
use App\Models\Category;
use App\Models\Announcement;
use App\Models\AppContext;
use App\Models\SubUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // 1. Get all units and users
        $units = Unit::all();
        $users = User::all();
        $categories = Category::all()->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Strategis', 'Operasional', 'Klinis'];
        }

        $this->command->info('Seeding Sub-Units...');
        foreach ($units as $unit) {
            for ($i = 1; $i <= 2; $i++) {
                SubUnit::firstOrCreate([
                    'unit_name' => $unit->name,
                    'name' => 'Ruangan ' . $faker->word . ' ' . $i
                ]);
            }
        }

        $this->command->info('Seeding Risks and Mitigations...');
        foreach ($users as $user) {
            if ($user->role === 'admin' && $user->email !== 'wadiryan@rsudmurjani.id' && $user->email !== 'direktur@rsudmurjani.id') continue;

            // Generate 2 risks for each relevant user/unit
            for ($i = 1; $i <= 2; $i++) {
                $awalD = rand(1, 5);
                $awalP = rand(1, 5);
                $resD = max(1, $awalD - rand(0, 2));
                $resP = max(1, $awalP - rand(0, 2));

                $subUnit = SubUnit::where('unit_name', $user->unit)->inRandomOrder()->first();

                $risk = Risk::create([
                    'user_id' => $user->id,
                    'kode' => Risk::generateNextKode(),
                    'unit' => $user->unit ?? $faker->company,
                    'sub_unit' => $subUnit ? $subUnit->name : null,
                    'kategori' => $faker->randomElement($categories),
                    'risiko' => $faker->sentence(10),
                    'dampak_deskripsi' => $faker->paragraph(2),
                    'penyebab' => $faker->sentence(8),
                    'awal_d' => $awalD,
                    'awal_p' => $awalP,
                    'pengendalian' => $faker->sentence(5),
                    'evaluasi' => $faker->randomElement(['Diterima', 'Diturunkan', 'Dibagi', 'Dihindari']),
                    'residual_d' => $resD,
                    'residual_p' => $resP,
                    'pj' => $user->name,
                    'status' => $faker->randomElement(['Not Started', 'In-Progress', 'Completed']),
                    'validasi' => $faker->randomElement(['Menunggu', 'Valid', 'Revisi']),
                    'validator' => 'Kusnadi Jaya',
                    'triwulan' => 'Triwulan ' . rand(1, 4),
                    'period_year' => 2026,
                    'is_active' => true,
                    'tanggal' => date('Y-m-d'),
                    'bidang' => $user->bidang,
                    'created_by_name' => $user->name,
                ]);

                // Add 1-3 mitigations for each risk
                $mitCount = rand(1, 3);
                for ($j = 1; $j <= $mitCount; $j++) {
                    $status = $risk->status;
                    if ($j < $mitCount && $status === 'Completed') $status = 'Completed';
                    else if ($status === 'Completed') $status = 'Completed';
                    else $status = $faker->randomElement(['Not Started', 'In-Progress', 'Completed']);

                    Mitigation::create([
                        'risk_id' => $risk->id,
                        'treatment' => $faker->sentence(12),
                        'status' => $status,
                        'evidence_link' => $status === 'Completed' ? 'https://drive.google.com/test-evidence-' . $faker->slug : null,
                    ]);
                }
            }
        }

        $this->command->info('Seeding Announcements...');
        $bidangs = ['Unit Kerja', 'Unit Pendukung Pelayanan', 'Komite Tenaga Kesehatan Lainnya', 'Unit Pelayanan', 'Pihak Ketiga', 'Global'];
        $admin = User::where('role', 'admin')->first();

        foreach ($bidangs as $bidang) {
            Announcement::create([
                'user_id' => $admin->id,
                'title' => 'PENGUMUMAN PENTING: ' . strtoupper($bidang),
                'message' => $faker->paragraph(3),
                'bidang' => $bidang === 'Global' ? null : $bidang,
                'is_active' => true,
                'expires_at' => now()->addDays(rand(7, 30)),
            ]);
        }

        $this->command->info('Seeding App Contexts...');
        foreach ($bidangs as $bidang) {
            if ($bidang === 'Global') {
                AppContext::create([
                    'year' => 2026,
                    'bidang' => null,
                    'sasaran' => 'Mewujudkan tata kelola RSUD dr. Murjani yang akuntabel dan transparan melalui implementasi Risk Management.',
                    'indikator' => '1. Persentase unit kerja yang melakukan risk assessment (Target 100%)' . "\n" . '2. Penurunan tingkat risiko kritis (Target 50%)',
                    'notify_until' => now()->addMonths(12),
                ]);
            } else {
                AppContext::create([
                    'year' => 2026,
                    'bidang' => $bidang,
                    'sasaran' => 'Optimalisasi pelayanan pada bidang ' . $bidang . ' sesuai standar akreditasi STARKES.',
                    'indikator' => '1. Kepatuhan pelaporan risiko bulanan' . "\n" . '2. Waktu respon penanganan mitigasi risiko',
                    'notify_until' => now()->addMonths(6),
                ]);
            }
        }

        $this->command->info('Dummy data seeding completed successfully!');
    }
}
