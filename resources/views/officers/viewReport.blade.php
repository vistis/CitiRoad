<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CitiRoad - Report Details</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans text-gray-800 min-h-screen flex flex-col">

  <div class="flex flex-1 bg-gray-100">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-8 max-w-6xl mx-auto bg-white rounded-3xl shadow-md my-8 overflow-auto">

      <!-- Back Link -->
      <a href="javascript:history.back()" class="inline-block mb-6 text-blue-600 hover:underline text-sm">&larr; Go Back</a>

      <!-- Report Header -->
      <!-- Report Header -->
<div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
  <div>
    <h1 class="text-3xl font-bold leading-tight">{{ $report->title }}</h1>
    <p class="text-gray-500 mt-1 text-sm">
      Reported on <time datetime="{{ $report->created_at->toDateString() }}">{{ $report->created_at->format('F j, Y') }}</time> by
      <a href="{{ route('officers.showCitizenProfile', $report->citizen_id) }}" class="text-blue-700 font-semibold underline hover:text-blue-800 ml-1">
        {{ $report->citizen->first_name }} {{ $report->citizen->last_name }}
      </a>
    </p>
  </div>

  <!-- Status and Buttons side by side -->
  <div class="flex items-center space-x-4">
    <span class="inline-block bg-blue-100 text-blue-800 font-semibold px-4 py-2 rounded-xl text-sm whitespace-nowrap">
      {{ $report->status }}
    </span>

    <div id="update-buttons" class="flex space-x-3">
      @php
  $userRole = auth()->user()->role; // assuming role is a string like 'Municipality Head' or 'Municipality Deputy'
  $canProceedOrReject = in_array($report->status, ['Reviewing', 'Investigating']);
  $canReopen = in_array($report->status, ['Resolved', 'Rejected']) && $userRole === 'Municipality Head';
  $canMarkResolvedOrRejected = $report->status === 'Resolving' && $userRole === 'Municipality Head';
@endphp

@if ($canProceedOrReject)
  <!-- Proceed Button -->
  <form action="{{ route('officers.updateStatus', $report->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $nextStatus }}">
    <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-xl text-sm font-semibold">
      Proceed
    </button>
  </form>

  <!-- Reject Button -->
  <form action="{{ route('officers.updateStatus', $report->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="Rejected">
    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
      Reject
    </button>
  </form>
@elseif ($canMarkResolvedOrRejected)
  <!-- Mark as Resolved -->
  <a href="{{ route('officers.updateResolvedReport', $report->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
    Update Resolved Report
  </a>

  <!-- Reject from Resolving -->
  <form action="{{ route('officers.updateStatus', $report->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="Rejected">
    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
      Reject
    </button>
  </form>
@elseif ($canReopen)
  <!-- Reopen Button -->
  <form action="{{ route('officers.updateStatus', $report->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="Reviewing">
    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl text-sm font-semibold">
      Reopen
    </button>
  </form>
@endif

    </div>
  </div>
</div>


      <!-- Images Section -->
      <section class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Before Images</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          @foreach ($report->beforeImages as $image)
            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Before Image" class="w-full h-48 object-cover rounded-xl shadow-md" />
          @endforeach
        </div>
      </section>

      <!-- Report Details -->
      <section class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-8 shadow-inner">
        <div class="grid md:grid-cols-2 gap-8">
          <div>
            <h3 class="font-semibold text-gray-600 mb-2">Address</h3>
            <p class="text-gray-800">{{ $report->address }}</p>
          </div>
          <div class="md:col-span-2">
            <h3 class="font-semibold text-gray-600 mb-2">Description</h3>
            <p class="text-gray-800 whitespace-pre-line">{{ $report->description }}</p>
          </div>
        </div>
      </section>

      @if ($report->status === 'Resolved')
      <!-- After Images & Remarks -->
      <section>
        <h2 class="text-xl font-semibold mb-4">After Images & Remarks</h2>
        <p class="text-gray-500 mt-1 text-s mb-8" >
      Last Updated on <time datetime="{{ $report->created_at->toDateString() }}">{{ $report->updated_at->format('F j, Y') }}</time> by
      <a href="{{ route('officers.showOtherOfficers', $report->updated_by) }}" class="text-blue-700 font-semibold underline hover:text-blue-800 ml-1">
        {{ $report->officer->first_name ?? '' }} {{ $report->officer->last_name ?? '' }}

      </a>
    </p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
          @foreach ($report->afterImages as $image)
          <img src="{{ asset('storage/' . $image->image_path) }}" alt="After Image" class="w-full h-48 object-cover rounded-xl shadow-md" />
      @endforeach
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-inner">
          <h3 class="font-semibold text-gray-600 mb-2">Remarks</h3>
          <p class="text-gray-800 whitespace-pre-line">{{ $report->remark ?? 'No remarks provided.' }}</p>
        </div>
      </section>
      @endif

    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const statusFlow = @json($statusFlow);
      const currentIndex = {{ $currentIndex }};

      const normalUpdateForm = document.getElementById('normal-update-form');
      const resolvedUpdateBtn = document.getElementById('resolved-update-btn');

      // Reset visibility
      normalUpdateForm.classList.add('hidden');
      resolvedUpdateBtn.classList.add('hidden');

      if (currentIndex === statusFlow.indexOf('Resolved') - 1) {
        resolvedUpdateBtn.classList.remove('hidden');
      } else if (currentIndex < statusFlow.indexOf('Resolved') - 1) {
        normalUpdateForm.classList.remove('hidden');
      }
    });
  </script>

</body>
</html>
