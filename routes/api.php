<?php

use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\TokenAuthenticationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Get current user information
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['guest'])->post('/login', [TokenAuthenticationController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/logout', [TokenAuthenticationController::class, 'destroy']);

Route::get('/quote', QuoteController::class);
