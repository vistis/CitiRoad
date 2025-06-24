<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    //** SHOW LOGIN PAGE */
    // Citizen
    public function createCitizen() {
        return view('citizen.login');
    }

    // Officer
    public function createOfficer() {
        return view('officer.login');
    }

    // Admin
    public function createAdmin() {
        return view('admin.login');
    }

    //** HANDLE LOGIN REQUEST */
    // Citizen
    public function storeCitizen(Request $request) {
        // Request rules
        $request->validate([
            'email' => ['string', 'email', 'not_in:deleted@account.shell'],
            'phone_number' => ['string', 'not_in:0000000000'],
            'password' => ['required', 'string'],
        ]);

        // Authenticate with email
        if (Auth::guard('citizen')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('citizen.dashboard'));
        }

        // Authenticate with phone number
        else if (Auth::guard('citizen')->attempt($request->only('phone_number', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('citizen.dashboard'));
        }

        // Authentication failed
        else {
            throw ValidationException::withMessages([
                'password' => trans('auth.failed'),
            ]);
        }
    }

    // Officer
    public function storeOfficer(Request $request) {
        // Request rules
        $request->validate([
            'id' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        // Authenticate
        if (Auth::guard('officer')->attempt($request->only('id', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('officer.dashboard'));
        }

        // Authentication failed
        else {
            throw ValidationException::withMessages([
                'password' => trans('auth.failed'),
            ]);
        }
    }

    // Admin
    public function storeAdmin(Request $request) {
        // Request rules
        $request->validate([
            'id' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        // Authenticate
        if (Auth::guard('admin')->attempt($request->only('id', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // Authentication failed
        else {
            throw ValidationException::withMessages([
                'password' => trans('auth.failed'),
            ]);
        }
    }

    //** HANDLE LOGOUT REQUEST */
    // Universal
    public function destroy(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
    // Citizen
    public function destroyCitizen(Request $request) {
        Auth::guard('citizen')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Officer
    public function destroyOfficer(Request $request) {
        Auth::guard('officer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/officer/login');
    }

    // Admin
    public function destroyAdmin(Request $request) {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
