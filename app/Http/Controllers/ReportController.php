<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Province;
use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function dashboard()
{
    if (Auth::guard('citizen')->check()) {
        $user = Auth::guard('citizen')->user();

        $reports = Report::where('citizen_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            $reportCount = $reports->count();


        return view('citizens.dashboard', compact('user', 'reports', 'reportCount'));

    } elseif (Auth::guard('officer')->check()) {
        $user = Auth::guard('officer')->user();

        $reports = Report::where('province_id', $user->province_id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('officers.dashboard', compact('user', 'reports'));

    } else {
        return redirect()->route('login')->with('error', 'Please log in to access dashboard.');
    }
}

    public function showReportForm(){

        $provinces = Province::orderBy('name')->get();
        return view('citizens.makeReport', compact('provinces'));
    }

    public function storeReport(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'province_id' => 'required|integer|exists:provinces,id',
        'address' => 'required|string|max:500',
        'description' => 'required|string',
        'picture.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
    ]);

    \DB::beginTransaction();

    try {
        $report = Report::create([
            'title'       => $request->title,
            'province_id' => $request->province_id,
            'address'     => $request->address,
            'description' => $request->description,
            'status'      => 'Reviewing',
            'citizen_id'  => auth()->id(),
            'created_at'  => now(),
        ]);

            if ($request->hasFile('picture')) {
        foreach ($request->file('picture') as $image) {
    $filename = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
    $image->storeAs('reports', $filename, 'public');

    ReportImage::create([
        'report_id'   => $report->id,
        'type'        => 'Before',
        'image_path'  => 'reports/' . $filename,
    ]);
}

    }


        \DB::commit();
        return redirect()->route('citizens.dashboard')->with('success', 'Report submitted successfully.');
    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->back()->withErrors('Failed to submit report: ' . $e->getMessage());
    }
    }

    public function showReport($id)
    {
    $report = Report::with(['province', 'reportImages'])->findOrFail($id);

    return view('citizens.viewReport', compact('report'));
    }

    public function showReportForOfficer($id)
    {
    $report = Report::with(['province', 'reportImages'])->findOrFail($id);
    $statusFlow = ['Reviewing', 'Investigating', 'Resolving', 'Resolved'];
    $currentIndex = array_search($report->status, $statusFlow);
    $nextStatus = $currentIndex !== false && $currentIndex < count($statusFlow) - 1
    ? $statusFlow[$currentIndex + 1]
    : null;


    return view('officers.viewReport', compact('report', 'nextStatus', 'statusFlow', 'currentIndex'));
    }

    public function updateStatus(Request $request, $id)
{
    $report = Report::findOrFail($id);
    $user = auth()->user();
    $newStatus = $request->input('status');
    $currentStatus = $report->status;
    $role = $user->role;


    $statusFlow = ['Reviewing', 'Investigating', 'Resolving', 'Resolved'];


    if (in_array($currentStatus, ['Resolved', 'Rejected']) && $newStatus === 'Reviewing') {
        if ($role !== 'Municipality Head') {
            return back()->withErrors('Only Municipality Heads can reopen reports.');
        }
    } elseif ($currentStatus === 'Resolving') {
        if ($role !== 'Municipality Head') {
            return back()->withErrors('Only Municipality Heads can update reports at the Resolving stage.');
        }
        if (!in_array($newStatus, ['Resolved', 'Rejected'])) {
            return back()->withErrors('Invalid status update from Resolving.');
        }
    } elseif (in_array($currentStatus, ['Reviewing', 'Investigating'])) {
        if (!in_array($newStatus, ['Rejected', $this->getNextStatus($currentStatus, $statusFlow)])) {
            return back()->withErrors('Invalid status transition.');
        }
    } else {
        return back()->withErrors('Status update not allowed.');
    }

    $report->status = $newStatus;
    $report->save();

    return redirect()->route('officers.report', $report->id)->with('success', 'Status updated successfully.');
}

private function getNextStatus($currentStatus, $statusFlow)
{
    $index = array_search($currentStatus, $statusFlow);
    return $statusFlow[$index + 1] ?? $currentStatus;
}


public function showUpdateResolvedForm($id)
{
    $report = Report::with('beforeImages', 'afterImages')->findOrFail($id);

    return view('officers.updateResolvedReport', compact('report'));
}
public function postResolvedUpdate(Request $request, $id)
{
    $request->validate([
        'remark' => 'required|string',
        'after_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $report = Report::findOrFail($id);

    $report->status = 'Resolved';
    $report->remark = $request->remark;
    $report->updated_at = now();
    $report->updated_by = auth('officer')->id();
    $report->save();

    if ($request->hasFile('after_images')) {
        foreach ($request->file('after_images') as $image) {
            // Generate a unique filename
            $filename = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();

            // Store the file in the 'reports' directory in the 'public' disk
            $image->storeAs('reports', $filename, 'public');

            // Save the path in the database
            $report->reportImages()->create([
                'image_path' => 'reports/' . $filename,
                'type' => 'after',
            ]);
        }
    }

    return redirect()->route('officers.report', $report->id)
        ->with('success', 'Report marked as resolved with updates.');
}




}