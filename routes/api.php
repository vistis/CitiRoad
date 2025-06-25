<?php

use App\Http\Controllers\Api\QuoteController;
// use App\Http\Controllers\Api\TokenAuthenticationController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Base URL
// http://localhost:8000/api

//** PUBLIC ROUTES */
// API Health
Route::get('/', function() {
    return response()->json([
        'message' => "API is up"
    ], 200);
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
    // Get current user information
    Route::get('/user', function (Request $request) {
        // Get user information
        $response = $request->user();

        // Resolve province name
        if ($response->province_id){
            $response->province_name = DB::table('provinces')->where('id', $request->user()->province_id)->value('name');
        }

        // Get user type (if has status is citizen - has role is officer - else is admin)
        $response->user_type = $response->status ? 'citizen' : ($response->role ? 'officer' : 'admin');

        return response()->json($response, 200);
    });

    // Logout
    Route::post('/logout', [TokenController::class, 'destroy']);

    // Report list
    Route::get('/reports', function(Request $request) {
        // Override request
        if ($request->user()->status) { // Is citizen; only show their own reports
            if ($request->user()->status == "Pending" || $request->user()->status == "Rejected") {
                return response()->json([
                    'message' => "Unauthorized"
                ], 403); // Reject request if they has not been approved before
            }
        }

        $response = app('App\Http\Controllers\ReportController')->readList($request);

        return response()->json([
            'count' => $response['count'],
            'reports' => $response['reports']
        ], $response['code']);
    });

    // View specific report
    Route::get('/report', function(Request $request) {
        if ($request->user()->status) { // If the reqeust comes from a citizen
            if ($request->user()->status == "Pending" || $request->user()->status == "Rejected") {
                return response()->json([
                    'message' => "Unauthorized"
                ], 403);
            }
        }

        $response = app('App\Http\Controllers\ReportController')->readOne($request);

        return response()->json([
            'message' => $response['message'],
            'report' => $response['report'],
            'images' => $response['images']
        ], $response['code']);
    });

    // Add report to bookmark
    Route::post('/report/bookmark', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->bookmark($request);

        return response()->json([
            'message' => $response['message'],
        ], $response['code']);
    });
});

//** CITIZEN ROTUES */
Route::middleware('auth:citizen-api')->group(function() {
    // Create report
    Route::post('/report/make', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->create($request);

        if (!$response['report']) {
            // Report creation failed
            return response()->json([
                'message' => $response['message']
            ], $response['code']);
        }

        return response()->json([
            'message' => $response['message'],
            'report' => $response['report'],
            'images' => $response['images']
        ], $response['code']);
    });

    // Delete own account
    Route::post('/user/delete', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->delete($request->user()->id);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });
});

//** OFFICER ROTUES */
Route::middleware('auth:officer-api')->group(function() {
    // Report statistics
    Route::get('/stats/reports', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->stats($request);

        return response()->json([
            'total' => $response['total'],
            'active' => $response['active'],
            'resolved' => $response['resolved']
        ], $response['code']);
    });

    // Update report status
    Route::post('/report/update', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->updateStatus($request);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });
});

//** ADMIN ROTUES */
Route::middleware('auth:admin-api')->group(function() {
    // Platform statistics
    Route::get('/stats', function(Request $request) {
        // Get report stats
        $reports = app('App\Http\Controllers\ReportController')->stats($request);

        // Get citizen stats
        $citizens = app('App\Http\Controllers\CitizenController')->stats($request);

        // Get officer stats
        $officers = app('App\Http\Controllers\OfficerController')->stats($request);

        return response()->json([
            'reports' => [
                'total' => $reports['total'],
                'resolved' => $reports['resolved'],
                'active' => $reports['active']
            ],
            'citizens' => [
                'total' => $citizens['total'],
                'pending' => $citizens['pending'],
                'approved' => $citizens['approved']
            ],
            'officers' => [
                'total' => $officers['total'],
                'heads' => $officers['heads'],
                'deputies' => $officers['deputies']
            ]
        ], 200);
    });

    // Delete report
    Route::post('/delete/report', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->delete($request);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });

    // Approve citizen
    Route::post('/approve/citizen', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->approve($request);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });

    // Reject citizen application
    Route::post('/reject/citizen', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->reject($request);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });

    // Restrict citizen
    Route::post('/restrict/citizen', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->restrict($request);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });

    // Unrestrict citizen
    Route::post('/unrestrict/citizen', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->unrestrict($request);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });

    // Issue officer account
    Route::post('/issue/officer', function(Request $request) {
        $response = app('App\Http\Controllers\OfficerController')->create($request);

        return response()->json([
            'message' => $response['message'],
            'account' => $response['account']
        ], $response['code']);
    });

    // Delete citizen [DANGER]
    Route::post('/delete/citizen', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->delete($request);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });

    // Delete officer
    Route::post('/delete/officer', function(Request $request) {
        $response = app('App\Http\Controllers\OfficerController')->delete($request);

        return response()->json([
            'message' => $response['message']
        ], $response['code']);
    });
});

// Route::middleware(['guest'])->post('/login', [TokenAuthenticationController::class, 'store']);
// Route::middleware(['auth:sanctum'])->post('/logout', [TokenAuthenticationController::class, 'destroy']);

Route::get('/quote', QuoteController::class);
