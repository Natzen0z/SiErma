<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Shuchkin\SimpleXLSX;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$file = __DIR__.'/../reff/Ekstraksi Data Pengguna ke Excel.xlsx';
$xlsx = SimpleXLSX::parse($file);

if ($xlsx) {
    $rows = $xlsx->rows();
    // Skip header
    array_shift($rows);
    
    $count = 0;
    foreach ($rows as $row) {
        if (empty($row[0])) continue; // skip empty rows
        
        $name = trim($row[0]);
        $email = strtolower(trim($row[1]));
        $bidangExcel = trim($row[2]);
        $bidang = ($bidangExcel === '-' || $bidangExcel === '') ? null : $bidangExcel;
        $role = strtolower(trim($row[3]));
        $password = trim($row[4]);
        
        $unit = $name; 
        
        // Custom logic based on role / special emails
        if ($role === 'auditor') {
            $unit = null;
        } elseif (in_array($email, ['kusnadijaya@rsudmurjani.id', 'direktur@rsudmurjani.id'])) {
             $unit = null;
             $role = 'admin';
        }
        
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'unit' => $unit,
                'bidang' => $bidang,
                'role' => $role,
                'password' => Hash::make($password),
                'password_plain' => $password
            ]
        );
        $count++;
    }
    echo "Successfully imported/updated $count users.\n";
} else {
    echo "Error parsing Excel file: " . SimpleXLSX::parseError();
}
