<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class UserImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Panggil command artisan users:import secara programmatic
        Artisan::call('users:import');
        
        // Tampilkan output dari command ke console seeder
        $this->command->info(Artisan::output());
    }
}
