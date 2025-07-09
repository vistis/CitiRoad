<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CitiRoad Dashboard - Officer Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-900 min-h-screen flex flex-col">

  <div class="flex flex-1 bg-gray-100">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <main class="flex-1 p-8 max-w-5xl mx-auto w-full">

      <a href="javascript:history.back()" class="inline-block mb-6 text-sm text-blue-600 hover:underline">
        &larr; Go Back
      </a>

      <!-- Profile Header -->
      <section class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 md:gap-12 mb-10">
        <div class="flex items-center gap-6">
          <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-300 shadow-lg">
            <img
              src="{{ asset('storage/' . $officer->profile_picture_path) }}"
              alt="{{ $officer->first_name }}'s Profile Picture"
              class="object-cover w-full h-full"
            />
          </div>
          <div>
            <h1 class="text-3xl font-extrabold text-gray-900">
              {{$officer->first_name}} {{$officer->last_name}}
            </h1>
            <p class="mt-1 text-blue-600 font-semibold uppercase tracking-wide">
              {{$officer->role}}
            </p>
          </div>
        </div>
      </section>

      <!-- Info Card -->
      <section class="bg-white rounded-3xl shadow-md border border-blue-100 p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 md:gap-x-12">

          <!-- Left Column -->
          <div class="space-y-6">
            <div>
              <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Government ID</h3>
              <p class="text-gray-800 text-lg font-medium">{{$officer->id}}</p>
            </div>
            <div>
              <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Municipality</h3>
              <p class="text-gray-800 text-lg font-medium">{{$officer->province->name}}</p>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <div>
              <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Email Address</h3>
              <p class="text-gray-800 text-lg font-medium">{{$officer->email}}</p>
            </div>
            <div>
              <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Phone Number</h3>
              <p class="text-gray-800 text-lg font-medium">{{$officer->phone_number}}</p>
            </div>
          </div>

        </div>
      </section>

    </main>
  </div>

</body>
</html>
