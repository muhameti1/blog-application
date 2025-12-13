<?php

use App\Http\Controllers\Api\NewsletterController;
use Illuminate\Support\Facades\Route;

// Public newsletter routes
Route::prefix('newsletter')->group(function () {
    Route::post('/subscribe', [NewsletterController::class, 'subscribe']);
    Route::get('/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe']);
    Route::post('/status', [NewsletterController::class, 'status']);
});
