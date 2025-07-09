<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Models\Province;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class CitizenController extends Controller
{
    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        return view('auth.register', compact('provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'unique:citizens,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:citizens,email'],
            'phone_number' => ['required', 'string', 'unique:citizens,phone_number'],
            'password' => ['required', 'confirmed', 'min:6'],
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'address' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'profile_picture' => ['required', 'image'],
            'gender' => ['required', 'in:Male,Female,Prefer Not to Say'],
        ]);

        // Store image
        $imagePath = $request->file('profile_picture')->store('citizens', 'public');

        $citizen = Citizen::create([
            'id' => $validated['id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'province_id' => $validated['province_id'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'profile_picture_path' => $imagePath,
            'gender' => $validated['gender'],
            'status' => 'Pending',
        ]);

        Auth::guard('citizen')->login($citizen);

        return redirect('/dashboard')
            ->with('success', 'Registration successful!');
    }

    public function edit(Citizen $citizen)
{
    return view('citizens.edit', compact('citizen'));
}

public function update(Request $request, Citizen $citizen)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string',
        'email' => 'required|email',
    ]);

    $citizen->update($validated);

    return redirect()->route('citizens.profile', $citizen)->with('message', 'Profile updated successfully!');
}


    public function showProfile()
    {
        $citizen = auth()->user();
        return view('citizens.profile', compact('citizen'));
    }

    public function destroy(Citizen $citizen)
{
    $citizen->delete();

    return redirect('/')
                     ->with('message', 'Citizen profile deleted successfully.');
}


    }
