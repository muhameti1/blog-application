<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Post Routes
|--------------------------------------------------------------------------
*/

// Public routes with optional authentication
Route::middleware('optional.auth')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{slug}', [PostController::class, 'show']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::get('/my-posts', [PostController::class, 'myPosts']);

    // Admin only
    Route::post('/posts/{post}/approve', [PostController::class, 'approve']);
    Route::post('/posts/{post}/reject', [PostController::class, 'reject']);
});
