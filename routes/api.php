<?php

use App\Http\Controllers\Api\QuoteController;
// use App\Http\Controllers\Api\TokenAuthenticationController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Base URL
// http://localhost:8000/api

// API Health
Route::get('/', function() {
    return response()->json(['message' => "API is up"], 200);
});

// Get current user information
Route::get('/citizen', function (Request $request) {
    return $request->user();
})->middleware('auth:citizen-api');

Route::get('/officer', function (Request $request) {
    return $request->user();
})->middleware('auth:officer-api');

Route::get('/admin', function (Request $request) {
    return $request->user();
})->middleware('auth:admin-api');

//** AUTHENTICATION */
// Public routes
Route::middleware('guest')->group(function () {
    // Citizen routes
    Route::post('/citizen/register', [TokenController::class, 'createCitizen']);
    Route::post('/citizen/login', [TokenController::class, 'storeCitizen']);
});

// Authenticated routes
Route::post('/citizen/logout', [TokenController::class, 'destroy'])->middleware('auth:citizen-api');
Route::post('/officer/logout', [TokenController::class, 'destroy'])->middleware('auth:officer-api');
Route::post('/admin/logout', [TokenController::class, 'destroy'])->middleware('auth:admin-api');

// Route::middleware(['guest'])->post('/login', [TokenAuthenticationController::class, 'store']);
// Route::middleware(['auth:sanctum'])->post('/logout', [TokenAuthenticationController::class, 'destroy']);

Route::get('/quote', QuoteController::class);
