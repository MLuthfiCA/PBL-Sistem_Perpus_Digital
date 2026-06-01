<!DOCTYPE html>
<html>
<head>
    <title>Debug Tools</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .button { padding: 10px 20px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .button:hover { background: #0056b3; }
        .result { margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #007bff; border-radius: 5px; }
        .success { border-left-color: #28a745; color: #155724; }
        .error { border-left-color: #dc3545; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Debug Tools</h1>

        <form method="POST">
            <button type="submit" name="action" value="clear_all" class="button">Clear All Cache</button>
            <button type="submit" name="action" value="clear_views" class="button">Clear View Cache</button>
            <button type="submit" name="action" value="test_db" class="button">Test Database</button>
            <button type="submit" name="action" value="test_peminjaman" class="button">Test Peminjaman Query</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require 'vendor/autoload.php';
            $app = require_once 'bootstrap/app.php';
            $kernel = $app->make('Illuminate\Contracts\Console\Kernel');
            $kernel->bootstrap();

            $action = $_POST['action'] ?? '';

            if ($action === 'clear_all') {
                \Illuminate\Support\Facades\Artisan::call('cache:clear');
                \Illuminate\Support\Facades\Artisan::call('view:clear');
                \Illuminate\Support\Facades\Artisan::call('config:clear');
                echo '<div class="result success">✓ All caches cleared successfully!</div>';
            }
            elseif ($action === 'clear_views') {
                \Illuminate\Support\Facades\Artisan::call('view:clear');
                echo '<div class="result success">✓ View cache cleared!</div>';
            }
            elseif ($action === 'test_db') {
                try {
                    $count = DB::table('peminjaman')->count();
                    echo '<div class="result success">✓ Database OK - Total peminjaman: ' . $count . '</div>';
                } catch (\Exception $e) {
                    echo '<div class="result error">✗ Database Error: ' . $e->getMessage() . '</div>';
                }
            }
            elseif ($action === 'test_peminjaman') {
                try {
                    $userId = 1; // Ganti dengan user ID yang sesuai
                    $data = DB::table('peminjaman')
                        ->where('id_pengguna', $userId)
                        ->where('status', 'dipinjam')
                        ->get();
                    echo '<div class="result success">✓ Query OK - Ditemukan ' . count($data) . ' buku dipinjam untuk user ' . $userId . '</div>';
                    if (count($data) > 0) {
                        echo '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>';
                    }
                } catch (\Exception $e) {
                    echo '<div class="result error">✗ Query Error: ' . $e->getMessage() . '</div>';
                }
            }
        }
        ?>

        <hr>
        <p style="color: #666; font-size: 12px;">After clearing cache, refresh your browser (Ctrl+F5) to see changes.</p>
    </div>
</body>
</html>
