<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BasicController extends Controller
{
    /* welcome function */
    public function welcome()
    {
        return view("welcome");
    }

    /* work function */
    public function work()
    {
        $posts = Post::all();
        return view("work", [
            'posts' => $posts,
        ]);
    }

    /* research function */
    public function research()
    {
        return view("research");
    }

    /* contact function */
    public function contacts()
    {
        return view("contacts");
    }
}
