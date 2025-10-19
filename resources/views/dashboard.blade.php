<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-gray-600 mt-1">Welcome back! Here's what's happening at your clinic today.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('appointments.create') }}" class="btn-modern btn-primary inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    New Appointment
                </a>
                <a href="{{ route('patients.create') }}" class="btn-modern btn-success inline-flex items-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    New Patient
                </a>
                <a href="{{ route('treatments.create') }}" class="btn-modern btn-info inline-flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>
                    New Treatment
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- Demo Account Banner --}}
            @if(auth()->user()->email === 'demo@dentistcms.com')
                <div class="relative overflow-hidden bg-gradient-to-r from-amber-50 via-orange-50 to-yellow-50 border-l-4 border-orange-400 rounded-2xl shadow-lg">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-400/5 to-yellow-400/5"></div>
                    <div class="relative px-6 py-5">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-amber-500 rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                                    <i class="fas fa-info-circle text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-orange-500 to-amber-500 text-white mr-3 shadow-md">
                                                <i class="fas fa-flask mr-1.5 animate-bounce"></i>
                                                DEMO MODE
                                            </span>
                                            Welcome to the Demo Account!
                                        </h3>
                                        <div class="mt-2 text-sm text-gray-700 space-y-1">
                                            <p class="flex items-center">
                                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                <span>You're viewing a <strong>live demo</strong> of the Dental CMS with sample data.</span>
                                            </p>
                                            <p class="flex items-center">
                                                <i class="fas fa-sync-alt text-blue-600 mr-2"></i>
                                                <span>All data <strong>resets automatically every hour</strong> to ensure a fresh experience.</span>
                                            </p>
                                            <p class="flex items-center">
                                                <i class="fas fa-user-shield text-purple-600 mr-2"></i>
                                                <span>Feel free to explore, create, edit, and test all features without restrictions!</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-6 text-right flex-shrink-0">
                                        <div class="bg-white rounded-xl shadow-md px-4 py-3 border border-orange-200">
                                            <div class="text-xs font-medium text-gray-600 mb-1">Next Reset</div>
                                            <div class="text-lg font-bold text-orange-600 flex items-center justify-center">
                                                <i class="fas fa-clock mr-2 animate-spin-slow"></i>
                                                {{ now()->addHour()->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Quick Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <!-- Patients Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 via-blue-500 to-purple-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-blue-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-blue-500 to-blue-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-users text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-blue-100 to-blue-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-blue-700 text-xs font-bold tracking-wider">ACTIVE</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">{{ $stats['total_patients'] }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Total Patients</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-green-100 rounded-full">
                                    <i class="fas fa-arrow-up text-green-600 text-xs animate-bounce"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">Growing community</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dentists Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-emerald-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-emerald-500 to-teal-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-user-md text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-emerald-100 to-teal-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-emerald-700 text-xs font-bold tracking-wider">STAFF</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">{{ $stats['total_dentists'] }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Active Dentists</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-emerald-100 rounded-full">
                                    <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">Professional team</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Appointments Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-400 via-orange-500 to-yellow-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-amber-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-amber-500 to-orange-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-calendar-day text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-amber-100 to-orange-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-amber-700 text-xs font-bold tracking-wider">TODAY</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">{{ $stats['today_appointments'] }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Today's Schedule</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-amber-100 rounded-full">
                                    <i class="fas fa-clock text-amber-600 text-xs"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">{{ \Carbon\Carbon::today()->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Invoices Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-400 via-pink-500 to-red-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-rose-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-rose-500 to-pink-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-file-invoice text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-rose-100 to-pink-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-rose-700 text-xs font-bold tracking-wider">PENDING</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-pink-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">{{ $stats['pending_invoices'] }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Pending Invoices</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-rose-100 rounded-full animate-pulse">
                                    <i class="fas fa-exclamation-circle text-rose-600 text-xs"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">Needs attention</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-violet-400 via-purple-500 to-indigo-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-violet-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-violet-500 to-purple-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-dollar-sign text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-violet-100 to-purple-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-violet-700 text-xs font-bold tracking-wider">REVENUE</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-purple-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">${{ number_format($stats['monthly_revenue'], 0) }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Monthly Revenue</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-violet-100 rounded-full">
                                    <i class="fas fa-chart-line text-violet-600 text-xs animate-pulse"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">This month</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Today's Schedule -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="px-8 py-6 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl mr-4 shadow-lg">
                                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Today's Schedule</h3>
                                        <p class="text-sm text-gray-600">Manage your daily appointments</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="bg-gradient-to-r from-blue-100 to-purple-100 px-4 py-2 rounded-full">
                                        <span class="text-sm font-bold text-blue-700">{{ \Carbon\Carbon::today()->format('M j, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($todayAppointments->count() > 0)
                                <div class="space-y-4">
                                    @foreach($todayAppointments as $appointment)
                                        <div class="relative group">
                                            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl blur opacity-0 group-hover:opacity-25 transition duration-300"></div>
                                            <div class="relative bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-lg transition-all duration-300">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-14 h-14 bg-gradient-to-r from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center">
                                                                <div class="text-center">
                                                                    <div class="text-sm font-bold text-blue-700">
                                                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                                                    </div>
                                                                    <div class="text-xs text-blue-600">
                                                                        {{ $appointment->duration }}m
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center mb-1">
                                                                <i class="fas fa-user text-gray-400 text-xs mr-2"></i>
                                                                <p class="text-lg font-semibold text-gray-900 truncate">
                                                                    {{ $appointment->patient->full_name }}
                                                                </p>
                                                            </div>
                                                            <div class="flex items-center mb-1">
                                                                <i class="fas fa-user-md text-gray-400 text-xs mr-2"></i>
                                                                <p class="text-sm text-gray-600">
                                                                    Dr. {{ $appointment->dentist->full_name }}
                                                                </p>
                                                            </div>
                                                            @if($appointment->treatments && $appointment->treatments->count() > 0)
                                                                <div class="flex items-center">
                                                                    <i class="fas fa-procedures text-gray-400 text-xs mr-2"></i>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ $appointment->treatments->pluck('name')->join(', ') }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-3">
                                                        <div class="text-right">
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                                @if($appointment->status === 'confirmed') bg-emerald-100 text-emerald-700
                                                                @elseif($appointment->status === 'completed') bg-green-100 text-green-700
                                                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                                                @elseif($appointment->status === 'in_progress') bg-yellow-100 text-yellow-700
                                                                @else bg-amber-100 text-amber-700
                                                                @endif">
                                                                @if($appointment->status === 'confirmed')
                                                                    <i class="fas fa-check-circle mr-1"></i>
                                                                @elseif($appointment->status === 'completed')
                                                                    <i class="fas fa-check-double mr-1"></i>
                                                                @elseif($appointment->status === 'cancelled')
                                                                    <i class="fas fa-times-circle mr-1"></i>
                                                                @elseif($appointment->status === 'in_progress')
                                                                    <i class="fas fa-spinner mr-1"></i>
                                                                @else
                                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                                @endif
                                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                                            </span>
                                                        </div>
                                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn-modern btn-primary !px-4 !py-2 text-xs">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            View
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6 text-center">
                                    <a href="{{ route('appointments.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                        View all appointments
                                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No appointments scheduled for today</p>
                                    <a href="{{ route('appointments.create') }}" class="mt-2 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                        Schedule an appointment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Activity Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="px-6 py-5 bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl mr-3 shadow-lg">
                                    <i class="fas fa-bolt text-white text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('patients.create') }}" class="group relative">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl blur opacity-0 group-hover:opacity-25 transition duration-300"></div>
                                    <div class="relative flex items-center p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-300">
                                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                            <i class="fas fa-user-plus text-blue-600"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-700">Register Patient</span>
                                        <i class="fas fa-chevron-right text-gray-400 ml-auto group-hover:text-blue-500"></i>
                                    </div>
                                </a>

                                <a href="{{ route('appointments.create') }}" class="group relative">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl blur opacity-0 group-hover:opacity-25 transition duration-300"></div>
                                    <div class="relative flex items-center p-4 rounded-xl border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50 transition-all duration-300">
                                        <div class="p-2 bg-emerald-100 rounded-lg mr-3">
                                            <i class="fas fa-calendar-plus text-emerald-600"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700 group-hover:text-emerald-700">Book Appointment</span>
                                        <i class="fas fa-chevron-right text-gray-400 ml-auto group-hover:text-emerald-500"></i>
                                    </div>
                                </a>

                                <a href="{{ route('invoices.create') }}" class="group relative">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl blur opacity-0 group-hover:opacity-25 transition duration-300"></div>
                                    <div class="relative flex items-center p-4 rounded-xl border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-300">
                                        <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                            <i class="fas fa-file-invoice-dollar text-purple-600"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700 group-hover:text-purple-700">Create Invoice</span>
                                        <i class="fas fa-chevron-right text-gray-400 ml-auto group-hover:text-purple-500"></i>
                                    </div>
                                </a>

                                <a href="{{ route('treatments.index') }}" class="group relative">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl blur opacity-0 group-hover:opacity-25 transition duration-300"></div>
                                    <div class="relative flex items-center p-4 rounded-xl border border-gray-200 hover:border-amber-300 hover:bg-amber-50 transition-all duration-300">
                                        <div class="p-2 bg-amber-100 rounded-lg mr-3">
                                            <i class="fas fa-procedures text-amber-600"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700 group-hover:text-amber-700">View Treatments</span>
                                        <i class="fas fa-chevron-right text-gray-400 ml-auto group-hover:text-amber-500"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Patients -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Patients</h3>
                        </div>
                        <div class="p-6">
                            @if($recentPatients->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentPatients as $patient)
                                        <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-xs font-medium text-blue-600">
                                                        {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $patient->full_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $patient->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('patients.show', $patient) }}" class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                                View
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('patients.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                        View all patients
                                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500">No patients registered yet</p>
                                    <a href="{{ route('patients.create') }}" class="mt-2 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                        Register first patient
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            @if($upcomingAppointments->count() > 0)
                <div class="mt-8 bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Upcoming Appointments
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dentist</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($upcomingAppointments as $appointment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                            <div class="text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium">{{ $appointment->patient->full_name }}</div>
                                            <div class="text-gray-500">{{ $appointment->patient->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Dr. {{ $appointment->dentist->full_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $appointment->type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($appointment->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($appointment->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                @elseif($appointment->status === 'completed') bg-gray-100 text-gray-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>