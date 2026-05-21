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
    
    echo "=== FIX USER ROLES ===\n\n";
    
    $updates = [
        ['username' => 'rayyan123', 'role' => 'student', 'status' => 'active'],
        ['username' => 'student', 'role' => 'student', 'status' => 'active'],
        ['username' => 'admin', 'role' => 'admin', 'status' => 'active'],
    ];
    
    foreach ($updates as $update) {
        $stmt = $pdo->prepare("UPDATE users SET role = ?, status = ? WHERE username = ?");
        $result = $stmt->execute([$update['role'], $update['status'], $update['username']]);
        
        if ($result) {
            echo "✓ Updated {$update['username']} - role: {$update['role']}, status: {$update['status']}\n";
        }
    }
    
    echo "\n=== VERIFY ===\n\n";
    $stmt = $pdo->query("SELECT username, role, status FROM users ORDER BY user_id");
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$user['username']}: {$user['role']} ({$user['status']})\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
