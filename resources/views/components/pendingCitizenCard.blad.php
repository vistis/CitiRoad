@props(['citizen'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center justify-between">
        {{-- CORRECTED ROUTE NAME: citizens.show -> citizens.profile --}}
       <a href="{{ route('citizens.show', $citizen->id) }}" class="flex items-center space-x-4 flex-grow">
            <div class="relative">
                <img src="{{ $citizen->image }}"
                     alt="Citizen Profile"
                     class="w-12 h-12 rounded-full object-cover">
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900">{{ $citizen->name }}</h3>
                <p class="text-sm text-gray-500">ID: {{ $citizen->id }}</p>
            </div>
        </a>

        <div class="flex items-center space-x-2">
            <button class="flex items-center justify-center w-10 h-10 bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition-colors duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>

            <button class="flex items-center justify-center w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition-colors duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
