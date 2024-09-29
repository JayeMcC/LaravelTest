<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Web\Content\CommentController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Content\HomeController;
use App\Http\Controllers\Web\Content\PostController;
use App\Http\Controllers\Web\Content\UserController;
use App\Http\Controllers\Web\WelcomeController;

Route::get('/', [WelcomeController::class, 'welcome'])->name('welcome');

// Swagger-related routes
Route::get('/swagger/openapi.yml', function () {
  $path = resource_path('swagger/openapi.yml');
  return Response::file($path, [
    'Content-Type' => 'application/x-yaml',
    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
    'Pragma' => 'no-cache',
    'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT',
  ]);
});

Route::get('/swagger/{filename}', function ($filename) {
  $path = resource_path('swagger/' . $filename);
  if (!file_exists($path)) {
    abort(404);
  }
  return Response::file($path, [
    'Content-Type' => 'application/x-yaml',
    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
    'Pragma' => 'no-cache',
    'Expires' => 'Thu, 01 Jan 1970 00:00:00 GMT',
  ]);
})->where('filename', '.*');

// Auth-related routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
  // Post-related routes
  Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
  Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
  Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
  Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
  Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
  Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
  Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

  // Comment-related routes
  Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
  Route::get('/posts/{post}/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
  Route::patch('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
  Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

  // User-related routes
  Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
  Route::get('/users/{user}/posts', [UserController::class, 'userPosts'])->name('users.posts');
  Route::get('/home', [HomeController::class, 'index'])->name('home');
});
