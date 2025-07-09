@extends('layouts.app')
@include('layouts.nav')
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
       <!-- Hero Section -->
    <div class="hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl md:text-6xl">
                    Welcome to <span class="text-blue-600">CitiRoad</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    A Citizen Engagement Platform for Transportation Infrastructure Integrity
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="px-8 py-3 rounded-full text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200 font-medium hover:shadow-lg">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-24 bg-white/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Transportation Infrastructure Integrity</h2>
                <p class="mt-4 text-gray-500 max-w-2xl mx-auto">
                    CitiRoad provides a simple and accessible platform that empowers citizens to conveniently voice their concerns over the integrity, state, and quality of transportation infrastructure.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl card-shadow border border-gray-200/50 p-8">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Infrastructure Monitoring</h3>
                    <p class="text-gray-500">Report and track the condition of roads, bridges, and other transportation infrastructure in your area.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl card-shadow border border-gray-200/50 p-8">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Citizen Engagement</h3>
                    <p class="text-gray-500">Participate actively in improving your community by reporting issues and tracking their resolution status.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl card-shadow border border-gray-200/50 p-8">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Issue Tracking</h3>
                    <p class="text-gray-500">Get real-time updates on reported infrastructure issues and their maintenance progress.</p>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-16 text-center">
                <a href="{{ route('citizens.dashboard') }}" class="inline-flex items-center px-8 py-3 rounded-full text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200 font-medium hover:shadow-lg">
                    Start Reporting Issues
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- <footer class="bg-white/80 border-t border-gray-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">About CitiRoad</h3>
                    <p class="text-gray-500">Your trusted platform for managing citizen information and services in Cambodia.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">About Us</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Contact</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-500">Email: support@citiroad.com</li>
                        <li class="text-gray-500">Phone: (855) 123-456-789</li>
                        <li class="text-gray-500">Address: Phnom Penh, Cambodia</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200/80 text-center">
                <p class="text-gray-500">&copy; 2025 CitiRoad. All rights reserved.</p>
            </div>
        </div>
    </footer> -->
</body>
</html>
