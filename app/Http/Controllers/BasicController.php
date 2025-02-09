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

    /* about me function */
    public function me()
    {
        return view("me");
    }

    /* contact function */
    public function contacts()
    {
        return view("contacts");
    }
}
