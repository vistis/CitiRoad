<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Officer;
use App\Models\Report;
use App\Models\Province;
use App\Models\Citizen;


class OfficerController extends Controller
{

    public function dashboard()
{
    $officer = Auth::guard('officer')->user();

    if (!$officer) {
        return redirect()->route('loginFormO')->with('error', 'Please login first.');
    }

    $provinceName = $officer->province->name ?? 'Unknown Province';

    // Get all reports for this province
    $reports = Report::where('province_id', $officer->province_id)->get();

    $totalReports = $reports->count();

    // Count resolved reports
    $resolvedReports = $reports->where('status', 'Resolved')->count();

    // Count all reports that are NOT resolved as ongoing
    $ongoingReports = $totalReports - $resolvedReports;

    return view('officers.dashboard', [
        'provinceName' => $provinceName,
        'officerName' => $officer->first_name . ' ' . $officer->last_name,
        'totalReports' => $totalReports,
        'ongoingReports' => $ongoingReports,
        'resolvedReports' => $resolvedReports,
    ]);
}


    public function province()
    {
    return $this->belongsTo(\App\Models\Province::class);

    }

    public function allReports()
{
    $officer = auth()->user();
    $province = $officer->province;

    // Officers only see reports in their own province
    $reports = Report::with(['officer', 'province'])
        ->where('province_id', $province->id)
        ->latest()
        ->get();

    return view('officers.allReports', compact('reports', 'province'));
}

public function showCitizenProfile($id)
    {
        $citizen = Citizen::findOrFail($id);
        $officer = auth()->user();
    
    // Get reports from this citizen that match the officer's province
    $reports = $citizen->reports()
        ->where('province_id', $officer->province_id)
        ->get();
        return view('officers.citizenProfile', compact('citizen', 'reports'));
    }

    public function showOfficerProfile() {
        $officer = auth()->user();
        return view('officers.profile', compact('officer'));
    }

    public function showAllOfficer() {
    $user = auth('officer')->user();

    // All roles should only see officers from their own province
    $officers = Officer::where('province_id', $user->province_id)->get();

    return view('officers.allOfficers', compact('officers'));
}


    public function showOtherOfficer($id){
        // Fetch the selected officer by ID
    $officer = Officer::findOrFail($id);

    // Optional: deny access if current officer shouldn't view this person
    $authOfficer = auth('officer')->user();

    // Head can see all, Deputy only from same province
    if (
        $authOfficer->role === 'Municipality Deputy' &&
        $authOfficer->province_id !== $officer->province_id
    ) {
        abort(403, 'Unauthorized to view this officer.');
    }

    return view('officers.otherOfficerProfile', compact('officer'));
    }
}