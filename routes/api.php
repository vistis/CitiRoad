<?php

use App\Http\Controllers\Api\QuoteController;
// use App\Http\Controllers\Api\TokenAuthenticationController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Base URL
// http://localhost:8000/api

//** PUBLIC ROUTES */
// API Health
Route::get('/', function() {
    return response()->json(['message' => "API is up"], 200);
});

//** GUEST ROUTES */
Route::middleware('guest')->group(function() {
    // Authentication
    Route::post('/citizen/register', [TokenController::class, 'createCitizen']);
    Route::post('/citizen/login', [TokenController::class, 'storeCitizen']);
    Route::post('/officer/login', [TokenController::class, 'storeOfficer']);
    Route::post('/admin/login', [TokenController::class, 'storeAdmin']);
});

//** AUTHENTICATED ROUTES */
Route::middleware('auth:sanctum')->group(function() {
    // View report
    Route::get('/report/view', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->readOne($request);

        return $response;
    });

    // Report list
    Route::get('/report', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->readList($request);

        return response()->json($response, 200);
    });
});

//** CITIZEN ROTUES */
Route::middleware('auth:citizen-api')->group(function() {
    // Get current user information
    Route::get('/citizen', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/citizen/logout', [TokenController::class, 'destroy']);

    // Create report
    Route::post('/report/make', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->store($request);
        return response()->json([
            'message' => "Report received",
            'report-info' => $response
        ], 201);
    });
});

//** OFFICER ROTUES */
Route::middleware('auth:officer-api')->group(function() {
    // Get current user information
    Route::get('/officer', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/officer/logout', [TokenController::class, 'destroy']);

    // Update report status
    Route::post('/report/update', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->updateStatus($request);
        return response()->json([
            'message' => "Report status updated",
            'report-info' => $response
        ], 200);
    });
});

//** ADMIN ROTUES */
Route::middleware('auth:admin-api')->group(function() {
    // Get current user information
    Route::get('/admin', function(Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/admin/logout', [TokenController::class, 'destroy']);
});

// Route::middleware(['guest'])->post('/login', [TokenAuthenticationController::class, 'store']);
// Route::middleware(['auth:sanctum'])->post('/logout', [TokenAuthenticationController::class, 'destroy']);

Route::get('/quote', QuoteController::class);
