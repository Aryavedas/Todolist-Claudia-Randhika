<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Artisan;

// Home setelah login
Route::get('/', [TodoController::class, 'index'])
    ->middleware(['auth'])
    ->name('todos.index');

// Update toggle checkbox
Route::put(
    '/update-toggle/{todo}',
    [TodoController::class, 'updateByClickToggle']
)->middleware(['auth'])->name('home.todos.update');

Route::get('/setup-database', function () {
    try {
        // Menjalankan migrasi database
        Artisan::call('migrate --force');
        return 'Sukses! Database berhasil dibuat. Silakan hapus route ini dan coba Register lagi.';
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
