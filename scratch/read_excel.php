<?php
require __DIR__.'/../vendor/autoload.php';

use Shuchkin\SimpleXLSX;

$xlsx = SimpleXLSX::parse('reff/Ekstraksi Data Pengguna ke Excel.xlsx');
if ($xlsx) {
    print_r(array_slice($xlsx->rows(), 0, 5));
} else {
    echo SimpleXLSX::parseError();
}
