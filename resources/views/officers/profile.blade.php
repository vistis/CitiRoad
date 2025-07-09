<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CitiRoad - Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-100 text-gray-800 font-sans">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  @include('layouts.sidebar')

  <!-- Main Content -->
  <main class="flex-1 p-6 md:p-10">
    <div class="max-w-6xl mx-auto">

      <!-- Profile Header -->
      <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-10">
        <!-- Avatar + Info -->
        <div class="flex items-center gap-6">
          <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-blue-200 shadow-md">
            <img
              src="{{ asset('storage/' . $officer->profile_picture_path) }}"
              alt="{{ $officer->first_name }}'s Profile Picture"
              class="w-full h-full object-cover"
            />
          </div>
          <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $officer->first_name }} {{ $officer->last_name }}</h1>
            <span class="text-blue-600 text-sm font-medium">{{ $officer->role }}</span>
          </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('officer.logout') }}">
          @csrf
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg transition-transform hover:scale-105">
            Log Out
          </button>
        </form>
      </div>

      <!-- Officer Details Card -->
      <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-xl border border-blue-100">
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x">

          <!-- Left Column -->
          <div class="p-6 space-y-6">
            <div>
              <label class="text-sm text-gray-500 uppercase font-semibold">Government ID</label>
              <p class="text-lg font-medium text-gray-800">{{ $officer->id }}</p>
            </div>
            <div>
              <label class="text-sm text-gray-500 uppercase font-semibold">Municipality</label>
              <p class="text-lg font-medium text-gray-800">{{ $officer->province->name }}</p>
            </div>
            <div>
              <label class="text-sm text-gray-500 uppercase font-semibold">Role</label>
              <p class="text-lg font-medium text-gray-800">{{ $officer->role }}</p>
            </div>
          </div>

          <!-- Right Column -->
          <div class="p-6 space-y-6">
            <div>
              <label class="text-sm text-gray-500 uppercase font-semibold">Email</label>
              <p class="text-lg font-medium text-gray-800">{{ $officer->email }}</p>
            </div>
            <div>
              <label class="text-sm text-gray-500 uppercase font-semibold">Phone Number</label>
              <p class="text-lg font-medium text-gray-800">{{ $officer->phone_number }}</p>
            </div>
          </div>

        </div>
      </div>

    </div>
  </main>
</div>

</body>
</html>
