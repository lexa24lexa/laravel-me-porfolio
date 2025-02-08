<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        $post = new Post();

        return view('posts.create', compact('post'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'description' => 'required',
        ]);

        $post = Post::create($validated);
        $post->save();

        return redirect('/work');
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post)
    {
        return view('posts.edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'description' => 'required',
        ]);
        $post->update($validated);

        return redirect('/work');
    }

    public function delete(Post $post)
    {
        return view('posts.delete', [
            'post' => $post,
        ]);
    }

    public function destroy(Post $post) {
        $post->delete();
        return redirect('/work');
    }
}
