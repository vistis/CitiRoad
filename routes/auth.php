<?php

use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

// Base URL
// http://localhost:8000

//** GUEST ROUTES */
Route::middleware('guest')->group(function () {
    // Dummy routes
    Route::get('/register', function() {})->name('register');

    Route::get('/login', function() {})->name('login');

    Route::get('/logout', function() {})->name('logout');

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

//** AUTHENTICATED ROUTES */
Route::middleware('auth:sanctum')->group(function() {
    // Citizen routes
    Route::post('/citizen/logout', [SessionController::class, 'destroyCitizen'])
        ->name('citizen.logout');

    // Officer routes
    Route::post('/officer/logout', [SessionController::class, 'destroyOfficer'])
        ->name('officer.logout');

    // Citizen routes
    Route::post('/admin/logout', [SessionController::class, 'destroyAdmin'])
        ->name('admin.logout');
});
