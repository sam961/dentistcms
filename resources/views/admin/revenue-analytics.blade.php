<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-chart-line text-green-600 mr-3"></i>
                    Revenue & Financial Analytics
                </h2>
                <p class="text-gray-600 mt-2">Track revenue performance, subscriptions, and financial metrics</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Revenue Analytics Section -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-sack-dollar text-green-600 mr-2"></i>
                    Revenue Metrics
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Revenue Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 rounded-xl">
                                <i class="fas fa-hand-holding-usd text-green-600 text-2xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full">ALL TIME</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">${{ number_format($revenueStats['total_revenue'], 2) }}</div>
                        <div class="text-sm font-medium text-gray-600">Total Revenue</div>
                        <div class="mt-3 text-xs text-gray-500">
                            <i class="fas fa-infinity mr-1"></i>
                            Lifetime earnings
                        </div>
                    </div>

                    <!-- Monthly Recurring Revenue Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 rounded-xl">
                                <i class="fas fa-redo-alt text-blue-600 text-2xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-blue-700 bg-blue-100 px-3 py-1 rounded-full">MRR</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">${{ number_format($revenueStats['mrr'], 2) }}</div>
                        <div class="text-sm font-medium text-gray-600">Monthly Recurring</div>
                        <div class="mt-3 text-xs text-gray-500">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Active subscriptions
                        </div>
                    </div>

                    <!-- Revenue This Month Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 rounded-xl">
                                <i class="fas fa-calendar-day text-purple-600 text-2xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-purple-700 bg-purple-100 px-3 py-1 rounded-full">THIS MONTH</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">${{ number_format($revenueStats['revenue_this_month'], 2) }}</div>
                        <div class="text-sm font-medium text-gray-600">Revenue This Month</div>
                        <div class="mt-3 text-xs flex items-center">
                            @if($revenueStats['mom_growth'] > 0)
                                <span class="text-green-600">
                                    <i class="fas fa-arrow-trend-up mr-1"></i>
                                    {{ number_format($revenueStats['mom_growth'], 1) }}% vs last month
                                </span>
                            @elseif($revenueStats['mom_growth'] < 0)
                                <span class="text-red-600">
                                    <i class="fas fa-arrow-trend-down mr-1"></i>
                                    {{ number_format(abs($revenueStats['mom_growth']), 1) }}% vs last month
                                </span>
                            @else
                                <span class="text-gray-500">
                                    <i class="fas fa-equals mr-1"></i>
                                    No change vs last month
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Average Revenue Per Client Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-cyan-100 rounded-xl">
                                <i class="fas fa-calculator text-cyan-600 text-2xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-cyan-700 bg-cyan-100 px-3 py-1 rounded-full">ARPC</span>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">${{ number_format($revenueStats['arpc'], 2) }}</div>
                        <div class="text-sm font-medium text-gray-600">Avg. Per Client</div>
                        <div class="mt-3 text-xs text-gray-500">
                            <i class="fas fa-divide mr-1"></i>
                            Per tenant average
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Overview Section -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                    Subscription Overview
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <!-- Active Subscriptions Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-green-100 rounded-xl mb-3 inline-block">
                            <i class="fas fa-badge-check text-green-600 text-xl"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $subscriptionStats['active_subscriptions'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Active Subscriptions</div>
                    </div>

                    <!-- Expiring Soon Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-orange-100 rounded-xl mb-3 inline-block">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $subscriptionStats['expiring_soon'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Expiring (7 days)</div>
                    </div>

                    <!-- Expiring This Month Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-yellow-100 rounded-xl mb-3 inline-block">
                            <i class="fas fa-calendar-xmark text-yellow-600 text-xl"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $subscriptionStats['expiring_this_month'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Expiring (30 days)</div>
                    </div>

                    <!-- Overdue Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-red-100 rounded-xl mb-3 inline-block">
                            <i class="fas fa-triangle-exclamation text-red-600 text-xl"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $subscriptionStats['overdue'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Overdue</div>
                    </div>

                    <!-- Trial Accounts Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-gray-100 rounded-xl mb-3 inline-block">
                            <i class="fas fa-flask text-gray-600 text-xl"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $subscriptionStats['trial_accounts'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Trial/Free</div>
                    </div>
                </div>
            </div>

            <!-- Revenue Trend Chart -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-xl mr-4">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Revenue Trend</h3>
                            <p class="text-sm text-gray-600">Last 6 months revenue performance</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <canvas id="revenueTrendChart" height="80"></canvas>
                </div>
            </div>

            <!-- Upcoming Renewals Section -->
            @if($subscriptionStats['upcoming_renewals']->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-xl mr-4">
                                <i class="fas fa-rotate text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Upcoming Renewals</h3>
                                <p class="text-sm text-gray-600">Subscriptions expiring in the next 30 days</p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires On</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Left</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($subscriptionStats['upcoming_renewals'] as $tenant)
                                    @php
                                        $daysLeft = now()->diffInDays($tenant->subscription_ends_at, false);
                                        $latestSubscription = $tenant->subscriptionHistory()->latest()->first();
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $tenant->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $tenant->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $tenant->subscription_ends_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($daysLeft <= 7) bg-red-100 text-red-800
                                                @elseif($daysLeft <= 14) bg-orange-100 text-orange-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            ${{ $latestSubscription ? number_format($latestSubscription->amount, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.tenants.subscription', $tenant) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                <i class="fas fa-redo mr-1"></i>
                                                Renew
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Monthly Revenue Breakdown -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-xl mr-4">
                            <i class="fas fa-table-list text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Monthly Revenue Breakdown</h3>
                            <p class="text-sm text-gray-600">Detailed revenue by month (last 12 months)</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subscriptions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg. Per Subscription</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($revenueByMonth as $month)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $month['month'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        ${{ number_format($month['revenue'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $month['subscription_count'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        ${{ $month['subscription_count'] > 0 ? number_format($month['revenue'] / $month['subscription_count'], 2) : '0.00' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueTrendChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($revenueTrend['months']),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenueTrend['revenues']),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: 'rgb(5, 150, 105)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.parsed.y.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                },
                                font: {
                                    size: 12
                                },
                                color: '#6b7280'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#6b7280'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        });
    </script>
</x-app-layout>
