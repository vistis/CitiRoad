<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\OfficerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Base URL
// http://localhost:8000

// Dummy routes (redirect to admin dashboard)
Route::get('/', function (Request $request) {
    return redirect('admin/dashboard');
});
Route::get('/admin', function (Request $request) {
    return redirect('admin/dashboard');
})->name('admin');

// ---
// AUTHENTICATED ADMIN ROUTES
// ---
Route::middleware('auth:admin')->group(function () {

    // Dashboard Route
    Route::get('/admin/dashboard', function(Request $request) {
        $reports = app(ReportController::class)->stats($request);
        $citizens = app(CitizenController::class)->stats($request);
        $officers = app(OfficerController::class)->stats($request);

        return view('admin.dashboard', [
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
        ]);
    })->name('admin.dashboard');

    // Citizens List Route
    Route::get('/admin/citizens', function(Request $request) {
        $response = app(CitizenController::class)->readAll($request);
        $pendingCount = $response['pending-count'];
        $pending = $response['pending'];
        $otherCount = $response['other-count'];
        $other = $response['other'];
        return view('admin.citizens', ['pendingCount' => $pendingCount, 'pending' => $pending, 'otherCount' => $otherCount, 'other' => $other, 'search' => $request->input('search', ''),
        'filter' => $request->input('filter', ''),
        'sort' => $request->input('sort', 'created_at'),
        'order' => $request->input('order', 'desc'),]);
    })->name('admin.citizens');

    // Citizen Profile (Show) Route - Expects ID as query parameter, controller returns array
    Route::get('/admin/citizen', function(Request $request) {
        $citizen = app('App\Http\Controllers\CitizenController')->readOne($request);
            $reports = app('App\Http\Controllers\ReportController')->readAll($request);
            $response = [
                'account' => $citizen['account'],
                'report-count' => $reports['count'],
                'reports' => $reports['reports'],
            ];

        return view('admin.citizenProfile', ['data' => $response]);
    })->name('admin.citizens.show');

    // Officers List Route
    Route::get('/admin/officers', function(Request $request) {
        $response = app(OfficerController::class)->readAll($request);

        return view('admin.officers', [
            'officers' => $response['officers'],
            'count' => $response['count'],
            'search' => $request->input('search', ''),
            'sort' => $request->input('sort', 'created_at'),
            'filter' => $request->input('filter', ''),
            'order' => $request->input('order', 'desc')
        ]);
    })->name('admin.officers');


    // Officer Profile (Show) Route
    Route::get('/admin/officer', function(Request $request) {
        $officerController = app(OfficerController::class);
        $responseArray = $officerController->readOne($request);

        if (isset($responseArray['code']) && $responseArray['code'] !== 200) {
            return back()->with('error', $responseArray['message'] ?? 'An error occurred.');
        }

        $officer = $responseArray['account'];
        return view('admin.officerProfile', compact('officer'));
    })->name('admin.officer.show');

    // Admins List Route
    Route::get('/admin/admins', function(Request $request) {
        $response = app(AdminController::class)->readAll($request);
        $count = $response['count'];
        $admins = $response['admins'];
        return view('admin.admins', ['count' => $count, 'admins' => $admins, 'search' => $request->input('search', ''),
        'sort' => $request->input('sort', 'created_at'),
        'order' => $request->input('order', 'desc')]);
    })->name('admin.admins');

    // Admin Profile (Show) Route
    Route::get('/admin/admin', function(Request $request) {
        $adminController = app(AdminController::class);
        $responseArray = $adminController->readOne($request);

        if (isset($responseArray['code']) && $responseArray['code'] !== 200) {
            return back()->with('error', $responseArray['message'] ?? 'An error occurred.');
        }

        $admin = $responseArray['account'];
        return view('admin.adminProfile', compact('admin'));
    })->name('admin.admin.show');

    // Admin Account Route (Current Admin's Own Profile)
    Route::get('/admin/account', function() {
        $admin = Auth::guard('admin')->user();
        return view('admin.account', compact('admin'));
    })->name('admin.account');

    // ---
    // NEW ROUTES FOR CURRENT ADMIN'S ACCOUNT EDITING
    // ---

    // Route to display the admin account edit form
    Route::get('/admin/account/edit', function () {
        $admin = Auth::guard('admin')->user(); // Get the currently authenticated admin
        return view('admin.accountEdit', compact('admin'));
    })->name('admin.account.edit');

    // Route to handle the update of admin account information
    // Route::put('/admin/account/update', [AdminController::class, 'update'])->name('admin.account.update');
    //
    Route::post('/admin/account/update', function(Request $request) {
        $adminController = app(AdminController::class);
        $response = $adminController->update($request);

        if (isset($response['code']) && $response['code'] !== 200) {
            return back()->withInput()->withErrors($response['message'] ?? 'Failed to update officer.');
        }
        return redirect()->route('admin.account', ['id' => $request->id])->with('success', 'Officer updated successfully.');
    })->name('admin.account.update');


    // Officer Edit/Update Routes
    Route::get('/admin/officer/edit', function(Request $request) {
        $officerController = app(OfficerController::class);
        $responseArray = $officerController->readOne($request);

        if (isset($responseArray['code']) && $responseArray['code'] !== 200) {
            return back()->with('error', $responseArray['message'] ?? 'Could not load officer for editing.');
        }

        $officer = $responseArray['account'];
        $provinces = DB::table('provinces')->select('name')->get();

        return view('admin.officerEdit', compact('officer', 'provinces'));
    })->name('admin.officer.edit');

    // This route is named `admin.officer.update` and uses `POST`.
    // It calls `OfficerController::update`.
    // The previous instruction had a comment about it updating the officer but redirecting to admin.account.
    // I am leaving its original behavior as per your instruction "dont fix old things".
    Route::post('/admin/officer/update', function(Request $request) {
        $officerController = app(OfficerController::class);
        $response = $officerController->update($request);

        if (isset($response['code']) && $response['code'] !== 200) {
            return back()->withInput()->withErrors($response['message'] ?? 'Failed to update officer.');
        }
        return redirect()->route('admin.officer.show', ['id' => $request->id])->with('success', 'Officer updated successfully.');
    })->name('admin.officer.update');

    // Officer Delete Route
    Route::delete('/admin/officer/delete', function(Request $request) {
        app(OfficerController::class)->delete($request);
        return redirect()->route('admin.officers')->with('success', 'Officer deleted successfully.');
    })->name('admin.officer.delete');

    // Officer Create Form Route
    Route::get('/admin/officer/create', function() {
        $provinces = DB::table('provinces')->pluck('name');
        return view('admin.createOfficer', compact('provinces'));
    })->name('admin.officer.create');

    // Officer Create (POST) Route
    Route::post('/admin/officer/create', function(Request $request) {
        app(OfficerController::class)->create($request);
        return redirect()->intended(route('admin.officers'))->with('success', 'Officer created successfully.');
    });

    // Report Detail/Show Route (for Admin)
    Route::get('/admin/report', function(Request $request) {
        $reportController = app(ReportController::class);
        $responseArray = $reportController->readOne($request);

        if (isset($responseArray['code']) && $responseArray['code'] !== 200) {
            return back()->with('error', $responseArray['message'] ?? 'An error occurred while fetching the report.');
        }

        $report = $responseArray['report'];
        if ($report instanceof \Illuminate\Support\Collection) {
            $report = $report->first();
        }

        $images = collect($responseArray['images'] ?? []);
        $is_bookmarked = $responseArray['is_bookmarked'] ?? false;

        return view('admin.reportProfile', compact('report', 'images', 'is_bookmarked'));
    })->name('admin.report.show');

    Route::get('/admin/reports', function(Request $request) {
            // Override request (only show the user's reports)
            $request->report_id = $request->user()->id;

            $response = app('App\Http\Controllers\ReportController')->readAll($request);

            return view('admin.reports', [
                'count' => $response['count'],
                'reports' => $response['reports'],
                'search' => $request->input('search', ''),
                'sort' => $request->input('sort', 'created_at'),
                'filter' => $request->input('filter', ''),
                'order' => $request->input('order', 'desc'),
            ]);
        })->name('admin.reports.all');

    Route::delete('/admin/reports/delete', function(Request $request) {
        app(ReportController::class)->delete($request);
        return redirect()->route('admin.reports.all');
    })->name('admin.reports.delete');

    Route::patch('/admin/citizens/approve', function(Request $request) {
        app(CitizenController::class)->approve($request);
        return back();
    })->name('admin.citizens.approve');

    Route::patch('/admin/citizens/reject', function(Request $request) {
        app(CitizenController::class)->reject($request);
        return back();
    })->name('admin.citizens.reject');

    Route::patch('/admin/citizens/restrict', function(Request $request) {
        app(CitizenController::class)->restrict($request);
        return back();
    })->name('admin.citizens.restrict');

    Route::patch('/admin/citizens/unrestrict', function(Request $request) {
        app(CitizenController::class)->unrestrict($request);
        return back();
    })->name('admin.citizens.unrestrict');

    Route::delete('/admin/citizens/delete', function(Request $request) {
        app(CitizenController::class)->delete($request);
        return redirect()->route('admin.citizens');
    })->name('admin.citizens.delete');
});

// ---
// COMMON REPORT ACTION ROUTES (ACCESSIBLE BY ADMIN - if authorized by controller)
// ---
Route::middleware(['auth:admin'])->group(function () {
    Route::post('/reports/bookmark', function (Request $request) {
        app(ReportController::class)->bookmark($request);
        return back();
    })->name('report.bookmark');
});


// Default Laravel authentication routes (for login/logout)
require __DIR__.'/auth.php';
