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

$data = json_decode(file_get_contents('db_backup.json'), true);

echo "=== RESTORING DATABASE ===\n\n";

// 1. Restore users
echo "1. Restoring users...\n";
$userCount = 0;
foreach ($data['users'] ?? [] as $user) {
    try {
        $stmt = $pdo->prepare("INSERT INTO users (identity_number, full_name, username, email, password, role, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user['identity_number'] ?? 'ID-'.date('YmdHis'),
            $user['full_name'] ?? $user['name'] ?? 'User',
            $user['username'] ?? 'user_'.uniqid(),
            $user['email'] ?? 'user'.uniqid().'@example.com',
            $user['password'] ?? bcrypt('password123'),
            $user['role'] ?? 'student',
            $user['status'] ?? 'active',
            $user['created_at'] ?? date('Y-m-d H:i:s'),
            $user['updated_at'] ?? date('Y-m-d H:i:s')
        ]);
        $userCount++;
    } catch (Exception $e) {
        echo "  ⚠ Error: " . $e->getMessage() . "\n";
    }
}
echo "  ✓ Restored $userCount users\n\n";

// 2. Restore categories
echo "2. Restoring categories...\n";
$categories = [];
foreach ($data['buku'] ?? [] as $buku) {
    $katName = $buku['kategori'] ?? 'Umum';
    if (!isset($categories[$katName])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori, slug, created_at, updated_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $katName,
                strtolower(str_replace(' ', '-', $katName)),
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ]);
            $categories[$katName] = $pdo->lastInsertId();
            echo "  + $katName\n";
        } catch (Exception $e) {
            echo "  ⚠ Error creating category: " . $e->getMessage() . "\n";
        }
    }
}
echo "  ✓ Done\n\n";

// 3. Restore buku
echo "3. Restoring buku...\n";
$bukuCount = 0;
foreach ($data['buku'] ?? [] as $buku) {
    try {
        $katName = $buku['kategori'] ?? 'Umum';
        $katId = $categories[$katName] ?? 1;
        
        // Mapping status dari database lama ke yang baru
        $statusMap = [
            'Tersedia' => 'tersedia',
            'tersedia' => 'tersedia',
            'available' => 'tersedia',
            'Dipinjam' => 'dipinjam',
            'dipinjam' => 'dipinjam',
            'borrowed' => 'dipinjam',
            'Hilang' => 'hilang',
            'hilang' => 'hilang',
            'lost' => 'hilang',
            'Pemeliharaan' => 'pemeliharaan',
            'pemeliharaan' => 'pemeliharaan',
            'maintenance' => 'pemeliharaan'
        ];
        
        $status = $statusMap[$buku['status'] ?? 'Tersedia'] ?? 'tersedia';

        $stmt = $pdo->prepare("INSERT INTO buku (judul, slug, penulis, genre, isbn, penerbit, tahun_terbit, kategori_id, bahasa, cetakan, deskripsi, cover, stok, tampil_katalog, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $buku['judul'],
            strtolower(str_replace(' ', '-', $buku['judul'])) . '-' . rand(100,999),
            $buku['penulis'],
            $buku['genre'] ?? null,
            $buku['isbn'] ?? '',
            $buku['penerbit'] ?? '',
            $buku['tahun_terbit'] ?? 2024,
            $katId,
            'Indonesia',
            $buku['cetakan'] ?? null,
            $buku['deskripsi'] ?? null,
            $buku['cover'] ?? null,
            $buku['stok'] ?? 1,
            $buku['tampil_katalog'] ?? 1,
            $status,
            $buku['created_at'] ?? date('Y-m-d H:i:s'),
            $buku['updated_at'] ?? date('Y-m-d H:i:s')
        ]);
        $bukuCount++;
    } catch (Exception $e) {
        echo "  ⚠ Error on buku '{$buku['judul']}': " . $e->getMessage() . "\n";
    }
}
echo "  ✓ Restored $bukuCount buku\n\n";

// 4. Restore peminjaman
echo "4. Restoring peminjaman...\n";
$peminjamanCount = 0;
foreach ($data['peminjamans'] ?? [] as $p) {
    try {
        // Mapping status peminjaman
        $statusMap = [
            'dikembalikan' => 'returned',
            'returned' => 'returned',
            'dipinjam' => 'borrowed',
            'borrowed' => 'borrowed',
            'late' => 'late',
            'telat' => 'late'
        ];
        
        // Mapping status denda
        $dendaStatusMap = [
            'lunas' => 'paid',
            'paid' => 'paid',
            'unpaid' => 'unpaid',
            'belum lunas' => 'unpaid'
        ];
        
        $peminjamanStatus = $statusMap[$p['status'] ?? 'dipinjam'] ?? 'borrowed';
        $dendaStatus = $dendaStatusMap[$p['status_denda'] ?? 'lunas'] ?? 'paid';

        $stmt = $pdo->prepare("INSERT INTO peminjamans (kode_peminjaman, user_id, buku_id, tanggal_pinjam, batas_kembali, tanggal_kembali, status, denda, status_denda, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            'TRX-' . str_pad($p['id'], 5, '0', STR_PAD_LEFT),
            $p['user_id'] ?? 1,
            $p['buku_id'],
            $p['tanggal_pinjam'],
            $p['batas_kembali'],
            $p['tanggal_kembali'],
            $peminjamanStatus,
            $p['denda'] ?? 0,
            $dendaStatus,
            $p['created_at'] ?? date('Y-m-d H:i:s'),
            $p['updated_at'] ?? date('Y-m-d H:i:s')
        ]);
        
        $peminjamanCount++;

    } catch (Exception $e) {
        echo "  ⚠ Error on peminjaman #{$p['id']}: " . $e->getMessage() . "\n";
    }
}
echo "  ✓ Restored $peminjamanCount peminjaman\n\n";

echo "=== RESTORE COMPLETE ===\n";
echo "Summary:\n";
echo "  - Users: $userCount\n";
echo "  - Buku: $bukuCount\n";
echo "  - Peminjaman: $peminjamanCount\n";
echo "Restore complete.\n";
