<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CitiRoad - Update Resolved Report</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
    }
  </style>
</head>
<body class="flex min-h-screen bg-gray-100">

  <!-- Sidebar -->
@include('layouts.sidebar')

  <!-- Main Content -->
  <main class="flex-1 p-8 max-w-4xl mx-auto">
    <h1 class="text-3xl font-semibold mb-6 text-gray-800">Update Resolved Report</h1>

    <form action="{{ route('officers.postResolvedUpdate', $report->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-8 space-y-6 border border-gray-200">
      @csrf
      @method('PATCH')

      <div>
        <label for="remark" class="block text-sm font-medium text-gray-700 mb-2">Remark</label>
        <textarea id="remark" name="remark" rows="5" required
          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
          placeholder="Leave your remarks about the resolution...">{{ old('remark') }}</textarea>
        @error('remark')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="after_images" class="block text-sm font-medium text-gray-700 mb-2">After Fix Pictures</label>
        <input type="file" id="after_images" name="after_images[]" multiple accept="image/*"
          class="block w-full text-sm text-gray-600
                 file:mr-4 file:py-2 file:px-4
                 file:rounded-xl file:border-0
                 file:text-sm file:font-semibold
                 file:bg-blue-50 file:text-blue-700
                 hover:file:bg-blue-100"
          required>
        @error('after_images.*')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
        Submit Update
      </button>
    </form>
  </main>

</body>
</html>
