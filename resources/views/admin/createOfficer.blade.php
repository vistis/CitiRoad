<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Officer Account</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .select-caret {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen">
        {{-- Pass full_name for consistency with sidebar component --}}
        @include('components.sideBar', ['user' => (object)['full_name' => 'Demo']])

        <div class="flex-1 bg-gray-50 p-8">
            <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-200"> {{-- Added shadow and border --}}
                <div class="mb-6">
                    {{-- Corrected structure: a tag with flex items --}}
                    <a href="{{ route('admin.officers') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Go Back
                    </a>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Create Officer Account</h1>
                    <p class="text-gray-500">All fields are required</p>
                </div>

                <form class="max-w-4xl mx-auto" method="POST" action="{{ route('admin.officer.create') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="id" class="block text-sm font-medium text-gray-700">
                                Government ID
                            </label>
                            <input
                                type="text"
                                id="id"
                                name="id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                placeholder="XXXX-XXXX-XXXX"
                            >
                                @foreach ($errors->get('id') as $error)
                                    <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                                @endforeach
                        </div>

                        <div class="space-y-2">
                            <label for="province" class="block text-sm font-medium text-gray-700">
                                Province
                            </label>
                            <select
                                id="province"
                                name="province"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors select-caret appearance-none bg-white"
                            >
                                <option value="" selected disabled>Select Province</option>
                                {{ $provinces = DB::table('provinces')->select('name')->get(); }}
                                @foreach($provinces as $province)
                                    <option value="{{ $province->name }}">{{ $province->name }}</option>
                                @endforeach
                            </select>

                            @foreach ($errors->get('province') as $error)
                                <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                            @endforeach
                        </div>

                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">
                                First Name
                            </label>
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                placeholder="John"
                            >
                            @foreach ($errors->get('first_name') as $error)
                                <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                            @endforeach
                        </div>

                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">
                                Last Name
                            </label>
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                placeholder="Doe"
                            >
                                @foreach ($errors->get('last_name') as $error)
                                    <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                                @endforeach
                        </div>

                        <div class="space-y-2">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">
                                Phone Number
                            </label>
                            <input
                                type="tel"
                                id="phone_number"
                                name="phone_number"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                placeholder="+855 12 345 678"
                            >

                                @foreach ($errors->get('phone_number') as $error)
                                    <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                                @endforeach
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email Address
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                placeholder="officer@example.com"
                            >

                                @foreach ($errors->get('email') as $error)
                                    <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                                @endforeach
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                    placeholder="••••••••"
                                >
                                <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>

                            @foreach ($errors->get('password') as $error)
                                <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                            @endforeach
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password_confirmation"
                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                    placeholder="••••••••"
                                >
                                <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-6">
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                Role
                            </label>
                            <select
                                id="role"
                                name="role"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors select-caret appearance-none bg-white"
                                required {{-- Added required attribute --}}
                            >
                                <option value="">Select Role</option>
                                {{-- Updated options based on OfficerController's validation rules --}}
                                <option value="Municipality Head" {{ old('role') == 'Municipality Head' ? 'selected' : '' }}>Municipality Head</option>
                                <option value="Municipality Deputy" {{ old('role') == 'Municipality Deputy' ? 'selected' : '' }}>Municipality Deputy</option>
                            </select>

                            @foreach ($errors->get('role') as $error)
                                <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                            @endforeach
                        </div>

                        <div class="space-y-2">
                            <label for="profile_picture_path" class="block text-sm font-medium text-gray-700">
                                Profile Picture
                            </label>
                            <div class="flex items-center space-x-4">
                                {{-- Image preview container --}}
                                <div id="image-preview-container">
                                    <img id="profile-picture-preview" src="#" alt="Profile Picture Preview">
                                    <div id="default-avatar-placeholder">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                </div>

                                <div class="relative flex-1">
                                    <input
                                        type="file"
                                        id="profile_picture_path"
                                        name="profile_picture_path"
                                        accept="image/*"
                                        class="sr-only"
                                        onchange="updateFileNameAndPreview(this)"
                                    >
                                <label for="profile_picture_path" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors cursor-pointer flex items-center justify-between bg-white hover:bg-gray-50">
                                <span id="file-name" class="text-gray-400">Upload profile photo</span>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </label>
                        </div>
                    </div>
                    @foreach ($errors->get('profile_picture_path') as $error)
                        <p class="text-red-500 text-sm mt-1">{{ $error }}</p>
                    @endforeach
                </div>
            </div>

                    <div class="mt-8 flex justify-center">
                        <button
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-8 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md"
                        >
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- <div id="success-message" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg max-w-md w-full mx-4 shadow-xl">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Account Created Successfully!</h3>
                <p class="text-gray-600 mb-6">The officer account has been successfully created.</p>
                <button
                    onclick="hideSuccessMessage()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Continue
                </button>
            </div>
        </div>
    </div> -->

    <script>
            // Toggle password visibility
            function togglePasswordVisibility(inputId) {
                const passwordInput = document.getElementById(inputId);
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Get the SVG element within the button
                const iconSvg = passwordInput.nextElementSibling.querySelector('svg');

                // Update the SVG paths for eye open/close
                if (type === 'password') {
                    // Show password icon (open eye)
                    iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                } else {
                    // Hide password icon (closed eye)
                    iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.879 16.121A3 3 0 1110 12.879m3.879 3.242L15 21m-1-1l-3.879-3.879M6 6l-2 2"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                }
            }

            // Combined function for file name and image preview
            function updateFileNameAndPreview(input) {
                const fileNameSpan = document.getElementById('file-name');
                const previewImage = document.getElementById('profile-picture-preview');
                const defaultAvatar = document.getElementById('default-avatar-placeholder');

                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    fileNameSpan.textContent = file.name; // Update file name

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block'; // Show the image
                        defaultAvatar.style.display = 'none'; // Hide the placeholder
                    };
                    reader.readAsDataURL(file); // Read the file as a data URL
                } else {
                    fileNameSpan.textContent = 'Upload profile photo'; // Reset file name
                    previewImage.style.display = 'none'; // Hide the image
                    previewImage.src = '#'; // Clear the source
                    defaultAvatar.style.display = 'flex'; // Show the placeholder
                }
            }

            // Show success message (if you uncomment it later)
            function showSuccessMessage() {
                document.getElementById('success-message').classList.remove('hidden');
            }

            // Hide success message (if you uncomment it later)
            function hideSuccessMessage() {
                document.getElementById('success-message').classList.add('hidden');
            }

            // Initial check for 'old' profile picture if validation failed and redirected back
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.getElementById('profile_picture_path');
                if (fileInput.files.length > 0) {
                     updateFileNameAndPreview(fileInput);
                }
            });

        </script>
</body>
</html>
