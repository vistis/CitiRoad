<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CitiRoad - All Reports</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col">

  <div class="flex flex-1 bg-gray-100">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-8 max-w-7xl mx-auto w-full overflow-y-auto">

      <!-- Header -->
      <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
        <h1 class="text-2xl font-extrabold text-blue-700">
          All Reports
          @if($province)
            In <span class="capitalize">{{ $province->name }}</span>
          @else
            - Nationwide
          @endif
        </h1>

        <div class="flex items-center space-x-3">

          <div class="relative text-gray-500 focus-within:text-blue-600">
            <input
              type="search"
              id="reportSearch"
              placeholder="Search reports..."
              class="block w-64 pl-10 pr-4 py-2 rounded-lg bg-white border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            />
            <svg
              class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
              fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
            </svg>
          </div>
        </div>
      </header>

      <!-- Reports List -->
      <section>
        @if($reports->isEmpty())
          <p class="mt-12 text-center text-gray-500 italic">No reports found.</p>
        @else
          <div class="space-y-6">
            @foreach($reports as $report)
              <a href="{{ route('officers.report', $report->id) }}" class="block">
                <article
                  class="flex items-center justify-between p-4 bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition cursor-pointer report-item"
                  data-title="{{ strtolower($report->title) }}"
                >
                  <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $report->title }}</h3>
                    <p class="mt-1 text-sm text-gray-500 truncate">
                      {{ $report->created_at->format('Y-m-d') }} · {{ $report->address }}
                    </p>
                  </div>

                  <span
                    class="ml-6 px-3 py-1 rounded-full text-sm font-semibold"
                    data-status="{{ $report->status }}"
                  >
                    {{ $report->status }}
                  </span>

                  <div class="ml-6 flex flex-col items-center space-y-2 text-gray-400">
                    <button aria-label="Mark as completed" class="hover:text-green-600 transition">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    </button>
                    <button aria-label="Add report" class="hover:text-blue-600 transition">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                    </button>
                  </div>
                </article>
              </a>
            @endforeach
          </div>
        @endif
      </section>

    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Report search filtering
      const searchInput = document.getElementById('reportSearch');
      const reports = document.querySelectorAll('.report-item');

      searchInput?.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        reports.forEach(report => {
          const title = report.dataset.title.toLowerCase();
          report.parentElement.style.display = title.includes(query) ? '' : 'none';
        });
      });

      // Status badge coloring
      const statusColorMap = {
        "Reviewing": { bg: "#FEF3C7", color: "#92400E" },
        "Investigating": { bg: "#DBEAFE", color: "#1D4ED8" },
        "Rejected": { bg: "#FECACA", color: "#B91C1C" },
        "Resolving": { bg: "#E9D5FF", color: "#7E22CE" },
        "Resolved": { bg: "#D1FAE5", color: "#065F46" }
      };

      document.querySelectorAll('.report-status, [data-status]').forEach(el => {
        const status = el.dataset.status;
        const colors = statusColorMap[status] || { bg: "#F3F4F6", color: "#374151" };
        el.style.backgroundColor = colors.bg;
        el.style.color = colors.color;
        el.style.fontWeight = "700";
        el.style.display = "inline-block";
      });
    });
  </script>
</body>
</html>
