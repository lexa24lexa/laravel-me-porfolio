<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        Post::create($validated);

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
        return view('posts.delete', compact('post'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('work')->with('success', 'Post deleted successfully!');
    }
}
