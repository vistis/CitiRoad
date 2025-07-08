<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ $admin->first_name ?? 'Admin' }} Profile</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        {{-- Side Bar Component --}}
        @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.admins.show'])

        <main class="flex-1 p-6 bg-gray-50 overflow-y-auto ml-64">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Administrator Profile</h2>
                <a href="{{ route('admin.admins.index') }}" class="text-blue-600 hover:underline">
                    &larr; Back to Administrators
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl mx-auto">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-8">
                    <img class="h-24 w-24 rounded-full object-cover shadow-lg"
                         src="{{ asset('storage/' . ($admin->profile_picture_path ?? '')) }}"
                         alt="{{ $admin->first_name ?? 'Admin' }}'s profile picture"
                         onerror="this.onerror=null;this.src='https://placehold.co/96x96/cccccc/ffffff?text=Admin';"
                    >
                    <div class="text-center md:text-left">
                        <h3 class="text-xl font-bold text-gray-900">{{ $admin->first_name ?? '' }} {{ $admin->last_name ?? '' }}</h3>
                        <p class="text-gray-600">ID: {{ $admin->id ?? '' }}</p>
                        <p class="text-gray-600">Email: {{ $admin->email ?? '' }}</p>
                        <p class="text-gray-600">Phone: {{ $admin->phone_number ?? '' }}</p>
                        <p class="text-gray-600">Joined: {{ \Carbon\Carbon::parse($admin->created_at ?? '')->format('M d, Y') }}</p>
                        <p class="text-gray-600">Last Updated: {{ \Carbon\Carbon::parse($admin->updated_at ?? '')->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Update Information</h3>
                <form action="{{ route('admin.admins.update', $admin->id ?? '') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH') {{-- Use PATCH method for update route --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-input" value="{{ old('first_name', $admin->first_name ?? '') }}">
                            @error('first_name') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-input" value="{{ old('last_name', $admin->last_name ?? '') }}">
                            @error('last_name') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $admin->email ?? '') }}">
                            @error('email') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-input" value="{{ old('phone_number', $admin->phone_number ?? '') }}">
                            @error('phone_number') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Leave blank to keep current">
                            @error('password') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                        </div>
                        <div class="form-group md:col-span-2">
                            <label for="profile_picture_path" class="form-label">Profile Picture</label>
                            <input type="file" id="profile_picture_path" name="profile_picture_path" class="form-input border-none p-0">
                            @error('profile_picture_path') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="submit" class="submit-button">Update Profile</button>
                    </div>
                </form>

                <h3 class="text-xl font-bold text-red-800 mb-4 border-b pb-2 pt-8">Danger Zone</h3>
                <div class="mt-4">
                    <form action="{{ route('admin.admins.delete', $admin->id ?? '') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="delete-button"
                                onclick="return confirm('Are you sure you want to permanently delete this administrator? This cannot be undone.');">
                            Delete Administrator
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
