<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUniversalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'admin_master'],
            [
                'name'  => 'Administrator Sistem',
                'nip'   => 'admin_master',
                'email' => 'it@rsudmurjani.com',
                'role'  => 'Admin', 
                'password' => bcrypt('admin_master'), // Fallback password just in case
            ]
        );
    }
}
