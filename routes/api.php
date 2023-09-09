<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route untuk login dan register user
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);   

/**
 * Route Post dari atas kebawah
 * Get all post
 * New Post
 * Get Post by id
 * Update Post
 * Delete Post
 */
Route::middleware('auth:api')->group(function() {
    Route::get('/view_post', [PostsController::class, 'index']);
    Route::post('/new_post', [PostsController::class, 'store']);
    Route::get('/view_post/{id}', [PostsController::class, 'show']);
    Route::put('/update_post/{id}', [PostsController::class, 'update']);
    Route::delete('/delete_post/{id}', [PostsController::class, 'destroy']);
});





Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});