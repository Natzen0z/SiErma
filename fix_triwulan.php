<?php
// Fix triwulan and tanggal values for all existing risks

use App\Models\Risk;

$risks = Risk::all();
$count = 0;

foreach ($risks as $risk) {
    $date = $risk->tanggal ?? $risk->created_at->format('Y-m-d');
    $month = (int) substr($date, 5, 2);
    $triwulan = 'Triwulan ' . ceil($month / 3);
    
    $risk->triwulan = $triwulan;
    if (!$risk->tanggal) {
        $risk->tanggal = $date;
    }
    $risk->save();
    $count++;
}

echo "Updated {$count} risks with proper triwulan and tanggal values.\n";
