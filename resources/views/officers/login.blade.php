<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Officer Login - CitiRoad</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

  <!-- Header -->
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
        CitiRoad
      </div>
      <span class="text-sm text-gray-500 hidden sm:inline">Municipality Dashboard</span>
    </div>
  </header>

  

  <!-- Login Form Container -->
  <div class="flex items-center justify-center min-h-[calc(100vh-5rem)] px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-md border border-gray-200">
      <h2 class="text-2xl font-bold text-center text-blue-700 mb-1">Officer Login</h2>
      <p class="text-sm text-center text-gray-500 mb-6">Access your municipal dashboard</p>

      @if (session('error'))
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded mb-4 text-sm">
          {{ session('error') }}
        </div>
      @endif

      <form method="POST" action="{{ route('officer.login') }}" class="space-y-5">
        @csrf

        <div>
          <label class="block text-sm font-medium mb-1 text-gray-700">Email</label>
          <input type="email" name="email" required autofocus
                 class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1 text-gray-700">Password</label>
          <input type="password" name="password" required
                 class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none"/>
        </div>

        <button type="submit"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 rounded-lg transition-colors">
          Log In
        </button>
      </form>
    </div>
  </div>
</body>
</html>
