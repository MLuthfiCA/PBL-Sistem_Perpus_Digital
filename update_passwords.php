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
    
    echo "=== UPDATE PASSWORD HASHING ===\n\n";
    
    // Define users with their passwords
    $users = [
        ['username' => 'rayyan123', 'password' => 'password123'],
        ['username' => 'admin', 'password' => 'admin123'],
        ['username' => 'student', 'password' => 'student123'],
    ];
    
    // Update each user with properly hashed password using Laravel's bcrypt
    // We'll use PHP's password_hash which is compatible with Laravel
    foreach ($users as $user) {
        $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->execute([$hashedPassword, $user['username']]);
        
        echo "✓ Updated {$user['username']} with password: {$user['password']}\n";
        echo "  Hashed: " . substr($hashedPassword, 0, 30) . "...\n\n";
    }
    
    echo "✓ All passwords updated successfully!\n\n";
    echo "Login credentials:\n";
    echo "  Admin: username=admin, password=admin123\n";
    echo "  Student 1: username=rayyan123, password=password123\n";
    echo "  Student 2: username=student, password=student123\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
