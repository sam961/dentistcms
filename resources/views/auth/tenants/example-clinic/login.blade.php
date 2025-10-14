<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ $tenant->name ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-cyan-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Card -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Header with gradient -->
                    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-8 text-center">
                        <div class="inline-flex w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full items-center justify-center shadow-lg mb-4">
                            <i class="fas fa-tooth text-4xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $tenant->name ?? 'Example Clinic' }}</h1>
                        <p class="text-blue-100">Professional Dental Care</p>
                    </div>

                    <!-- Form -->
                    <div class="p-8">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Sign In</h2>
                            <p class="text-gray-600">Access your account</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="your@email.com">
                                </div>
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input id="password" type="password" name="password" required autocomplete="current-password"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="••••••••">
                                </div>
                                @error('password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" name="remember">
                                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-lg">
                                Sign In
                            </button>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-8 py-4 text-center text-sm text-gray-500 border-t">
                        <p>&copy; {{ date('Y') }} {{ $tenant->name ?? 'Example Clinic' }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
