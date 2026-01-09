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

Route::get('/setup-db-fresh', function () {
    try {
        // 1. Matikan Cache & Session Database sementara (Bypass Error)
        Config::set('cache.default', 'array');
        Config::set('session.driver', 'array');

        // 2. Wipe Database Jalur Keras (Khusus Postgres/Neon)
        // Ini menghapus schema 'public' secara paksa untuk membersihkan transaksi yang nyangkut
        DB::statement('DROP SCHEMA public CASCADE');
        DB::statement('CREATE SCHEMA public');

        // 3. Jalankan Migrasi & Seeder
        // --force wajib untuk Vercel (Production)
        Artisan::call('migrate --force --seed');

        return 'BERHASIL! Database sudah di-reset total dari nol. Silakan coba Register sekarang.';
    } catch (\Exception $e) {
        return 'Gagal: ' . $e->getMessage();
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
