<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{-- Use first_name and last_name for the title --}}
  <title>Officer Profile: {{ $officer->first_name ?? 'Officer' }} {{ $officer->last_name ?? '' }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="">
  <div class="flex h-screen justify-center items-center">
    {{-- Assuming 'user' is passed to sideBar for displaying Admin Name at the bottom --}}
    {{-- Note: 'Auth::guard('admin')->user()' would be ideal here if available --}}
    @include('components.sideBar', ['user' => (object)['full_name' => 'Admin Name', 'role' => 'Administrator']])

    <main class="flex-1 p-6">
      {{-- Main content area, centered horizontally, WITHOUT shadow --}}
      <div class="max-w-4xl mx-auto p-8 rounded-lg">
        {{-- Back Button --}}
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Go Back
        </a>

        {{-- Profile Header --}}
        <div class="flex flex-col md:flex-row items-center gap-6 mb-8 mt-4">
            {{-- Profile Image: Using profile_picture_path from the controller's data --}}
            <img src="{{ asset('storage/' . ($officer->profile_picture_path ?? '')) }}"
                 alt="{{ $officer->first_name ?? 'Officer' }} {{ $officer->last_name ?? '' }} Photo"
                 onerror="this.onerror=null;this.src='https://placehold.co/96x96/cccccc/ffffff?text=Officer';"
                 class="w-24 h-24 rounded-full border-4 border-gray-200 object-cover">


            <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between">
                <div class="text-center md:text-left">
                    {{-- Displaying full name using first_name and last_name --}}
                    <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ $officer->first_name ?? 'Full' }} {{ $officer->last_name ?? 'Name' }}</h1>
                    {{-- Using 'province' from the controller's data --}}
                    <p class="text-gray-600 text-lg">Municipality of {{ $officer->province ?? '(Province Name)' }}</p>
                </div>

            </div>

            {{-- Action Buttons (Edit/Delete) --}}
            <div class="mt-8 pt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.officer.edit', ['id' => $officer->id]) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium">
                    Edit Officer
                </a>

                <form action="{{ route('admin.officer.delete', ['id' => $officer->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this officer?');">
                    @csrf
                    @method('DELETE') {{-- Assuming a DELETE route method for deletion --}}
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium">
                        Delete Officer
                    </button>
                </form>
            </div>
        </div>

        {{-- Information Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mt-8 p-6 bg-white rounded-lg shadow-md">
            {{-- Government ID: Uses 'id' from officer data --}}
            <div>
                <p class="text-sm text-gray-500">Government ID</p>
                <p class="text-lg font-medium text-gray-800">{{ $officer->id ?? 'N/A' }}</p>
            </div>

            {{-- Role: Uses 'role' from officer data --}}
            <div>
                <p class="text-sm text-gray-500">Role</p>
                <p class="text-lg font-medium text-gray-800">{{ $officer->role ?? 'N/A' }}</p>
            </div>

            {{-- Phone Number: Uses 'phone_number' from officer data --}}
            <div>
                <p class="text-sm text-gray-500">Phone Number</p>
                <p class="text-lg font-medium text-gray-800">{{ $officer->phone_number ?? 'N/A' }}</p>
            </div>

            {{-- Email Address: Uses 'email' from officer data --}}
            <div>
                <p class="text-sm text-gray-500">Email Address</p>
                <p class="text-lg font-medium text-gray-800">{{ $officer->email ?? 'N/A' }}</p>
            </div>

            {{-- Created At --}}
            <div>
                <p class="text-sm text-gray-500">Created At</p>
                <p class="text-lg font-medium text-gray-800">{{ $officer->created_at ? \Carbon\Carbon::parse($officer->created_at)->format('M d, Y H:i A') : 'N/A' }}</p>
            </div>

            {{-- Updated At --}}
            <div>
                <p class="text-sm text-gray-500">Last Updated</p>
                <p class="text-lg font-medium text-gray-800">{{ $officer->updated_at ? \Carbon\Carbon::parse($officer->updated_at)->format('M d, Y H:i A') : 'N/A' }}</p>
            </div>
        </div>



      </div>
    </main>
  </div>
</body>
</html>
