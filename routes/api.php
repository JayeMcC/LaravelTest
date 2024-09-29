<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Content\PostController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Content\CommentController;
use App\Http\Controllers\API\Content\UserController;

# Auth endpoints
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    # Post-related API endpoints
    Route::get('/posts', [PostController::class, 'index']); // List all posts (pagination)
    Route::get('/posts/{id}', [PostController::class, 'show']); // Show a specific post
    Route::post('/posts', [PostController::class, 'store']); // Create a new post
    Route::patch('/posts/{id}', [PostController::class, 'update']); // Update a specific post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // Delete a specific post

    # Comment-related API endpoints (to match web routes)
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']); // List comments for a post
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']); // Create a comment on a post
    Route::get('/posts/{post}/comments/{comment}', [CommentController::class, 'show']); // Show a specific comment
    Route::get('/posts/{post}/comments/{comment}/edit', [CommentController::class, 'edit']); // Edit comment (API can return the data to edit)
    Route::patch('/posts/{post}/comments/{comment}', [CommentController::class, 'update']); // Update a comment
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']); // Delete a comment

    # User-related API endpoints
    Route::get('/users', [UserController::class, 'index']); // List all users (pagination)
    Route::get('/users/{id}', [UserController::class, 'show']); // Show a specific user's profile
    Route::patch('/users/{user}', [UserController::class, 'update']); // Update a user profile
    Route::delete('/users/{user}', [UserController::class, 'destroy']); // Delete a user
});
