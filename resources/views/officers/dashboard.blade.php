<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CitiRoad - Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col">

  <!-- Wrapper: Sidebar + Main -->
  <div class="flex flex-1 min-h-screen">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-10 max-w-7xl mx-auto flex flex-col justify-center">

      <!-- Header -->
      <header class="mb-10 text-center md:text-left">
        <h1 class="text-3xl font-extrabold text-gray-900">
          Municipality of <span class="text-blue-600">{{ $provinceName }}</span>
        </h1>
        <p class="mt-1 text-lg text-gray-600 font-medium">{{ $officerName }}</p>
      </header>

      <!-- Stats Cards -->
      <section class="grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-4xl mx-auto">

        <!-- Total Reports -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
          <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-2">Total Reports</h2>
          <p class="text-4xl font-extrabold text-gray-900">{{ $totalReports }}</p>
        </div>

        <!-- Ongoing Reports -->
        <div class="bg-yellow-50 border border-yellow-300 rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
          <h2 class="text-sm font-semibold text-yellow-700 uppercase tracking-wide mb-2">Ongoing</h2>
          <p class="text-4xl font-extrabold text-yellow-800">{{ $ongoingReports }}</p>
        </div>

        <!-- Resolved Reports -->
        <div class="bg-green-50 border border-green-300 rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
          <h2 class="text-sm font-semibold text-green-700 uppercase tracking-wide mb-2">Resolved</h2>
          <p class="text-4xl font-extrabold text-green-800">{{ $resolvedReports }}</p>
        </div>

      </section>

    </main>
  </div>

</body>
</html>
