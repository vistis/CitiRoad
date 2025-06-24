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
    // Statistics
    public function stats() {
        $total = Citizen::count();
        $pending = Citizen::where('status', 'Pending')->count();
        $approved = Citizen::where('status', 'Approved')->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'code' => "200"
        ];
    }

    // Create a new citizen type user
    public function create(Request $request) {
        $request->validate([
            'id' => ['required', 'string', 'unique:citizens,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:citizens,email'],
            'phone_number' => ['required', 'string', 'max:16', 'unique:citizens,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'province_name' => ['required', 'string', 'exists:provinces,name'],
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

        return [
            'message' => "Citizen created",
            'account' => $citizen,
            'code' => "201"
        ];
    }

    // Citizen approval by admin
    public function approve(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'numeric', 'exists:citizens,id'],
        ]);

        // Try to find the citizen
        $citizen = Citizen::find($request->id);

        if (!$citizen) {
            // Not found
            return ['message' => 'Citizen not found', 'code' => '404'];
        }
        else if ($citizen->status == 'Approved') {
            // Found but already approved
            return ['message' => 'This citizen is already approved', 'code' => '400'];
        }

        // Updated the status
        $citizen->status = 'Approved';
        $citizen->save();

        return [
            'message' => 'Citizen approved',
            'code' => "200"
        ];
    }

    // Citizen application rejection by admin
    public function reject(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'numeric', 'exists:citizens,id'],
        ]);

        // Try to find the citizen
        $citizen = Citizen::find($request->id);

        if (!$citizen) {
            // Not found
            return ['message' => 'Citizen not found', 'code' => '404'];
        }
        else if ($citizen->status != 'Pending') {
            // Found but no longer in review
            return ['message' => 'This citizen is no longer in review', 'code' => '400'];
        }
        else if ($citizen->status == 'Rejected') {
            // Found but already rejected
            return ['message' => 'This citizen is already rejected', 'code' => '400'];
        }

        // Updated the status
        $citizen->status = 'Rejected';
        $citizen->save();

        return [
            'message' => 'Citizen rejected',
            'code' => "200"
        ];
    }

    // Delete Citizen (move associates to a 'Deleted Account' shell account) [ADMIN ONLY]
    public function delete(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'numeric', 'exists:citizens,id'],
        ]);

        // Attempt to find citizen
        $citizen = Citizen::find($request->id);

        if (!$citizen) {
            // Account not found
            return ['message' => 'Citizen not found', 'code' => '404'];
        }

        // Account found - move reports posted by account to the shell account
        DB::table('reports')->where('citizen_id', $citizen->id)->update(['citizen_id' => 0]);

        // Delete the account
        $citizen->delete();

        return [
            'message' => 'Citizen deleted',
            'code' => "200"
        ];
    }

}
