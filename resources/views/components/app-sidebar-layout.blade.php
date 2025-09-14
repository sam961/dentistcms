<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Dentist CMS') }} - Modern Dental Practice Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Font Awesome Icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Chart.js for data visualization -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div x-data="{ sidebarOpen: false, profileDropdown: false, notificationDropdown: false }" class="flex h-screen overflow-hidden">

            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center justify-between h-16 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-tooth text-blue-600 text-xl"></i>
                            </div>
                            <span class="text-xl font-bold text-white">DentistCMS</span>
                        </a>
                        <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- User Profile Section -->
                    <div class="px-6 py-4 bg-gradient-to-b from-gray-50 to-white border-b">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-home w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Dashboard
                            @if(request()->routeIs('dashboard'))
                                <span class="ml-auto w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            @endif
                        </a>

                        <!-- Patients -->
                        <a href="{{ route('patients.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('patients.*') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-users w-5 h-5 mr-3 {{ request()->routeIs('patients.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Patients
                            <span class="ml-auto bg-blue-100 text-blue-600 px-2 py-1 text-xs rounded-full">{{ \App\Models\Patient::count() ?? 0 }}</span>
                        </a>

                        <!-- Appointments -->
                        <a href="{{ route('appointments.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('appointments.*') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-calendar-alt w-5 h-5 mr-3 {{ request()->routeIs('appointments.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Appointments
                            @php
                                $todayCount = \App\Models\Appointment::whereDate('appointment_date', today())->whereNotIn('status', ['cancelled'])->count();
                            @endphp
                            @if($todayCount > 0)
                                <span class="ml-auto bg-green-100 text-green-600 px-2 py-1 text-xs rounded-full">{{ $todayCount }} today</span>
                            @endif
                        </a>

                        <!-- Treatments -->
                        <a href="{{ route('treatments.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('treatments.*') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-procedures w-5 h-5 mr-3 {{ request()->routeIs('treatments.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Treatments
                        </a>

                        <!-- Invoices -->
                        <a href="{{ route('invoices.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-file-invoice-dollar w-5 h-5 mr-3 {{ request()->routeIs('invoices.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Invoices
                            @php
                                $pendingCount = \App\Models\Invoice::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto bg-amber-100 text-amber-600 px-2 py-1 text-xs rounded-full">{{ $pendingCount }} pending</span>
                            @endif
                        </a>

                        <!-- Dentists -->
                        <a href="{{ route('dentists.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dentists.*') ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-user-md w-5 h-5 mr-3 {{ request()->routeIs('dentists.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Dentists
                        </a>

                        <!-- Divider -->
                        <div class="my-4 border-t border-gray-200"></div>

                        <!-- Reports -->
                        <div class="px-4 py-2">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Analytics</p>
                        </div>

                        <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fas fa-chart-line w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600"></i>
                            Reports
                        </a>

                        <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fas fa-chart-pie w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600"></i>
                            Analytics
                        </a>

                        <!-- Settings -->
                        <div class="px-4 py-2 mt-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fas fa-user-cog w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600"></i>
                            Profile Settings
                        </a>

                        <a href="#" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-100 transition-all duration-200">
                            <i class="fas fa-cog w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-600"></i>
                            Settings
                        </a>
                    </nav>

                    <!-- Logout -->
                    <div class="p-4 border-t">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-red-600 hover:bg-red-50 transition-all duration-200">
                                <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation Bar -->
                <header class="bg-white shadow-sm border-b">
                    <div class="flex items-center justify-between h-16 px-6">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Search Bar -->
                        <div class="flex-1 max-w-xl mx-4">
                            <div class="relative">
                                <input type="text" placeholder="Search patients, appointments, treatments..." class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Right side buttons -->
                        <div class="flex items-center space-x-4">
                            <!-- Quick Add Button -->
                            <div class="relative" x-data="{ quickAdd: false }">
                                <button @click="quickAdd = !quickAdd" class="relative p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors duration-200 shadow-lg">
                                    <i class="fas fa-plus text-lg"></i>
                                </button>

                                <!-- Quick Add Dropdown -->
                                <div x-show="quickAdd"
                                     @click.away="quickAdd = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-gray-100 py-2 z-50">
                                    <a href="{{ route('patients.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user-plus w-4 h-4 mr-3 text-gray-400"></i>
                                        New Patient
                                    </a>
                                    <a href="{{ route('appointments.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-calendar-plus w-4 h-4 mr-3 text-gray-400"></i>
                                        New Appointment
                                    </a>
                                    <a href="{{ route('treatments.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-plus-circle w-4 h-4 mr-3 text-gray-400"></i>
                                        New Treatment
                                    </a>
                                    <a href="{{ route('invoices.create') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-file-invoice w-4 h-4 mr-3 text-gray-400"></i>
                                        New Invoice
                                    </a>
                                </div>
                            </div>

                            <!-- Notifications -->
                            <div class="relative" x-data="{ notifications: false }">
                                <button @click="notifications = !notifications" class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors duration-200">
                                    <i class="fas fa-bell text-xl"></i>
                                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                </button>

                                <!-- Notifications Dropdown -->
                                <div x-show="notifications"
                                     @click.away="notifications = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-cloak
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50">
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50">
                                            <div class="flex items-start">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                    <i class="fas fa-calendar text-blue-600 text-xs"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-900">New appointment scheduled</p>
                                                    <p class="text-xs text-gray-500 mt-1">John Doe - Tomorrow at 10:00 AM</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50">
                                            <div class="flex items-start">
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-900">Payment received</p>
                                                    <p class="text-xs text-gray-500 mt-1">Invoice #1234 - $250.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                                            <div class="flex items-start">
                                                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                    <i class="fas fa-exclamation text-amber-600 text-xs"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-900">Appointment reminder</p>
                                                    <p class="text-xs text-gray-500 mt-1">3 appointments scheduled for today</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 border-t border-gray-100">
                                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all notifications</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Help -->
                            <button class="p-2 text-gray-600 hover:text-gray-900 transition-colors duration-200">
                                <i class="fas fa-question-circle text-xl"></i>
                            </button>

                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ profile: false }">
                                <button @click="profile = !profile" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition-colors duration-200">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                                </button>

                                <!-- Profile Dropdown Menu -->
                                <div x-show="profile"
                                     @click.away="profile = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-gray-100 py-2 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user w-4 h-4 mr-3 text-gray-400"></i>
                                        My Profile
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog w-4 h-4 mr-3 text-gray-400"></i>
                                        Settings
                                    </a>
                                    <div class="border-t border-gray-100 mt-2 pt-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50">
                    <!-- Page Header -->
                    @isset($header)
                        <div class="bg-white shadow-sm border-b">
                            <div class="px-6 py-4">
                                {{ $header }}
                            </div>
                        </div>
                    @endisset

                    <!-- Main Content -->
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"></div>

        <!-- Add Alpine cloak styles -->
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>