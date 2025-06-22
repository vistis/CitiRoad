<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class OfficerController extends Controller
{
    // Create a new officer type user
    public function create(Request $request) {
        $request->validate([
            'id' => ['required', 'string', 'unique:officers,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:officers,email'],
            'phone_number' => ['required', 'string', 'max:16', 'unique:officers,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:1,2'],
            'province_id' => ['required', 'numeric', 'exists:provinces,id'],
            'profile_picture_path' => ['required', 'string'],
        ]);
        $officer = Officer::create([
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'province_id' => $request->province_id,
            'profile_picture_path' => $request->profile_picture_path,
        ]);

        return $officer;
    }
}
