<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\AdminCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route with middleware auth
// Display dashboard if user has logged in
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/post/{post:id}', [HomeController::class, 'detail'])->name('detail-post');

// Display list of posts and detail of post
Route::get('/posts', [PostController::class, 'index'])->name('list-posts');
Route::get('/posts/detail/{post:id}', [PostController::class, 'show'])->name('detail-post');

// Create new post
Route::get('/posts/create', [PostController::class, 'create'])->name('create-post');
Route::post('/posts', [PostController::class, 'store'])->name('store-post');

// Update post
Route::get('/posts/edit/{post:id}', [PostController::class, 'edit'])->name('edit-post');
Route::put('/posts/update/{post:id}', [PostController::class, 'update'])->name('update-post');

// Delete post (I use soft delete)
Route::delete('/posts/delete/{post:id}', [PostController::class, 'delete'])->name('delete-post');

// Route with middleware is_admin to handle admin category page
Route::resource('/categories', AdminCategoryController::class)->except('show')->middleware('admin');