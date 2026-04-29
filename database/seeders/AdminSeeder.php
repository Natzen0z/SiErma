<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to ensure a clean sync
        DB::statement('DELETE FROM users');
        DB::statement('DELETE FROM units');
        DB::statement('DELETE FROM categories');

        // 1. Super Admin
        User::create([
            'name' => 'Kusnadi Jaya',
            'email' => 'kusnadijaya@rsudmurjani.id',
            'password' => Hash::make('kusnadijaya1'),
            'password_plain' => 'kusnadijaya1',
            'role' => 'admin',
            'unit' => null,
            'bidang' => null,
        ]);

        // 2. Transcribed Data from Reference Material
        $users = [
            // Unit Kerja
            ['uak@rsudmurjani.id', 'Wakil Direktur Umum, Anggaran dan Keuangan', 'Unit Kerja', 'admin'],
            ['hukbang@rsudmurjani.id', 'Bagian Hukum dan Pengembangan Rumah Sakit', 'Unit Kerja', 'user'],
            ['akun@rsudmurjani.id', 'Sub Bagian Akuntansi', 'Unit Kerja', 'user'],
            ['benda@rsudmurjani.id', 'Sub Bagian Perbendaharaan', 'Unit Kerja', 'user'],
            ['mobdan@rsudmurjani.id', 'Sub Bagian Mobilisasi Dana', 'Unit Kerja', 'user'],
            ['keu@rsudmurjani.id', 'Bagian Keuangan', 'Unit Kerja', 'user'],
            ['bmd@rsudmurjani.id', 'Sub Bagian Barang Milik Daerah', 'Unit Kerja', 'user'],
            ['sunevang@rsudmurjani.id', 'Sub Bagian Penyusunan dan Evaluasi Anggaran', 'Unit Kerja', 'user'],
            ['renang@rsudmurjani.id', 'Bagian Anggaran dan Barang Milik Daerah', 'Unit Kerja', 'user'],
            ['tu@rsudmurjani.id', 'Sub Bagian Tata Usaha', 'Unit Kerja', 'user'],
            ['umum@rsudmurjani.id', 'Bagian Umum', 'Unit Kerja', 'user'],
            ['yanjang@rsudmurjani.id', 'Bidang Pelayanan Penunjang', 'Unit Kerja', 'user'],
            ['yankep@rsudmurjani.id', 'Bidang Pelayanan Keperawatan', 'Unit Kerja', 'user'],
            ['yanmed@rsudmurjani.id', 'Bidang Pelayanan Medik', 'Unit Kerja', 'user'],

            // Unit Pendukung Pelayanan
            ['diklatlit@rsudmurjani.id', 'Bagian Pendidikan Pelatihan dan Penelitian', 'Unit Pendukung Pelayanan', 'user'],
            ['pkrs@rsudmurjani.id', 'Unit Pendidikan Kesehatan Rumah Sakit', 'Unit Pendukung Pelayanan', 'user'],
            ['wadirsdm-p@rsudmurjani.id', 'Wadir SDM & Pengembangan', 'Unit Pendukung Pelayanan', 'admin'],
            ['uhc@rsudmurjani.id', 'Handling Complain', 'Unit Pendukung Pelayanan', 'user'],

            // Komite
            ['komnakesla@rsudmurjani.id', 'Komite Tenaga Kesehatan Lainnya', 'Komite Tenaga Kesehatan Lainnya', 'user'],
            ['komtik@rsudmurjani.id', 'Komite Etik', 'Komite Etik Rumah Sakit', 'user'],
            ['kft@rsudmurjani.id', 'Komite Farmakologi & Terapi (KFT)', 'Komite Farmakologi & Terapi (KFT)', 'user'],
            ['kpra@rsudmurjani.id', 'Komite Pengendalian Resistensi Antimikroba (KPRA)', 'Komite Pengendalian Resistensi Antimikroba (KPRA)', 'user'],
            ['komed@rsudmurjani.id', 'Komite Medik', 'Komite Medik', 'user'],
            ['komkep@rsudmurjani.id', 'Komite Keperawatan', 'Komite Keperawatan', 'user'],
            ['ppi@rsudmurjani.id', 'Komite Pencegahan dan Pengendalian Infeksi (PPI)', 'Komite Pencegahan dan Pengendalian Infeksi (PPI)', 'user'],
            ['komut@rsudmurjani.id', 'Komite Mutu', null, 'admin'],

            // Unit Pelayanan
            ['mpp@rsudmurjani.id', 'Manajer Pelayanan Pasien (MPP)', 'Unit Pelayanan', 'user'],
            ['simrs@rsudmurjani.id', 'Instalasi Sistem Informasi Manajemen Rumah Sakit', 'Unit Pelayanan', 'user'],
            ['ibs@rsudmurjani.id', 'Instalasi Bedah Sentral', 'Unit Pelayanan', 'user'],
            ['ribogen@rsudmurjani.id', 'Ruang Rawat Bougenvile', 'Unit Pelayanan', 'user'],
            ['vk@rsudmurjani.id', 'Vartus Khmer (Kamar Bersalin)', 'Unit Pelayanan', 'user'],
            ['riperina@rsudmurjani.id', 'Ruang Rawat Perinatologi', 'Unit Pelayanan', 'user'],
            ['riasoka@rsudmurjani.id', 'Ruang Rawat Asoka', 'Unit Pelayanan', 'user'],
            ['jenazah@rsudmurjani.id', 'Instalasi Pemulasaran Jenazah', 'Unit Pelayanan', 'user'],
            ['ricempaka@rsudmurjani.id', 'Ruang Rawat Cempaka', 'Unit Pelayanan', 'user'],
            ['ritulip@rsudmurjani.id', 'Ruang Rawat Tulip', 'Unit Pelayanan', 'user'],
            ['riseruni@rsudmurjani.id', 'Ruang Rawat Seruni', 'Unit Pelayanan', 'user'],
            ['deporj@rsudmurjani.id', 'Depo Rawat Jalan', 'Unit Pelayanan', 'user'],
            ['dialisis@rsudmurjani.id', 'Unit Dialisis', 'Unit Pelayanan', 'user'],
            ['depori@rsudmurjani.id', 'Depo Rawat Inap', 'Unit Pelayanan', 'user'],
            ['riseroja@rsudmurjani.id', 'Ruang Rawat Seroja', 'Unit Pelayanan', 'user'],
            ['icu@rsudmurjani.id', 'Intensive Care Unit', 'Unit Pelayanan', 'user'],
            ['labpa@rsudmurjani.id', 'Laboratorium Patologi Anatomis', 'Unit Pelayanan', 'user'],
            ['bankdarah@rsudmurjani.id', 'Unit Bank Darah Rumah Sakit', 'Unit Pelayanan', 'user'],
            ['radiologi@rsudmurjani.id', 'Instalasi Radiologi', 'Unit Pelayanan', 'user'],
            ['ipsrs@rsudmurjani.id', 'Instalasi Pemeliharaan Sarana Rumah Sakit', 'Unit Pelayanan', 'user'],
            ['anggrektewu@rsudmurjani.id', 'Ruang Anggrek Tewu', 'Unit Pelayanan', 'user'],
            ['farmasi@rsudmurjani.id', 'Instalasi Farmasi', 'Unit Pelayanan', 'user'],
            ['gizi@rsudmurjani.id', 'Instalasi Gizi', 'Unit Pelayanan', 'user'],
            ['laundry@rsudmurjani.id', 'Unit Laundry', 'Unit Pelayanan', 'user'],
            ['pos@rsudmurjani.id', 'Pengantar Orang Sakit', 'Unit Pelayanan', 'user'],
            ['depougd@rsudmurjani.id', 'Depo UGD', 'Unit Pelayanan', 'user'],
            ['poliklinik@rsudmurjani.id', 'Poliklinik', 'Unit Pelayanan', 'user'],
            ['ponek@rsudmurjani.id', 'Unit PONEK', 'Unit Pelayanan', 'user'],
            ['okcyto@rsudmurjani.id', 'Unit OK Cyto', 'Unit Pelayanan', 'user'],
            ['labpk@rsudmurjani.id', 'Laboratorium Patologi Klinis', 'Unit Pelayanan', 'user'],
            ['sanitasi@rsudmurjani.id', 'Instalasi Sanitasi', 'Unit Pelayanan', 'user'],
            ['helpdesk@rsudmurjani.id', 'Layanan Helpdesk', 'Unit Pelayanan', 'user'],
            ['rekammedik@rsudmurjani.id', 'Instalasi Rekam Medik', 'Unit Pelayanan', 'user'],
            ['cssd@rsudmurjani.id', 'CSSD', 'Unit Pelayanan', 'user'],
            ['ambulance@rsudmurjani.id', 'Ambulance', 'Unit Pelayanan', 'user'],
            ['codeblue@rsudmurjani.id', 'Code Blue', 'Unit Pelayanan', 'user'],
            ['depoibs@rsudmurjani.id', 'Depo IBS', 'Unit Pelayanan', 'user'],
            ['depookcyto@rsudmurjani.id', 'Depo OK Cyto', 'Unit Pelayanan', 'user'],
            ['ugd@rsudmurjani.id', 'Unit Gawat Darurat', 'Unit Pelayanan', 'user'],
            ['wadiryan@rsudmurjani.id', 'Wadir Pelayanan', 'Unit Pelayanan', 'admin'],
            ['riteratai@rsudmurjani.id', 'Ruang Rawat Teratai', 'Unit Pelayanan', 'user'],

            // Pihak Ketiga
            ['security@rsudmurjani.id', 'Satuan Pengamanan', 'Pihak Ketiga', 'user'],
            ['cs3@rsudmurjani.id', 'Cleaning Service', 'Pihak Ketiga', 'user'],

            // Management
            ['direktur@rsudmurjani.id', 'Direktur', null, 'admin'],
        ];

        foreach ($users as $data) {
            $email = $data[0];
            $name = $data[1];
            $bidang = $data[2];
            $role = $data[3];
            
            // Generate password from email slug
            $slug = explode('@', $email)[0];
            $password = $slug . '12345';

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'password_plain' => $password,
                'role' => $role,
                'unit' => $name, // Each account is linked to its department name as 'unit'
                'bidang' => $bidang,
            ]);

            // Populate the units table for filtering
            Unit::firstOrCreate(['name' => $name], ['bidang' => $bidang]);
        }

        // 3. Seed Risk Categories
        $categories = [
            'Strategis',
            'Operasional',
            'Fraud',
            'Hukum',
            'Keuangan',
            'SDM',
            'Teknologi Informasi',
            'Klinis',
            'HAZARD',
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }
    }
}
