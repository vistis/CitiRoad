<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Officers</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        {{-- Side Bar Component --}}
        @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.officers'])

        <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Officers ({{ $count ?? 0 }})</h1>
                <a href="{{ route('admin.officer.create') }}">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Add Officer</span>
                    </button>
                </a>
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
                        <input type="text" placeholder="Search officers..." name="search"
                            value="{{ request('search', '') }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Filter, Sort, Order, Apply Group --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
                        {{-- Filter Select --}}
                        <select name="filter" class="block w-full sm:w-auto border border-gray-300 rounded-md bg-white text-gray-700 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" {{ request('filter', '') === '' ? 'selected' : '' }}>All Roles</option>
                            <option value="Municipality Head" {{ request('filter') === 'Municipality Head' ? 'selected' : '' }}>Municipality Head</option>
                            <option value="Municipality Deputy" {{ request('filter') === 'Municipality Deputy' ? 'selected' : '' }}>Municipality Deputy</option>
                        </select>

                        {{-- Sort Select --}}
                        <select name="sort" class="block w-full sm:w-auto border border-gray-300 rounded-md bg-white text-gray-700 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Created At</option>
                            <option value="updated_at" {{ request('sort') === 'updated_at' ? 'selected' : '' }}>Updated At</option>
                            <option value="first_name" {{ request('sort') === 'first_name' ? 'selected' : '' }}>First Name</option>
                            <option value="last_name" {{ request('sort') === 'last_name' ? 'selected' : '' }}>Last Name</option> {{-- Added last_name sort option --}}
                        </select>

                        {{-- Order Select --}}
                        <select name="order" class="block w-full sm:w-auto border border-gray-300 rounded-md bg-white text-gray-700 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="asc" {{ request('order', 'desc') === 'asc' ? 'selected' : '' }}>Ascending</option> {{-- Default to desc if no order is set --}}
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

            {{-- Officer Cards Grid --}}
            <div class="grid grid-cols-1 gap-2">
                @forelse($officers as $officer)
                    <a href="{{ route('admin.officer.show', ['id' => $officer->id]) }}" class="block">
                        <div class="bg-white flex p-4 items-center justify-between rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center space-x-4">
                                <img class="h-12 w-12 rounded-full object-cover"
                                    src="{{ asset('storage/' . ($officer->profile_picture_path ?? '')) }}"
                                    alt="{{ $officer->first_name ?? 'Officer' }}'s profile picture"
                                    onerror="this.onerror=null;this.src='https://placehold.co/48x48/cccccc/ffffff?text=Officer';"
                                >
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $officer->first_name ?? '' }} {{ $officer->last_name ?? '' }}
                                    </h3>
                                    <p class="text-sm text-gray-600">ID: {{ $officer->id ?? '' }}</p>
                                    <p class="text-sm text-gray-600">{{ $officer->role ?? 'N/A' }} of {{ $officer->province }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-600 p-4 bg-white rounded-lg shadow-sm col-span-full text-center">No officers found matching your criteria.</p>
                @endforelse
            </div>

            {{-- Pagination information (adjusted for Collection) --}}
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $officers->count() }}</span> of <span class="font-medium">{{ $count }}</span> officers (Total matching criteria)
                </div>
                {{-- Laravel's built-in pagination links (like $officers->links()) require the
                    controller method to return a Paginator instance (e.g., using ->paginate()).
                    Since OfficerController::readAll uses ->get() and returns a Collection,
                    direct pagination links are not available here without modifying the controller. --}}
            </div>
        </main>
    </div>
</body>
</html>
