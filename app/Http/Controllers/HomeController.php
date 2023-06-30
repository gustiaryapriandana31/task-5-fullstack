<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = Post::latest()->filter(request(["category", "user"]))->paginate(2);
        
        return view('home', [
            'posts' => $posts
        ]);
    }

    public function detail(Post $post)
    {
        // Find Post by ID
        $post = Post::find($post->id);
        
        return view('post', [
            'post' => $post
        ]);
    }
}