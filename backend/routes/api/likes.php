<?php

use App\Http\Controllers\Api\LikeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Like Routes
|--------------------------------------------------------------------------
*/

// Public routes with optional authentication
Route::middleware('optional.auth')->group(function () {
    Route::get('/posts/{post}/like-status', [LikeController::class, 'checkLike']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle']);
});
