@props(['citizen'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200 @if($citizen->status === 'Deactivated') opacity-75 @endif">
    <div class="flex items-center justify-between">
        {{-- Link to citizen profile/details --}}
        <a href="{{ route('citizens.show', $citizen->id) }}" class="flex items-center space-x-4">
            <div class="relative">
                <img src="{{ $citizen->image }}"
                     alt="Citizen Profile"
                     class="w-12 h-12 rounded-full object-cover @if($citizen->status === 'Deactivated') grayscale @endif">
            </div>

            <div>
                <div class="flex items-center space-x-2">
                    <h3 class="text-lg font-medium @if($citizen->status === 'Deactivated') text-gray-600 @else text-gray-900 @endif">{{ $citizen->name }}</h3>
                    @if($citizen->status === 'Approved')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Approved</span>
                    @elseif($citizen->status === 'Deactivated')
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Deactivated</span>
                    @elseif($citizen->status === 'Restricted')
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Restricted</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500">ID: {{ $citizen->id }}</p>
            </div>
        </a>

        {{-- The Action Buttons Section --}}
        <div class="flex items-center space-x-2">
            @if($citizen->status === 'Approved')
                <button class="flex items-center justify-center w-10 h-10 bg-yellow-100 hover:bg-yellow-200 text-yellow-600 rounded-full transition-colors duration-200" title="Restrict">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <button class="flex items-center justify-center w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition-colors duration-200" title="Deactivate">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            @elseif($citizen->status === 'Deactivated')
                <button class="flex items-center justify-center w-10 h-10 bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition-colors duration-200" title="Activate">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"></path>
                    </svg>
                </button>
            @elseif($citizen->status === 'Restricted')
                <button class="flex items-center justify-center w-10 h-10 bg-green-100 hover:bg-green-200 text-green-600 rounded-full transition-colors duration-200" title="Approve">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>
</div>
