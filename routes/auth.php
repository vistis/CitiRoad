<?php

// use App\Http\Controllers\Auth\AuthenticatedSessionController;
// use App\Http\Controllers\Auth\ConfirmablePasswordController;
// use App\Http\Controllers\Auth\EmailVerificationNotificationController;
// use App\Http\Controllers\Auth\EmailVerificationPromptController;
// use App\Http\Controllers\Auth\NewPasswordController;
// use App\Http\Controllers\Auth\PasswordController;
// use App\Http\Controllers\Auth\PasswordResetLinkController;
// use App\Http\Controllers\Auth\RegisteredUserController;
// use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

// Base URL
// http://localhost:8000

//** PUBLIC ROUTES */
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
Route::middleware('auth:sanctum')->group(function () {
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

//** END OF CUSTOM AUTH */

// Route::middleware('guest')->group(function () {
//     Route::get('register', [RegisteredUserController::class, 'create'])
//         ->name('register');

//     Route::post('register', [RegisteredUserController::class, 'store']);

//     Route::get('login', [AuthenticatedSessionController::class, 'create'])
//         ->name('login');

//     Route::post('login', [AuthenticatedSessionController::class, 'store']);

//     Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
//         ->name('password.request');

//     Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
//         ->name('password.email');

//     Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//         ->name('password.reset');

//     Route::post('reset-password', [NewPasswordController::class, 'store'])
//         ->name('password.store');
// });

// Route::middleware('auth')->group(function () {
//     Route::get('verify-email', EmailVerificationPromptController::class)
//         ->name('verification.notice');

//     Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//         ->middleware(['signed', 'throttle:6,1'])
//         ->name('verification.verify');

//     Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//         ->middleware('throttle:6,1')
//         ->name('verification.send');

//     Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
//         ->name('password.confirm');

//     Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

//     Route::put('password', [PasswordController::class, 'update'])->name('password.update');

//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
//         ->name('logout');
// });
