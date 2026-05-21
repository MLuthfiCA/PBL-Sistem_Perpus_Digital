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
    
    echo "=== TEST LOGIN CREDENTIALS ===\n\n";
    
    $users = [
        ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
        ['username' => 'rayyan123', 'password' => 'password123', 'role' => 'student'],
        ['username' => 'student', 'password' => 'student123', 'role' => 'student'],
    ];
    
    foreach ($users as $user) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user['username']]);
        
        if ($dbUser = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Test password verification
            $passwordMatch = password_verify($user['password'], $dbUser['password']);
            $roleMatch = $dbUser['role'] === $user['role'];
            
            echo "User: {$user['username']}\n";
            echo "  DB Role: {$dbUser['role']} | Expected: {$user['role']} | " . ($roleMatch ? "✓" : "✗") . "\n";
            echo "  Password Match: " . ($passwordMatch ? "✓" : "✗") . "\n";
            echo "  Full Name: {$dbUser['full_name']}\n";
            echo "  Status: {$dbUser['status']}\n";
            echo "\n";
        } else {
            echo "✗ User not found: {$user['username']}\n\n";
        }
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
