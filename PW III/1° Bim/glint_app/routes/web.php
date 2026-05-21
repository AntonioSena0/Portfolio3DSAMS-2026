<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GlintController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GlintController::class, 'index'])->name('landing');
Route::get('/login', [GlintController::class, 'indexLogin'])->name('login');
Route::get('/register', [GlintController::class, 'indexRegister'])->name('register');
Route::get('/home', [GlintController::class, 'indexHomePosts'])->name('home.posts')->middleware('auth');
Route::get('/home/search', [GlintController::class, 'indexHomeSearch'])->name('home.search')->middleware('auth');
Route::get('/home/create-posts', [GlintController::class, 'indexHomeCreatePost'])->name('home.posts.create')->middleware('auth');
Route::get('/home/activity', [GlintController::class, 'indexHomeActivity'])->name('home.activity')->middleware('auth');
Route::get('/perfil', [GlintController::class, 'profile'])->name('perfil')->middleware('auth');

Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

Route::patch('/users/update', [UserController::class, 'update'])->name('users.update')->middleware('auth');
Route::delete('/users/delete', [UserController::class, 'delete'])->name('users.delete')->middleware('auth');

Route::post('/posts/create-post', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/post/{post}', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::delete('/posts/{post}', [PostController::class, 'delete'])->name('posts.delete')->middleware('auth');

Route::post('/posts/{post}/like', [PostLikeController::class, 'like'])->name('posts.like')->middleware('auth');
Route::delete('/posts/{post}/unlike', [PostLikeController::class, 'unlike'])->name('posts.unlike')->middleware('auth');

Route::post('/posts/{post}/comments', [CommentController::class, 'makeComment'])->name('comments.create')->middleware('auth');
Route::delete('/comments/{comment}', [CommentController::class, 'delete'])->name('comments.delete')->middleware('auth');

Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow')->middleware('auth');
Route::delete('/users/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow')->middleware('auth');

Route::get('/users/{user}/posts', [GlintController::class, 'posts'])->name('users.posts');
Route::get('/users/{user}/profile', [GlintController::class, 'profile'])->name('users.profile');
