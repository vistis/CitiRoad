<?php

namespace App\Http\Controllers\Auth;

use App\Models\Officer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\CitizenRegisterRequest;
use App\Http\Requests\Auth\OfficerRegisterRequest;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    /** REGISTRATION */
    // Citizens
    public function registerCitizen(CitizenRegisterRequest $request) {
        // Call create function
        $citizen = app('App\Http\Controllers\Api\CitizenController')->create($request);

        // Log citizen in
        Auth::guard('citizen')->login($citizen);
        $request->session()->regenerate();

        return response()->response();
    }

    // Officers
    public function registerOfficer(OfficerRegisterRequest $request) {
        // Call create function
        $officer = app('App\Http\Controllers\Api\OfficerController')->create($request);

        // Log officer in
        Auth::guard('officer')->login($officer);
        $request->session()->regenerate();

        return response()->response();
    }

    // Admins
    public function registerAdmin(AdminRegisterRequest $request) {
        // Call create function
        $admin = app('App\Http\Controllers\Api\AdminController')->create($request);

        // Log admin in
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return response()->response();
    }

    //** LOG IN */
    // Universal
    public function login(Request $request) {
        // Validate password
        $request->validate([
            'password' => ['required', 'string'],
            'remember' => ['boolean']
        ]);

        // User types to try
        $guardList = ['citizen', 'officer', 'admin'];
        $authenticated = null;

        foreach ($guardList as $guard) {
            // Citizen conditions
            if ($guard == 'citizen') {
                // Check with email
                $request->validate([
                    'email' => ['string', 'email']
                ]);
                if (Auth::guard($guard)->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                    $request->session()->regenerate();
                    $authenticated = true;
                    break;
                }
                // Check with phone number
                $request->validate([
                    'phone_number' => ['string']
                ]);
                if (Auth::guard($guard)->attempt($request->only('phone_number', 'password'), $request->boolean('remember'))) {
                    $request->session()->regenerate();
                    $authenticated = true;
                    break;
                }
            }

            // Officer/Admin condition
            $request->validate([
                'id' => ['string']
            ]);
            if (Auth::guard($guard)->attempt($request->only('id', 'password'), $request->boolean('remember'))) {
                $request->session()->regenerate();
                $authenticated = true;
                break;
            }
        }

        // Cannot log in
        if (!$authenticated){
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Successfully logged in
        return response()->noContent();
    }

    //** LOG OUT */
    // Universal
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
