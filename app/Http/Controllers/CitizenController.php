<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;

class CitizenController extends Controller
{
    //** STATISTICS (FOR ADMIN DASHBOARD) */
    public function stats() {
        // Database query
        $query = DB::table('citizens')->get();

        // Get total citizen account count
        $total = $query->count() - 1; // -1 to exclude shell account

        // Get total pending account
        $pending = $query->where('status', 'Pending')->count() - 1; // -1 to exclude shell account

        // Get total approved account
        $approved = $query->where('status', 'Approved')->count();

        // Response
        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'code' => 200
        ];
    }

    //** CREATE CITIZEN (GUESTS ONLY) */
    public function create(Request $request) {
        // Request rules
        $request->validate([
            'id' => ['required', 'string', 'unique:citizens,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:citizens,email'],
            'phone_number' => ['required', 'string', 'max:16', 'unique:citizens,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'province' => ['required', 'string', 'exists:provinces,name'],
            'address' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'profile_picture_path' => ['required', 'image'],
            'gender' => ['required', 'string', 'in:Male,Female,Prefer Not to Say'],
        ]);

        // Generate filename
        $filename = $request->id . '-' . time() . '.' . $request->profile_picture_path->extension();

        // Move uploaded image to server storage with new name
        $request->profile_picture_path->move(public_path('storage/citizens'), $filename);

        // Set file path
        $filepath = 'citizens/' . $filename;

        // Resovle province from name to ID
        $provinceID = DB::table('provinces')->where('name', $request->province)->value('id');

        // Create the user
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
            'profile_picture_path' => $filepath,
            'gender' => $request->gender,
        ]);

        // Response
        return [
            'message' => "Citizen created",
            'account' => $citizen,
            'code' => 201
        ];
    }

    //** READ INFROMAITON ON A SPECIFIC CITIZEN (OFFICERS AND ADMIN ONLY) */
    public function readOne(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'not_in:0', 'exists:citizens,id'], // Citizen ID
        ]);

        // Get user
        $user = $request->user();

        if ($user->status && $request->id != $user->id) {
            // If the user is a citizen, do not allow to read other users
            return ['message' => "Unauthorized", 'code' => 403];
        }

        // Find the citizen and resolve province from ID to name
        $citizen = Citizen::join('provinces', 'citizens.province_id', '=', 'provinces.id')
            ->where('citizens.id', $request->id)
            ->select([
                'citizens.id as id',
                'citizens.first_name as first_name',
                'citizens.last_name as last_name',
                'citizens.status as status',
                'citizens.email as email',
                'citizens.phone_number as phone_number',
                'provinces.name as province',
                'citizens.address as address',
                'citizens.date_of_birth as date_of_birth',
                'citizens.gender as gender',
                'citizens.profile_picture_path as profile_picture_path',
                'citizens.created_at as created at',
                'citizens.updated_at as updated_at'
            ])
            ->first();

        // Return the information
        return [
            'message' => 'Citizen found',
            'account' => $citizen,
            'code' => 200
        ];
    }

    //** READ ALL CITIZENS (ADMINS ONLY) */
    public function readAll(Request $request) {
        // Request rules
        $request->validate([
            'search' => ['string'],
            'sort' => ['string', 'in:first_name,created_at,updated_at'],
            'order' => ['string', 'in:asc,desc'],
            'filter' => ['string']
        ]);

        // Get search query from the request
        $search = "%" . $request->search . "%";

        // Default sorting and ordering behavior
        if (!$request->sort) {
            $sort = 'created_at';
        }
        else {
            $sort = $request->sort;
        }
        if (!$request->order) {
            $order = 'desc';
        }
        else {
            $order = $request->order;
        }

        // Database query
        $citizens = DB::table('citizens')
            ->select('id', 'first_name', 'last_name', 'status', 'profile_picture_path')
            ->whereNot('id', 0)
            ->whereAny(['first_name', 'last_name'], 'like', $search)
            ->orderBy($sort, $order)
            ->get();

        // Get filter option from request
        if ($request->filter) {
            if ($provinceName = DB::table('provinces')->where('name', $request->filter)->value('name')) {
                $citizens = $citizens->where('province', $provinceName);
            }
        }

        // Get pending accounts
        $pending = $citizens->where('status', "Pending");

        // The rest of the accounts
        $other = $citizens->whereNotIn('status', "Pending");

        // Response
        return [
            'pending-count' => $pending->count(),
            'pending' => $pending,
            'other-count' => $other->count(),
            'other' => $other,
            'code' => 200,
        ];
    }

    //** UPDATE ACCOUNT INFO (ACCOUNT HOLDER ONLY)*/
    public function update(Request $request) {
        // Request rules
        $data = $request->validate([
            'email' => ['email', 'unique:citizens,email'],
            'phone_number' => ['string', 'max:16', 'unique:citizens,phone_number'],
            'province' => ['string', 'exists:provinces,name'],
            'address' => ['string'],
            'password' => ['string', 'confirmed', Password::defaults()]
        ]);

        // Resolve province ID
        if ($request->province) {
            $data['province_id'] = DB::table('provinces')->where('name', $request->province)->value('id');

            // Remove province field
            unset($data['province']);
        }

        // Hashify password
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        // Update the information
        Citizen::where('id', $request->user()->id)->update($data);

        // Response
        return [
            'message' => "Account information updated",
            'code' => 200,
        ];
    }

    //** RESET PASSWORD (ACCOUNT HOLDER ONLY) */
    public function resetPassword(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:citizens,id'], // Ask for ID
            'password' => ['required', 'string', 'confirmed', Password::defaults()] // New password
        ]);

        // Get account
        $citizen = Citizen::find($request->user()->id);

        // Update the password
        $citizen->password = Hash::make($request->password);
        $citizen->save();

        // Response
        return [
            'message' => "Password changed",
            'code' => 200,
        ];
    }

    //** ACCOUNT APPROVAL (FOR ADMIN USE) */
    public function approve(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'not_in:0', 'exists:citizens,id'] // Citizen ID
        ]);

        // Try to find the citizen
        $citizen = Citizen::find($request->id);

        if ($citizen->status == 'Approved') {
            // Found but already approved
            return ['message' => "This citizen is already approved", 'code' => 400];
        }

        // Update the status
        $citizen->status = "Approved";
        $citizen->save();

        return [
            'message' => "Citizen approved",
            'code' => 200
        ];
    }

    //** ACCOUNT REJECTION (FOR ADMIN USE) */
    public function reject(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'not_in:0', 'exists:citizens,id'], // Citizen ID
        ]);

        // Try to find the citizen
        $citizen = Citizen::find($request->id);

        if ($citizen->status == 'Rejected') {
            // Found but already rejected
            return ['message' => "This citizen is already rejected", 'code' => 400];
        }
        else if ($citizen->status != 'Pending') {
            // Found but no longer in review
            return ['message' => "This citizen is no longer in review", 'code' => 400];
        }

        // Updated the status
        $citizen->status = "Rejected";
        $citizen->save();

        return [
            'message' => "Citizen rejected",
            'code' => 200
        ];
    }

    //** RESTRICT ACCOUNT (FOR ADMIN USE) */
    public function restrict(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'not_in:0', 'exists:citizens,id'] // Citizen ID
        ]);

        // Try to find citizen
        $citizen = Citizen::find($request->id);

        if ($citizen->status == 'Restricted') {
            // Found but is account was restricted
            return ['message' => "This citizen is already restricted", 'code' => 400];
        }
        else if ($citizen->status != 'Approved') {
            // Found but is pending/rejected
            return ['message' => "This citizen is not subject to moderation", 'code' => 400];
        }

        // Updated the status
        $citizen->status = "Restricted";
        $citizen->save();

        return [
            'message' => "Citizen access restricted",
            'code' => 200
        ];
    }

    //** UNRESTRICT ACCOUNT (FOR ADMIN USE) */
    public function unrestrict(Request $request) {
        $request->validate([
            'id' => ['required', 'string', 'not_in:0', 'exists:citizens,id'], // Citizen ID
        ]);

        // Try to find the citizen
        $citizen = Citizen::find($request->id);

        if ($citizen->status != "Restricted") {
            // Found but is account was not restricted
            return ['message' => "This citizen is not restricted", 'code' => 400];
        }

        // Updated the status
        $citizen->status = "Approved";
        $citizen->save();

        return [
            'message' => "Citizen access unrestricted",
            'code' => 200
        ];
    }

    //** DELETE ACCOUNT (ACCOUNT HOLDER OR ADMIN ONLY) */
    public function delete(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'not_in:0', 'exists:citizens,id'] // Citizen ID
        ]);

        // Find citizen
        $citizen = Citizen::find($request->id);

        // Account found - move reports posted by account to the shell account
        DB::table('reports')->where('citizen_id', $citizen->id)->update(['citizen_id' => 0]);

        // Delete picture off storage
        Storage::disk('public')->delete($citizen->profile_picture_path);

        // Delete the account
        $citizen->delete();

        return [
            'message' => 'Citizen deleted',
            'code' => 200
        ];
    }
}
