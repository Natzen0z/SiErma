<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Risk;
use Illuminate\Support\Facades\DB;

$risks = Risk::all();
$badCount = 0;
foreach($risks as $r) {
    $raw = $r->getRawOriginal('shared_with');
    if ($raw === null) {
        echo "ID {$r->id}: NULL (Fixing to [])\n";
        DB::table('risks')->where('id', $r->id)->update(['shared_with' => '[]']);
        $badCount++;
        continue;
    }
    
    $decoded = json_decode($raw);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "ID {$r->id}: BAD JSON ({$raw}) - Fixing to []\n";
        DB::table('risks')->where('id', $r->id)->update(['shared_with' => '[]']);
        $badCount++;
    }
}

echo "Finished. Fixed {$badCount} records.\n";
