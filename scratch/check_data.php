<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'yanjang@rsudmurjani.id')->first();
Auth::login($user);
echo "USER: " . $user->name . " | Bidang: '" . $user->bidang . "' | Unit: '" . $user->unit . "'\n";
echo "CONTEXTS COUNT: " . \App\Models\AppContext::count() . "\n";
echo "ANNOUNCEMENTS COUNT: " . \App\Models\Announcement::count() . "\n";

$contexts = \App\Models\AppContext::all();
foreach($contexts as $c) {
    echo "CTX: " . $c->year . " | " . ($c->bidang ?? 'NULL') . " | " . substr($c->sasaran, 0, 20) . "...\n";
}

$announcements = \App\Models\Announcement::all();
foreach($announcements as $a) {
    echo "ANN: " . $a->title . " | Bidang: " . ($a->bidang ?? 'NULL') . " | Units: " . count($a->target_units ?? []) . " | Active: " . $a->is_active . " | Expires: " . ($a->expires_at ?? 'NEVER') . "\n";
}
