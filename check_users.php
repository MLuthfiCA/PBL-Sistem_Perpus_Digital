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
    
    echo "=== CHECK USER DATA ===\n\n";
    
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total Users: " . count($users) . "\n\n";
    
    foreach ($users as $user) {
        echo "User ID: {$user['user_id']}\n";
        echo "  Full Name: {$user['full_name']}\n";
        echo "  Username: {$user['username']}\n";
        echo "  Email: {$user['email']}\n";
        echo "  Role: {$user['role']}\n";
        echo "  Password: " . substr($user['password'], 0, 20) . "...\n";
        echo "\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
