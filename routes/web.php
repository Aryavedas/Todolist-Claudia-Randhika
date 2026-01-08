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
        Artisan::call('migrate:fresh --force --seed');

        return 'Sukses! Database berhasil di-reset dan dibuat ulang. Sekarang coba Register lagi.';
    } catch (\Exception $e) {
        // Tampilkan error lengkap biar kita tahu kenapa
        return 'Gagal: ' . $e->getMessage();
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
