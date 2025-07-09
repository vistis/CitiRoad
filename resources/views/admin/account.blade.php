<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account: {{ Auth::guard('admin')->user()->first_name ?? 'Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Shared profile picture style with adminProfile.blade.php */
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
    <div class="flex h-screen justify-center items-center">
        {{-- The sidebar is included here as per your existing setup --}}
        @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.account'])

        <main class="flex-1 p-6 overflow-y-auto">
            <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-md">

                {{-- Success and Error Messages --}}
                {{-- IMPORTANT: Keep your Blade @if(session('success')) and @if(session('error')) for non-JS flows,
                            but add a new div for JS-driven messages. --}}
                <!-- <div id="ajax-messages" class="mb-4"></div> {{-- THIS IS THE NEW DIV FOR JS MESSAGES --}} -->

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Profile Header (mimicking adminProfile.blade.php's structure for name/buttons) --}}
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
                            {{-- Action Buttons (Edit and Logout) --}}
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.account.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium shadow-md flex items-center">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </a>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-medium shadow-md">
                                        Logout
                                    </button>
                                </form>
                            </div>
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

    {{-- Script to handle success messages from sessionStorage --}}
    <!-- <script>
        window.addEventListener('load', function() {
            const successMessage = sessionStorage.getItem('adminAccountSuccessMessage');
            const ajaxMessagesDiv = document.getElementById('ajax-messages');

            if (successMessage) {
                // Clear any existing messages in the AJAX div (though unlikely on a fresh load)
                ajaxMessagesDiv.innerHTML = '';
                // Append the new success message
                ajaxMessagesDiv.innerHTML = `
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">${successMessage}</span>
                    </div>
                `;
                sessionStorage.removeItem('adminAccountSuccessMessage'); // Remove it so it doesn't reappear on refresh
            }
        });
    </script> -->
</body>
</html>
