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
    Config::set('cache.default', 'array');
    Config::set('session.driver', 'array');

    $host = env('DB_HOST');
    $port = env('DB_PORT', 5432);
    $database = env('DB_DATABASE');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');

    try {
        // 1. KONEKSI PDO LANGSUNG
        $dsn = "pgsql:host={$host};port={$port};dbname={$database};sslmode=require";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        echo "✅ Koneksi berhasil<br>";

        // 2. ROLLBACK & TERMINATE
        try {
            $pdo->exec('ROLLBACK');
        } catch (Exception $e) {
        }

        try {
            $pdo->exec("SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '{$database}' AND pid <> pg_backend_pid()");
        } catch (Exception $e) {
        }

        // 3. RESET SCHEMA
        $pdo->exec('DROP SCHEMA IF EXISTS public CASCADE');
        $pdo->exec('CREATE SCHEMA public');
        $pdo->exec('GRANT ALL ON SCHEMA public TO PUBLIC');
        $pdo->exec("GRANT ALL ON SCHEMA public TO {$username}");

        echo "✅ Schema direset<br><br>";

        // 4. BUAT TABEL MANUAL (TANPA LARAVEL MIGRATION)
        echo "📦 Membuat tabel users...<br>";
        $pdo->exec("
            CREATE TABLE users (
                id BIGSERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "✅ Tabel users dibuat<br>";

        echo "📦 Membuat tabel password_reset_tokens...<br>";
        $pdo->exec("
            CREATE TABLE password_reset_tokens (
                email VARCHAR(255) PRIMARY KEY,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL
            )
        ");
        echo "✅ Tabel password_reset_tokens dibuat<br>";

        echo "📦 Membuat tabel sessions...<br>";
        $pdo->exec("
            CREATE TABLE sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id BIGINT NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload TEXT NOT NULL,
                last_activity INTEGER NOT NULL
            )
        ");
        $pdo->exec("CREATE INDEX sessions_user_id_index ON sessions (user_id)");
        $pdo->exec("CREATE INDEX sessions_last_activity_index ON sessions (last_activity)");
        echo "✅ Tabel sessions dibuat<br>";

        echo "📦 Membuat tabel cache...<br>";
        $pdo->exec("
            CREATE TABLE cache (
                key VARCHAR(255) PRIMARY KEY,
                value TEXT NOT NULL,
                expiration INTEGER NOT NULL
            )
        ");
        echo "✅ Tabel cache dibuat<br>";

        echo "📦 Membuat tabel cache_locks...<br>";
        $pdo->exec("
            CREATE TABLE cache_locks (
                key VARCHAR(255) PRIMARY KEY,
                owner VARCHAR(255) NOT NULL,
                expiration INTEGER NOT NULL
            )
        ");
        echo "✅ Tabel cache_locks dibuat<br>";

        echo "📦 Membuat tabel jobs...<br>";
        $pdo->exec("
            CREATE TABLE jobs (
                id BIGSERIAL PRIMARY KEY,
                queue VARCHAR(255) NOT NULL,
                payload TEXT NOT NULL,
                attempts SMALLINT NOT NULL,
                reserved_at INTEGER NULL,
                available_at INTEGER NOT NULL,
                created_at INTEGER NOT NULL
            )
        ");
        $pdo->exec("CREATE INDEX jobs_queue_index ON jobs (queue)");
        echo "✅ Tabel jobs dibuat<br>";

        echo "📦 Membuat tabel job_batches...<br>";
        $pdo->exec("
            CREATE TABLE job_batches (
                id VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                total_jobs INTEGER NOT NULL,
                pending_jobs INTEGER NOT NULL,
                failed_jobs INTEGER NOT NULL,
                failed_job_ids TEXT NOT NULL,
                options TEXT NULL,
                cancelled_at INTEGER NULL,
                created_at INTEGER NOT NULL,
                finished_at INTEGER NULL
            )
        ");
        echo "✅ Tabel job_batches dibuat<br>";

        echo "📦 Membuat tabel failed_jobs...<br>";
        $pdo->exec("
            CREATE TABLE failed_jobs (
                id BIGSERIAL PRIMARY KEY,
                uuid VARCHAR(255) NOT NULL UNIQUE,
                connection TEXT NOT NULL,
                queue TEXT NOT NULL,
                payload TEXT NOT NULL,
                exception TEXT NOT NULL,
                failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");
        echo "✅ Tabel failed_jobs dibuat<br>";

        // 5. BUAT TABEL MIGRATIONS
        echo "📦 Membuat tabel migrations...<br>";
        $pdo->exec("
            CREATE TABLE migrations (
                id SERIAL PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INTEGER NOT NULL
            )
        ");

        // Insert migration records
        $pdo->exec("
            INSERT INTO migrations (migration, batch) VALUES 
            ('0001_01_01_000000_create_users_table', 1),
            ('0001_01_01_000001_create_cache_table', 1),
            ('0001_01_01_000002_create_jobs_table', 1)
        ");
        echo "✅ Tabel migrations dibuat<br><br>";

        // Tutup koneksi
        $pdo = null;

        // 6. RECONNECT LARAVEL
        DB::purge('pgsql');
        DB::reconnect('pgsql');

        echo "<h2>🎉 SUKSES TOTAL! Database sudah siap dipakai!</h2>";
        echo "<p><strong>LANGKAH SELANJUTNYA:</strong></p>";
        echo "<ol>";
        echo "<li>✅ Hapus route /nuke-database dari routes/web.php</li>";
        echo "<li>✅ Git add, commit, push</li>";
        echo "<li>✅ Buka halaman /register</li>";
        echo "<li>✅ Buat akun baru</li>";
        echo "</ol>";

        return;
    } catch (Exception $e) {
        return "<h2>❌ ERROR:</h2><pre>" . $e->getMessage() . "</pre>";
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
