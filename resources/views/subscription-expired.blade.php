<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Subscription Expired - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome Icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 flex items-center justify-center p-4">
            <div class="max-w-2xl w-full">
                <!-- Logo / Brand -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-600 rounded-3xl shadow-2xl mb-4">
                        <i class="fas fa-crown text-white text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ config('app.name', 'DentistCMS') }}</h1>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
                    <!-- Header Section -->
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-8 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full mb-4">
                            <i class="fas fa-exclamation-triangle text-white text-5xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-2">Subscription Expired</h2>
                        <p class="text-amber-100 text-lg">Your clinic's subscription needs attention</p>
                    </div>

                    <!-- Content Section -->
                    <div class="p-8">
                        <div class="space-y-6">
                            <!-- Main Message -->
                            <div class="text-center">
                                <p class="text-gray-700 text-lg leading-relaxed">
                                    Your clinic's subscription has expired or has not been set up yet. To continue using all features of DentistCMS, please contact your administrator.
                                </p>
                            </div>

                            <!-- Information Box -->
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-blue-900 mb-2">What happens now?</h3>
                                        <ul class="space-y-2 text-blue-800">
                                            <li class="flex items-center">
                                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                                <span>Your data is safe and secure</span>
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                                <span>Contact your administrator to renew</span>
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                                <span>Access will be restored upon renewal</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-center">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Need Help?</h3>
                                <p class="text-gray-600 mb-4">
                                    Please reach out to your clinic administrator or our support team
                                </p>
                                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                    <a href="mailto:support@dentistcms.com" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 shadow-lg hover:shadow-xl transition-all duration-200">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Email Support
                                    </a>
                                    <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                                        <i class="fas fa-history mr-2"></i>
                                        View Subscription History
                                    </a>
                                </div>
                            </div>

                            <!-- Logout Button -->
                            <div class="text-center pt-4">
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8 text-gray-600">
                    <p class="text-sm">
                        &copy; {{ date('Y') }} {{ config('app.name', 'DentistCMS') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
