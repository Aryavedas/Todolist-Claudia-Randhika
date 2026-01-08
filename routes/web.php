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
    // 1. BYPASS CACHE DATABASE (Kunci Solusinya Disini!)
    // Kita paksa Laravel pakai 'array' (RAM) saja sementara, 
    // supaya tidak mencari tabel 'cache' yang belum ada.
    Config::set('cache.default', 'array');
    Config::set('session.driver', 'array');

    try {
        // 2. HAPUS PAKSA SCHEMA (Cara Postgres)
        // Ini lebih kuat daripada migrate:fresh biasa
        // Ini akan menghapus semua tabel, view, dan sequence tanpa ampun.
        DB::statement('DROP SCHEMA public CASCADE');
        DB::statement('CREATE SCHEMA public');

        // 3. JALANKAN MIGRASI
        // Karena schema sudah kosong melompong, migrate akan jalan lancar
        Artisan::call('migrate --force --seed');

        return "SUKSES TOTAL! Database sudah bersih dan Cache driver berhasil di-bypass. Silakan coba Register sekarang.";
    } catch (\Exception $e) {
        return "Masih Error: " . $e->getMessage();
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
