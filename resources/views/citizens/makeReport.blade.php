<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CitiRoad - Submit a Report</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .input-field:focus {
      transform: translateY(-1px);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
  
  <!-- Navbar -->
  @include('layouts.nav')

  <!-- Main Content -->
  <main class="py-10 px-4 md:px-6 lg:px-8 flex justify-center items-start min-h-[calc(100vh-4rem)]">
    <div class="w-full max-w-3xl bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-200 p-8">

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-1">Submit a Report</h1>
      </div>

      <!-- Form -->
      <form method="POST" action="{{ route('citizens.storeReport') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Title & Province -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Report Title</label>
            <input type="text" id="title" name="title" required placeholder="e.g., Pothole on Main St."
              class="input-field mt-2 w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500" />
          </div>
          <div>
            <label for="province_id" class="block text-sm font-medium text-gray-700">Province</label>
            <select id="province_id" name="province_id" required
              class="input-field mt-2 w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500">
              <option value="">Select a province</option>
              @foreach($provinces as $province)
              <option value="{{ $province->id }}">{{ $province->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Address -->
        <div>
          <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
          <input type="text" id="address" name="address" required placeholder="Enter the location of the issue"
            class="input-field mt-2 w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500" />
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea id="description" name="description" rows="5" required
            placeholder="Describe the issue..."
            class="input-field mt-2 w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 resize-none"></textarea>
        </div>

        <!-- Picture Upload -->
        <div>
          <label for="picture" class="block text-sm font-medium text-gray-700">Upload Photos</label>
          <input type="file" id="picture" name="picture[]" accept="image/*" multiple required
            class="mt-2 w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200" />
          <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, GIF. You can select multiple images.</p>

          <!-- Preview -->
          <div id="preview" class="mt-4 flex flex-wrap gap-3"></div>
        </div>

        <!-- Submit -->
        <div class="pt-4">
          <button type="submit"
            class="w-full py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md">
            Submit Report
          </button>
        </div>
      </form>
    </div>
  </main>

  <!-- Scripts -->
  <script>
    const preview = document.getElementById('preview');
    document.getElementById('picture').addEventListener('change', function (event) {
      preview.innerHTML = '';
      const files = event.target.files;
      Array.from(files).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'h-24 w-24 object-cover rounded-lg border border-gray-300 shadow';
          preview.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    });

    document.querySelector('form').addEventListener('submit', function (event) {
      let hasError = false;

      const title = document.getElementById('title').value.trim();
      const province = document.getElementById('province_id').value;
      const address = document.getElementById('address').value.trim();
      const description = document.getElementById('description').value.trim();
      const files = document.getElementById('picture').files;

      document.querySelectorAll('.error-message').forEach(el => el.remove());

      function showError(id, message) {
        const input = document.getElementById(id);
        const error = document.createElement('p');
        error.className = 'error-message text-sm text-red-500 mt-1';
        error.innerText = message;
        input.closest('div').appendChild(error);
      }

      if (!title) { showError('title', 'Title is required.'); hasError = true; }
      if (!province) { showError('province_id', 'Select a province.'); hasError = true; }
      if (!address) { showError('address', 'Address is required.'); hasError = true; }
      if (!description) { showError('description', 'Description is required.'); hasError = true; }
      if (files.length === 0) {
        showError('picture', 'At least one image is required.'); hasError = true;
      } else {
        Array.from(files).forEach(file => {
          const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
          if (!validTypes.includes(file.type)) {
            showError('picture', `Invalid file type: ${file.name}`);
            hasError = true;
          }
        });
      }

      if (hasError) {
        event.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    });
  </script>
</body>
</html>
