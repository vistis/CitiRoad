<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    // Create a new admin type user
    public function create(Request $request) {
        $request->validate([
            'id' => ['required', 'string', 'unique:admins,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'phone_number' => ['required', 'string', 'max:16', 'unique:admins,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'profile_picture_path' => ['required', 'string'],
        ]);

        $admin = Admin::create([
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'profile_picture_path' => $request->profile_picture_path,
        ]);

        return [
            'message' => "Admin created",
            'account' => $admin,
            'code' => "201"
        ];
    }
}
