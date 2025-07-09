<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Officer: {{ $officer->first_name ?? 'Officer' }} {{ $officer->last_name ?? '' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        @include('components.sideBar', ['user' => Auth::guard('admin')->user(), 'currentRoute' => 'admin.officers'])

        <main class="flex-1 p-6">
            <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-md">
                {{-- Back Button --}}
                <a href="javascript:history.back()" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Officer Profile
                </a>

                <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Officer Information</h1>

                {{-- Display Validation Errors --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Oops!</strong>
                        <span class="block sm:inline">There were some problems with your input.</span>
                        <ul class="mt-3 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.officer.update', ['id' => $officer->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    {{-- @method('PUT') was removed previously --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Government ID (Read-only as it's the identifier) --}}
                        <div>
                            <label for="id" class="block text-sm font-medium text-gray-700">Government ID</label>
                            <input type="text" name="id" id="id" value="{{ old('id', $officer->id) }}" readonly
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-gray-100 cursor-not-allowed">
                        </div>

                        {{-- First Name --}}
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $officer->first_name) }}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Last Name --}}
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $officer->last_name) }}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $officer->email) }}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Phone Number --}}
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $officer->phone_number) }}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Role --}}
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Municipality Head" {{ old('role', $officer->role) == 'Municipality Head' ? 'selected' : '' }}>Municipality Head</option>
                                <option value="Municipality Deputy" {{ old('role', $officer->role) == 'Municipality Deputy' ? 'selected' : '' }}>Municipality Deputy</option>
                            </select>
                        </div>

                        {{-- Province --}}
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                            <select name="province" id="province" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->name }}" {{ old('province', $officer->province) == $province->name ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Profile Picture --}}
                        <div>
                            <label for="profile_picture_path" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                            <input type="file" name="profile_picture_path" id="profile_picture_path" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep current picture. Max 9MB.</p>
                            @if($officer->profile_picture_path)
                                <div class="mt-2 flex items-center">
                                    <span class="text-sm text-gray-600 mr-2">Current:</span>
                                    <img src="{{ asset('storage/' . $officer->profile_picture_path) }}" alt="Current Profile" class="w-16 h-16 rounded-full object-cover">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- New Password (Optional) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700"> New Password (Optional)</label>
                        <input type="password" name="password" id="password" }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Password Confirmation (Optional) -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password (Optional)</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium shadow-md">
                            Update Officer
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
