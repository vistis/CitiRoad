<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CitiRoad Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen text-gray-800">

  <!-- Navbar -->
  @include('layouts.nav')

  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Welcome Section -->
    <section class="text-center mb-10">
      <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth('citizen')->user()->first_name }} {{ auth('citizen')->user()->last_name }}</h1>
      <p class="text-gray-600 mb-6">Have something to report?</p>

      @if (auth('citizen')->user()->status !== 'Pending' && auth('citizen')->user()->status !== 'Restricted')
        <a href="report" class="inline-flex items-center px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-full shadow-md transition">
          Make a New Report
        </a>
      @else
        <button disabled class="inline-flex items-center px-6 py-3 bg-gray-400 text-white rounded-full cursor-not-allowed" title="You cannot create reports while your account status is Pending or Restricted.">
          Make a New Report
        </button>
      @endif
    </section>

    <!-- Reports Section -->
    <section class="bg-white rounded-2xl shadow-xl p-6 space-y-6 border border-gray-200">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
          <h2 class="text-xl font-semibold">Previous Reports</h2>
          <span class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded-full">
            {{ $reportCount }} {{ Str::plural('Report', $reportCount) }}
          </span>
        </div>
        <!-- Search + Filters -->
        <div class="flex gap-3 flex-wrap">
          <div class="relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </span>
            <input id="reportSearch" type="text" placeholder="Search reports..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none w-64" />
          </div>
        </div>
      </div>

      <!-- Reports List -->
      <div class="space-y-4">
        @if ($reports->isEmpty())
          <div class="text-center text-gray-500 py-6">
            <p class="text-lg font-medium">You have no previous reports yet.</p>
            <p class="text-sm">Click "Make a New Report" above to get started.</p>
          </div>
        @else
          @foreach ($reports as $report)
            <a href="{{ route('citizens.report', $report->id) }}">
              <div class="report-item mb-3 flex justify-between items-center p-4 bg-white border border-gray-200 rounded-xl hover:border-blue-400 transition" data-title="{{ strtolower($report->title) }}">
                <div>
                  <h3 class="text-lg font-semibold">{{ $report->title }}</h3>
                  <p class="text-sm text-gray-500">{{ $report->address }}, {{ $report->province->name }} • {{ $report->created_at->format('Y-m-d') }}</p>
                </div>
                <div>
                  <span class="report-status text-sm font-semibold px-3 py-1 rounded-full" data-status="{{ $report->status }}">
                    {{ $report->status }}
                  </span>
                </div>
              </div>
            </a>
          @endforeach
        @endif
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.getElementById('reportSearch');
      const reports = document.querySelectorAll('.report-item');

      searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        reports.forEach(report => {
          const title = (report.dataset.title || '').toLowerCase();
          report.closest('a').style.display = title.startsWith(query) ? '' : 'none';
        });
      });

      const statusColorMap = {
        "Reviewing": { bg: "#FEF3C7", color: "#92400E" },
        "Investigating": { bg: "#DBEAFE", color: "#1D4ED8" },
        "Rejected": { bg: "#FECACA", color: "#B91C1C" },
        "Resolving": { bg: "#E9D5FF", color: "#7E22CE" },
        "Resolved": { bg: "#D1FAE5", color: "#065F46" }
      };

      document.querySelectorAll('.report-status').forEach(el => {
        const status = el.dataset.status;
        const colors = statusColorMap[status] || { bg: "#F3F4F6", color: "#374151" };
        el.style.backgroundColor = colors.bg;
        el.style.color = colors.color;
      });
    });
  </script>
</body>
</html>
