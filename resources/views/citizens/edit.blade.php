@extends('layouts.app')
@include('layouts.nav')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-200 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
    
    <!-- Header -->
    <div class="mb-8">
      <h2 class="text-3xl font-extrabold text-gray-800">Edit Your Profile</h2>
      <p class="mt-2 text-gray-500">Update your personal information below.</p>
    </div>

    <!-- Form -->
    <form action="{{ route('citizens.update', $citizen->id) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700">First Name</label>
          <input type="text" name="first_name" value="{{ old('first_name', $citizen->first_name) }}" class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700">Last Name</label>
          <input type="text" name="last_name" value="{{ old('last_name', $citizen->last_name) }}" class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700">Phone Number</label>
          <input type="text" name="phone_number" value="{{ old('phone_number', $citizen->phone_number) }}" class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700">Email</label>
          <input type="email" name="email" value="{{ old('email', $citizen->email) }}" class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700">Address</label>
          <input type="text" name="address" value="{{ old('address', $citizen->address) }}" class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
      </div>

      <div class="pt-6">
        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow">
          Save Changes
        </button>
      </div>
    </form>
  </div>
</div>
