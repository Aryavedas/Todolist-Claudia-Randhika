<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::prefix('todo')->name('todos.')->group(function () {

    // View Create: form tambah todo
    Route::get('/create', [TodoController::class, 'create'])->name('create');
    Route::post('/', [TodoController::class, 'store'])->name('store');

    // View Show: tampilkan 1 todo
    Route::get('/{todo}', [TodoController::class, 'show'])->name('show');

    // View Update: form edit + update
    Route::get('/{todo}/edit', [TodoController::class, 'edit'])->name('edit');
    Route::put('/{todo}', [TodoController::class, 'update'])->name('update');

    // View Delete: konfirmasi hapus);
    Route::delete('/{todo}', [TodoController::class, 'destroy'])->name('destroy');
});
