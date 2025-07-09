<!-- Navigation Bar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200/80 nav-shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex-shrink-0 flex items-center">
                    @auth('citizen')
                    <a href="{{ route('citizens.dashboard') }}" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                        CitiRoad 
                    </a>
                    @else
                    <a href="{{ route('landing') }}" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                        CitiRoad 
                    </a>
                    @endauth
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('aboutus') }}" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">About Us</a>
                    <a href="{{ route('help') }}" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">Help</a>
                    @auth('citizen')
                    <a href="{{ route('citizens.profile', auth('citizen')->user()->id ) }}" class="px-5 py-2 rounded-full border border-gray-300/80 text-gray-700 hover:bg-gray-50 hover:border-gray-400/80 transition-all duration-200 font-medium hover:shadow-sm">
                        {{ auth('citizen')->user()->first_name }} {{ auth('citizen')->user()->last_name }}
                    </a>
                     @else
                     <a href="{{ route('loginC') }}" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">Log In</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>