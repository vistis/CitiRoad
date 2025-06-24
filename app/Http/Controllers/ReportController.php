<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
// use \Illuminate\Validation\ValidationException;
use Illuminate\Database\Query\Builder;

class ReportController extends Controller
{
    // Statistics
    public function stats(Request $request) {
        $total = DB::table('reports');

        $active = DB::table('reports')
            ->where(function (Builder $query) {
                $query->where('status', 'Reviewing')
                    ->orWhere('status', 'Investigating')
                    ->orWhere('status', 'Resolving');
            });

        $resolved = DB::table('reports')
            ->where('status', 'Resolved');

        // Only count within a second province for officers
        if ($request->province ) {
            $province = DB::table('provinces')->where('name', $request->province);
            if ($province->exists()) {
                // Get province ID
                $province_id = $province->first()->id;

                $total = $total->where('province_id', $province_id);
                $active = $active->where('province_id', $province_id);
                $resolved = $resolved->where('province_id', $province_id);
            }
        }

        return [
            'total' => $total->count(),
            'active' => $active->count(),
            'resolved' => $resolved->count(),
            'code' => '200'
        ];
    }

    // Create new report
    public function create(Request $request) {
        // Reqeust rules
        $request->validate([
            'title' => ['required', 'string'],
            'province' => ['required', 'string', 'exists:provinces,name'],
            'address' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image_path' => ['required', 'string']
        ]);

        // Validate account status
        if ($request->user()->status != 'Approved') {
            return ['message' => 'Your account is not approved', 'code' => "403"];
        }

        // Get user ID
        $citizenID = $request->user()->id;

        // Resolve province
        $provinceID = DB::table('provinces')->where('name', $request->province)->value('id');

        // Create report
        $report = Report::create([
            'title' => $request->title,
            'province_id' => $provinceID,
            'address' => $request->address,
            'description' => $request->description,
            'citizen_id' => $citizenID,
        ]);

        // Store images
        DB::table('report_images')->insert(['type' => 'Before', 'image_path' => $request->image_path, 'report_id' => $report->id]);

        $images = DB::table('report_images')->where('report_id', $report->id)->get();

        return [
            'message' => "Report received",
            'report' => $report,
            'images' => $images,
            'code' => "200"
        ];
    }

    // Get all info of a report
    public function readOne(Request $request) {
        // Request rule
        $request->validate(['id' => ['required', 'string']]);

        // Get report
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

        // Get images of report
        $images = DB::table('report_images')->where(['report_id' => $request->id])->get();

        return [
            'message' => "Report found",
            'report' => $report,
            'images' => $images,
            'code' => "200"
        ];
    }

    // Report list
    public function readList(Request $request) {
        // Get search term for report title
        $search = "%" . $request->search . "%";

        // Default sorting
        $sort = 'created_at';
        $order = 'desc';

        // Get sorting
        if ($request->sort) {
            if ($request->sort == 'title-desc') {
                $sort = 'title';
                $order = 'desc';
            }
            else if ($request->sort == 'title-asc') {
                $sort = 'title';
                $order = 'asc';
            }
            else if ($request->sort == 'created_at-asc') {
                $sort = 'created_at';
                $order = 'asc';
            }
            else if ($request->sort == 'created_at-desc') {
                $sort = 'created_at';
                $order = 'desc';
            }
            else if ($request->sort == 'updated_at-asc') {
                $sort = 'updated_at';
                $order = 'asc';
            }
            else if ($request->sort == 'updated_at-desc') {
                $sort = 'updated_at';
                $order = 'desc';
            }
        }

        // Database query
        $query = DB::table('reports')
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
                DB::raw('MIN(report_images.image_path) as image_path') // Select only one picture for preview
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

        $response = $query;

        // If citizen is specified (for citizen profile page)
        if ($request->citizen_id) {
            $response = $response->where('citizen_id', $request->citizen_id);
        }

        // For officer (only show reports within their province)
        if ($request->province) {
            if (DB::table('provinces')->where('name', $request->province)->exists()) {
                $response = $response->where('province', $request->province);
            }
        }

        // Get filter
        if ($request->filter) {
            if ($request->filter == 'Reviewing' || $request->filter == 'Investigating' || $request->filter == 'Rejected' || $request->filter == 'Resolving' || $request->filter == 'Resolved') {
                $response = $query->where('status', $request->filter);
            }
        }

        return [
            'count' => $response->count(),
            'reports' => $response,
            'code' => "200"
        ];
    }

    // Update status
    public function updateStatus(Request $request) {
        // Request Rules
        $request->validate([
            'id' => ['required', 'exists:reports,id'],
            'status' => ['required', 'in:Reviewing,Investigating,Rejected,Resolving,Resolved'],
            'image_path' => ['string'],
            'remark' => ['string']
        ]);

        // Check officer role
        $role = $request->user()->role;

        // Find report
        $report = Report::where('id', $request->id)->first();

        // Check officer province
        $provinceID = $request->user()->province_id;

        if ($provinceID != $report->province_id) {
            return ['message' => 'Unauthorized', 'code' => "403"];
        }
        if (!$role) {
            // User is not an officer
            return ['message' => 'Unauthorized', 'code' => "403"];
        }
        if ($role == 'Municipality Deputy' && $request->status == 'Resolved') {
            // Insufficent permission (only Municipality Heads can set status to Resolved)
            return ['message' => 'Unauthorized', 'code' => "403"];
        }
        if ($role == 'Municipality Deputy' && $report->status == 'Resolved'
            || $role == 'Municipality Deputy' && $report->status == 'Rejected') {
            // Insufficent permission (only Municipality Heads can update Resolved or Rejected status)
            return ['message' => 'Unauthorized', 'code' => "403"];
        }

        // Update report
        Report::where('id', $request->id)->update(['status' => $request->status, 'remark' => $request->remark, 'updated_by' => $request->user()->id]);

        // Store proof of resolution
        if ($request->status == 'Resolved') {
            DB::table('report_images')->insert(['type' => 'After', 'image_path' => $request->image_path, 'report_id' => $request->id]);
        }

        $report = Report::find($request->id);
        $images = DB::table('report_images')->where('report_id', $report->id)->get();

        return [
            'message' => 'Report updated successfully',
            'report' => $report,
            'images' => $images,
            'code' => '200'
        ];
    }

    // Delete report
    public function delete(Request $request)
    {
        // Find report
        $report = Report::find($request->id);

        // Failed to find report
        if (!$report) {
            return ['message' => "Report not found", 'code' => 404];
        }

        // Delete associated images
        DB::table('report_images')->where('report_id', $report->id)->delete();

        // Delete the report itself
        $report->delete();

        return [
            'message' => 'Report deleted successfully',
            'code' => '200'
        ];
    }
}
