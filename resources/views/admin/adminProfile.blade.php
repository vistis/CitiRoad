<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile: {{ $admin->first_name ?? 'Admin' }} {{ $admin->last_name ?? '' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Shared profile picture style with account.blade.php */
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #cbd5e0; /* gray-300 */
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    {{-- Main container, consistent with account.blade.php --}}
    <div class="flex h-screen justify-center items-center">
        {{-- The sidebar is included here as per your existing setup --}}
        @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.admins'])

        {{-- Main content area, consistent with account.blade.php --}}
        <main class="flex-1 p-6 overflow-y-auto">
            {{-- Inner content wrapper, consistent with account.blade.php --}}
            <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-md">

                {{-- Back Button --}}
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back to Administrators
                </a>

                {{-- Profile Header (mimicking account.blade.php's structure for name/buttons) --}}
                <div class="flex flex-col md:flex-row items-center md:items-start gap-8 mb-8 mt-4">
                    {{-- Profile Picture --}}
                    <div class="flex-shrink-0">
                        @if($admin->profile_picture_path)
                            <img src="{{ asset('storage/' . $admin->profile_picture_path) }}" alt="Profile Picture" class="profile-picture">
                        @else
                            <div class="profile-picture bg-gray-300 flex items-center justify-center text-gray-600 text-6xl">
                                <i class="fas fa-user-circle"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Admin Details & Action Buttons --}}
                    <div class="flex-1 text-center md:text-left">
                        {{-- Flex container to place name/role and action buttons side-by-side --}}
                        <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                            <div> {{-- Container for admin name and role --}}
                                <h1 class="text-2xl font-semibold text-gray-900 mb-1">{{ $admin->first_name ?? 'Full' }} {{ $admin->last_name ?? 'Name' }}</h1>
                                <p class="text-gray-600">{{ $admin->role ?? 'Administrator' }}</p>
                            </div>
                            {{-- Action Buttons (Edit/Delete) - Styled consistently --}}
                        </div>
                    </div>
                </div>

                {{-- Information Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mt-8 p-6 bg-white rounded-lg shadow-md">
                    <div>
                        <p class="text-sm text-gray-500">Government ID</p>
                        <p class="text-lg font-medium text-gray-800">{{ $admin->id ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-lg font-medium text-gray-800">{{ $admin->email ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="text-lg font-medium text-gray-800">{{ $admin->phone_number ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Created At</p>
                        <p class="text-lg font-medium text-gray-800">{{ $admin->created_at ? \Carbon\Carbon::parse($admin->created_at)->format('M d, Y H:i A') : 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="text-lg font-medium text-gray-800">{{ $admin->updated_at ? \Carbon\Carbon::parse($admin->updated_at)->format('M d, Y H:i A') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
