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
            'province' => ['required', 'string', 'exists:provinces,name'],
            'profile_picture_path' => ['required', 'string'],
        ]);

        $provinceID = DB::table('provinces')->where('name', $request->province)->value('id');

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

    // Get information on a specific officer
    public function readOne(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:officers,id'],
        ]);

        // Restrict citizen requests
        if ($request->user()->status) {
            return [
                'message' => "Unauthorized",
                'code' => "403"
            ];
        }

        // Try to find the requested officer
        if ($request->user()->role) {
            // If request is coming from officers, only show other officers in their province
            $officer = Officer::join('provinces', 'officers.province_id', '=', 'provinces.id')
                ->where('officers.id', $request->id)
                ->where('officers.province_id', $request->user()->province_id)
                ->first();
        } else {
            $officer = Officer::join('provinces', 'officers.province_id', '=', 'provinces.id')
                ->where('officers.id', $request->id)
                ->first();
        }

        if (!$officer) {
            // Officer does not exist
            return [
                'message' => "Officer not found",
                'code' => "404"
            ];
        }

        // Officer found, proceed with retrieval
        return [
            'message' => "Officer retrieved",
            'account' => $officer,
            'code' => "200"
        ];
    }

    // Officer list
    public function readAll(Request $request) {
        // Reject requests from citizens
        if ($request->user()->status) {
            return [
                'message' => "Unauthorized",
                'code' => 403
            ];
        }

        // Get officers
        if ($request->user()->role) {
            // For officers only show officers in the same province
            $officers = Officer::join('provinces', 'officers.province_id', '=', 'provinces.id')
                ->where('officers.province_id', $request->user()->province_id)
                ->get();
        } else {
            $officers = Officer::join('provinces', 'officers.province_id', '=', 'provinces.id')->get();
        }

        return [
            'officers' => $officers,
            'code' => 200,
        ];
    }

    public function update(Request $request) {
        // Request rules
        $request->validate([
            'id' => ['string', 'exists:officers,id'],
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'email' => ['email', 'unique:citizens,email'],
            'phone_number' => ['string', 'max:16', 'unique:citizens,phone_number'],
            'role' => ['string', 'in:Municipality Head,Municipality Deputy'],
            'province' => ['string', 'exists:provinces,name'],
            'password' => ['string', 'confirmed', Password::defaults()],
            'profile_picture_path' => ['string'],
        ]);

        // Resolve province ID
        $provinceID = DB::table('provinces')->where('name', $request->province)->id;

        // Update the information
        $officer = Officer::where('id', $request->id)->update([
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'province_id' => $provinceID,
            'password' => Hash::make($request->password),
            'profile_picture_path' => $request->profile_picture_path,
        ]);

        return [
            'message' => "Account information updated",
            'account' => $officer,
            'code' => 200,
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
