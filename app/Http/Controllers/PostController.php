<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

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

        Post::create([
            'title' => $validated['title'],
            'date' => $validated['date'],
            'description' => $validated['description'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('work')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        $post->update($validated);

        return redirect()->route('work')->with('success', 'Post updated successfully!');
    }

    public function delete(Post $post)
    {
        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        return view('posts.delete', compact('post'));
    }

    public function destroy(Post $post)
    {
        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        $post->delete();

        return redirect()->route('work')->with('success', 'Post deleted successfully!');
    }
}
