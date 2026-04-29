<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$wadirs = App\Models\User::where('role', 'admin')
    ->where(function($q) {
        $q->where('unit', 'like', '%Wadir%')
          ->orWhere('unit', 'like', '%Wakil Direktur%');
    })->get(['name', 'email', 'unit', 'bidang'])->toArray();

echo json_encode($wadirs, JSON_PRETTY_PRINT);
