<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CitiRoad - Admin Login</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            min-height: 100vh;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (min-width: 640px) {
            .login-container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .login-container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        .login-form-container {
            max-width: 28rem;
            width: 100%;
            margin-top: 3rem;
            margin-bottom: 3rem;
        }

        .header {
            text-align: center;
        }

        .title {
            font-size: 2.25rem;
            line-height: 2.5rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 0.75rem;
        }

        .subtitle {
            font-size: 2.25rem;
            line-height: 2.5rem;
            font-weight: 700;
            color: #000;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            color: #000;
            margin-bottom: 0.5rem;
        }

        .form-input {
            appearance: none;
            position: relative;
            display: block;
            width: 100%;
            padding: 0.75rem;
            padding-right: 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: #fff;
            color: #111827;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .form-input:focus {
            outline: none;
            ring-width: 2px;
            ring-color: #3b82f6;
            border-color: #3b82f6;
            z-index: 10;
        }

        .password-container {
            position: relative;
        }

        .eye-button {
            position: absolute;
            top: 0;
            right: 0;
            padding-right: 0.75rem;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .eye-icon {
            color: #9ca3af;
            height: 1.25rem;
            width: 1.25rem;
        }

        .eye-button:hover .eye-icon {
            color: #4b5563;
        }

        .eye-button:focus {
            outline: none;
        }

        .error-message {
            margin-top: 0.25rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: #dc2626;
        }

        .submit-button {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 0.75rem 1rem;
            border: 1px solid transparent;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            border-radius: 0.375rem;
            color: #fff;
            background-color: #3b82f6;
            transition-property: background-color;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        .submit-button:hover {
            background-color: #2563eb;
        }

        .submit-button:focus {
            outline: none;
            ring-width: 2px;
            ring-offset: 2px;
            ring-color: #3b82f6;
        }

        .form-space {
            margin-top: 1.5rem;
        }

        .pt-4 {
            padding-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form-container">
            <!-- Header -->
            <div class="header">
                <h1 class="title">CitiRoad</h1>
                <h2 class="subtitle">Admin Log In</h2>
            </div>

            <!-- Login Form -->
            <div class="form-space">
                <form class="form-group" method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    <!-- Admin ID Field -->
                    <div class="form-group">
                        <label for="id" class="form-label">
                            Admin ID
                        </label>
                        <input
                            id="id"
                            name="id"
                            type="text"
                            required
                            class="form-input"
                            placeholder="XXXX-XXXX-XXXX"
                            value="{{ old('id') }}"
                        >
                        @error('id')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password
                        </label>
                        <div class="password-container">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="form-input"
                                placeholder="•••••••••"
                            >
                            <!-- Eye Icon -->
                            <div class="eye-button">
                                <button type="button" onclick="togglePassword()" class="eye-button">
                                    <svg id="eye-icon" class="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="remember" class="form-label">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                class="mr-2 leading-tight"
                            >
                            Remember Me
                        </label>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="submit-button"
                        >
                            Log In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Password Toggle -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L5.636 5.636m4.242 4.242L15.122 15.12M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }
    </script>
</body>
</html>
