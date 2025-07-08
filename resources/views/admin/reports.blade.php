<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        {{-- Side Bar Component --}}
        @include('components.sideBar', ['user' => (object)['name' => 'Demo']])

        {{-- CHANGED: Applied classes directly for consistency, removed inner p-6 div --}}
        <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Reports</h2>
            </div>

            <div class="flex items-center justify-between mb-4 bg-white p-4 rounded-lg shadow-sm flex-wrap gap-4">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="flex-1 min-w-[250px] relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        {{-- Search input will no longer function without JavaScript --}}
                        <input type="text" placeholder="Search..." name="search" value="{{ $search }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex items-center space-x-4">
                        <select name="filter" class="block w-48 border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" {{ $filter === '' ? 'selected' : '' }}>All</option>
                            <option value="Reviewing" {{ $filter === 'Reviewing' ? 'selected' : '' }}>Reviewing</option>
                            <option value="Rejected" {{ $filter === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Investigating" {{ $filter === 'Investigating' ? 'selected' : '' }}>Investigating</option>
                            <option value="Resolving" {{ $filter === 'Resolving' ? 'selected' : '' }}>Resolving</option>
                            <option value="Resolved" {{ $filter === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Bookmarked" {{ $filter === 'Bookmarked' ? 'selected' : '' }}>Bookmarked</option>
                        </select>
                        <select name="sort" class="block w-48 border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at" {{ $sort === 'created_at' ? 'selected' : '' }}>Created At</option>
                            <option value="updated_at" {{ $sort === 'updated_at' ? 'selected' : '' }}>Updated At</option>
                            <option value="title" {{ $sort === 'title' ? 'selected' : '' }}>Title</option>
                        </select>
                        <select name="order" class="block w-48 border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="asc" {{ $order === 'asc' ? 'selected' : '' }}>Ascending</option>
                            <option value="desc" {{ $order === 'desc' ? 'selected' : '' }}>Descending</option>
                        </select>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search"></i> Apply
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-2" id="reportsList">
                @foreach($reports as $index => $report)
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
                            'date' => \Carbon\Carbon::parse($report->created_at)->format('F j, Y'),
                            'location' => $report->province,
                            'citizen' => $citizen // Pass the newly created citizen object
                        ])
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center items-center mt-6 space-x-4">
                {{-- Pagination buttons will no longer function without JavaScript --}}
                <button id="prevPage" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
                <span class="text-gray-700">Page <span id="currentPage">1</span></span>
                <button id="nextPage" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
            </div>

        </main>
    </div>

</body>
</html>
