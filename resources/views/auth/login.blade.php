@extends('layouts.app')

<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    .input-transition {
        transition: all 0.3s ease;
    }
    .login-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex flex-col">
    @include('layouts.nav')

    <div class="flex flex-1 items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
                    <p class="text-gray-600">Please enter your details to sign in</p>
                </div>

                <!-- Toggle -->
                <div class="flex justify-center gap-4 mb-4">
                    <button type="button" onclick="showEmail()" id="emailBtn"
                        class="toggle-btn px-4 py-2 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700">
                        Use Email
                    </button>
                    <button type="button" onclick="showPhone()" id="phoneBtn"
                        class="toggle-btn px-4 py-2 rounded-xl bg-gray-200 text-gray-800 font-medium hover:bg-gray-300">
                        Use Phone
                    </button>
                </div>

                <!-- Error Message -->
                @if ($errors->has('email') || $errors->has('phone'))
                    <div id="loginError"
                        class="mb-4 p-3 rounded-xl text-sm text-red-700 bg-red-100 border border-red-300 transition-opacity duration-500">
                        {{ $errors->first('email') ?: $errors->first('phone') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('loginC') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div id="emailField">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email"
                            placeholder="Enter your email"
                            class="input-transition mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Phone Field -->
                    <div id="phoneField" class="hidden">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" id="phone"
                            placeholder="Enter your phone number"
                            class="input-transition mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password"
                            placeholder="Enter your password"
                            class="input-transition mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="login-button w-full py-3 px-4 rounded-xl text-white bg-blue-600 hover:bg-blue-700 font-medium transition-transform duration-200">
                        Sign in to your account
                    </button>

                    <!-- Register Prompt -->
                    <div class="text-center text-sm text-gray-600 mt-4">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                            Create an account
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>

<!-- Toggle Script -->
<script>
    function showEmail() {
        document.getElementById('emailField').classList.remove('hidden');
        document.getElementById('phoneField').classList.add('hidden');
        toggleBtnState('email');
    }

    function showPhone() {
        document.getElementById('phoneField').classList.remove('hidden');
        document.getElementById('emailField').classList.add('hidden');
        toggleBtnState('phone');
    }

    function toggleBtnState(active) {
        const emailBtn = document.getElementById('emailBtn');
        const phoneBtn = document.getElementById('phoneBtn');

        if (active === 'email') {
            emailBtn.classList.add('bg-blue-600', 'text-white');
            emailBtn.classList.remove('bg-gray-200', 'text-gray-800');
            phoneBtn.classList.remove('bg-blue-600', 'text-white');
            phoneBtn.classList.add('bg-gray-200', 'text-gray-800');
        } else {
            phoneBtn.classList.add('bg-blue-600', 'text-white');
            phoneBtn.classList.remove('bg-gray-200', 'text-gray-800');
            emailBtn.classList.remove('bg-blue-600', 'text-white');
            emailBtn.classList.add('bg-gray-200', 'text-gray-800');
        }
    }

    // Fade-in and auto-hide error box
    document.addEventListener('DOMContentLoaded', () => {
        const errorBox = document.getElementById('loginError');
        if (errorBox) {
            setTimeout(() => {
                errorBox.classList.add('opacity-100');
            }, 100);
            setTimeout(() => {
                errorBox.classList.remove('opacity-100');
                errorBox.classList.add('opacity-0');
            }, 5000);
        }
    });
</script>

