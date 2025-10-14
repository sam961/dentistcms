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
        <div class="min-h-screen flex">
            <!-- Left Side - Branding with Beauty Theme -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-pink-400 via-rose-400 to-purple-500 relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute top-20 left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col justify-center items-center w-full p-12 text-white">
                    <!-- Logo/Icon -->
                    <div class="mb-8">
                        <div class="w-24 h-24 bg-white/20 backdrop-blur-lg rounded-3xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-spa text-5xl text-white"></i>
                        </div>
                    </div>

                    <!-- Clinic Name -->
                    <h1 class="text-5xl font-bold mb-4 text-center">{{ $tenant->name ?? 'Beauty Smile' }}</h1>
                    <p class="text-xl text-white/90 mb-12 text-center max-w-md">
                        Your smile, our passion. Experience dental care with elegance.
                    </p>

                    <!-- Features -->
                    <div class="space-y-6 max-w-md w-full">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-md text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Expert Care</h3>
                                <p class="text-white/80 text-sm">Professional dental services with a personal touch</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar-check text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Easy Scheduling</h3>
                                <p class="text-white/80 text-sm">Book appointments at your convenience</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shield-alt text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Safe & Secure</h3>
                                <p class="text-white/80 text-sm">Your data and privacy are our priority</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-8">
                        <div class="inline-flex w-16 h-16 bg-gradient-to-br from-pink-400 to-purple-500 rounded-2xl items-center justify-center shadow-lg mb-4">
                            <i class="fas fa-spa text-3xl text-white"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $tenant->name ?? 'Beauty Smile' }}</h2>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <div class="mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                            <p class="text-gray-600">Sign in to access your dashboard</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-pink-500 mr-2"></i>Email Address
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
                                    placeholder="your@email.com">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-pink-500 mr-2"></i>Password
                                </label>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
                                    placeholder="Enter your password">
                                @error('password')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center justify-between mb-6">
                                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500" name="remember">
                                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-pink-600 hover:text-pink-700 font-medium">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-pink-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transform transition-all hover:scale-[1.02] shadow-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Sign In
                            </button>
                        </form>

                        <!-- Footer -->
                        <div class="mt-6 text-center text-sm text-gray-500">
                            <p>&copy; {{ date('Y') }} {{ $tenant->name ?? 'Beauty Smile' }}. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
