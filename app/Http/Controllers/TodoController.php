<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::orderBy('created_at', 'desc')->lazy();

        return view('home', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "string|required",
            "description" => "string|required"
        ]);

        DB::table('todos')->insert([
            'user_id' => 1,
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('todos.index')
            ->with('success', 'Todo berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        return view('show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit-todo', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            "title" => "string|required",
            "description" => "string|required",
            "is_completed" => "nullable|boolean"
        ]);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => $request->boolean('is_completed'),
        ]);

        return redirect()
            ->route('todos.index')
            ->with('success', 'Todo berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()
            ->route('todos.index')
            ->with('success', 'Todo berhasil dihapus');
    }

    public function updateByClickToggle(Request $request, Todo $todo)
    {
        $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $todo->update([
            'is_completed' => $request->is_completed,
        ]);

        return redirect()->back();
    }
}
