<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\AuthRedirect;

// Base URL
// http://localhost:8000

Route::get('/', function () {
    return view('welcome');
});

//** DUMMY ROUTES */
Route::get('/dashboard', function () {})->name('dashboard');

Route::get('/citizen', function() {
    return redirect()->route('citizen.dashboard');
})->name('citizen');

Route::get('/officer', function() {
    return redirect()->route('officer.dashboard');
})->name('officer');

Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
})->name('admin');

//** AUTHENTICATED ROTUES */
// Route::middleware('auth')->group(function() {

// });

//** CITIZEN ROUTES */
Route::middleware('auth:citizen')->group(function () {
    // Dashboard
    Route::get('/citizen/dashboard', function(Request $request) {
        // Override request (only show the user's reports)
        $request->citizen_id = $request->user()->id;

        $reports = app('App\Http\Controllers\ReportController')->readList($request);

        return view('citizen.dashboard', [
            'reports' => $reports,
            'search' => $request->input('search', ''),
            'sort' => $request->input('sort', 'created_at-desc'),
            'filter' => $request->input('filter', '')
        ]);
    })->name('citizen.dashboard');

    // View report
    Route::get('/citizen/report/{id}', function($id) {
        $report = app('App\Http\Controllers\ReportController')->readOne($id);
        return view('citizen.report.view', [
            'report' => $report
        ]);
    })->name('citizen.report.view');

    // Create report
    Route::get('/citizen/report/make', function() {
        return view('citizen.report.make');
    })->name('citizen.report.make');
    Route::post('/citizen/report/make', [ReportController::class, 'create']);
});

//** OFFICER ROTUES */
Route::middleware('auth:officer')->group(function () {
    // Dashboard
    Route::get('/officer/dashboard', function(Request $request) {
        // Get the province of the officer
        $provinceName = DB::table('provinces')->where('id', $request->user()->province_id)->value('name');

        // Apply key for filter
        $request->province_name = $provinceName;

        // Get report stats
        $stats = app('App\Http\Controllers\ReportController')->stats($request);

        return view('officer.dashboard', [
            'stats' => $stats
        ]);
    })->name('officer.dashboard');

    // View report
    Route::get('/officer/report/{id}', function($id) {
        $report = app('App\Http\Controllers\ReportController')->readOne($id);
        return view('officer.report.view', [
            'report' => $report
        ]);
    })->name('officer.report.view');

    // Update report status
    Route::get('/officer/report/update', function($id) {
        return view('officer.report.update');
    })->name('officer.report.update');
    Route::post('/officer/report/update', [ReportController::class, 'updateStatus']);
});

//** ADMIN ROTUES */
Route::middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', function(Request $request) {
        $reportStats = app('App\Http\Controllers\ReportController')->stats($request);
        // $accountStats = app('App\Http\Controllers\AdminController')->stats($request);

        return view('officer.dashboard', [
            'report-stats' => $reportStats,
            // 'account-stats' => $accountStats
        ]);
    })->name('admin.dashboard');

    // View report
    Route::get('/admin/report/{id}', function($id) {
        $report = app('App\Http\Controllers\ReportController')->readOne($id);
        return view('admin.report.view', [
            'report' => $report
        ]);
    })->name('admin.report.view');

    // Delete report
    Route::post('/admin/report/delete', [ReportController::class, 'delete']);
});

require __DIR__.'/auth.php';

// Route::get('/citizen/dashboard', function() {
//     return view('citizen.dashboard', ['user' => Auth::user()]);
// })->middleware(['auth:citizen'])->name('citizen.dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
