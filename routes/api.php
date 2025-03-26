<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NYTimesController;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', [UserController::class, 'getUser']);
        Route::get('/nytimes/best-sellers-history', [NYTimesController::class, 'getBestSellersHistory']);
    });
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
});
