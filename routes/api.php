<?php

use App\Http\Controllers\Api\QuoteController;
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

    // Update own account
    Route::post('/account/update', function(Request $request) {
        // Get user
        $user = $request->user();

        if ($user->status) { // For citizen
            $response = app('App\Http\Controllers\CitizenController')->update($request);
        }
        else if (!$user->role) { // For admin
            $response = app('App\Http\Controllers\AdminController')->update($request);
        }
        else { // For officer
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($response, $response['code']);
    });

    // Report list
    Route::get('/reports', function(Request $request) {

        $response = app('App\Http\Controllers\ReportController')->readAll($request);

        return response()->json($response, $response['code']);
    });

    // View specific report
    Route::get('/report', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->readOne($request);

        return response()->json($response, $response['code']);
    });

    // Add report to bookmark
    Route::post('/report/bookmark', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->bookmark($request);

        return response()->json($response, $response['code']);
    });

    // View a citizen's information
    Route::get('/citizen', function(Request $request) {
        $citizen = app('App\Http\Controllers\CitizenController')->readOne($request);

        $reports = app('App\Http\Controllers\ReportController')->readAll($request);

        $response = [
            'account' => $citizen['account'],
            'report-count' => $reports['count'],
            'reports' => $reports['reports']
        ];

        return response()->json($response, 200);
    });

    // View an officer's information
    Route::get('/officer', function(Request $request) {
        $response = app('App\Http\Controllers\OfficerController')->readOne($request);

        return response()->json($response, $response['code']);
    });

    // Officer list
    Route::get('/officers', function(Request $request) {
        $response = app('App\Http\Controllers\OfficerController')->readAll($request);

        return response()->json($response, $response['code']);
    });

});

//** CITIZEN ROTUES */
Route::middleware('auth:citizen-api')->group(function() {
    // Create report
    Route::post('/report/make', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->create($request);

        return response()->json($response, $response['code']);
    });

    // Delete own account
    Route::post('/account/delete', function(Request $request) {
        // Override request
        $request['id'] = $request->user()->id;

        $response = app('App\Http\Controllers\CitizenController')->delete($request);

        return response()->json($response, $response['code']);
    });

    // Reset password
    Route::post('/account/reset', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->resetPassword($request);

        return response()->json($response, $response['code']);
    });
});

//** OFFICER ROTUES */
Route::middleware('auth:officer-api')->group(function() {
    // Report statistics
    Route::get('/reports/stats', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->stats($request);

        return response()->json($response, $response['code']);
    });

    // Proceed report
    Route::post('/report/proceed', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->proceed($request);

        return response()->json($response, $response['code']);
    });

    // Reject report
    Route::post('/report/reject', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->reject($request);

        return response()->json($response, $response['code']);
    });

    // Mark report as resolved (Municipality Heads only)
    Route::post('/report/resolve', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->resolve($request);

        return response()->json($response, $response['code']);
    });

    // Reopen report (Municipality Heads only)
    Route::post('/report/reopen', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->reopen($request);

        return response()->json($response, $response['code']);
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
                'active' => $reports['active'],
                'resolved' => $reports['resolved']
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
    Route::post('/report/delete', function(Request $request) {
        $response = app('App\Http\Controllers\ReportController')->delete($request);

        return response()->json($response, $response['code']);
    });

    // Citizen list
    Route::get('/citizens', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->readAll($request);

        return response()->json($response, $response['code']);
    });

    // Approve citizen
    Route::post('/citizen/approve', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->approve($request);

        return response()->json($response, $response['code']);
    });

    // Reject citizen application
    Route::post('/citizen/reject', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->reject($request);

        return response()->json($response, $response['code']);
    });

    // Restrict citizen
    Route::post('/citizen/restrict', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->restrict($request);

        return response()->json($response, $response['code']);
    });

    // Unrestrict citizen
    Route::post('/citizen/unrestrict', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->unrestrict($request);

        return response()->json($response, $response['code']);
    });

    // Issue officer account
    Route::post('/officer/issue', function(Request $request) {
        $response = app('App\Http\Controllers\OfficerController')->create($request);

        return response()->json($response, $response['code']);
    });

    // Update officer account
    Route::post('/officer/update', function(Request $request) {
        $response = app('App\Http\Controllers\OfficerController')->update($request);

        return response()->json($response, $response['code']);
    });

    // Delete citizen [DANGER]
    Route::post('/citizen/delete', function(Request $request) {
        $response = app('App\Http\Controllers\CitizenController')->delete($request);

        return response()->json($response, $response['code']);
    });

    // Delete officer
    Route::post('/officer/delete', function(Request $request) {
        $response = app('App\Http\Controllers\OfficerController')->delete($request);

        return response()->json($response, $response['code']);
    });

    // View specific admin
    Route::get('/admin', function(Request $request) {
        $response = app('App\Http\Controllers\AdminController')->readOne($request);

        return response()->json($response, $response['code']);
    });

    // Admin list
    Route::get('/admins', function(Request $request) {
        $response = app('App\Http\Controllers\AdminController')->readAll($request);

        return response()->json($response, $response['code']);
    });

});
