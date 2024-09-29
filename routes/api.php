<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Content\PostController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Content\CommentController;
use App\Http\Controllers\API\Content\UserController;

# Auth endpoints
Route::post('/register', [RegisterController::class, 'register']); # Register a new user
Route::post('/login', [LoginController::class, 'login']); # Login a user

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/logout', [LoginController::class, 'logout']); # Logout a user

  # Post-related API endpoints
  Route::get('/posts', [PostController::class, 'index']); # List all posts (pagination)
  Route::get('/posts/{post}', [PostController::class, 'show']); # Get a specific post
  Route::post('/posts', [PostController::class, 'store']); # Create a new post
  Route::patch('/posts/{post}', [PostController::class, 'update']); # Update an existing post
  Route::delete('/posts/{post}', [PostController::class, 'destroy']); # Delete a specific post

  # Comment-related API endpoints 
  # Implied by part 4: Features "a. Implement pagination for list endpoints (posts, comments, users)."
  Route::get('/posts/{post}/comments', [CommentController::class, 'index']); # List all comments for a post (pagination)
  Route::post('/posts/{post}/comments', [CommentController::class, 'store']); # Create a comment on a post
  Route::get('/posts/{post}/comments/{comment}', [CommentController::class, 'show']); # Show a specific comment
  Route::patch('/posts/{post}/comments/{comment}', [CommentController::class, 'update']); # Update a comment
  Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']); # Delete a comment

  # User-related API endpoints
  Route::get('/users', [UserController::class, 'index']); # List all users (pagination)
  Route::get('/users/{user}', [UserController::class, 'show']); # Get a specific user's details
});
