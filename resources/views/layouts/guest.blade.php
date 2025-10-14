<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-white to-purple-50">
            <!-- Logo and Clinic Name -->
            <div class="text-center mb-8">
                @php
                    $tenant = $tenant ?? null;
                @endphp

                @if($tenant && $tenant->logo)
                    <!-- Tenant Logo -->
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name ?? 'Logo' }}" class="h-24 w-auto mx-auto">
                    </div>
                @else
                    <!-- Default Icon -->
                    <div class="mb-4">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg">
                            <i class="fas fa-tooth text-white text-4xl"></i>
                        </div>
                    </div>
                @endif

                <!-- Clinic Name -->
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    {{ $tenant->name ?? config('app.name', 'DentistCMS') }}
                </h1>
                <p class="text-gray-600">
                    Welcome back! Please sign in to continue.
                </p>
            </div>

            <!-- Login Form Card -->
            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border border-gray-100">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} {{ $tenant->name ?? config('app.name', 'DentistCMS') }}. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
