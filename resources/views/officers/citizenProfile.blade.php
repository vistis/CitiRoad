<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CitiRoad - Citizen Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800 font-sans">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  @include('layouts.sidebar')

  <!-- Main Content -->
  <main class="flex-1 p-8">
    <a href="javascript:history.back()" class="text-sm text-blue-600 hover:underline">&larr; Back</a>

    <!-- Profile Header -->
    <section class="mt-6 flex items-center gap-6">
      <img src="{{ asset('storage/' . $citizen->profile_picture_path) }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-blue-100 shadow-sm"/>
      <div>
        <h1 class="text-3xl font-bold tracking-tight">{{$citizen->first_name}} {{$citizen->last_name}}</h1>
        <span class="mt-2 inline-block text-sm font-semibold px-3 py-1 rounded-lg shadow" id="citizen-status" data-status="{{ $citizen->status }}">
          {{$citizen->status}}
        </span>
      </div>
    </section>

    <!-- Info Cards -->
    <section class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 bg-white/40 backdrop-blur-sm p-6 rounded-2xl shadow border border-gray-200">
      <div>
        <p class="text-gray-500 text-sm">National ID</p>
        <p class="text-base font-medium">{{$citizen->id}}</p>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Date of Birth</p>
        <p class="text-base font-medium">{{$citizen->date_of_birth -> format('Y-m-d')}}</p>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Gender</p>
        <p class="text-base font-medium">{{$citizen->gender}}</p>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Email</p>
        <p class="text-base font-medium">{{$citizen->email}}</p>
      </div>
      <div>
        <p class="text-gray-500 text-sm">Phone</p>
        <p class="text-base font-medium">{{$citizen->phone_number}}</p>
      </div>
      <div class="md:col-span-2">
        <p class="text-gray-500 text-sm">Address</p>
        <p class="text-base font-medium">{{$citizen->address}}</p>
      </div>
    </section>

    <!-- Reports Section -->
    <section class="mt-12">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">All Reports</h2>
        <div class="flex items-center gap-2">
          <div class="relative">
            <input type="text" id="reportSearch" placeholder="Search..."
              class="pl-10 pr-4 py-2 bg-white/60 border border-gray-300 rounded-xl focus:ring-blue-500 focus:outline-none backdrop-blur-md" />
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
          </div>
          <button class="text-sm hover:underline text-gray-600">Filter</button>
          <button class="text-sm hover:underline text-gray-600">Sort</button>
        </div>
      </div>

      <div class="space-y-4">
        @if ($reports->isEmpty())
          <div class="text-center text-gray-600 py-8">
            <p class="text-lg font-medium">No previous reports available.</p>
          </div>
        @else
          @foreach ($reports as $report)
            <a href="{{route('officers.report', $report->id)}}" class="block">
              <div class="report-item flex justify-between gap-4 items-start p-4 bg-white/60 border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition backdrop-blur-sm" data-title="{{ strtolower($report->title) }}">
                <div class="flex-1">
                  <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-base">{{$report -> title}}</h3>
                    <span class="report-status text-xs font-semibold px-3 py-1 rounded" data-status="{{ $report->status }}">
                      {{ $report->status }}
                    </span>
                  </div>
                  <p class="text-sm text-gray-500 mt-1">{{$report -> address}}</p>
                  <p class="text-xs text-gray-400">{{$report -> created_at -> format('Y-m-d')}}</p>
                </div>
                <div class="flex flex-col justify-between items-end space-y-2">
                  <svg class="w-5 h-5 text-gray-400 hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <svg class="w-5 h-5 text-gray-400 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </div>
              </div>
            </a>
          @endforeach
        @endif
      </div>
    </section>
  </main>
</div>

<!-- JS -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const statusEl = document.getElementById("citizen-status");
  if (statusEl) {
    const status = statusEl.dataset.status;
    const colorMap = {
      Approved: ["bg-green-100", "text-green-800"],
      Pending: ["bg-yellow-100", "text-yellow-800"],
      Restricted: ["bg-red-100", "text-red-800"],
      Deactivated: ["bg-red-100", "text-red-800"]
    };
    const classes = colorMap[status] || ["bg-gray-200", "text-gray-800"];
    statusEl.classList.add(...classes);
  }

  const searchInput = document.getElementById('reportSearch');
  const reports = document.querySelectorAll('.report-item');

  searchInput?.addEventListener('input', function () {
    const query = this.value.toLowerCase();
    reports.forEach(report => {
      const title = (report.dataset.title || '').toLowerCase();
      report.closest('a').style.display = title.includes(query) ? '' : 'none';
    });
  });

  const reportStatusElements = document.querySelectorAll(".report-status");
  const statusColorMap = {
    Reviewing: ["bg-yellow-100", "text-yellow-800"],
    Investigating: ["bg-blue-100", "text-blue-800"],
    Rejected: ["bg-red-100", "text-red-800"],
    Resolving: ["bg-purple-100", "text-purple-800"],
    Resolved: ["bg-green-100", "text-green-800"]
  };
  reportStatusElements.forEach(el => {
    const status = el.dataset.status;
    const [bg, text] = statusColorMap[status] || ["bg-gray-100", "text-gray-800"];
    el.classList.add(bg, text);
  });
});
</script>
</body>
</html>
