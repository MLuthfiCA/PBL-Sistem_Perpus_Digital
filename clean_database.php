<?php
// Baca database config dari Laravel .env
$envPath = __DIR__ . '/.env';
$envVars = [];
if (file_exists($envPath)) {
    foreach (file($envPath) as $line) {
        $line = trim($line);
        if (!empty($line) && !str_starts_with($line, '#')) {
            [$key, $value] = explode('=', $line, 2);
            $envVars[trim($key)] = trim($value);
        }
    }
}

$dbName = $envVars['DB_DATABASE'] ?? 'perpusdigital';
$dbUser = $envVars['DB_USERNAME'] ?? 'root';
$dbPass = $envVars['DB_PASSWORD'] ?? '';

$pdo = new PDO('mysql:host=127.0.0.1;dbname='.$dbName, $dbUser, $dbPass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== CLEANING DATABASE ===\n\n";

// Disable foreign key checks temporarily
$pdo->exec('SET FOREIGN_KEY_CHECKS=0');

try {
    // Clean up tables
    echo "1. Clearing existing data...\n";
    $pdo->exec("TRUNCATE TABLE detail_peminjaman");
    echo "   ✓ detail_peminjaman\n";
    
    $pdo->exec("TRUNCATE TABLE peminjamans");
    echo "   ✓ peminjamans\n";
    
    $pdo->exec("TRUNCATE TABLE buku");
    echo "   ✓ buku\n";
    
    $pdo->exec("TRUNCATE TABLE kategori");
    echo "   ✓ kategori\n";
    
    // Keep users data
    echo "\n";
    
    // Re-enable foreign key checks
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    
    echo "2. Done! Database cleaned.\n";
} catch (Exception $e) {
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
