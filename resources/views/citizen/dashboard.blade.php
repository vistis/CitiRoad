@extends('layouts.citizen.app')

@section('title', 'Citizen Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Citizen Dashboard') }}
    </h2>
@endsection

@section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900">
                    Welcome,
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!
                </div>
                <table class="table-auto mt-3 border border-gray-300 w-full text-gray-900">
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">National ID</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->id }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Status</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->status }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">First Name</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->first_name }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Last Name</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Email</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Phone Number</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->phone_number }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Province</td>
                            <td class="border border-gray-300 p-2">{{ DB::table('provinces')->where('id', Auth::user()->province_id)->first()->name }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Address</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->address }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Date of Birth</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->date_of_birth }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Gender</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->gender }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Created At</td>
                            <td class="border border-gray-300 p-2">{{ Auth::user()->created_at }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="container mt-4">
                    <h1 class="mb-4">My Reports</h1>

                    <form action="{{ route('citizen.dashboard') }}" method="GET" class="mb-4">
                        <div class="row-auto">
                            <div class="col-auto">
                                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ $search }}">
                            </div>
                            <div class="col-auto">
                                <select name="sort" class="form-select">
                                    <option value="title-asc" @if($sort == 'title-asc') selected @endif>Title (Ascending)</option>
                                    <option value="title-desc" @if($sort == 'title-desc') selected @endif>Title (Descending)</option>
                                    <option value="created_at-asc" @if($sort == 'created_at-asc') selected @endif>Created At (Ascending)</option>
                                    <option value="created_at-desc" @if($sort == 'created_at-desc') selected @endif>Created At (Descending)</option>
                                    <option value="updated_at-asc" @if($sort == 'updated_at-asc') selected @endif>Updated At (Ascending)</option>
                                    <option value="updated_at-desc" @if($sort == 'updated_at-desc') selected @endif>Updated At (Descending)</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="filter" class="form-select">
                                    <option value="" @if($filter == '') selected @endif>All</option>
                                    <option value="Reviewing" @if($filter == 'Reviewing') selected @endif>Reviewing</option>
                                    <option value="Investigating" @if($filter == 'Investigating') selected @endif>Investigating</option>
                                    <option value="Resolving" @if($filter == 'Resolving') selected @endif>Resolving</option>
                                    <option value="Rejected" @if($filter == 'Rejected') selected @endif>Rejected</option>
                                    <option value="Resolved" @if($filter == 'Resolved') selected @endif>Resolved</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-100">Apply Filter</button>
                            </div>
                        </div>
                    </form>

                    <p>Total Reports: {{ $count }}</p>

                    @if($reports->isEmpty()) {{-- Check if the collection is empty --}}
                        <div class="alert alert-info">No reports found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table border w-full mt-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Province</th>
                                        <th>Address</th>
                                        <th>Reported By</th>
                                        <th>Date Reported</th>
                                        <th>Last Updated By</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>{{ $report->id }}</td>
                                            <td>{{ $report->title }}</td>
                                            <td>
                                                <span class="badge {{
                                                    $report->status == 'Reviewing' ? 'bg-warning' :
                                                    ($report->status == 'Resolved' ? 'bg-success' :
                                                    ($report->status == 'Rejected' ? 'bg-danger' : 'bg-secondary'))
                                                }}">
                                                    {{ $report->status }}
                                                </span>
                                            </td>
                                            <td>{{ $report->province }}</td>
                                            <td>{{ $report->address }}</td>
                                            <td>{{ $report->citizen_first_name }} {{ $report->citizen_last_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('M d, Y H:i A') }}</td>
                                            <td>
                                                @if($report->officer_first_name && $report->officer_last_name)
                                                    {{ $report->officer_first_name }} {{ $report->officer_last_name }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($report->image_path)
                                                    <img src="{{ asset('storage/' . $report->image_path) }}" alt="Report Image" class="img-thumbnail" style="width: 70px; height: auto;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- If you used ->paginate() in the controller, display links here --}}
                        {{-- @if (isset($reports['pagination']))
                            <div class="d-flex justify-content-center">
                                {!! $reports['pagination'] !!}
                            </div>
                        @endif --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
