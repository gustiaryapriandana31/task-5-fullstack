<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    private $prefix;
    
    public function __construct()
    {
        $this->middleware('auth');
        
        // Mendapatkan prefiks route
        $this->prefix = Route::getCurrentRoute()->getPrefix();
    }
    
    /**
     * index
     *
     * @return void
    */
    public function index(Request $request)
    {
        
        // Mengecek jika route memiliki prefik "api/v1.0"
        if ($this->prefix === 'api/v1.0') {
            // get all posts with pagination who created by auth user
            $posts = Post::where('user_id', Auth::user()->id)->paginate(2); 
            return response()->json([
                'status' => 'Success',
                'message' => 'List All Posts',
                'data' => $posts
            ], 200);
        } else {
            // get all posts with pagination who created by auth user
            $posts = Post::where('user_id', Auth::user()->id)->get(); 
            return view('posts.index', [
                'posts' => $posts
            ]);
        }        
    }

    /**
      * create
      *
      * @return void
    */
    public function create()
    {
        return view('posts.create', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
    */
    public function store(Request $request) 
    {
        // Define validation rules
        $validate = Validator::make($request->all(), [
            'category_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|file|max:2048'
        ]);

        // Check if validation fails
        if($validate->fails()) {
            if($this->prefix === 'api/v1.0') {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Validation Error',
                    'data' => $validate->errors()
                ], 422);
            } else {
                return redirect()->back()->withErrors($validate)->withInput();
            }
        }
        
        // Upload Image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());
        
        $user = Auth::user();

        // ddd($request->all());
        // Create Post
        $post = Post::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $image->hashName()
        ]);
        
        // Mengecek jika route memiliki prefik "api/v1.0"
        if ($this->prefix === 'api/v1.0') {
            return response()->json([
                'status' => 'Success',
                'message' => 'Post Created Successfully',
                'data' => $post
            ], 200);
        } else {
            return redirect(route('list-posts'))->with('status', 'New post has been added');
        }
    }
    
    /**
     * show
     *
     * @param  mixed $post
     * @return void
    */
    public function show(Post $post)
    {
        // Find Post by ID
        $post = Post::find($post->id);


        if ($this->prefix === 'api/v1.0') {
            return response()->json([
                'status' => 'Success',
                'message' => 'Detail Post ' . $post->id,
                'data' => $post
            ], 200);
        } else {
            return view('posts.show', [
                'post' => $post
            ]);
        }

        // Check if post not found
        if($post) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Detail Post ' . $post->id,
                'data' => $post
            ], 200);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Post Not Found'
            ]);
        }
    }

    /**
     * edit
     *
     * @param  mixed $post
     * @return void
    */
    public function edit(Post $post) 
    {
        return view('posts.edit', [
            'post' => $post,
            'categories' => Category::all()
        ]);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
    */
    public function update(Request $request, Post $post)
    {
        // Define validation rules
        $validate = Validator::make($request->all(), [
            'category_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'image' => 'image|file|max:2048'
        ]);

        // Check if validation fails
        if($validate->fails()) {
            if($this->prefix === 'api/v1.0') {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Validation Error',
                    'data' => $validate->errors()
                ], 422);
            } else {
                return redirect()->back()->withErrors($validate)->withInput();
            }
        }

        // save request data to $post variable
        $user = Auth::user();
        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        
        // if request has image, and delete old image
        if ($request->hasFile('image')) {
            // upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //delete old image
            Storage::delete('public/posts/'.basename($post->image));

            // Update post with image
            Post::where('id', $post->id)
            ->update([
                'user_id' => $user->id,
                'category_id' => $post->category_id,
                'title' => $post->title,
                'content' => $post->content,
                'image' => $image->hashName()
            ]);
        } else { // Update post without image
            Post::where('id', $post->id)
            ->update([
                'user_id' => $user->id,
                'category_id' => $post->category_id,
                'title' => $post->title,
                'content' => $post->content
            ]);
        }

        // Mengecek jika route memiliki prefik "api/v1.0"
        if ($this->prefix === 'api/v1.0') {
            return response()->json([
                'status' => 'Success',
                'message' => 'Post Updated Successfully',
                'data' => $post
            ], 200);
        } else {
            return redirect(route('list-posts'))->with('status', 'Post has been updated');
        }
    }

    /**
     * delete (Use Soft Delete)
     *
     * @param  mixed $post
     * @return void
    */
    public function delete(Post $post)
    {
        // Find post by ID
        $post = Post::find($post->id);

        //delete image
        Storage::delete('public/posts/'.basename($post->image));

        //delete post
        $post->delete();
        
        // Mengecek jika route memiliki prefik "api/v1.0"
        if ($this->prefix === 'api/v1.0') {
            return response()->json([
                'status' => 'Success',
                'message' => 'Post Deleted Succesfully'
            ], 200);
        } else {
            return redirect(route('list-posts'))->with('status', 'Post has been deleted');
        }
    }
}