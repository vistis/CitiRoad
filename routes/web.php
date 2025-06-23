<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/citizen/dashboard', function() {
//     return view('citizen.dashboard', ['user' => Auth::user()]);
// })->middleware(['auth:citizen'])->name('citizen.dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

//** AUTHENTICATED ROTUES */
// Route::middleware('auth')->group(function() {

// });

//** CITIZEN ROUTES */
Route::middleware('auth:citizen')->group(function () {
    // Dashboard
    Route::get('/citizen/dashboard', function(Request $request) {
        $request->id = $request->user()->id;
        $reportsData = app('App\Http\Controllers\ReportController')->readList($request);
        return view('citizen.dashboard', ['reportsData' => $reportsData,
            'searchQuery' => $request->input('search', ''),
            'sort' => $request->input('sort', 'created-desc'),
            'filter' => $request->input('filter', '')
        ]);
    })->name('citizen.dashboard');

    // View report


    // Create report
    Route::get('/citizen/report/make', function() {
        return view('citizen.report.make');
    })->name('citizen.report.make');
    Route::post('/citizen/report/make', [ReportController::class, 'create']);
});

//** OFFICER ROTUES */
Route::middleware('auth:officer')->group(function () {
    // Dashboard
    Route::get('/officer/dashboard', function() {
        return view('officer.dashboard');
    })->name('officer.dashboard');

    // Update report status
    Route::get('/officer/report/update', function($id) {
        return view('officer.report.update');
    })->name('officer.report.update');
    Route::post('/officer/report/update', [ReportController::class, 'updateStatus']);
});

//** ADMIN ROTUES */
Route::middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', function() {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Delete report
    Route::post('/admin/report/delete', [ReportController::class, 'delete']);
});

require __DIR__.'/auth.php';
