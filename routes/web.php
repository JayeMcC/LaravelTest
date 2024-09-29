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

Route::get('/swagger/openapi.yml', function () {
    $path = resource_path('swagger/openapi.yml');
    return Response::file($path, [
        'Content-Type' => 'application/x-yaml',
    ]);
});

Route::get('/swagger/{filename}', function ($filename) {
    $path = resource_path('swagger/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return Response::file($path, [
        'Content-Type' => 'application/x-yaml',
    ]);
})->where('filename', '.*');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/posts/{post}/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::patch('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
