<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OfficerController extends Controller
{
    //** STATISTICS (FOR ADMIN DASHBOARD) *//
    public function stats() {
        // Database query
        $query = DB::table('officers')->get();

        // Count total officers
        $total = $query->count();

        // Count municipality heads
        $heads = $query->where('role', 'Municipality Head')->count();

        // Count municipality deputies
        $deputies = $query->where('role', 'Municipality Deputy')->count();

        // Response
        return [
            'total' => $total,
            'heads' => $heads,
            'deputies' => $deputies,
            'code' => 200
        ];
    }

    //** CREATE OFFCIER (ADMIN ONLY) */
    public function create(Request $request) {
        // Request rules
        $request->validate([
            'id' => ['required', 'string', 'unique:officers,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:officers,email'],
            'phone_number' => ['required', 'string', 'max:16', 'unique:officers,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:Municipality Head,Municipality Deputy'],
            'province' => ['required', 'string', 'exists:provinces,name'],
            'profile_picture_path' => ['required', 'image', 'max:9192'],
        ]);

        // Generate filename
        $filename = $request->id . '-' . time() . '.' . $request->profile_picture_path->extension();

        // Move uploaded image to server storage with new name
        $request->profile_picture_path->move(public_path('storage/officers'), $filename);

        // Set file path
        $filepath = 'officers/' . $filename;

        // Resolve province from name to ID
        $provinceID = DB::table('provinces')->where('name', $request->province)->value('id');

        // Create account
        $officer = Officer::create([
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'province_id' => $provinceID,
            'profile_picture_path' => $filepath,
        ]);

        // Reponse
        return [
            'message' => "Officer created",
            'account' => $officer,
            'code' => 201
        ];
    }

    //** READ INFORMATION OF A SPCIFIED OFFICER (FOR OFFICERS AND ADMINS) *//
    public function readOne(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:officers,id'],
        ]);

        // Get user
        $user = $request->user();

        // Restrict citizen requests
        if ($user->status) {
            return [
                'message' => "Unauthorized",
                'code' => 403
            ];
        }

        // Prohibit officers viewing officers outside their province
        if ($user->role && $user->province_id != Officer::find($request->id)->province_id) {
            return [
                'message' => "Unauthorized",
                'code' => 403
            ];
        }

        // Database query
        $officer = Officer::join('provinces', 'officers.province_id', '=', 'provinces.id')
            ->where('officers.id', $request->id)
            ->select([
                'officers.id as id',
                'officers.first_name as first_name',
                'officers.last_name as last_name',
                'officers.role as role',
                'provinces.name as province',
                'officers.email as email',
                'officers.phone_number as phone_number',
                'officers.profile_picture_path as profile_picture_path',
                'officers.created_at as created_at',
                'officers.updated_at as updated_at'
            ])
            ->first();

        // Officer found, proceed with retrieval
        return [
            'message' => "Officer retrieved",
            'account' => $officer,
            'code' => 200
        ];
    }

    //** READ ALL OFFICERS (ADMINS AND OFFICERS ONLY) *//
    public function readAll(Request $request) {
        // Get user sending request
        $user = $request->user();

        // Reject requests from citizens
        if ($user->status) {
            return [
                'message' => "Unauthorized",
                'code' => 403
            ];
        }

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
        $officers = DB::table('officers')
            ->join('provinces', 'officers.province_id', '=', 'provinces.id')
            ->select(
                'officers.id as id',
                'officers.first_name as first_name',
                'officers.last_name as last_name',
                'officers.role as role',
                'provinces.name as province',
                'officers.profile_picture_path as profile_picture_path'
            )
            ->whereAny(['officers.first_name', 'officers.last_name'], 'like', $search)
            ->orderBy($sort, $order)
            ->get();

        if ($user->role) {
            // For officers only show officers in the same province
            $officers = $officers->where('province', DB::table('provinces')->where('id', $request->user()->province_id)->first()->name);
        }

        // Get filter option from request
        if ($request->filter) {
            if ($request->filter == "Municipality Head" || $request->filter == "Municipality Deputy") {
                $officers = $officers->where('role', $request->filter);
            }
            else if ($provinceName = DB::table('provinces')->where('name', $request->filter)->value('name')) {
                $officers = $officers->where('province', $provinceName);
            }
        }

        // Response
        return [
            'count' => $officers->count(),
            'officers' => $officers,
            'code' => 200
        ];
    }

    //** UPDATE OFFICER ACCOUNT (BY ADMIN ONLY) */
    public function update(Request $request) {
        // Request rules
        $data = $request->validate([
            'id' => ['string', 'required', 'exists:officers,id'],
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'email' => ['email', 'unique:citizens,email'],
            'phone_number' => ['string', 'max:16', 'unique:citizens,phone_number'],
            'role' => ['string', 'in:Municipality Head,Municipality Deputy'],
            'province' => ['string', 'exists:provinces,name'],
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
        Officer::where('id', $request->id)->update($data);

        // If the request contains file
        if ($request->hasFile('profile_picture_path')) {
            // Generate filename
            $filename = $request->id . '-' . time() . '.' . $request->profile_picture_path->extension();

            // Move uploaded image to server storage with new name
            $request->profile_picture_path->move(public_path('storage/officers'), $filename);

            // Set file path
            $filepath = 'officers/' . $filename;

            // Delete old profile picture
            $oldProfilePicturePath = Officer::find($request->id)->profile_picture_path;
            Storage::disk('public')->delete($oldProfilePicturePath);

            Officer::where('id', $request->id)->update([
                'profile_picture_path' => $filepath
            ]);
        }

        // Response
        return [
            'message' => "Account information updated",
            'code' => 200,
        ];
    }

    //** DELETE AN OFFICER */
    public function delete(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:officers,id'], // Officer ID
        ]);

        // Find the requested officer
        $officer = Officer::find($request->id);

        // Delete references to this officer
        DB::table('reports')->where('updated_by', $request->id)->update([
            'updated_by' => null
        ]);

        // Delete picture off storage
        Storage::disk('public')->delete($officer->profile_picture_path);

        // Officer found, proceed with deletion
        $officer->delete();

        return [
            'message' => "Officer deleted",
            'code' => 200
        ];
    }

}
