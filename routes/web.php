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

Route::get('/reset-db-hard', function () {
    // 1. MATIKAN CACHE & SESSION DATABASE
    // Supaya Laravel TIDAK PERNAH mencari tabel 'cache' atau 'sessions'
    Config::set('cache.default', 'array');
    Config::set('session.driver', 'array');

    try {
        // 2. PUTUS KONEKSI LAMA (PENTING UNTUK NEON)
        // Ini me-reset status "transaction aborted" yang nyangkut
        DB::purge('pgsql');
        DB::reconnect('pgsql');

        // 3. HAPUS SEMUA TABEL SECARA MANUAL (CASCADE)
        // Kita tidak pakai migrate:fresh dulu, kita hancurkan manual
        $schema = 'public';

        // Matikan foreign key check
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        // Hapus Schema Public dan Buat Ulang (Cara Paling Bersih di Postgres)
        // Menggunakan unprepared agar tidak dibungkus transaction laravel
        DB::unprepared('DROP SCHEMA public CASCADE; CREATE SCHEMA public;');

        // 4. JALANKAN MIGRASI DARI NOL
        Artisan::call('migrate --force');

        return "✅ SUKSES BESAR! Database sudah bersih total. Silakan hapus route ini dan Register sekarang.";
    } catch (\Exception $e) {
        // Jika masih gagal, kita coba cara fallback: Hapus tabel satu per satu
        try {
            $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
            foreach ($tables as $table) {
                DB::statement('DROP TABLE IF EXISTS "' . $table->tablename . '" CASCADE');
            }
            Artisan::call('migrate --force');
            return "✅ SUKSES (Jalur Fallback)! Database bersih. Silakan Register.";
        } catch (\Exception $ex) {
            return "❌ MASIH ERROR: " . $e->getMessage() . " || " . $ex->getMessage();
        }
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
