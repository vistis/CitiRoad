<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use App\Models\Citizen;
use App\Models\Officer;
use App\Models\Admin;

class TokenController extends Controller
{
    //** REGISTRATION */
    // Citizen
    public function createCitizen(Request $request) {
        // Call create function
        $citizen = app('App\Http\Controllers\CitizenController')->create($request);

        // Resolve province name
        $citizen->province = DB::table('provinces')->where('id', $citizen->province_id)->first()->name;

        // JSON response
        $response = [
            'message' => "Registered as citizen",
            'account-info' => $citizen,
            'token' => $citizen->createToken('citizen-api')->plainTextToken // Generate token
        ];

        return response()->json($response, 201);
    }

    // Officer
    public function createOfficer(Request $request) {
        // Call create function
        $officer = app('App\Http\Controllers\OfficerController')->create($request);

        // Resolve province name
        $officer->province = DB::table('provinces')->where('id', $officer->province_id)->first()->name;

        // JSON response
        $response = [
            'message' => "Registered as officer",
            'account-info' => $officer,
            'token' => $officer->createToken('officer-api')->plainTextToken // Generate token
        ];

        return response()->json($response, 201);
    }

    // Admin
    public function createAdmin(Request $request) {
        // Call create function
        $admin = app('App\Http\Controllers\AdminController')->create($request);

        // JSON response
        $response = [
            'message' => "Registered as admin",
            'account-info' => $admin,
            'token' => $admin->createToken('admin-api')->plainTextToken // Generate token
        ];

        return response()->json($response, 201);
    }

    //** LOG IN */
    // Citizen
    public function storeCitizen(Request $request) {
        // Request rules
        $request->validate([
            'email' => ['string', 'email'],
            'phone_number' => ['string'],
            'password' => ['required', 'string'],
        ]);

        // Grab password
        $requestPassword = $request->string('password')->toString();

        // Try to authenticate with email
        if ($request->email) {
            $citizen = Citizen::where('email', $request->email)->first();

            if (!$citizen || !Hash::check($requestPassword, $citizen->password)) {
                 // Failed to authenticate
                return response()->json(['message' => "The provided credentials are incorrect"], 401);
            }
        }

        // Try to authenticate with phone number
        else {
            $citizen = Citizen::where('phone_number', $request->phone_number)->first();

            if (!citizen || Hash::check($requestPassword, $citizen->password)) {
                // Failed to authenticate
                return response()->json(['message' => "The provided credentials are incorrect"], 401);
            }
        }

        // Resolve province name
        $citizen->province = DB::table('provinces')->where('id', $citizen->province_id)->first()->name;

        // Authentication attempt successful
        $response = [
            'message' => "Logged in as citizen",
            'account-info' => $citizen,
            'token' => $citizen->createToken('citizen-api')->plainTextToken // Generate token
        ];

        return response()->json($response, 200);
    }

    // Officer
    public function storeOfficer(Request $request) {
        // Request rules
        $request->validate([
            'id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Grab password
        $requestPassword = $request->string('password')->toString();

        // Try to authenticate
        $officer = Officer::where('id', $request->id)->first();

        if (!$officer || !Hash::check($requestPassword, $officer->password)) {
                // Failed to authenticate
            return response()->json(['message' => "The provided credentials are incorrect"], 401);
        }

        // Resolve province name
        $officer->province = DB::table('provinces')->where('id', $officer->province_id)->first()->name;

        // Authentication attempt successful
        $response = [
            'message' => "Logged in as officer",
            'account-info' => $officer,
            'token' => $officer->createToken('officer-api')->plainTextToken // Generate token
        ];

        return response()->json($response, 200);
    }

    // Admin
    public function storeAdmin(Request $request) {
        // Request rules
        $request->validate([
            'id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Grab password
        $requestPassword = $request->string('password')->toString();

        // Try to authenticate
        $admin = Admin::where('id', $request->id)->first();

        if (!$admin || !Hash::check($requestPassword, $admin->password)) {
                // Failed to authenticate
            return response()->json(['message' => "The provided credentials are incorrect"], 401);
        }

        // Authentication attempt successful
        $response = [
            'message' => "Logged in as admin",
            'account-info' => $admin,
            'token' => $admin->createToken('admin-api')->plainTextToken // Generate token
        ];

        return response()->json($response, 200);
    }

    //** LOG OUT */
    // Universal
    public function destroy(Request $request) {
        // Get user
        $user = $request->user();

        // Get user token
        $token = $user->currentAccessToken();

        // Delete token
        $token->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
