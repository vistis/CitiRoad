<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Essential for AJAX security --}}
    <title>Edit My Account: {{ Auth::guard('admin')->user()->first_name ?? 'Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-picture-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #cbd5e0;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.account.edit'])

        <main class="flex-1 p-6 overflow-y-auto">
            <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-md">
                {{-- Back Button --}}
                <a href="{{ route('admin.account') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to My Account Profile
                </a>

                <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit My Account Information</h1>

                {{-- We'll use this div for AJAX messages, hiding the Blade ones when JS takes over --}}
                <div id="ajax-messages" class="mb-4"></div>

                {{-- Keep original Blade error/success messages for initial page load or non-JS fallback --}}
                @if(session('success'))
                    <div id="initial-alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div id="initial-alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="adminAccountUpdateForm" action="{{ route('admin.account.update') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    {{-- Profile Picture Section --}}

                    <div class="flex flex-col items-center mb-6">
                        <label for="profile_picture_path" class="cursor-pointer">
                            <img id="profile_picture_preview" src="{{ $admin->profile_picture_path ? asset('storage/' . $admin->profile_picture_path) : asset('images/default-profile.png') }}" alt="Profile Picture" class="profile-picture-preview mb-4">
                            <span class="text-blue-600 hover:underline">Change Profile Picture</span>
                        </label>
                        <input type="file" name="profile_picture_path" id="profile_picture_path" class="hidden" onchange="previewImage(event)">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        {{-- First Name --}}
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $admin->first_name) }}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Last Name --}}
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $admin->last_name) }}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Phone Number --}}
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $admin->phone_number) }}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Password (Optional) -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password (Leave blank to keep current)</label>
                            <input type="password" name="password" id="password" }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Password Confirmation (Optional) -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.account') }}" class="px-5 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium shadow-sm">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium shadow-md">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- <script>
        // JavaScript for profile picture preview
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profile_picture_preview');
                if (output) { // Added null check
                    output.src = reader.result;
                } else {
                    console.error("Profile picture preview element not found!");
                }
            }
            if (event.target.files && event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        // --- AJAX Form Submission for admin account update ---
        // Use DOMContentLoaded to ensure the form is loaded before trying to attach the listener
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded and parsed. Attaching event listener...'); // Debugging log

            const form = document.getElementById('adminAccountUpdateForm');
            const messagesDiv = document.getElementById('ajax-messages');

            if (!form) {
                console.error("Error: Form with ID 'adminAccountUpdateForm' not found!");
                if (messagesDiv) {
                    messagesDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">Initialization error: Could not find the update form.</span>
                        </div>
                    `;
                }
                return;
            }

            form.addEventListener('submit', async function(event) {
                console.log('Form submission intercepted!'); // Debugging log
                event.preventDefault(); // Stop the default form submission

                const formData = new FormData(form);

                // Clear previous AJAX messages
                if (messagesDiv) {
                    messagesDiv.innerHTML = '';
                    document.getElementById('initial-alert-success')?.style.display = 'none';
                    document.getElementById('initial-alert-error')?.style.display = 'none';
                }

                try {
                    console.log('Sending fetch request...'); // Debugging log
                    const response = await fetch(form.action, {
                        method: 'POST', // This should match your web.php route
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    console.log('Fetch response received, status:', response.status); // Debugging log

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.error('Expected JSON response, but received non-JSON:', await response.text());
                        if (messagesDiv) {
                            messagesDiv.innerHTML = `
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span class="block sm:inline">An unexpected server response occurred. Server did not return JSON.</span>
                                </div>
                            `;
                        }
                        return;
                    }

                    const data = await response.json();
                    console.log('Parsed JSON data:', data);

                    if (response.ok && data.code === 200) {
                        if (data.message) {
                            sessionStorage.setItem('adminAccountSuccessMessage', data.message);
                            console.log('Success message stored in sessionStorage. Redirecting...');
                        }
                        window.location.href = "{{ route('admin.account') }}";
                    } else {
                        let errorMessage = data.message || 'An unexpected error occurred.';
                        let errorsHtml = '';

                        if (data.errors) {
                            errorsHtml += '<ul class="list-disc pl-5">';
                            for (const key in data.errors) {
                                if (data.errors.hasOwnProperty(key)) {
                                    data.errors[key].forEach(error => {
                                        errorsHtml += `<li>${error}</li>`;
                                    });
                                }
                            }
                            errorsHtml += '</ul>';
                        }

                        if (messagesDiv) {
                            messagesDiv.innerHTML = `
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span class="block sm:inline">${errorMessage}</span>
                                    ${errorsHtml}
                                </div>
                            `;
                        }
                        console.error('Form submission failed:', data);
                    }
                } catch (error) {
                    console.error('Submission or network error:', error);
                    if (messagesDiv) {
                        messagesDiv.innerHTML = `
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">Network error or unable to connect. Please try again.</span>
                            </div>
                        `;
                    }
                }
            });

            // This script handles messages from sessionStorage when accountEdit.blade.php loads
            const successMessage = sessionStorage.getItem('adminAccountSuccessMessage');
            if (successMessage) {
                if (messagesDiv) {
                    messagesDiv.innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">${successMessage}</span>
                        </div>
                    `;
                }
                sessionStorage.removeItem('adminAccountSuccessMessage');
            }
        });
    </script> -->
</body>
</html>
