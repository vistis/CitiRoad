<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CitiRoad</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">


  <div class="flex min-h-screen">
     @include('components.sideBar', ['user' => (object)['name' => 'Demo']])
    <main class="flex-1 p-6">
      <div class="p-6">

          <!-- Report -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Reports</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 rounded-2xl overflow-hidden shadow-md shadow">
            <div class="bg-white p-6 text-center border-r-2 border-gray-200">
                <div class="flex flex-col items-center">
                    <div class="bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-gray-600 px-4 py-2">{{ $reports['total'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Total</h3>
                </div>
            </div>

            <div class="bg-white p-6 text-center border-r-2 border-gray-200">
                <div class="flex flex-col items-center">
                    <div class="bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-orange-600 px-4 py-2">{{ $reports['active'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Active</h3>
                </div>
            </div>

            <div class="bg-white p-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600 px-4 py-2">{{ $reports['resolved'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Resolved</h3>
                </div>
            </div>
          </div>
        </div>

        <!-- Citizen -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Citizens</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 rounded-2xl overflow-hidden shadow-md shadow">
            <div class="bg-white p-6 text-center border-r-2 border-gray-200">
                <div class="flex flex-col items-center">
                    <div class="bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-gray-600 px-4 py-2">{{ $citizens['total'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Total</h3>
                </div>
            </div>

            <div class="bg-white p-6 text-center border-r-2 border-gray-200">
                <div class="flex flex-col items-center">
                    <div class="bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-orange-600 px-4 py-2">{{ $citizens['pending'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Pending</h3>
                </div>
            </div>

            <div class="bg-white p-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600 px-4 py-2">{{ $citizens['approved'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Approved</h3>
                </div>
            </div>
          </div>
        </div>

        <!-- Officer -->
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Officers</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 rounded-2xl overflow-hidden shadow-md shadow">
            <div class="bg-white p-6 text-center border-r-2 border-gray-200">
                <div class="flex flex-col items-center">
                    <div class="bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-gray-600 px-4 py-2">{{ $officers['total'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Total</h3>
                </div>
            </div>

            <div class="bg-white p-6 text-center border-r-2 border-gray-200">
                <div class="flex flex-col items-center">
                    <div class="bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-gray-600 px-4 py-2">{{ $officers['heads'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Heads</h3>
                </div>
            </div>

            <div class="bg-white p-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-gray-600 px-4 py-2">{{ $officers['deputies'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Deputies</h3>
                </div>
            </div>
          </div>
        </div>


      </div>
    </main>
  </div>

</body>
</html>
