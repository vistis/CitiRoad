<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Include CSRF token if forms are present --}}
    <title>Admin - Citizens</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Removed custom <style> block --}}
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        {{-- Side Bar Component --}}
        {{-- Ensure your layout passes $user and $currentRoute --}}
        @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.citizens.index'])

        <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Citizens</h2>
            </div>

            {{-- Search, Filter, Sorting Form --}}
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
                        <option value="Pending" {{ $filter === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $filter === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Restricted" {{ $filter === 'Restricted' ? 'selected' : '' }}>Restricted</option>
                        <option value="Rejected" {{ $filter === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <select name="sort" class="block w-48 border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_at" {{ $sort === 'created_at' ? 'selected' : '' }}>Created At</option>
                        <option value="updated_at" {{ $sort === 'updated_at' ? 'selected' : '' }}>Updated At</option>
                        <option value="first_name" {{ $sort === 'first_name' ? 'selected' : '' }}>First Name</option>
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

            {{-- Pending Citizens Section --}}
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Pending Accounts ({{ $pendingCount ?? 0 }})</h2>
                <div class="space-y-2">
                    @forelse($pending as $citizen)
                        @include('components.citizenCard', ['citizen' => $citizen])
                    @empty
                        <p class="text-gray-600 p-4 bg-white rounded-lg shadow-sm">No pending citizens found.</p>
                    @endforelse
                </div>
            </div>

            {{-- Other Citizens Section (Approved, Restricted, etc.) --}}
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Other Accounts ({{ $otherCount ?? 0 }})</h2>
                <div class="space-y-2">
                    @forelse($other as $citizen)
                        @include('components.citizenCard', ['citizen' => $citizen])
                    @empty
                        <p class="text-gray-600 p-4 bg-white rounded-lg shadow-sm">No other citizens found.</p>
                    @endforelse
                </div>
            </div>

            {{-- Pagination: Removed hardcoded pagination as it won't work without controller pagination --}}
        </main>
    </div>
</body>
</html>
