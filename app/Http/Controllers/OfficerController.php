<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class OfficerController extends Controller
{
    // Statistics
    public function stats() {
        $total = Officer::count();
        $heads = Officer::where('role', 'Municipality Head')->count();
        $deputies = Officer::where('role', 'Municipality Deputy')->count();

        return [
            'total' => $total,
            'heads' => $heads,
            'deputies' => $deputies,
            'code' => "200"
        ];
    }

    // Create a new officer type user
    public function create(Request $request) {
        $request->validate([
            'id' => ['required', 'string', 'unique:officers,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:officers,email'],
            'phone_number' => ['required', 'string', 'max:16', 'unique:officers,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:Municipality Head,Municipality Deputy'],
            'province_name' => ['required', 'string', 'exists:provinces,name'],
            'profile_picture_path' => ['required', 'string'],
        ]);

        $provinceID = DB::table('provinces')->where('name', $request->province_name)->value('id');

        $officer = Officer::create([
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'province_id' => $provinceID,
            'profile_picture_path' => $request->profile_picture_path,
        ]);

        return [
            'message' => "Officer created",
            'account' => $officer,
            'code' => "201"
        ];
    }

    // Delete an officer
    public function delete(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:officers,id'],
        ]);

        // Try to find the requested officer
        $officer = Officer::where('id', $request->id)->first();

        if (!$officer) {
            // Officer does not exist
            return [
                'message' => "Officer not found",
                'code' => "404"
            ];
        }

        // Officer found, proceed with deletion
        $officer->delete();

        return [
            'message' => "Officer deleted",
            'code' => "200"
        ];
    }

}
