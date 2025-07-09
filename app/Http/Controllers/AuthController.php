<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Citizen;
use App\Models\Officer;

class AuthController extends Controller
{


    // Citizen

    public function showLoginFormCitizen()
    {
        if (Auth::guard('citizen')->check()) {
        return redirect()->route('citizens.profile', Auth::guard('citizen')->id())
                         ->with('message', 'You are already logged in.');
    }
    return view('auth.login');
    }

    public function loginCitizen(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $field = $request->email ? 'email' : 'phone_number';
        $value = $request->email ?? $request->phone;

        $citizen = Citizen::where($field, $value)->first();

        if ($citizen && Hash::check($request->password, $citizen->password)) {
            Auth::guard('citizen')->login($citizen);
            return redirect()->route('citizens.dashboard', $citizen->id);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logoutCitizen(Request $request)
    {
       Auth::guard('citizen')->logout();
        return redirect('/');
    }


    // Offcier

    public function showLoginFormOfficer(){
        return view('officers.login');
    }

    public function loginOfficer(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $officer = Officer::where('email', $request->email)->first();

    if ($officer && Hash::check($request->password, $officer->password)) {
        Auth::guard('officer')->login($officer);

        return redirect()->route('officers.dashboard')->with('message', 'Welcome back, ' . $officer->first_name . '!');
    }

        return back()->with('error', 'Invalid credentials.');
    }

    public function logoutOfficer(Request $request)
{
    Auth::guard('officer')->logout();
    return redirect('/officer/login');
}

    
}
