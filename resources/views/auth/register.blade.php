<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitiRoad - Citizen Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.05);
        }
        .signup-button {
            transition: all 0.3s ease;
        }
        .signup-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -1px rgba(59, 130, 246, 0.1), 0 3px 6px -1px rgba(59, 130, 246, 0.05);
        }
        .card-shadow {
            box-shadow: 
                0 0 0 5px rgba(0, 0, 0, 0.03),
                0 2px 4px 0 rgba(0, 0, 0, 0.02),
                0 4px 8px -2px rgba(0, 0, 0, 0.02),
                0 8px 16px -4px rgba(0, 0, 0, 0.02),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        }
        .nav-shadow {
            box-shadow: 
                0 1px 2px 0 rgba(0, 0, 0, 0.03),
                0 1px 6px -1px rgba(0, 0, 0, 0.02),
                0 2px 4px 0 rgba(0, 0, 0, 0.02);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Navigation Bar -->
    @include('layouts.nav')

    <!-- Signup Container -->
    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] p-4">
        <div class="w-full max-w-2xl">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl card-shadow border border-gray-200/50 p-8">
                <form method="POST" action="{{ route('citizens.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- National ID -->
                    <div class="space-y-1">
                        <label for="id" class="block text-sm font-medium text-gray-700">National ID *</label>
                        <input type="text" id="id" name="id" required
                            class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50"
                            placeholder="Enter your National ID">
                    </div>

                    <!-- Name Fields -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        </div>
                        <div class="space-y-1">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" id="email" name="email" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50"
                                placeholder="example@example.com">
                        </div>
                        <div class="space-y-1">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone *</label>
                            <input type="tel" id="phone_number" name="phone_number" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50"
                                placeholder="XXX XXX XXX">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                            <input type="password" id="password" name="password" required minlength="6"
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                            <p class="mt-1 text-xs text-gray-500">Minimum 6 characters</p>
                        </div>
                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        </div>
                    </div>

                    <!-- Province -->
                    <div class="space-y-1">
                        <label for="province_id" class="block text-sm font-medium text-gray-700">Province *</label>
                        <select id="province_id" name="province_id" required
                            class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                            <option value="">Select Province</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="space-y-1">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                        <textarea id="address" name="address" rows="3" required
                            class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50"></textarea>
                    </div>

                    <!-- Personal Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        </div>
                        <div class="space-y-1">
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
                            <select id="gender" name="gender" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                                <option value="">Select One</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Prefer Not to Say">Prefer Not to Say</option>
                            </select>
                        </div>
                    </div>

                    <!-- Profile Picture -->
                    <div class="space-y-1">
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Photo *</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required
                            class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        <p class="mt-1 text-xs text-gray-500">JPEG or PNG format</p>
                    </div>
                    <!-- Image preview -->
                    <img id="profile_picture_preview" class="mt-4 max-w-xs rounded-lg shadow-md hidden" alt="Profile Preview" />
                    </div>

                    <p class="text-sm text-gray-600 italic">All fields marked with * are required.</p>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="signup-button w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500/50 font-medium">
                        Register
                    </button>

                    <!-- Login Link -->
                    <div class="text-center text-sm pt-2">
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="{{ route('loginC') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                Sign in instead
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update the display when a file is selected
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
            // You can display the filename somewhere if needed
            console.log('Selected file:', fileName);
        });
        
    document.addEventListener("DOMContentLoaded", function () {
        @if ($errors->any())
            let errorMessages = "";

            @foreach ($errors->all() as $error)
                errorMessages += `- {{ $error }}\n`;
            @endforeach

            alert("Oops! Please fix the following errors:\n\n" + errorMessages);
        @endif
        
    });
    document.getElementById('profile_picture').addEventListener('change', function (e) {
    const preview = document.getElementById('profile_picture_preview');
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (event) {
            preview.src = event.target.result;
            preview.classList.remove('hidden');
        };

        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.classList.add('hidden');
    }
});


    </script>
    <div id="toast-container" class="fixed top-5 right-5 space-y-4 z-50"></div>
</body>
</html>