<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->lazy();

        return view('home', compact('todos'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string",
            "description" => "required|string"
        ]);

        DB::table('todos')->insert([
            'user_id' => auth()->id(), // 🔐 user login
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

    public function show(Todo $todo)
    {
        $this->authorizeTodo($todo);

        return view('show', compact('todo'));
    }

    public function edit(Todo $todo)
    {
        $this->authorizeTodo($todo);

        return view('todos.edit-todo', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        $this->authorizeTodo($todo);

        $request->validate([
            "title" => "required|string",
            "description" => "required|string",
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

    public function destroy(Todo $todo)
    {
        $this->authorizeTodo($todo);

        $todo->delete();

        return redirect()
            ->route('todos.index')
            ->with('success', 'Todo berhasil dihapus');
    }

    public function updateByClickToggle(Request $request, Todo $todo)
    {
        $this->authorizeTodo($todo);

        $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $todo->update([
            'is_completed' => $request->is_completed,
        ]);

        return redirect()->back();
    }

    /**
     * 🔐 Helper sederhana (tidak ribet)
     */
    private function authorizeTodo(Todo $todo)
    {
        abort_if($todo->user_id !== auth()->id(), 403);
    }
}
