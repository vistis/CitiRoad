@extends('layouts.app')
    <style>
        .card-shadow {
            box-shadow:
                0 0 0 1px rgba(0, 0, 0, 0.03),
                0 2px 4px 0 rgba(0, 0, 0, 0.02),
                0 4px 8px -2px rgba(0, 0, 0, 0.02),
                0 8px 16px -4px rgba(0, 0, 0, 0.02);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Navigation Bar -->
    @include('layouts.nav')


    <!-- Help Content -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- FAQ Section -->
        <div class="space-y-6">
            <!-- Question 1 -->
            <div class="glass-effect rounded-2xl p-8 card-shadow">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">"I signed-up, but I cannot make report"</h2>
                <p class="text-gray-600">Account creation must be approved by an Admin before being able to post a report. Be sure to enter valid information to ensure that will be approved to use the platform.</p>
            </div>

            <!-- Question 2 -->
            <div class="glass-effect rounded-2xl p-8 card-shadow">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Can Information be Updated After Submission?</h2>
                <p class="text-gray-600 mb-2">Information that has been submitted, be it for report or account, will not be able to be modified down the line. Please review that all information are correct and legitimate before submission.</p>
            </div>

            <!-- Contact Form -->
           <!-- <div class="glass-effect rounded-2xl p-8 card-shadow">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Still Have Question?</h2>
            <p class="text-gray-600 mb-6">Reach out to us through the emails below.</p>
            <p class="text-gray-600 mb-6">pkong3@gmail.com</p>
            <p class="text-gray-600 mb-6">pkong3@gmail.com</p>
            <p class="text-gray-600 mb-6">pkong3@gmail.com</p>
            </div> -->
        </div>
    </div>
    <script>
  // Popup auto-hide after 3 seconds
  window.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('popup');
    if (popup) {
      setTimeout(() => {
        popup.style.transition = "opacity 0.5s ease";
        popup.style.opacity = 0;
        setTimeout(() => popup.remove(), 500);
      }, 3000);
    }
  });
</script>
</body>
</html>
