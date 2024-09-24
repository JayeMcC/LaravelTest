<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;

// Post Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Comment Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::get('/posts/{post}/comments/{comment}', [CommentController::class, 'show']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::patch('/posts/{post}/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']);
});

// User Routes
Route::get('/users/{id}', [AuthController::class, 'getUser']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
