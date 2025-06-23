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
    Route::post('/register/citizen', [TokenController::class, 'createCitizen']);
    Route::post('/login/citizen', [TokenController::class, 'storeCitizen']);
    Route::post('/login/officer', [TokenController::class, 'storeOfficer']);
    Route::post('/login/admin', [TokenController::class, 'storeAdmin']);
});

//** AUTHENTICATED ROUTES */
Route::middleware('auth:sanctum')->group(function() {
    // Logout
    Route::post('/logout', [TokenController::class, 'destroy']);

    // View report
    Route::get('/report', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->readOne($request);

        return $response;
    });
});

//** CITIZEN ROTUES */
Route::middleware('auth:citizen-api')->group(function() {
    // Get current user information
    Route::get('/citizen', function (Request $request) {
        return $request->user();
    });

    // Report list
    Route::get('/report/list/citizen', function(Request $request) {
        // Override request (show only reports of the user)
        $request->citizen_id = $request->user()->id;

        $response = app('App\Http\Controllers\ReportController')->readList($request);

        return response()->json($response, 200);
    });

    // Create report
    Route::post('/report/make', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->create($request);
        return $response;
    });
});

//** OFFICER ROTUES */
Route::middleware('auth:officer-api')->group(function() {
    // Get current user information
    Route::get('/officer', function (Request $request) {
        return $request->user();
    });

    // Report statistics
    Route::get('/report/stats/officer', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->stats($request);
        return $response;
    });

    // Report list
    Route::get('/report/list/officer', function(Request $request) {
        // Get the province of the officer
        $provinceName = DB::table('provinces')->where('id', $request->user()->province_id)->value('name');

        // Apply key for filter
        $request->filter = $provinceName;

        $response = app('App\Http\Controllers\ReportController')->readList($request);

        return response()->json($response, 200);
    });

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

    // Report statistics
    Route::get('/report/stats/admin', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->stats($request);
        return $response;
    });

    // Report list
    Route::get('/report/list/admin', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->readList($request);

        return response()->json($response, 200);
    });

    // Delete report
    Route::delete('/report/delete', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->delete($request);

        return response()->json(['message' => "Report deleted",], 200);
    });
});

// Route::middleware(['guest'])->post('/login', [TokenAuthenticationController::class, 'store']);
// Route::middleware(['auth:sanctum'])->post('/logout', [TokenAuthenticationController::class, 'destroy']);

Route::get('/quote', QuoteController::class);
