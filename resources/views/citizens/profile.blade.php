<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CitiRoad - Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    .nav-shadow {
      box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
    }
    .card-shadow {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>

<body class="min-h-screen">

  <!-- Navigation -->
  @include('layouts.nav')

  <!-- Flash Message -->
  @if(session('message'))
  <div class="max-w-2xl mx-auto mt-6 px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg shadow-lg text-center font-semibold animate-pulse">
    {{ session('message') }}
  </div>
  @endif

  <!-- Profile Container -->
  <div class="max-w-5xl mx-auto mt-10 bg-white rounded-2xl shadow-md p-8">
    
    <!-- Profile Header -->
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-8">
      <div class="flex items-center gap-4">
        <img src="{{ asset('storage/' . $citizen->profile_picture_path) }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-blue-100 shadow-sm"/>
        <div>
          <h2 class="text-2xl font-bold text-gray-900">{{ $citizen->first_name }} {{ $citizen->last_name }}</h2>
          <p class="mt-1 text-sm text-gray-600">
            Status: 
            <span id="citizen-status" data-status="{{ $citizen->status }}" class="text-xs font-medium rounded px-2 py-1 inline-block">
              {{ $citizen->status }}
            </span>
          </p>
        </div>
      </div>
      <div class="flex gap-3 mt-4 md:mt-0">
        <form method="POST" action="{{ route('logoutC') }}">
          @csrf
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm">
            Log Out
          </button>
        </form>
        <a href="{{ route('citizens.edit', $citizen->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg font-medium shadow-sm">
        Edit
      </a>
        <form method="POST" action="{{ route('citizens.destroy', $citizen->id) }}" onsubmit="return confirm('Are you sure you want to delete this citizen?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="border border-red-500 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium">
          Delete
        </button>
      </form>

      </div>
    </div>

    <!-- Profile Details Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-xl card-shadow">
      <!-- Column 1 -->
      <div class="space-y-4">
        <div>
          <p class="text-xs text-blue-600 font-semibold uppercase">National ID</p>
          <p class="text-gray-800">{{ $citizen->id }}</p>
        </div>
        <div>
          <p class="text-xs text-blue-600 font-semibold uppercase">Gender</p>
          <p class="text-gray-800">{{ $citizen->gender }}</p>
        </div>
        <div>
          <p class="text-xs text-blue-600 font-semibold uppercase">Phone Number</p>
          <p class="text-gray-800">{{ $citizen->phone_number }}</p>
        </div>
        <div>
          <p class="text-xs text-blue-600 font-semibold uppercase">Address</p>
          <p class="text-gray-800">{{ $citizen->address }}</p>
        </div>
      </div>

      <!-- Column 2 -->
      <div class="space-y-4">
        <div>
          <p class="text-xs text-blue-600 font-semibold uppercase">Date of Birth</p>
          <p class="text-gray-800">{{ $citizen->date_of_birth->format('Y-m-d') }}</p>
        </div>
        <div>
          <p class="text-xs text-blue-600 font-semibold uppercase">Email</p>
          <p class="text-gray-800">{{ $citizen->email }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Status Color Script -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const statusEl = document.getElementById("citizen-status");
      const status = statusEl.dataset.status;

      const styles = {
        Approved: { text: "text-green-700", bg: "bg-green-100" },
        Pending: { text: "text-yellow-800", bg: "bg-yellow-100" },
        Restricted: { text: "text-red-700", bg: "bg-red-100" },
        Deactivated: { text: "text-red-700", bg: "bg-red-100" },
      };

      const defaultStyle = { text: "text-gray-700", bg: "bg-gray-100" };
      const { text, bg } = styles[status] || defaultStyle;

      statusEl.classList.add(text, bg);
    });
  </script>
</body>
</html>
