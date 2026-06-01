<?php
// File untuk clear cache Laravel
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$artisan = $app->make('Illuminate\Contracts\Console\Kernel');

// Clear various caches
$artisan->call('cache:clear');
echo "✓ Cache cleared\n";

$artisan->call('view:clear');
echo "✓ View cache cleared\n";

$artisan->call('config:clear');
echo "✓ Config cleared\n";

echo "\nSekarang buka browser dan refresh halaman profile (Ctrl+F5 untuk hard refresh)\n";
?>
