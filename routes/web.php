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

Route::get('/db-setup', function () {
    Config::set('cache.default', 'array');
    Config::set('session.driver', 'array');

    try {
        // 1. PUTUS SEMUA KONEKSI AKTIF
        DB::purge('pgsql');
        
        // 2. BUAT KONEKSI BARU DENGAN PDO LANGSUNG (Bypass Laravel)
        $host = config('database.connections.pgsql.host');
        $port = config('database.connections.pgsql.port');
        $database = config('database.connections.pgsql.database');
        $username = config('database.connections.pgsql.username');
        $password = config('database.connections.pgsql.password');
        
        $dsn = "pgsql:host={$host};port={$port};dbname={$database};sslmode=require";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_AUTOCOMMIT => true, // KUNCI: Auto commit tanpa transaction
        ]);
        
        // 3. ROLLBACK transaksi yang gagal
        $pdo->exec('ROLLBACK');
        
        // 4. HAPUS DAN BUAT ULANG SCHEMA
        $pdo->exec('DROP SCHEMA IF EXISTS public CASCADE');
        $pdo->exec('CREATE SCHEMA public');
        $pdo->exec('GRANT ALL ON SCHEMA public TO PUBLIC');
        $pdo->exec('GRANT ALL ON SCHEMA public TO neondb_owner');
        
        // 5. TUTUP KONEKSI PDO
        $pdo = null;
        
        // 6. RECONNECT LARAVEL
        DB::reconnect('pgsql');
        
        // 7. JALANKAN MIGRASI (Laravel sudah reconnect ke DB yang bersih)
        Artisan::call('migrate', ['--force' => true]);
        
        return "✅ SUKSES TOTAL! Database sudah bersih. Silakan register.";
        
    } catch (\Exception $e) {
        return "❌ ERROR: " . $e->getMessage() . "<br><br>Trace: " . $e->getTraceAsString();
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
