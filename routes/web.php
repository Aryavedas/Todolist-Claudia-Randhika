<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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
    try {
        // 1. Bersihkan Cache Laravel agar konfigurasi fresh
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        // 2. Paksa Hapus Semua Tabel & Migrasi Ulang (Fresh)
        Artisan::call('migrate:fresh --force --seed');

        return "BERHASIL! Database sudah di-reset total. Silakan hapus route ini dan coba Register sekarang.";
    } catch (\Exception $e) {
        // Jika masih error, kita coba paksa wipe manual schema public (khusus Postgres)
        try {
            DB::statement('DROP SCHEMA public CASCADE');
            DB::statement('CREATE SCHEMA public');
            Artisan::call('migrate --force --seed');

            return "BERHASIL (Lewat Jalur Wipe Schema)! Database bersih. Silakan coba Register.";
        } catch (\Exception $e2) {
            return "GAGAL TOTAL. Error 1: " . $e->getMessage() . " | Error 2: " . $e2->getMessage();
        }
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
