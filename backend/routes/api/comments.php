<?php

use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Comment Routes
|--------------------------------------------------------------------------
*/

// Public routes with optional authentication
Route::middleware('optional.auth')->group(function () {
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::get('/my-comments', [CommentController::class, 'myComments']);

    // Admin only
    Route::get('/admin/comments', [CommentController::class, 'adminComments']);
    Route::post('/comments/{comment}/approve', [CommentController::class, 'approve']);
    Route::post('/comments/{comment}/reject', [CommentController::class, 'reject']);
});
