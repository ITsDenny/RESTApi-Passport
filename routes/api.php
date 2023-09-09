<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;

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

//Route untuk login,logout dan register user
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);   


Route::middleware('auth:api')->group(function() {
    //Route Untuk Post
    Route::get('/view_post', [PostsController::class, 'index']);
    Route::post('/new_post', [PostsController::class, 'store']);
    Route::get('/view_post/{id}', [PostsController::class, 'show']);
    Route::put('/update_post/{id}', [PostsController::class, 'update']);
    Route::delete('/delete_post/{id}', [PostsController::class, 'destroy']);
    //Route untuk Profile
    Route::get('/profile/{id}',[ProfileController::class,'show']);
    Route::post('/profile_image',[ProfileController::class,'updateProfileImage']);
    //Route Like
    Route::post('/post/{post}/like', [LikeController::class, 'like'])->name('posts.like');
    Route::delete('/post/{post}/unlike', [LikeController::class, 'unlike'])->name('posts.unlike');
    //Route Follow
    Route::post('/follow/{user_id}', [FollowController::class,'followUser']);
    Route::delete('/unfollow/{user_id}', [FollowController::class, 'unfollowUser']);
});




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});