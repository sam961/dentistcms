<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-chart-line text-purple-600 mr-3"></i>
                    Subscription Analytics
                </h2>
                <p class="text-gray-600 mt-2">Revenue tracking, insights, and subscription metrics</p>
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-clock mr-1"></i>
                Updated: {{ now()->format('M d, Y H:i') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Key Metrics Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Revenue -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-2xl p-6 text-white transform hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                            <i class="fas fa-dollar-sign text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-white/80 uppercase font-semibold">Total Revenue</p>
                            <p class="text-4xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-white/90">
                        <i class="fas fa-info-circle mr-1"></i> All-time earnings
                    </div>
                </div>

                <!-- MRR -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-2xl p-6 text-white transform hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                            <i class="fas fa-sync-alt text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-white/80 uppercase font-semibold">MRR</p>
                            <p class="text-4xl font-bold">${{ number_format($mrr, 2) }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-white/90">
                        <i class="fas fa-info-circle mr-1"></i> Monthly Recurring Revenue
                    </div>
                </div>

                <!-- This Month Revenue -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-2xl p-6 text-white transform hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                            <i class="fas fa-calendar-check text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-white/80 uppercase font-semibold">This Month</p>
                            <p class="text-4xl font-bold">${{ number_format($revenueThisMonth, 2) }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-white/90 flex items-center justify-between">
                        <span><i class="fas fa-calendar mr-1"></i> {{ now()->format('F Y') }}</span>
                        @if($growthRate > 0)
                            <span class="bg-green-400 text-green-900 px-2 py-1 rounded-full text-xs font-bold">
                                <i class="fas fa-arrow-up"></i> {{ number_format($growthRate, 1) }}%
                            </span>
                        @elseif($growthRate < 0)
                            <span class="bg-red-400 text-red-900 px-2 py-1 rounded-full text-xs font-bold">
                                <i class="fas fa-arrow-down"></i> {{ number_format(abs($growthRate), 1) }}%
                            </span>
                        @endif
                    </div>
                </div>

                <!-- ARPC -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-2xl p-6 text-white transform hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                            <i class="fas fa-user-circle text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-white/80 uppercase font-semibold">ARPC</p>
                            <p class="text-4xl font-bold">${{ number_format($arpc, 2) }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-white/90">
                        <i class="fas fa-info-circle mr-1"></i> Avg Revenue Per Customer
                    </div>
                </div>
            </div>

            <!-- Key Metrics Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Active Subscriptions -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 uppercase font-semibold">Active</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $activeSubscriptions }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-xl">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Expired Subscriptions -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 uppercase font-semibold">Expired</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $expiredSubscriptions }}</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-xl">
                            <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Expiring Soon -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-amber-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 uppercase font-semibold">Expiring Soon</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $expiringSoon }}</p>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-xl">
                            <i class="fas fa-exclamation-triangle text-amber-600 text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Next 30 days</p>
                </div>

                <!-- Total Tenants -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 uppercase font-semibold">Total Clients</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalTenants }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <i class="fas fa-building text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Revenue Trend Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-chart-area text-blue-600 mr-3"></i>
                        Revenue Trend (Last 6 Months)
                    </h3>
                    <div class="space-y-4">
                        @foreach($revenueByMonth as $data)
                            @php
                                $maxRevenue = $revenueByMonth->max('revenue') ?: 1;
                                $percentage = ($data['revenue'] / $maxRevenue) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-700">{{ $data['month'] }}</span>
                                    <span class="text-sm font-bold text-gray-900">${{ number_format($data['revenue'], 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Subscription Status Breakdown -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-pie-chart text-purple-600 mr-3"></i>
                        Subscription Status
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                            <div class="text-center">
                                <p class="text-4xl font-bold text-green-600">{{ $statusBreakdown['active'] }}</p>
                                <p class="text-sm text-gray-600 mt-2 font-semibold">Active</p>
                            </div>
                        </div>
                        <div class="bg-red-50 rounded-xl p-4 border-2 border-red-200">
                            <div class="text-center">
                                <p class="text-4xl font-bold text-red-600">{{ $statusBreakdown['expired'] }}</p>
                                <p class="text-sm text-gray-600 mt-2 font-semibold">Expired</p>
                            </div>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200">
                            <div class="text-center">
                                <p class="text-4xl font-bold text-blue-600">{{ $statusBreakdown['trial'] }}</p>
                                <p class="text-sm text-gray-600 mt-2 font-semibold">Trial</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-200">
                            <div class="text-center">
                                <p class="text-4xl font-bold text-gray-600">{{ $statusBreakdown['cancelled'] }}</p>
                                <p class="text-sm text-gray-600 mt-2 font-semibold">Cancelled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Clients and Recent Subscriptions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Paying Clients -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-crown text-amber-600 mr-2"></i>
                            Top Paying Clients
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($topClients->count() > 0)
                            <div class="space-y-3">
                                @foreach($topClients as $index => $client)
                                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold mr-3">
                                                {{ $index + 1 }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $client->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $client->subscriptionHistory->count() }} payments</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-green-600">${{ number_format($client->total_paid ?? 0, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-8">No data available</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Subscriptions -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-history text-blue-600 mr-2"></i>
                            Recent Subscription Activity
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($recentSubscriptions->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentSubscriptions as $subscription)
                                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $subscription->tenant->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $subscription->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-green-600">${{ number_format($subscription->amount, 2) }}</p>
                                            <p class="text-xs text-gray-500 capitalize">{{ $subscription->action }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-8">No recent activity</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
