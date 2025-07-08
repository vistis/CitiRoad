<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Use first_name and last_name for the title --}}
    <title>Citizen Profile: {{ $data['account']->first_name ?? 'Citizen' }} {{ $data['account']->last_name ?? '' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        {{-- Assuming 'user' is passed to sideBar for displaying Admin Name at the bottom --}}
        {{-- Note: 'Auth::guard('admin')->user()' would be ideal here if available --}}
        @include('components.sideBar', ['user' => (object)['full_name' => 'Admin Name', 'role' => 'Administrator']])

        <main class="flex-1 p-6 overflow-y-auto">
            {{-- Main content area, centered horizontally, WITHOUT shadow directly on this div --}}
            <div class="max-w-4xl mx-auto rounded-lg">

                {{-- Back Button --}}
                <a href="javascript:history.back()" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back
                </a>

                {{-- Success/Error Messages --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Profile Header Section --}}
                <div class="bg-white p-8 rounded-lg shadow-md flex flex-col md:flex-row items-center gap-6 mb-8 mt-4">
                    {{-- Profile Image --}}
                    <img src="{{ asset('storage/' . ($data['account']->profile_picture_path ?? '')) }}"
                         alt="{{ $data['account']->first_name ?? 'Citizen' }} {{ $data['account']->last_name ?? '' }} Photo"
                         onerror="this.onerror=null;this.src='https://placehold.co/96x96/cccccc/ffffff?text=User';"
                         class="w-24 h-24 rounded-full border-4 border-gray-200 object-cover flex-shrink-0">

                    <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between text-center md:text-left">
                        <div>
                            {{-- Displaying full name --}}
                            <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ $data['account']->first_name ?? 'Full' }} {{ $data['account']->last_name ?? 'Name' }}</h1>
                            {{-- Displaying Province --}}
                            <p class="text-gray-600 text-lg">Resident of {{ $data['account']->province ?? 'N/A' }}</p>
                            {{-- Displaying Status --}}
                            <p class="text-md text-gray-700">Status:
                                @if($data['account']->status == 'Pending')
                                    <span class="text-yellow-600 font-bold">{{ $data['account']->status }}</span>
                                @elseif($data['account']->status == 'Approved')
                                    <span class="text-green-600 font-bold">{{ $data['account']->status }}</span>
                                @elseif($data['account']->status == 'Restricted')
                                    <span class="text-red-600 font-bold">{{ $data['account']->status }}</span>
                                @else
                                    <span class="text-gray-600 font-bold">{{ $data['account']->status }}</span>
                                @endif
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-4 md:mt-0 flex flex-wrap justify-center md:justify-end gap-3">
                            @if($data['account']->status == 'Pending')
                                <form action="{{ route('admin.citizens.approve') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $data['account']->id }}">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                            onclick="return confirm('Are you sure you want to approve {{ $data['account']->first_name }} {{ $data['account']->last_name }}?');">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.citizens.reject') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $data['account']->id }}">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                            onclick="return confirm('Are you sure you want to reject {{ $data['account']->first_name }} {{ $data['account']->last_name }}?');">
                                        Reject
                                    </button>
                                </form>
                            @elseif($data['account']->status == 'Approved')
                                <form action="{{ route('admin.citizens.restrict') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $data['account']->id }}">
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-4 py-2 rounded-lg font-medium transition-colors"
                                            onclick="return confirm('Are you sure you want to restrict {{ $data['account']->first_name }} {{ $data['account']->last_name }}?');">
                                        Restrict
                                    </button>
                                </form>
                            @elseif($data['account']->status == 'Restricted')
                                <form action="{{ route('admin.citizens.unrestrict') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $data['account']->id }}">
                                    <input type="hidden" name="id" value="{{ $data['account']->id }}">
                                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                            onclick="return confirm('Are you sure you want to unrestrict {{ $data['account']->first_name }} {{ $data['account']->last_name }}?');">
                                        Unrestrict
                                    </button>
                                </form>
                            @elseif($data['account']->status == 'Rejected')
                                <form action="{{ route('admin.citizens.approve') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $data['account']->id }}">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                            onclick="return confirm('Are you sure you want to approve {{ $data['account']->first_name }} {{ $data['account']->last_name }}?');">
                                        Approve
                                    </button>
                                </form>
                            @endif

                            {{-- Universal Delete Button --}}
                            <form action="/admin/citizens/delete/?id={{ $data['account']->id }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete {{ $data['account']->first_name }} {{ $data['account']->last_name }}? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    Delete Citizen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Information Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mt-8 p-6 bg-white rounded-lg shadow-md">
                    {{-- Citizen ID --}}
                    <div>
                        <p class="text-sm text-gray-500">Citizen ID</p>
                        <p class="text-lg font-medium text-gray-800">{{ $data['account']->id ?? 'N/A' }}</p>
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="text-lg font-medium text-gray-800">{{ $data['account']->phone_number ?? 'N/A' }}</p>
                    </div>

                    {{-- Email Address --}}
                    <div>
                        <p class="text-sm text-gray-500">Email Address</p>
                        <p class="text-lg font-medium text-gray-800">{{ $data['account']->email ?? 'N/A' }}</p>
                    </div>

                    {{-- Address --}}
                    <div>
                        <p class="text-sm text-gray-500">Full Address</p>
                        <p class="text-lg font-medium text-gray-800">{{ $data['account']->address ?? 'N/A' }}</p>
                    </div>

                    {{-- Reports Filed --}}
                    <div>
                        <p class="text-sm text-gray-500">Reports Filed</p>
                        <p class="text-lg font-medium text-gray-800">{{ $data['reports'] ? $data['report-count'] : 0 }}</p>
                    </div>

                    {{-- Created At (Joined) --}}
                    <div>
                        <p class="text-sm text-gray-500">Joined On</p>
                        <p class="text-lg font-medium text-gray-800">{{ $data['account']->created_at ? \Carbon\Carbon::parse($data['account']->created_at)->format('M d, Y H:i A') : 'N/A' }}</p>
                    </div>

                    {{-- Updated At --}}
                    <div>
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="text-lg font-medium text-gray-800">{{ $data['account']->updated_at ? \Carbon\Carbon::parse($data['account']->updated_at)->format('M d, Y H:i A') : 'N/A' }}</p>
                    </div>
                </div>

                {{-- Reports Filed by Citizen (if any) --}}
                @if($data['reports'] && $data['report-count'] > 0)
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 mt-8">Reports Filed by {{ $data['account']->first_name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($data['reports'] as $report)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <p class="text-lg font-semibold">{{ $report->title }}</p>
                                <p class="text-sm text-gray-600">Status: {{ $report->status }}</p>
                                <p class="text-sm text-gray-600">Filed On: {{ \Carbon\Carbon::parse($report->created_at)->format('F j, Y') }}</p>
                                <a href="{{ route('admin.report.show', ['id' => $report->id]) }}" class="text-blue-500 hover:underline text-sm mt-2 block">View Report</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 mt-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">This citizen has not filed any reports yet.</p>
                @endif

            </div>
        </main>
    </div>
</body>
</html>
