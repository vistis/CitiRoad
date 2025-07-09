<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitiRoad - Sign Up</title>
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
                        <label for="id" class="block text-sm font-medium text-gray-700">National ID</label>
                        <input type="text" id="id" name="id" required
                            class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50"
                            placeholder="Enter your National ID">
                    </div>

                    <!-- Name Fields -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="first_name" name="first_name" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        </div>
                        <div class="space-y-1">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        </div>
                    </div>
                    <!-- Phone and Email -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50"
                                placeholder="XXX XXX XXX">
                        </div>
                        <div class="space-y-1">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" id="email" name="email" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50"
                                placeholder="example@example.com">
                        </div>
                    </div>
                    <!-- Password-->  
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        </div>
                        <div>
                            <!-- Province -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="province_id" class="block text-sm font-medium text-gray-700">Province</label>
                            <select id="province_id" name="province_id" required
                            class="mt-1 w-full p-2 border rounded">
                            <option value="">Select Province</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Address -->
                    <div class="space-y-1">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" id="address" name="address" required
                            class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                    </div>

             <!-- Gender Date of Birth -->
             <div class="grid grid-cols-2 gap-6">
                
                        <div class="space-y-1">
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required
                                class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50"
                                placeholder="YYYY-MM-DD">
                        </div>
                        <div class="space-y-1">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select id="gender" name="gender" required
                        class="input-field mt-1 block w-full px-4 py-3 border border-gray-200/80 rounded-xl bg-gray-50/50 focus:outline-none focus:border-blue-500/80 focus:ring-1 focus:ring-blue-500/50">
                        <option value="">Select One</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Prefer Not to Say">Prefer Not to Say</option>
                    </select>
                </div> 
                    </div>

                    
                        </div>
                    </div>
                            <!-- Picture -->
                        <label for="profile_picture" class="block text-sm font-medium">Profile Photo *</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required
                            class="mt-1 w-full p-2 border rounded">
                        <p class="text-xs text-gray-500 mt-1"></p>
                    </div>

                    </div>

                    <p class="text-sm text-gray-600 italic">All fields are required.</p>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="signup-button w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500/50 font-medium">
                        Request Account
                    </button>

                    <!-- Login Link -->
                    <div class="text-center text-sm">
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="login" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
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
    document.getElementById('profile_picture_path').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
        document.getElementById('profile_picture_display').value = fileName;
    });
</script>
</body>
</html> 