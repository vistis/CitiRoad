<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class CitizenController extends Controller
{
    // Create a new citizen type user
    public function create(Request $request) {
        $request->validate([
            'id' => ['required', 'string', 'unique:citizens,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:citizens,email'],
            'phone_number' => ['required', 'string', 'max:16', 'unique:citizens,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'province_id' => ['required', 'numeric', 'exists:provinces,id'],
            'address' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'profile_picture_path' => ['required', 'string'],
            'gender' => ['required', 'string', 'in:Male,Female,Prefer Not to Say'],
        ]);

        $provinceID = DB::table('provinces')->where('name', $request->province_name)->value('id');

        $citizen = Citizen::create([
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'province_id' => $provinceID,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'profile_picture_path' => $request->profile_picture_path,
            'gender' => $request->gender,
        ]);

        return $citizen;
    }
}
