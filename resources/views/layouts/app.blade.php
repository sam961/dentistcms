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
        
        <!-- Font Awesome Icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Custom Styles -->
        <style>
            .nav-link-modern {
                @apply flex items-center px-4 py-2 text-sm font-medium text-white/90 hover:text-white hover:bg-white/20 rounded-2xl transition-all duration-200 ease-in-out;
            }
            .nav-link-modern.active {
                @apply text-white bg-white/25 shadow-md;
            }
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .card-modern {
                @apply bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-2;
            }
            .card-elegant {
                @apply bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl hover:border-gray-200 transition-all duration-300 ease-in-out;
            }
            .card-minimal {
                @apply bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200 ease-in-out;
            }
            .btn-modern {
                @apply px-6 py-3 rounded-2xl font-semibold text-white shadow-lg hover:shadow-2xl transition-all duration-200 ease-in-out transform hover:-translate-y-1;
            }
            .btn-elegant {
                @apply px-4 py-2 rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-200 ease-in-out;
            }
            .input-modern {
                @apply rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200;
            }
            .table-modern {
                @apply bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100;
            }
            .btn-primary { @apply bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700; }
            .btn-success { @apply bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700; }
            .btn-warning { @apply bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700; }
            .btn-danger { @apply bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700; }
            .btn-info { @apply bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700; }
            .btn-secondary { @apply bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700; }
            
            /* Enhanced Shadows */
            .shadow-soft { box-shadow: 0 4px 25px -4px rgba(0, 0, 0, 0.1); }
            .shadow-elegant { box-shadow: 0 8px 40px -8px rgba(0, 0, 0, 0.15); }
            .shadow-premium { box-shadow: 0 20px 60px -12px rgba(0, 0, 0, 0.25); }
            
            /* Glassmorphism */
            .glass {
                backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.75);
                border: 1px solid rgba(255, 255, 255, 0.125);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 backdrop-blur-sm shadow-lg border-b border-white/20">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
