<?php

namespace App\Http\Controllers\Auth;

use App\Models\Officer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /** SHOW REGISTRATION PAGE */
    // Citizen
    public function createCitizen() {
        return view('citizen.register');
    }

    // Officer
    public function createOfficer() {
        return view('officer.register');
    }

    // Admin
    public function createAdmin() {
        return view('admin.register');
    }

    /** HANDLE REGISTRATION REQUEST */
    //Citizen
    public function storeCitizen(Request $request) {
        // Call create function
        $response = app('App\Http\Controllers\CitizenController')->create($request);

        // Log citizen in
        Auth::guard('citizen')->login($response['account']);
        $request->session()->regenerate();

        return redirect(route('citizen.dashboard', absolute: false));
    }

    // Officers
    public function storeOfficer(Request $request) {
        // Call create function
        $response = app('App\Http\Controllers\OfficerController')->create($request);

        // Log officer in
        Auth::guard('officer')->login($response['account']);
        $request->session()->regenerate();

        return redirect(route('officer.dashboard', absolute: false));
    }

    // Admins
    public function storeAdmin(Request $request) {
        // Call create function
        $response = app('App\Http\Controllers\AdminController')->create($request);

        // Log admin in
        Auth::guard('admin')->login($response['account']);
        $request->session()->regenerate();

        return redirect(route('admin.dashboard', absolute: false));
    }
}
