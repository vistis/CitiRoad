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

    // Get information on a specific admin
    public function readOne(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:officers,id'],
        ]);

        $admin = Admin::find($request->id);

        if (!$admin) {
            // Admin does not exist
            return [
                'message' => "Admin not found",
                'code' => 404
            ];
        }

        // Admin found, proceed with retrieval
        return [
            'message' => "Admin retrieved",
            'account' => $officer,
            'code' => 200
        ];
    }

    // Admin list
    public function readAll() {
        $admins = Admin::all();

        return [
            'admins' => $admins,
            'code' => 200,
        ];
    }


    // Update own profile
    public function update(Request $request) {
        // Request rules
        $request->validate([
            'id' => ['string', 'exists:admins,id'],
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'email' => ['email', 'unique:citizens,email'],
            'phone_number' => ['string', 'max:16', 'unique:citizens,phone_number'],
            'password' => ['string', 'confirmed', Password::defaults()],
            'profile_picture_path' => ['string'],
        ]);

        // Resolve province ID
        $provinceID = DB::table('provinces')->where('name', $request->province)->id;

        // Update the information
        $admin = Admin::where('id', $request->user()->id)->update([
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'profile_picture_path' => $request->profile_picture_path,
        ]);

        return [
            'message' => "Account information updated",
            'account' => $admin,
            'code' => 200,
        ];
    }
}
