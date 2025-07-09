@extends('layouts.app')
<style> body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .nav-shadow {
            box-shadow:
                0 1px 2px 0 rgba(0, 0, 0, 0.03),
                0 1px 6px -1px rgba(0, 0, 0, 0.02),
                0 2px 4px 0 rgba(0, 0, 0, 0.02);
        }
        .hero-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .card-shadow {
            box-shadow:
                0 0 0 1px rgba(0, 0, 0, 0.03),
                0 2px 4px 0 rgba(0, 0, 0, 0.02),
                0 4px 8px -2px rgba(0, 0, 0, 0.02),
                0 8px 16px -4px rgba(0, 0, 0, 0.02),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        }
        .input-field {
            transition: all 0.3s ease;
        }
        .input-field:focus {
            transform: translateY(-1px);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.05);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        </style>
<!-- Navigation Bar -->
@include('layouts.nav')
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">

    <section class="bg-white/80 backdrop-blur-md px-6 py-12 border-t border-gray-200/80">
  <div class="max-w-6xl mx-auto text-gray-800">
    <!-- About Section -->
    <div class="mb-16">
      <h2 class="text-3xl font-bold text-gray-900 mb-4">About This Platform</h2>
      <p class="text-gray-600 mb-4">
        As a developing nation, Cambodia faces significant challenges with its infrastructure, especially roads and streets, which are often either non-existent or in severe disrepair due to damage, negligence, and shortcuts taken during construction.
        Maintaining and monitoring these infrastructures is a daunting task for the government, hindered by limited funding and a shortage of official personnel.
      </p>
      <p class="text-gray-600">
        Through this platform, users can easily report damaged roads or streets, highlight poor construction work, request new infrastructure projects,
         and more — making it simpler for the government to respond and for communities to thrive.
      </p>
    </div>

    <!-- Developers Section -->
    <div>
      <h2 class="text-2xl font-bold text-gray-900 mb-10">Developers</h2>
      <div class="flex flex-wrap gap-12 justify-center items-center">
        <!-- Developer 1 -->
        <div class="flex flex-col items-center">
          <div class="w-20 h-20 rounded-full bg-gray-800 flex items-center justify-center text-white text-3xl mb-4">
            👤
          </div>
          <p class="font-semibold text-gray-800">Visoth Kim</p>
          <p class="text-gray-500 text-sm">API</p>
        </div>

        <!-- Developer 2 -->
        <div class="flex flex-col items-center">
          <div class="w-20 h-20 rounded-full bg-gray-800 flex items-center justify-center text-white text-3xl mb-4">
            👤
          </div>
          <p class="font-semibold text-gray-800">Heang Piv Phour</p>
          <p class="text-gray-500 text-sm">Backend</p>
        </div>

        <!-- Developer 3 -->
        <div class="flex flex-col items-center">
          <div class="w-20 h-20 rounded-full bg-gray-800 flex items-center justify-center text-white text-3xl mb-4">
            👤
          </div>
          <p class="font-semibold text-gray-800">Puthiroth Kong</p>
          <p class="text-gray-500 text-sm">Frontend</p>
        </div>
      </div>
    </div>
  </div>
</section>

    <!-- Footer -->
    <!-- @include('layouts.footer') -->
</body>
</html>
