<?php
// Baca database config dari Laravel .env
$envPath = __DIR__ . '/.env';
$envVars = [];
if (file_exists($envPath)) {
    foreach (file($envPath) as $line) {
        $line = trim($line);
        if (!empty($line) && !str_starts_with($line, '#')) {
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $envVars[trim($parts[0])] = trim($parts[1]);
            }
        }
    }
}

$dbName = $envVars['DB_DATABASE'] ?? 'perpusdigital';
$dbUser = $envVars['DB_USERNAME'] ?? 'root';
$dbPass = $envVars['DB_PASSWORD'] ?? '';

// Bersihkan string dari quotes
$dbName = trim($dbName, '"\'');
$dbUser = trim($dbUser, '"\'');
$dbPass = trim($dbPass, '"\'');

$pdo = new PDO('mysql:host=127.0.0.1;dbname='.$dbName, $dbUser, $dbPass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = json_decode(file_get_contents('db_backup.json'), true);

echo "=== RESTORING DATABASE (ERD STRUCTURE) ===\n\n";

// Clear existing tables to avoid duplicate keys
echo "Clearing existing tables...\n";
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
$pdo->exec("TRUNCATE TABLE riwayat;");
$pdo->exec("TRUNCATE TABLE detail_peminjaman;");
$pdo->exec("TRUNCATE TABLE peminjaman;");
$pdo->exec("TRUNCATE TABLE buku;");
$pdo->exec("TRUNCATE TABLE kategori;");
$pdo->exec("TRUNCATE TABLE users;");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
echo "✓ Tables cleared.\n\n";

// 1. Restore users
echo "1. Restoring users...\n";
$userCount = 0;
foreach ($data['users'] ?? [] as $user) {
    try {
        $stmt = $pdo->prepare("INSERT INTO users (id_pengguna, nama, email, password, role, identity_number, username, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $role = $user['role'] ?? 'mahasiswa';
        if ($role === 'student') {
            $role = 'mahasiswa';
        }
        
        $stmt->execute([
            $user['id'],
            $user['name'] ?? $user['full_name'] ?? 'User',
            $user['email'] ?? 'user'.uniqid().'@example.com',
            $user['password'] ?? password_hash('password123', PASSWORD_BCRYPT),
            $role,
            $user['identity_number'] ?? 'ID-'.str_pad($user['id'], 5, '0', STR_PAD_LEFT),
            $user['username'] ?? 'user_'.uniqid(),
            $user['status'] ?? 'active',
            $user['created_at'] ?? date('Y-m-d H:i:s'),
            $user['updated_at'] ?? date('Y-m-d H:i:s')
        ]);
        $userCount++;
    } catch (Exception $e) {
        echo "  ⚠ Error restoring user #{$user['id']}: " . $e->getMessage() . "\n";
    }
}
echo "  ✓ Restored $userCount users\n\n";

// 2. Restore categories
echo "2. Restoring categories...\n";
$categories = [];
$catIdCounter = 1;
foreach ($data['buku'] ?? [] as $buku) {
    $katName = $buku['kategori'] ?? 'Umum';
    if (!isset($categories[$katName])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO kategori (id_kategori, nama_kategori, slug, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $catIdCounter,
                $katName,
                strtolower(str_replace(' ', '-', $katName)),
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ]);
            $categories[$katName] = $catIdCounter;
            echo "  + Category: $katName (ID: $catIdCounter)\n";
            $catIdCounter++;
        } catch (Exception $e) {
            echo "  ⚠ Error creating category '$katName': " . $e->getMessage() . "\n";
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

        $stmt = $pdo->prepare("INSERT INTO buku (id_buku, judul, slug, penulis, genre, isbn, penerbit, tahun_terbit, id_kategori, bahasa, cetakan, deskripsi, cover, stok, tampil_katalog, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $buku['id'],
            $buku['judul'],
            $buku['slug'] ?? (strtolower(str_replace(' ', '-', $buku['judul'])) . '-' . rand(100,999)),
            $buku['penulis'],
            $buku['genre'] ?? null,
            $buku['isbn'] ?? '',
            $buku['penerbit'] ?? '',
            $buku['tahun_terbit'] ?? 2024,
            $katId,
            $buku['bahasa'] ?? 'Indonesia',
            $buku['cetakan'] ?? null,
            $buku['deskripsi'] ?? null,
            $buku['cover'] ?? null,
            $buku['stok'] ?? 1,
            $buku['tampil_katalog'] ?? 1,
            $buku['status'] ?? 'Tersedia',
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
        // Status mapping to match enum: 'dipinjam', 'dikembalikan', 'terlambat'
        $pStatus = strtolower($p['status'] ?? 'dipinjam');
        if ($pStatus === 'borrowed') {
            $pStatus = 'dipinjam';
        } elseif ($pStatus === 'returned') {
            $pStatus = 'dikembalikan';
        } elseif ($pStatus === 'late') {
            $pStatus = 'terlambat';
        }
        
        $dendaStatus = strtolower($p['status_denda'] ?? 'lunas');
        if ($dendaStatus === 'paid') {
            $dendaStatus = 'lunas';
        } elseif ($dendaStatus === 'unpaid' || $dendaStatus === 'belum lunas') {
            $dendaStatus = 'belum_lunas';
        }

        $stmt = $pdo->prepare("INSERT INTO peminjaman (id_peminjaman, id_pengguna, id_buku, tanggal_pinjam, batas_kembali, tanggal_kembali, status, denda, status_denda, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $p['id'],
            $p['user_id'] ?? 1,
            $p['buku_id'],
            $p['tanggal_pinjam'],
            $p['batas_kembali'],
            $p['tanggal_kembali'] === '' ? null : $p['tanggal_kembali'],
            $pStatus,
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
echo "Restore complete successfully.\n";

