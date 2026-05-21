<?php
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

$dbName = $envVars['DB_DATABASE'] ?? 'readspace';
$dbUser = $envVars['DB_USERNAME'] ?? 'root';
$dbPass = $envVars['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname='.$dbName, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DATABASE TEST (readspace) ===\n\n";
    
    // Test Buku
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM buku");
    $bukuCount = $stmt->fetch()['count'];
    echo "1. Total Buku: $bukuCount\n";
    
    $stmt = $pdo->query("SELECT judul, status, stok FROM buku LIMIT 1");
    if ($buku = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   First Buku: {$buku['judul']} (Status: {$buku['status']}, Stok: {$buku['stok']})\n";
    }
    echo "\n";
    
    // Test Users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $userCount = $stmt->fetch()['count'];
    echo "2. Total Users: $userCount\n";
    
    $stmt = $pdo->query("SELECT full_name, role FROM users LIMIT 1");
    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   First User: {$user['full_name']} ({$user['role']})\n";
    }
    echo "\n";
    
    // Test Peminjaman
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM peminjamans");
    $peminjamanCount = $stmt->fetch()['count'];
    echo "3. Total Peminjaman: $peminjamanCount\n";
    
    $stmt = $pdo->query("SELECT kode_peminjaman, status FROM peminjamans LIMIT 1");
    if ($p = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   First Peminjaman: {$p['kode_peminjaman']} (Status: {$p['status']})\n";
    }
    echo "\n";
    
    // Test Kategori
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM kategori");
    $katCount = $stmt->fetch()['count'];
    echo "4. Total Kategori: $katCount\n";
    
    echo "\n5. Sample Buku Data:\n";
    $stmt = $pdo->query("SELECT judul, status, stok FROM buku LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$row['judul']} (Status: {$row['status']}, Stok: {$row['stok']})\n";
    }
    
    echo "\n✓ Database test passed!\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
