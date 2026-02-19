<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])
    ->withoutMiddleware('api')
    ->middleware('web');
Route::post('/register', [AuthController::class, 'register'])
    ->withoutMiddleware('api')
    ->middleware('web');
Route::post('/logout', [AuthController::class, 'logout'])
    ->withoutMiddleware('api')
    ->middleware(['web', 'auth:web']);
Route::post('/social-login', [AuthController::class, 'socialLogin'])
    ->withoutMiddleware('api')
    ->middleware('web');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->withoutMiddleware('api')
    ->middleware('web');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->withoutMiddleware('api')
    ->middleware('web');

Route::middleware('web')->withoutMiddleware('api')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth');
    Route::get('/google/redirect', [AuthController::class, 'googleRedirect']);
    Route::get('/google/callback', [AuthController::class, 'googleCallback']);
});

Route::middleware(['web', 'auth'])->apiResource('subscriptions', SubscriptionController::class);
