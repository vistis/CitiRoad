<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CitiRoad - View Report</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

  <!-- Navigation -->
  @include('layouts.nav')

  <!-- Main Content -->
  <main class="max-w-6xl mx-auto px-6 py-10">

    <!-- Back Link -->
    <a href="javascript:history.back()" class="text-blue-600 text-sm font-medium hover:underline flex items-center mb-4">
      &larr; Back to Reports
    </a>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $report->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">Reported on {{ $report->created_at->format('Y-m-d') }}</p>
      </div>
      <span class="inline-block px-4 py-1 text-sm rounded-full font-medium
        {{ $report->status === 'Resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
        {{ $report->status }}
      </span>
    </div>

    <!-- Image Gallery -->
    @if(count($report->beforeImages))
      <div class="mb-8">
        <h2 class="text-lg font-semibold mb-3">Before Images</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @foreach ($report->beforeImages as $image)
        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Before Image" class="w-full h-48 object-cover rounded-xl shadow-md" />
    @endforeach
</div>

      </div>
    @endif

    <!-- Report Info -->
    <div class="bg-white border rounded-2xl shadow p-6 mb-8">
      <div class="grid md:grid-cols-2 gap-6 text-sm text-gray-700">
        <div>
          <p class="text-blue-600 font-semibold text-xs uppercase mb-1">Address</p>
          <p>{{ $report->address }}</p>
        </div>
        <div class="md:col-span-2">
          <p class="text-blue-600 font-semibold text-xs uppercase mb-1">Description</p>
          <p>{{ $report->description }}</p>
        </div>
      </div>
    </div>

    <!-- Resolved Section -->
    @if($report->status === 'Resolved')
      <div class="bg-white border rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">After Images & Remarks</h2>
        <p class="text-sm text-gray-500 mb-6">Last updated {{ $report->updated_at->format('Y-m-d') }}</p>

        <!-- After Images -->
        @if(count($report->afterImages))
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            @foreach ($report->afterImages as $image)
          <img src="{{ asset('storage/' . $image->image_path) }}" alt="After Image" class="w-full h-48 object-cover rounded-xl shadow-md" />
      @endforeach
          </div>
        @endif

        <!-- Remarks -->
        <div class="bg-gray-50 border p-4 rounded-xl">
          <p class="text-blue-600 font-semibold text-xs uppercase mb-1">Remarks</p>
          <p class="text-sm text-gray-700">{{ $report->remark ?? 'No remarks provided.' }}</p>
        </div>
      </div>
    @endif

  </main>

</body>
</html>
