<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\ContactController;

// Sending Email
Route::post('/contact/send', [ContactController::class, 'sendEmail'])->name('contact.send');

// Static Pages
Route::get('/', function () {return view('landing');})->name('landing');
Route::get('/help', function () {return view('help');})->name('help');
Route::get('/aboutus', function () {return view('aboutus');})->name('aboutus');


// Citizen Sign Up
Route::get('/register', [CitizenController::class, 'create'])->name('register');
Route::post('/register', [CitizenController::class, 'store'])->name('citizens.store');


// Citizen Login & Logout
Route::get('login', [AuthController::class, 'showLoginFormCitizen'])->name('login');
Route::post('login', [AuthController::class, 'loginCitizen'])->name('loginC');


// Citizen After Logged In
Route::middleware(['auth:citizen'])->group(function () {
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('citizens.dashboard');
    Route::get('/profile', [CitizenController::class, 'showProfile'])->name('citizens.profile');
    Route::get('/report', [ReportController::class, 'showReportForm'])->name('citizens.reportForm');
    Route::post('/report', [ReportController::class, 'storeReport'])->name('citizens.storeReport');
    Route::get('/reports/{id}', [ReportController::class, 'showReport'])->name('citizens.report');
    Route::delete('/citizens/{citizen}', [CitizenController::class, 'destroy'])->name('citizens.destroy');
    Route::get('/citizens/{citizen}/edit', [CitizenController::class, 'edit'])->name('citizens.edit');
    Route::put('/citizens/{citizen}', [CitizenController::class, 'update'])->name('citizens.update');
    Route::post('logout', [AuthController::class, 'logoutCitizen'])->name('logoutC');
});


// Officer Login & Logout
Route::get('/officer/login', [AuthController::class, 'showLoginFormOfficer'])->name('officer.login');
Route::post('/officer/login', [AuthController::class, 'loginOfficer'])->name('officer.login.submit');


// Officer After Logged In
Route::middleware(['auth:officer'])->group(function () {
   Route::get('/officer/dashboard', [OfficerController::class, 'dashboard'])->name('officers.dashboard');
   Route::get('/officer/allReports', [OfficerController::class, 'allReports'])->name('officers.allReports');
   Route::get('/officer/Report/{id}', [ReportController::class, 'showReportForOfficer'])->name('officers.report');
   Route::patch('/officer/reports/{id}/status', [ReportController::class, 'updateStatus'])->name('officers.updateStatus');
   Route::patch('/officers/report/{id}/resolved-update', [ReportController::class, 'postResolvedUpdate'])->name('officers.postResolvedUpdate');
   Route::get('/officers/report/{id}/update-resolved', [ReportController::class, 'showUpdateResolvedForm'])->name('officers.updateResolvedReport');
   Route::get('/officer/Citizen/{id}}', [OfficerController::class, 'showCitizenProfile'])->name('officers.showCitizenProfile');
   Route::get('/officer/profile', [OfficerController::class, 'showOfficerProfile'])->name('officers.profile');
   Route::get('/officer/all', [OfficerController::class, 'showAllOfficer'])->name('officers.allOfficers');
   Route::get('/officer/{id}', [OfficerController::class, 'showOtherOfficer'])->name('officers.showOtherOfficers');
   Route::post('/officer/logout', [AuthController::class, 'logoutOfficer'])->name('officer.logout');
});
