<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Peminjaman;

$data = Peminjaman::where('user_id', 1)->with('buku')->get();

echo "Count: " . count($data) . "\n";
foreach ($data as $p) {
    echo $p->peminjaman_id . ' => ' . ($p->buku?->judul ?? 'NO_BOOK') . "\n";
}
