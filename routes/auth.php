<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

// Base URL
// http://localhost:8000

//** DUMMY ROUTES */
Route::post('/logout', function() {})->name('logout');

//** GUEST ROUTES */
Route::middleware('guest')->group(function () {
    // Dummy routes
    Route::get('/register', function() {})->name('register');
    Route::get('/login', function() {})->name('login');

    // Citizen routes
    Route::get('/citizen/register', [RegistrationController::class, 'createCitizen'])
        ->name('citizen.register');

    Route::post('/citizen/register', [RegistrationController::class, 'storeCitizen']);

    Route::get('/citizen/login', [SessionController::class, 'createCitizen'])
        ->name('citizen.login');

    Route::post('/citizen/login', [SessionController::class, 'storeCitizen']);

    // Officer routes
    Route::get('/officer/login', [SessionController::class, 'createOfficer'])
        ->name('officer.login');

    Route::post('/officer/login', [SessionController::class, 'storeOfficer']);

    // Admin routes
    Route::get('/admin/login', [SessionController::class, 'createAdmin'])
        ->name('admin.login');

    Route::post('/admin/login', [SessionController::class, 'storeAdmin']);
});

// Citizen routes
Route::middleware('auth:citizen')->post('/citizen/logout', [SessionController::class, 'destroyCitizen'])
    ->name('citizen.logout');

// Officer routes
Route::middleware('auth:officer')->post('/officer/logout', [SessionController::class, 'destroyOfficer'])
    ->name('officer.logout');

// Admin routes
Route::middleware('auth:admin')->post('/admin/logout', [SessionController::class, 'destroyAdmin'])
    ->name('admin.logout');
