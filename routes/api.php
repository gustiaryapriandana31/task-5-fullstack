<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1.0', 'middleware' => ['api', 'api.version:v1.0']], function() {

    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('test-auth', function() {
            return response()->json([
                'success' => true,
                'message' => 'You are authorized to access this route!'
            ]); 
        });
    
        // Route Post
        Route::get('posts', [PostController::class, 'index']);
        Route::get('posts/detail/{post:id}', [PostController::class, 'show']);
        
        Route::post('posts', [PostController::class,'store']);
        
        Route::put('posts/update/{post:id}', [PostController::class, 'update']);
        
        Route::delete('posts/delete/{post:id}', [PostController::class, 'delete']);
    });
});