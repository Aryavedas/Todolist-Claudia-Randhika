<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::get('/', [TodoController::class, 'index'])->name('todos.index');
Route::put('/update-toggle/{todo}', [TodoController::class, 'updateByClickToggle'])->name('home.todos.update');

// Route CRUD TODOLIST
require __DIR__ . '/web/todos.php';
