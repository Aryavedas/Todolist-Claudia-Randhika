<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

// Home setelah login
Route::get('/', [TodoController::class, 'index'])
    ->middleware(['auth'])
    ->name('todos.index');

// Update toggle checkbox
Route::put('/update-toggle/{todo}', 
    [TodoController::class, 'updateByClickToggle']
)->middleware(['auth'])->name('home.todos.update');

// CRUD TODOLIST
require __DIR__ . '/web/todos.php';

// Breeze auth routes
require __DIR__ . '/auth.php';

// Breeze auth routes
require __DIR__ . '/web/profile.php';
