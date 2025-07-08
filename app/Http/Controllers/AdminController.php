<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    //** CREATE ADMIN (NOT USED) */
    public function create(Request $request) {
        $request->validate([
            'id' => ['required', 'string', 'unique:admins,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'phone_number' => ['required', 'string', 'max:16', 'unique:admins,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'profile_picture_path' => ['required', 'image'],
        ]);

        // Generate filename
        $filename = $request->id . '-' . time() . '.' . $request->profile_picture_path->extension();

        // Move uploaded image to server storage with new name
        $request->profile_picture_path->move(public_path('storage/admins'), $filename);

        // Set file path
        $filepath = 'admins/' . $filename;

        $admin = Admin::create([
            'id' => $request->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'profile_picture_path' => $filepath,
        ]);

        return [
            'message' => "Admin created",
            'account' => $admin,
            'code' => 201
        ];
    }

    //** READ A SPECIFIED ADMIN (FROM ADMINS ONLY) */
    public function readOne(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:admins,id'],
        ]);

        $admin = Admin::find($request->id);

        // Admin found, proceed with retrieval
        return [
            'message' => "Admin retrieved",
            'account' => $admin,
            'code' => 200
        ];
    }

    //** GET ADMIN LIST */
    public function readAll(Request $request) {
        // Request rules
        $request->validate([
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'string', 'in:first_name,created_at,updated_at'],
            'order' => ['nullable', 'string', 'in:asc,desc']
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
        $admins = DB::table('admins')
            ->select(
                'id',
                'first_name',
                'last_name',
                'profile_picture_path'
            )
            ->whereAny(['first_name', 'last_name'], 'like', $search)
            ->orderBy($sort, $order)
            ->get();

        return [
            'count' => $admins->count(),
            'admins' => $admins,
            'code' => 200,
        ];
    }

    //** UPDATE ACCOUNT INFORMATION */
    public function update(Request $request) {
        // Request rules
        $data = $request->validate([
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'email' => ['email', 'unique:citizens,email'],
            'phone_number' => ['string', 'max:16', 'unique:citizens,phone_number'],
            'password' => ['string', 'confirmed', Password::defaults()],
            'profile_picture_path' => ['image', 'mimes:jpeg,png,jpg' ,'max:2048'],
        ]);

        // Hashify password
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        // Update the information
        Admin::where('id', $request->user()->id)->update($data);

        // If the request contains file
        if ($request->hasFile('profile_picture_path')) {
            // Generate filename
            $filename = $request->id . '-' . time() . '.' . $request->profile_picture_path->extension();

            // Move uploaded image to server storage with new name
            $request->profile_picture_path->move(public_path('storage/admins'), $filename);

            // Set file path
            $filepath = 'admins/' . $filename;

            // Delete old profile picture
            $oldProfilePicturePath = Admin::find($request->id)->profile_picture_path;
            Storage::disk('public')->delete($oldProfilePicturePath);

            Admin::where('id', $request->id)->update([
                'profile_picture_path' => $filepath
            ]);
        }

        return [
            'message' => "Account information updated",
            'code' => 200,
        ];
    }
}
