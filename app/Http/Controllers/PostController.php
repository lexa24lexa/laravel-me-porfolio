<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'date' => $validated['date'],
            'description' => $validated['description'],
            'user_id' => Auth::id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json($post, 201);
        }

        return redirect()->route('work')->with('success', 'Post created successfully!');
    }


    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        // 🔹 Atualiza os dados do post corretamente
        $post->title = $validated['title'];
        $post->date = $validated['date'];
        $post->description = $validated['description'];
        $post->save(); // 🔹 Garante que a atualização é feita na base de dados

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Post updated successfully!',
                'post' => Post::find($post->id),
            ], 200);
        }

        return redirect()->route('work')->with('success', 'Post updated successfully!');
    }

    public function delete(Post $post)
    {
        return view('posts.delete', compact('post'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('work')->with('success', 'Post deleted successfully!');
    }
}
