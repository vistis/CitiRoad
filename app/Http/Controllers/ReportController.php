<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    //** STATISTICS (FOR OFFICER AND ADMIN DASHBOARDS) */
    public function stats(Request $request) {

        // Database query
        // If the request comes from an officer, only show statistics of their province
        if ($request->user()->role) {
            // Get the province ID of the officer
            $provinceID = $request->user()->province_id;

            $query = DB::table('reports')->where('province_id', $provinceID)->get();
        }
        else {
            $query = DB::table('reports')->get();
        }

        // Select all entries in the reports table
        $total = $query->count();

        // Select active entries (not Resolved or Rejected)
        $active = DB::table('reports')->where(function (Builder $query) {
            $query->where('status', "Reviewing")
                ->orWhere('status', "Investigating")
                ->orWhere('status', "Resolving");
            })
            ->count();

        // Select Resolved entries
        $resolved = $query->where('status', "Resolved")->count();

        // Return the count of each categories
        return [
            'total' => $total,
            'active' => $active,
            'resolved' => $resolved,
            'code' => 200
        ];
    }

    //** CREATE (APPROVED CITIZENS ONLY) */
    public function create(Request $request) {
        // Reqeust rules
        $request->validate([
            'title' => ['required', 'string'],
            'province' => ['required', 'string', 'exists:provinces,name'],
            'address' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image_path.*' => ['required', 'image'],
        ]);

        // Get user
        $user = $request->user();

        // Validate citizen account status; only approved citizen accounts can create reports
        if ($user->status != 'Approved') {
            return ['message' => "Your account is not approved", 'code' => 403];
        }

        // Resolve province from name to ID
        $provinceID = DB::table('provinces')->where('name', $request->province)->value('id');

        // Create the report
        $report = Report::create([
            'title' => $request->title,
            'province_id' => $provinceID,
            'address' => $request->address,
            'description' => $request->description,
            'citizen_id' => $user->id,
        ]);

        $imageCounter = 0;

        // Store attached images
        foreach ($request->image_path as $imagePath) {
            // Generate filename
            $filename = $report->id . '-before-' . $imageCounter . '-' . time() . '.' . $imagePath->extension();

            // Move uploaded image to server storage with new name
            $imagePath->move(public_path('storage/reports'), $filename);
            // Storage::put('reports/' . $filename, file_get_contents($imagePath));

            // Set file path
            $filepath = 'reports/' . $filename;

            DB::table('report_images')->insert(['type' => 'Before', 'image_path' => $filepath, 'report_id' => $report->id]);

            $imageCounter++;
        }

        // Respond with success message and information of the newly created report
        return [
            'message' => "Report received",
            'code' => 200
        ];
    }

    //** READ ALL INFORMATION OF A SPECIFIED REPORT */
    public function readOne(Request $request) {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:reports,id'] // Report id
        ]);

        // Get user
        $user = $request->user();

        if ($user->status) {
            // Prohibit citizens with a Pending/Rejected account from reading reports at all
            if ($user->status == "Pending" || $user->status == "Rejected") {
                return ['message' => "Unauthorized", 'code' => 403];
            }
            // Prohibit citizens from reading other citizens' reports
            else if (Report::find($request->id)->citizen_id != $user->id) {
                return ['message' => "Unauthorized", 'code' => 403];
            }
        }

        // Prohibit officers from reading reports in other provinces
        if ($user->role && $user->province_id != Report::find($request->id)->province_id) {
            return ['message' => "Unauthorized", 'code' => 403];
        }

        // Get the report with the specified ID
        $report = DB::table('reports')
            ->join('citizens', 'reports.citizen_id', '=', 'citizens.id')
            ->leftJoin('provinces', 'citizens.province_id', '=', 'provinces.id')
            ->leftJoin('officers', 'reports.updated_by', '=', 'officers.id')
            ->where('reports.id', $request->id)
            ->select(
                'reports.id as id',
                'reports.status as status',
                'reports.title as title',
                'reports.description as description',
                'provinces.name as province',
                'reports.address as address',
                'reports.created_at as created_at',
                'reports.citizen_id',
                'citizens.first_name as citizen_first_name',
                'citizens.last_name as citizen_last_name',
                'reports.updated_at as updated_at',
                'reports.updated_by',
                'officers.first_name as officer_first_name',
                'officers.last_name as officer_last_name',
                'reports.remark as remark'
            )->get();

        // Get images of the report
        $images = DB::table('report_images')
            ->where('report_id', $request->id)
            ->select('type', 'image_path')
            ->get();

        // Prepare the response
        $response = [
            'message' => "Report found",
            'report' => $report,
            'images' => $images,
            'code' => 200
        ];

        // Get user
        $user = $request->user();

        // Get bookmark status (if the request comes form an officer or admin)
        if ($user->role) { // For officers
            if (DB::table('officer_bookmarks')->where(['officer_id' => $user->id])->where(['report_id' => $request->id])->first()) {
                $response['is_bookmarked'] = true;
            }
            else {
                $response['is_bookmarked'] = false;
            }
        }
        else if (!$user->address) { // For admins
            if (DB::table('admin_bookmarks')->where(['admin_id' => $user->id])->where(['report_id' => $request->id])->first()) {
                $response['is_bookmarked'] = true;
            }
            else {
                $response['is_bookmarked'] = false;
            }
        }

        return $response;
    }

    //** READ ALL REPORTS */
    public function readAll(Request $request) {
        // Get user
        $user = $request->user();

        // Prohibit access from citizens with a Pending/Rejected account
        if ($user->status) {
            if ($user->status == "Pending" || $user->status == "Rejected") {
                return ['message' => "Unauthorized", 'code' => 403];
            }
        }

        // Request rules
        $request->validate([
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'string', 'in:title,created_at,updated_at'],
            'order' => ['nullable', 'string', 'in:asc,desc'],
            'filter' => ['nullable', 'string']
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
        $reports = DB::table('reports')
            ->join('provinces', 'reports.province_id', '=', 'provinces.id')
            ->join('citizens', 'reports.citizen_id', '=', 'citizens.id')
            ->leftJoin('officers', 'reports.updated_by', '=', 'officers.id')
            ->leftJoin('report_images', 'reports.id', '=', 'report_images.report_id')
            ->select(
                'reports.id as id',
                'reports.title as title',
                'reports.status as status',
                'provinces.name as province',
                'reports.address as address',
                'reports.created_at as created_at',
                'reports.citizen_id as citizen_id',
                'citizens.first_name as citizen_first_name',
                'citizens.last_name as citizen_last_name',
                'reports.updated_at as updated_at',
                'reports.updated_by',
                'officers.first_name as officer_first_name',
                'officers.last_name as officer_last_name',
                DB::raw('MIN(report_images.image_path) as image_path'), // Select only one picture for preview
            )
            ->groupBy(
                'id',
                'title',
                'status',
                'province',
                'address',
                'created_at',
                'citizen_id',
                'citizen_first_name',
                'citizen_last_name',
                'updated_at',
                'updated_by',
                'officer_first_name',
                'officer_last_name'
            )
            ->whereAny(['reports.title', 'provinces.name', 'reports.address', 'citizens.first_name', 'citizens.last_name'], 'like',  $search)
            ->orderBy($sort, $order)
            ->get();

        // Fetch bookmark of the current user if they are not not a citizen
        if (!$user->status) {
            // Collect all report IDs
            $reportIds = $reports->pluck('id')->toArray();

            // Fetch bookmarks for officer
            if ($user->role) {
                $officerBookmarks = DB::table('officer_bookmarks')
                    ->whereIn('report_id', $reportIds)
                    ->where('officer_id', $user->id)
                    ->pluck('report_id')
                    ->toArray();

                foreach ($reports as $report) {
                    // Check if the report ID exists in the officer array
                    // Append a new key to the response for bookmark
                    if (in_array($report->id, $officerBookmarks)) {
                        $report->is_bookmarked = true;
                    } else {
                        $report->is_bookmarked = false;
                    }
                }
            }

            // Fetch bookmarks for admin
            else {
                $adminBookmarks = DB::table('admin_bookmarks')
                    ->whereIn('report_id', $reportIds)
                    ->where('admin_id', $user->id)
                    ->pluck('report_id')
                    ->toArray();

                foreach ($reports as $report) {
                    // Check if the report ID exists in the admin array
                    // Append a new key to the response for bookmark
                    if (in_array($report->id, $adminBookmarks)) {
                        $report->is_bookmarked = true;
                    } else {
                        $report->is_bookmarked = false;
                    }
                }
            }
        }

        // If the request comes from a citizen; only show their own reports
        if ($user->status) {
            $reports = $reports->where('citizen_id', $user->id);
        }

        // If the 'id' field is present in the request, means that the requester is trying to view the report list of a citizen
        else if ($request->id) {
            $reports = $reports->where('citizen_id', $request->id);
        }

        // If the request comes from an officer; only show reports within their province
        if ($user->role) {
            $reports = $reports->where('province', DB::table('provinces')->where('id', $user->province_id)->value('name'));
        }

        // Get filter option from request
        if ($request->filter) {
            // Filter status
            if ($request->filter == 'Reviewing' || $request->filter == 'Investigating' || $request->filter == 'Rejected' || $request->filter == 'Resolving' || $request->filter == 'Resolved') {
                $reports = $reports->where('status', $request->filter);
            }
            // Filter bookmark
            else if ($request->filter == 'Bookmarked') {
                $reports = $reports->where('is_bookmarked', true);
            }
            // Filter province
            else if ($provinceName = DB::table('provinces')->where('name', $request->filter)->value('name')) {
                $reports = $reports->where('province', $provinceName);
            }
        }

        // Return report list
        return [
            'count' => $reports->count(),
            'reports' => $reports,
            'code' => 200,
        ];
    }

    //** PROCEED WITH REPORT (OFFICERS ONLY) */
    public function proceed(Request $request) {
        // Request Rules
        $request->validate([
            'id' => ['required', 'exists:reports,id'],
            'remark' => ['required', 'string']
        ]);

        // Get user
        $user = $request->user();

        // Find the report
        $report = Report::find($request->id);

        if ($user->province_id != $report->province_id) {
            // Officers can only update report within their province
            return ['message' => "Cannot update reports outside your province", 'code' => 403];
        }

        // Get the status of the requested report
        $currentStatus = $report->status;

        // Progress the status by 1
        if ($currentStatus == "Reviewing") {
            $nextStatus = "Investigating";
        }
        else if ($currentStatus == "Investigating") {
            $nextStatus = "Resolving";
        }
        else {
            return [
                'message' => "Report is already " . $currentStatus,
                'code' => 200
            ];
        }

        // Update the status
        Report::where('id', $request->id)->update([
            'status' => $nextStatus,
            'remark' => $request->remark,
            'updated_by' => $user->id
        ]);

        // Response
        return [
            'message' => "Status is now " . $nextStatus,
            'code' => 200
        ];
    }

    //** REJECT REPORT (OFFICERS ONLY) */
    public function reject(Request $request) {
        // Request Rules
        $request->validate([
            'id' => ['required', 'exists:reports,id'],
            'remark' => ['required', 'string']
        ]);

        // Get user
        $user = $request->user();

        // Find the report
        $report = Report::find($request->id);

        if ($user->province_id != $report->province_id) {
            // Officers can only update report within their province
            return ['message' => "Cannot update reports outside your province", 'code' => 403];
        }

        if ($report->status == "Rejected") {
            return ['message' => "Report has already been rejected", 'code' => 400];
        }
        else if ($report->status == "Resolving" && $user->role != 'Municipality Head') {
            return ['message' => "Only Municipality Heads can reject reports that are being resolved", 'code' => 403];
        }
        else if ($report->status == "Resolved") {
            return ['message' => "Report has already been resolved", 'code' => 400];
        }

        // Update the status
        Report::where('id', $request->id)->update([
            'status' => "Rejected",
            'remark' => $request->remark,
            'updated_by' => $user->id
        ]);

        // Response
        return [
            'message' => "Report has been rejected",
            'code' => 200
        ];
    }

    //** MARK REPORT AS RESOLVED (MUNICIPALITY HEADS ONLY) */
    public function resolve(Request $request) {
        // Request Rules
        $request->validate([
            'id' => ['required', 'exists:reports,id'],
            'remark' => ['required', 'string'],
            'image_path.*' => ['required', 'image']
        ]);

        // Get user
        $user = $request->user();

        // Find the report
        $report = Report::find($request->id);

        if ($user->province_id != $report->province_id) {
            // Officers can only update report within their province
            return ['message' => "Cannot update reports outside your province", 'code' => 403];
        }
        else if ($user->role != 'Municipality Head') {
            // Only municipality heads can resolve reports
            return ['message' => "Only municipality heads can resolve reports", 'code' => 403];
        }

        if ($report->status == "Resolved") {
            return [
                'message' => "Report is already resolved",
                'code' => 200
            ];
        }
        else if ($report->status != "Resolving") {
            return [
                'message' => "Report must be in the Resolving stage",
                'code' => 200
            ];
        }

        $imageCounter = 0;

        // Store proof of resolution
        foreach ($request->image_path as $imagePath) {
            // Generate filename
            $filename = $report->id . '-after-' . $imageCounter . '-' . time() . '.' . $imagePath->extension();

            // Move uploaded image to server storage with new name
            $imagePath->move(public_path('storage/reports'), $filename);

            // Set file path
            $filepath = 'reports/' . $filename;

            DB::table('report_images')->insert(['type' => 'After', 'image_path' => $filepath, 'report_id' => $report->id]);

            $imageCounter++;
        }

        // Update the status
        Report::where('id', $request->id)->update([
            'status' => "Resolved",
            'remark' => $request->remark,
            'updated_by' => $user->id
        ]);

        // Response
        return [
            'message' => "Report resolved",
            'code' => 200
        ];
    }

    //** REOPEN REPORT (MUNICIPALITY HEADS ONLY) */
    public function reopen(Request $request) {
        // Request Rules
        $request->validate([
            'id' => ['required', 'exists:reports,id'],
            'remark' => ['required', 'string']
        ]);

        // Get user
        $user = $request->user();

        // Find the report
        $report = Report::find($request->id);

        if ($user->province_id != $report->province_id) {
            // Officers can only update report within their province
            return ['message' => "Cannot update reports outside your province", 'code' => 403];
        }
        else if ($user->role != 'Municipality Head') {
            // Only municipality heads can reopen reports
            return ['message' => "Only municipality heads can reopen reports", 'code' => 403];
        }

        if ($report->status == "Resolved" || $report->status == "Rejected") {
            // Update the status
            Report::where('id', $request->id)->update([
                'status' => "Reviewing",
                'remark' => $request->remark,
                'updated_by' => $user->id
            ]);

            // Delete old proof images
            $images = DB::table('report_images')->where('report_id', $report->id)->where('type', 'After')->pluck('image_path')->toArray();
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
            DB::table('report_images')->where('report_id', $report->id)->where('type', 'After')->delete();

            // Response
            return [
                'message' => "Report reopened",
                'code' => 200
            ];
        }
        else {
            return [
                'message' => "Report is still active",
                'code' => 200
            ];
        }
    }

    //** DELETE REPORT (ADMIN ONLY) */
    public function delete(Request $request)
    {
        // Request rule
        $request->validate([
            'id' => ['required', 'string', 'exists:reports,id'] // Report ID
        ]);

        // Find report
        $report = Report::find($request->id);

        // Delete associated images
        $images = DB::table('report_images')->where('report_id', $report->id)->pluck('image_path')->toArray();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }
        DB::table('report_images')->where('report_id', $report->id)->delete();

        // Find and delete bookmark record
        DB::table('officer_bookmarks')->where('report_id', $report->id)->delete();
        DB::table('admin_bookmarks')->where('report_id', $report->id)->delete();

        // Delete the report itself
        $report->delete();

        // Response
        return [
            'message' => 'Report deleted successfully',
            'code' => 200
        ];
    }

    //** BOOKMARK (OFFICER AND ADMIN ONLY) */
    public function bookmark(Request $request) {
        // Request rule
        $request->validate([
            'id' => ["required", "string", "exists:reports,id"] // Report ID
        ]);

        // Get user
        $user = $request->user();

        // Find the report
        $report = Report::find($request->id);

        // Citizens cannot utilize bookmark feature
        if ($user->status) {
            return ['message' => "Citizens cannot bookmark reports", 'code' => 403];
        }

        // For officers
        else if ($user->role) {
            // Prohibit officers from bookmarking reports outside their province
            if ($user->province_id != $report->province_id) {
                return ['message' => "You cannot bookmark reports from other provinces", 'code' => 403];
            }

            // Requested report to bookmark is already bookmarked
            if (DB::table('officer_bookmarks')->where('officer_id', $user->id)->where('report_id', $report->id)->exists()) {
                // Remove it from bookmark
                DB::table('officer_bookmarks')->where('officer_id', $user->id)->where('report_id', $report->id)->delete();
                return [
                    'message' => "Bookmark removed",
                    'code' => 200
                ];
            }

            // Requested report is not bookmark - add it to bookmark
            DB::table('officer_bookmarks')->insert([
                'officer_id' => $user->id,
                'report_id' => $report->id
            ]);
            return [
                'message' => 'Bookmark added',
                'code' => 200
            ];
        }

        // For admins
        else {
            if (DB::table('admin_bookmarks')->where('admin_id', $user->id)->where('report_id', $report->id)->exists()) {
                // Report is already bookmarked - remove it
                DB::table('admin_bookmarks')->where('admin_id', $user->id)->where('report_id', $report->id)->delete();
                return [
                    'message' => "Bookmark removed",
                    'code' => 200
                ];
            }

            // Report is not bookmarked - add it
            DB::table('admin_bookmarks')->insert([
                'admin_id' => $user->id,
                'report_id' => $report->id
            ]);
            return [
                'message' => 'Bookmark added',
                'code' => 200
            ];
        }
    }
}
