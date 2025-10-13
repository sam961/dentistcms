<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-chart-bar text-blue-600 mr-3"></i>
                    Analytics & Reports
                </h2>
                <p class="text-gray-600 mt-2">Comprehensive insights and performance metrics</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Date Filter -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-filter mr-2"></i>Apply Filter
                </button>
            </form>
        </div>

        <!-- Financial Overview -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-dollar-sign text-green-600 mr-3"></i>
                Financial Overview
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Revenue -->
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($financialMetrics['total_revenue'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Paid Invoices -->
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Paid Invoices</p>
                            <p class="text-2xl font-bold text-green-600">${{ number_format($financialMetrics['paid_invoices'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Invoices -->
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">${{ number_format($financialMetrics['pending_invoices'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Overdue Invoices -->
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Overdue</p>
                            <p class="text-2xl font-bold text-red-600">${{ number_format($financialMetrics['overdue_invoices'], 2) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Financial Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Total Invoices</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($financialMetrics['invoice_count']) }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Average Invoice Value</p>
                    <p class="text-xl font-bold text-gray-900">${{ number_format($financialMetrics['average_invoice_value'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Monthly Revenue Trend -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    Revenue Trend (Last 6 Months)
                </h3>
                <div style="height: 300px; position: relative;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Appointment Status Distribution -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                    Appointment Status Distribution
                </h3>
                <div style="height: 300px; position: relative;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Appointment Metrics -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-calendar-check text-purple-600 mr-3"></i>
                Appointment Analytics
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Total Appointments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($appointmentMetrics['total_appointments']) }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Completed</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($appointmentMetrics['completed_appointments']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $appointmentMetrics['completion_rate'] }}%</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Confirmed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($appointmentMetrics['confirmed_appointments']) }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Cancelled</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($appointmentMetrics['cancelled_appointments']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $appointmentMetrics['cancellation_rate'] }}%</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">No Show</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($appointmentMetrics['no_show_appointments']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $appointmentMetrics['no_show_rate'] }}%</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm mt-4">
                <p class="text-sm text-gray-600 mb-1">Average Appointments Per Day</p>
                <p class="text-xl font-bold text-gray-900">{{ $appointmentMetrics['avg_appointments_per_day'] }}</p>
            </div>
        </div>

        <!-- Patient Metrics -->
        <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-users text-green-600 mr-3"></i>
                Patient Analytics
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Total Patients</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($patientMetrics['total_patients']) }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">New Patients</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($patientMetrics['new_patients']) }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Active Patients</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($patientMetrics['active_patients']) }}</p>
                </div>
            </div>

            <!-- Age Distribution -->
            <div class="bg-white rounded-xl p-5 shadow-sm">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Patient Age Distribution</h4>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach($patientMetrics['age_groups'] as $group)
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-lg font-bold text-gray-900">{{ $group->count }}</p>
                            <p class="text-xs text-gray-600">{{ $group->age_group }} years</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Dentist Performance -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-user-md text-indigo-600 mr-3"></i>
                Dentist Performance
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dentist</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dentistPerformance as $dentist)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">Dr. {{ $dentist['name'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $dentist['specialization'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $dentist['total_appointments'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-green-600">{{ $dentist['completed_appointments'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $dentist['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $dentist['completion_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-green-600">${{ number_format($dentist['revenue'], 2) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No dentist performance data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Treatment Analytics -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-tooth text-blue-600 mr-3"></i>
                Top Treatments
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($treatmentAnalytics['top_treatments'] as $treatment)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $treatment->name }}</h4>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-600">Performed:</span>
                            <span class="text-sm font-bold text-gray-900">{{ $treatment->count }} times</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Revenue:</span>
                            <span class="text-sm font-bold text-green-600">${{ number_format($treatment->total_revenue, 2) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-8">
                        No treatment data available for the selected period
                    </div>
                @endforelse
            </div>
            <div class="mt-4 p-4 bg-blue-50 rounded-xl">
                <p class="text-sm text-gray-700">
                    <span class="font-semibold">Total Treatments Performed:</span>
                    {{ number_format($treatmentAnalytics['total_treatments_performed']) }}
                </p>
            </div>
        </div>

        <!-- Payment Methods -->
        @if($financialMetrics['payment_methods']->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-credit-card text-green-600 mr-3"></i>
                Payment Methods Breakdown
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($financialMetrics['payment_methods'] as $method)
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 text-center">
                        <div class="text-3xl mb-2">
                            @if($method->payment_method == 'cash')
                                <i class="fas fa-money-bill-wave text-green-600"></i>
                            @elseif($method->payment_method == 'credit_card')
                                <i class="fas fa-credit-card text-blue-600"></i>
                            @elseif($method->payment_method == 'debit_card')
                                <i class="fas fa-credit-card text-purple-600"></i>
                            @elseif($method->payment_method == 'insurance')
                                <i class="fas fa-shield-alt text-indigo-600"></i>
                            @else
                                <i class="fas fa-exchange-alt text-gray-600"></i>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-1 capitalize">{{ str_replace('_', ' ', $method->payment_method ?? 'Other') }}</p>
                        <p class="text-lg font-bold text-gray-900">{{ $method->count }}</p>
                        <p class="text-xs text-green-600 font-semibold">${{ number_format($method->total, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Revenue Trend Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyRevenue['months']),
                datasets: [{
                    label: 'Revenue',
                    data: @json($monthlyRevenue['revenues']),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: $' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        },
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: @json($appointmentStatusDistribution['labels']),
                datasets: [{
                    data: @json($appointmentStatusDistribution['data']),
                    backgroundColor: [
                        'rgb(34, 197, 94)',   // confirmed - green
                        'rgb(251, 146, 60)',  // in_progress - orange
                        'rgb(59, 130, 246)',  // completed - blue
                        'rgb(239, 68, 68)',   // cancelled - red
                        'rgb(156, 163, 175)'  // no_show - gray
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-sidebar-layout>
