<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import {--dry-run} {--path=} {--fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import user accounts from CSV according to PortalMurjani standard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $fresh = $this->option('fresh');
        $path = $this->option('path') ?? 'Migrate/migrasi akun.csv';

        $csvPath = base_path($path);
        
        if (!file_exists($csvPath)) {
            $this->error('CSV file not found at: ' . $csvPath);
            return;
        }

        if ($fresh && !$dryRun) {
            $this->warn('Truncating non-admin users (--fresh flag used)...');
            // Delete all users except those with role 'admin' or 'Admin'
            User::whereNotIn('role', ['admin', 'Admin'])->delete();
            $this->info('Non-admin users truncated.');
        }

        $file = fopen($csvPath, 'r');
        // Read headers
        $headers = fgetcsv($file, 1000, ';');

        $this->info($dryRun ? 'Starting DRY RUN sync...' : 'Starting sync...');
        $count = 0;
        $updatedCount = 0;
        $createdCount = 0;

        while (($data = fgetcsv($file, 1000, ';')) !== FALSE) {
            if (count($data) < 2) continue;
            
            $nama = trim($data[0]);
            $nipRaw = trim($data[1]);
            // Remove the leading apostrophe if present
            $nip = ltrim($nipRaw, "'");

            if (empty($nip)) continue;
            
            $rawRole = isset($data[2]) ? trim($data[2]) : 'User';
            // Standardize role to 'User' (capitalized) if it's 'user'
            $role = ucfirst(strtolower($rawRole));

            $count++;

            // Find existing user by exact name or NIP
            $user = User::where('nip', $nip)->orWhere('username', $nip)->orWhere('name', $nama)->first();

            if ($user) {
                if (!$dryRun) {
                    $user->username = $nip;
                    $user->nip = $nip;
                    $user->role = $role;
                    $user->is_active = true;
                    $user->save();
                }
                $updatedCount++;
                $this->line("Updated user: {$nama} -> NIP/Username: {$nip} | Role: {$role}");
            } else {
                if (!$dryRun) {
                    User::create([
                        'name' => $nama,
                        'username' => $nip,
                        'nip' => $nip,
                        'role' => $role,
                        'is_active' => true,
                        // Default password is NIP
                        'password' => bcrypt($nip),
                    ]);
                }
                $createdCount++;
                $this->line("Created new user: {$nama} -> NIP: {$nip} | Role: {$role}");
            }
        }

        fclose($file);
        
        $this->info("====================================");
        if ($dryRun) {
            $this->info("DRY RUN COMPLETED. No data was saved.");
        } else {
            $this->info("SYNC COMPLETED.");
        }
        
        $this->info("Processed: {$count}");
        $this->info("Created: {$createdCount}");
        $this->info("Updated: {$updatedCount}");
        $this->info("====================================");
    }
}
