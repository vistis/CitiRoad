<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CitiRoad - All Officers</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans min-h-screen flex flex-col">

  <div class="flex flex-1 bg-gray-100">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <main class="flex-1 p-8 max-w-5xl mx-auto w-full flex flex-col">

      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <h1 class="text-2xl font-extrabold text-blue-700">
            All Officers in {{ auth('officer')->user()->province->name }}
        </h1>

        <div class="relative w-full max-w-sm">
          <input
            type="text"
            id="officerSearch"
            placeholder="Search officers..."
            class="w-full pl-10 pr-4 py-2 rounded-lg bg-white/70 border border-gray-300 backdrop-blur-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
          <svg
            class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
          </svg>
        </div>
      </div>

      <!-- Officers List -->
      <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @if($officers->isEmpty())
          <p class="col-span-full text-center text-gray-500 italic mt-12">No officers found.</p>
        @else
          @foreach($officers as $officer)
          <a href="{{ route('officers.showOtherOfficers', $officer->id) }}" class="group">
            <article
              class="p-5 rounded-2xl bg-white/80 backdrop-blur-md border border-gray-200 shadow-sm flex flex-col justify-between hover:shadow-lg transition cursor-pointer h-full"
              data-name="{{ strtolower($officer->first_name . ' ' . $officer->last_name) }}"
            >
              <div>
                <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700 truncate">
                  {{ $officer->first_name }} {{ $officer->last_name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 truncate">{{ $officer->email }}</p>
              </div>
              <div class="mt-4 text-sm text-gray-600 font-medium">
                {{ $officer->role }}
              </div>
            </article>
          </a>
          @endforeach
        @endif
      </section>

    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const searchInput = document.getElementById('officerSearch');
      const officers = document.querySelectorAll('article[data-name]');

      searchInput?.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        officers.forEach(officer => {
          const name = officer.dataset.name || '';
          officer.parentElement.style.display = name.includes(query) ? '' : 'none';
        });
      });
    });
  </script>

</body>
</html>
