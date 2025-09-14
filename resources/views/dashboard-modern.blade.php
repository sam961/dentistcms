<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                <p class="text-sm text-gray-500 mt-1">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
                <span class="text-sm font-semibold text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                    <i class="fas fa-clock mr-1"></i> {{ now()->format('g:i A') }}
                </span>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Patients Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1 text-green-600 bg-green-50 px-2 py-1 rounded-lg text-xs font-semibold">
                        <i class="fas fa-arrow-up text-xs"></i>
                        <span>12%</span>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_patients'] }}</h3>
                <p class="text-sm text-gray-500">Total Patients</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">New this month</span>
                        <span class="font-semibold text-gray-700">+24</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
        </div>

        <!-- Today's Appointments Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1 text-amber-600 bg-amber-50 px-2 py-1 rounded-lg text-xs font-semibold">
                        <i class="fas fa-clock text-xs"></i>
                        <span>Today</span>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['today_appointments'] }}</h3>
                <p class="text-sm text-gray-500">Appointments Today</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">Completed</span>
                        <span class="font-semibold text-gray-700">{{ $stats['completed_today'] ?? 0 }}/{{ $stats['today_appointments'] }}</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
        </div>

        <!-- Pending Invoices Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1 text-red-600 bg-red-50 px-2 py-1 rounded-lg text-xs font-semibold">
                        <i class="fas fa-exclamation-circle text-xs"></i>
                        <span>Pending</span>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['pending_invoices'] }}</h3>
                <p class="text-sm text-gray-500">Pending Invoices</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">Total amount</span>
                        <span class="font-semibold text-gray-700">${{ number_format($stats['pending_amount'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-1 text-green-600 bg-green-50 px-2 py-1 rounded-lg text-xs font-semibold">
                        <i class="fas fa-arrow-up text-xs"></i>
                        <span>8%</span>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">${{ number_format($stats['monthly_revenue'], 0) }}</h3>
                <p class="text-sm text-gray-500">Monthly Revenue</p>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">Target</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600" style="width: {{ min(($stats['monthly_revenue'] / 50000) * 100, 100) }}%"></div>
                            </div>
                            <span class="text-xs text-gray-600">{{ round(($stats['monthly_revenue'] / 50000) * 100) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-purple-500 to-purple-600"></div>
        </div>
    </div>

    <!-- Charts and Activities Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Revenue Overview</h2>
                    <p class="text-sm text-gray-500">Monthly revenue for the last 6 months</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        6M
                    </button>
                    <button class="px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        1Y
                    </button>
                    <button class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        All
                    </button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">Recent Activity</h2>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-plus text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">New patient registered</p>
                        <p class="text-xs text-gray-500">John Smith - 5 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-green-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Appointment completed</p>
                        <p class="text-xs text-gray-500">Sarah Johnson - 1 hour ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-dollar-sign text-purple-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Payment received</p>
                        <p class="text-xs text-gray-500">Invoice #1234 - 2 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar text-amber-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Appointment scheduled</p>
                        <p class="text-xs text-gray-500">Mike Davis - 3 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="bg-white rounded-2xl shadow-sm mb-8">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Today's Schedule</h2>
                        <p class="text-sm text-gray-500">{{ $todayAppointments->count() }} appointments scheduled</p>
                    </div>
                </div>
                <a href="{{ route('appointments.index') }}" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors font-medium text-sm">
                    View Calendar
                </a>
            </div>
        </div>

        @if($todayAppointments->count() > 0)
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($todayAppointments as $appointment)
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-200 group">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold text-blue-700">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $appointment->patient->full_name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">Dr. {{ $appointment->dentist->full_name }}</p>
                                        @if($appointment->treatments && $appointment->treatments->count() > 0)
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                @foreach($appointment->treatments as $treatment)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs">{{ $treatment->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($appointment->status === 'confirmed') bg-green-100 text-green-700
                                        @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-700
                                        @elseif($appointment->status === 'completed') bg-gray-100 text-gray-700
                                        @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                        @else bg-amber-100 text-amber-700
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                        View →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 mb-4">No appointments scheduled for today</p>
                <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium text-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Schedule Appointment
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Treatment Types Chart -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Popular Treatments</h3>
            <div class="h-40">
                <canvas id="treatmentChart"></canvas>
            </div>
        </div>

        <!-- Appointment Status -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Appointment Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Scheduled</span>
                    <span class="text-sm font-semibold text-blue-600">{{ $stats['scheduled_appointments'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Confirmed</span>
                    <span class="text-sm font-semibold text-green-600">{{ $stats['confirmed_appointments'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Completed</span>
                    <span class="text-sm font-semibold text-gray-600">{{ $stats['completed_appointments'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Cancelled</span>
                    <span class="text-sm font-semibold text-red-600">{{ $stats['cancelled_appointments'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Top Dentists -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Top Performing Dentists</h3>
            <div class="space-y-3">
                @foreach($topDentists ?? [] as $dentist)
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                            {{ substr($dentist->first_name, 0, 1) }}{{ substr($dentist->last_name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Dr. {{ $dentist->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $dentist->appointments_count ?? 0 }} appointments</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">New Patients</h3>
            <div class="space-y-3">
                @foreach($recentPatients->take(4) as $patient)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 font-bold text-xs">
                                {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $patient->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $patient->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ route('patients.show', $patient) }}" class="text-blue-600 hover:text-blue-700">
                            <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [30000, 35000, 32000, 40000, 38000, {{ $stats['monthly_revenue'] }}],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return '$' + (value / 1000) + 'K';
                            }
                        }
                    }
                }
            }
        });

        // Treatment Types Chart
        const treatmentCtx = document.getElementById('treatmentChart').getContext('2d');
        new Chart(treatmentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Cleaning', 'Filling', 'Extraction', 'Root Canal', 'Other'],
                datasets: [{
                    data: [30, 25, 15, 20, 10],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(251, 146, 60)',
                        'rgb(147, 51, 234)',
                        'rgb(156, 163, 175)'
                    ],
                    borderWidth: 0,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 8,
                            usePointStyle: true,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-sidebar-layout>