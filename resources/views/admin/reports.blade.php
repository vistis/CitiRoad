<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans"> {{-- Added font-sans for consistency --}}

    <div class="flex min-h-screen">
        {{-- Side Bar Component --}}
        {{-- Ensure your layout passes $user and $currentRoute --}}
        @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.reports.all']) {{-- Pass currentRoute for sidebar active state --}}

        <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Reports</h2>
            </div>

            {{-- Search, Filter, Sorting Form --}}
            <form action="{{ url()->current() }}" method="GET" class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    {{-- Search Input Group --}}
                    <div class="relative flex-grow md:flex-grow-0 md:min-w-[300px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" placeholder="Search reports..." name="search"
                            value="{{ request('search', '') }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Filter, Sort, Order, Apply Group --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
                        {{-- Filter Select --}}
                        <select name="filter" class="block w-full sm:w-auto border border-gray-300 rounded-md bg-white text-gray-700 py-2 @vite(['resources/css/app.css', 'resources/js/app.js'])focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" {{ request('filter', '') === '' ? 'selected' : '' }}>All Statuses</option>
                            <option value="Reviewing" {{ request('filter') === 'Reviewing' ? 'selected' : '' }}>Reviewing</option>
                            <option value="Rejected" {{ request('filter') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Investigating" {{ request('filter') === 'Investigating' ? 'selected' : '' }}>Investigating</option>
                            <option value="Resolving" {{ request('filter') === 'Resolving' ? 'selected' : '' }}>Resolving</option>
                            <option value="Resolved" {{ request('filter') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Bookmarked" {{ request('filter') === 'Bookmarked' ? 'selected' : '' }}>Bookmarked</option>
                        </select>

                        {{-- Sort Select --}}
                        <select name="sort" class="block w-full sm:w-auto border border-gray-300 rounded-md bg-white text-gray-700 py-2 @vite(['resources/css/app.css', 'resources/js/app.js'])focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Created At</option>
                            <option value="updated_at" {{ request('sort') === 'updated_at' ? 'selected' : '' }}>Updated At</option>
                            <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title</option>
                        </select>

                        {{-- Order Select --}}
                        <select name="order" class="block w-full sm:w-auto border border-gray-300 rounded-md bg-white text-gray-700 py-2 @vite(['resources/css/app.css', 'resources/js/app.js'])focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="asc" {{ request('order', 'desc') === 'asc' ? 'selected' : '' }}>Ascending</option>
                            <option value="desc" {{ request('order', 'desc') === 'desc' ? 'selected' : '' }}>Descending</option>
                        </select>

                        {{-- Apply Button --}}
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                            <i class="fas fa-filter mr-2"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>

            <div class="space-y-2" id="reportsList">
                @forelse($reports as $index => $report)
                    {{-- Create a simple object for citizen to pass to reportItem --}}
                    @php
                        $citizen = (object) [
                            'first_name' => $report->citizen_first_name,
                            'last_name' => $report->citizen_last_name,
                        ];
                    @endphp
                    {{-- Data attributes will be present but unused without JavaScript --}}
                    <div data-status="{{ $report->status }}" data-page="{{ floor($index / 6) + 1 }}" data-pinned="{{ $report->is_bookmarked ? 'true' : 'false' }}" data-title="{{ $report->title }}">
                        @include('components.reportItem', [
                            'id' => $report->id,
                            'title' => $report->title,
                            'status' => $report->status,
                            'province' => $report->province,
                            'address' => $report->address,
                            'created_at' => $report->created_at,
                            'citizen_id' => $report->citizen_id,
                            'citizen_first_name' => $report->citizen_first_name,
                            'citizen_last_name' => $report->citizen_last_name,
                            'updated_at' => $report->updated_at,
                            'updated_by' => $report->updated_by,
                            'officer_first_name' => $report->officer_first_name,
                            'officer_last_name' => $report->officer_last_name,
                            'image_path' => $report->image_path,
                            'is_bookmarked' => $report->is_bookmarked,
                            'date' => \Carbon\Carbon::parse($report->created_at)->format('F j, Y'), // This is still here for the specific 'date' prop if needed by the component
                            'location' => $report->province,
                            'citizen' => $citizen // Pass the newly created citizen object
                        ])
                    </div>
                @empty
                    <p class="text-gray-600 p-4 bg-white rounded-lg shadow-sm text-center">No reports found matching your criteria.</p>
                @endforelse
            </div>

        </main>
    </div>

</body>
</html>
