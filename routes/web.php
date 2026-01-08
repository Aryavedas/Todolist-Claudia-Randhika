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
    try {
        // Jalankan migrasi
        Artisan::call('migrate --force');
        return 'BERHASIL! Database sudah siap. Silakan Register.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
