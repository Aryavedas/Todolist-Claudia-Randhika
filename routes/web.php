<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// Home setelah login
Route::get('/', [TodoController::class, 'index'])
    ->middleware(['auth'])
    ->name('todos.index');

// Update toggle checkbox
Route::put(
    '/update-toggle/{todo}',
    [TodoController::class, 'updateByClickToggle']
)->middleware(['auth'])->name('home.todos.update');

Route::get('/nuke-database', function () {
    // Matikan cache dan session
    Config::set('cache.default', 'array');
    Config::set('session.driver', 'array');

    $host = env('DB_HOST');
    $port = env('DB_PORT', 5432);
    $database = env('DB_DATABASE');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');

    try {
        // 1. KONEKSI LANGSUNG TANPA LARAVEL
        $dsn = "pgsql:host={$host};port={$port};dbname={$database};sslmode=require";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        echo "✅ Koneksi berhasil<br>";

        // 2. ROLLBACK transaksi yang error
        try {
            $pdo->exec('ROLLBACK');
            echo "✅ Rollback transaksi lama<br>";
        } catch (Exception $e) {
            echo "⚠️ Rollback: " . $e->getMessage() . "<br>";
        }

        // 3. TERMINATE koneksi yang bermasalah
        try {
            $pdo->exec("
                SELECT pg_terminate_backend(pid) 
                FROM pg_stat_activity 
                WHERE datname = '{$database}' 
                  AND pid <> pg_backend_pid()
            ");
            echo "✅ Terminate koneksi lama<br>";
        } catch (Exception $e) {
            echo "⚠️ Terminate: " . $e->getMessage() . "<br>";
        }

        // 4. HAPUS SCHEMA PUBLIC
        $pdo->exec('DROP SCHEMA IF EXISTS public CASCADE');
        echo "✅ Schema dihapus<br>";

        // 5. BUAT SCHEMA BARU
        $pdo->exec('CREATE SCHEMA public');
        echo "✅ Schema dibuat ulang<br>";

        // 6. GRANT PERMISSION
        $pdo->exec('GRANT ALL ON SCHEMA public TO PUBLIC');
        $pdo->exec('GRANT ALL ON SCHEMA public TO ' . $username);
        echo "✅ Permission di-grant<br>";

        // Tutup koneksi PDO
        $pdo = null;

        // Tunggu 2 detik
        sleep(2);

        // 7. RECONNECT LARAVEL
        DB::purge('pgsql');
        DB::reconnect('pgsql');
        echo "✅ Laravel reconnect<br>";

        // 8. TEST KONEKSI
        DB::select('SELECT 1 as test');
        echo "✅ Test koneksi berhasil<br><br>";

        // 9. JALANKAN MIGRASI
        echo "<strong>📦 Menjalankan migrasi...</strong><br><br>";

        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();

        echo "<pre>{$output}</pre>";

        echo "<br><h2>🎉 SELESAI! Database sudah bersih dan siap dipakai!</h2>";
        echo "<p><strong>LANGKAH SELANJUTNYA:</strong></p>";
        echo "<ol>";
        echo "<li>Hapus route /nuke-database dari routes/web.php</li>";
        echo "<li>Buka halaman Register</li>";
        echo "<li>Buat akun baru</li>";
        echo "</ol>";

        return;
    } catch (Exception $e) {
        return "<h2>❌ ERROR:</h2><pre>" . $e->getMessage() . "</pre><br><br>" .
            "<p><strong>Jika error di atas, lakukan ini:</strong></p>" .
            "<ol>" .
            "<li>Buka <a href='https://console.neon.tech' target='_blank'>Neon Console</a></li>" .
            "<li>Pilih project Anda → SQL Editor</li>" .
            "<li>Copy-paste dan jalankan query ini:</li>" .
            "</ol>" .
            "<pre>DROP SCHEMA IF EXISTS public CASCADE;\nCREATE SCHEMA public;\nGRANT ALL ON SCHEMA public TO PUBLIC;</pre>" .
            "<p>Lalu refresh halaman ini.</p>";
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
