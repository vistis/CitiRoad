<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use \Illuminate\Validation\ValidationException;
use Illuminate\Database\Query\Builder;

class ReportController extends Controller
{
    // Statistics
    public function stats(Request $request) {
        $total = DB::table('reports');

        $ongoing = DB::table('reports')
            ->where(function (Builder $query) {
                $query->where('status', 'Reviewing')
                    ->orWhere('status', 'Investigating')
                    ->orWhere('status', 'Resolving');
            });


        $resolved = DB::table('reports')
            ->where('status', 'Resolved');

        // Only count within a second province for officers
        if ($request->province_name ) {
            $province = DB::table('provinces')->where('name', $request->province_name);
            if ($province->exists()) {
                // Get province ID
                $province_id = $province->first()->id;

                $total = $total->where('province_id', $province_id);
                $ongoing = $ongoing->where('province_id', $province_id);
                $resolved = $resolved->where('province_id', $province_id);
            }
        }

        return [
            'total' => $total->count(),
            'ongoing' => $ongoing->count(),
            'resolved' => $resolved->count()
        ];
    }

    // Create new report
    public function create(Request $request) {
        // Reqeust rules
        $request->validate([
            'title' => ['required', 'string'],
            'province_id' => ['required', 'numeric', 'exists:provinces,id'],
            'address' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image_path' => ['required', 'string']
        ]);

        // Validate account status
        if ($request->user()->status != 'Approved') {
            // throw ValidationException::withMessages([
            //     'status' => 'Your account is not approved'
            // ]);
            return response()->json(['message' => 'Your account is not approved'], 403);
        }

        // Get user ID
        $citizenID = $request->user()->id;

        // Create report
        $report = Report::create([
            'title' => $request->title,
            'province_id' => $request->province_id,
            'address' => $request->address,
            'description' => $request->description,
            'citizen_id' => $citizenID,
        ]);

        // Store images
        DB::table('report_images')->insert(['type' => 'Before', 'image_path' => $request->image_path, 'report_id' => $report->id]);

        $images = DB::table('report_images')->where('report_id', $report->id)->get();

        return ['message' => "Report receivd",
            'report' => $report,
            'images' => $images];
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
                'provinces.name as province_name',
                'reports.address as address',
                'reports.created_at as created at',
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

        return ['report' => $report, 'images' => $images];
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
                'provinces.name as province_name',
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
                'province_name',
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
        if ($request->province_name) {
            if (DB::table('provinces')->where('name', $request->province_Name)->exists()) {
                $response = $response->where('province_name', $request->province_name);
            }
        }

        // Get filter
        if ($request->filter) {
            if ($request->filter == 'Reviewing' || $request->filter == 'Investigating' || $request->filter == 'Rejected' || $request->filter == 'Resolving' || $request->filter == 'Resolved') {
                $response = $query->where('status', $request->filter);
            }
        }

        return ['count' => $response->count(), 'list' => $response];
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

        if (!$role) {
            // User is not an officer
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($role == 'Municipality Deputy' && $request->status == 'Resolved') {
            // Insufficent permission (only Municipality Heads can set status to Resolved)
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($role == 'Municipality Deputy' && $report->status == 'Resolved'
            || $role == 'Municipality Deputy' && $report->status == 'Rejected') {
            // Insufficent permission (only Municipality Heads can update Resolved or Rejected status)
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update report
        Report::where('id', $request->id)->update(['status' => $request->status, 'remark' => $request->remark, 'updated_by' => $request->user()->id]);

        // Store proof of resolution
        if ($request->status == 'Resolved') {
            DB::table('report_images')->insert(['type' => 'After', 'image_path' => $request->image_path, 'report_id' => $request->id]);
        }

        $report = Report::find($request->id);
        $images = DB::table('report_images')->where('report_id', $report->id)->get();

        return ['report' => $report, 'images' => $images];
    }

    // Delete report
    public function delete(Request $request)
    {
        // Delete associated images
        DB::table('report_images')->where('report_id', $request->id)->delete();

        // Delete the report itself
        $report = Report::find($request->id)->delete();

        return response()->noContent();
    }
}
