<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                    Subscription Management
                </h2>
                <p class="text-gray-600 mt-2">Manage your subscription plan and view payment history</p>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Current Subscription Status -->
    @if($currentSubscription)
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-8 mb-6 text-white">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fas fa-star text-yellow-400 text-2xl"></i>
                        <h3 class="text-2xl font-bold">{{ $currentSubscription->plan_name }}</h3>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium uppercase">
                            {{ $currentSubscription->status }}
                        </span>
                    </div>

                    @if($currentSubscription->plan_description)
                        <p class="text-blue-100 mb-4">{{ $currentSubscription->plan_description }}</p>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                            <div class="text-blue-200 text-sm mb-1">Amount</div>
                            <div class="text-2xl font-bold">{{ $currentSubscription->formatted_amount }}</div>
                            <div class="text-blue-200 text-xs mt-1">{{ ucfirst($currentSubscription->billing_cycle) }}</div>
                        </div>

                        <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                            <div class="text-blue-200 text-sm mb-1">Next Renewal</div>
                            <div class="text-2xl font-bold">{{ $currentSubscription->renewal_date->format('M d, Y') }}</div>
                            <div class="text-blue-200 text-xs mt-1">
                                @php
                                    $daysUntil = $currentSubscription->daysUntilRenewal();
                                @endphp
                                @if($daysUntil > 0)
                                    In {{ $daysUntil }} days
                                @elseif($daysUntil === 0)
                                    Today
                                @else
                                    {{ abs($daysUntil) }} days overdue
                                @endif
                            </div>
                        </div>

                        <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                            <div class="text-blue-200 text-sm mb-1">Auto-Renewal</div>
                            <div class="text-2xl font-bold">
                                @if($currentSubscription->auto_renewal)
                                    <i class="fas fa-check-circle text-green-400"></i> Enabled
                                @else
                                    <i class="fas fa-times-circle text-red-400"></i> Disabled
                                @endif
                            </div>
                            <div class="text-blue-200 text-xs mt-1">Payment Method: {{ $currentSubscription->payment_method ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('subscriptions.edit', $currentSubscription) }}" class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-colors text-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Subscription
                    </a>

                    @if($currentSubscription->status === 'active')
                        <form method="POST" action="{{ route('subscriptions.cancel', $currentSubscription) }}" onsubmit="return confirm('Are you sure you want to cancel your subscription?');">
                            @csrf
                            <button type="submit" class="w-full px-6 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors">
                                <i class="fas fa-times-circle mr-2"></i>
                                Cancel Subscription
                            </button>
                        </form>
                    @endif

                    @if($currentSubscription->isExpired())
                        <form method="POST" action="{{ route('subscriptions.renew', $currentSubscription) }}">
                            @csrf
                            <button type="submit" class="w-full px-6 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
                                <i class="fas fa-sync mr-2"></i>
                                Renew Now
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-6 text-center">
            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Subscription</h3>
            <p class="text-gray-600 mb-6">Subscribe to a plan to continue using the Dentist CMS</p>
            <a href="{{ route('subscriptions.create') }}" class="btn-modern btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Subscribe Now
            </a>
        </div>
    @endif

    <!-- Payment History -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-history text-blue-600 mr-2"></i>
                    Payment History
                </h3>
                <span class="text-sm text-gray-500">{{ $paymentHistory->count() }} total payments</span>
            </div>
        </div>

        @if($paymentHistory->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
                <p>No payment history available</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Billing Cycle</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transaction</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($paymentHistory as $subscription)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $subscription->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $subscription->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $subscription->plan_name }}</div>
                                    @if($subscription->plan_description)
                                        <div class="text-xs text-gray-500 line-clamp-1">{{ $subscription->plan_description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $subscription->formatted_amount }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600 capitalize">{{ $subscription->billing_cycle }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'expired' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                        $color = $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-600">
                                        {{ $subscription->start_date->format('M d') }} - {{ $subscription->end_date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($subscription->transaction_id)
                                        <span class="text-xs font-mono text-gray-500">{{ Str::limit($subscription->transaction_id, 15) }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('subscriptions.show', $subscription) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Spent</p>
                    <p class="text-2xl font-bold text-gray-900">
                        ${{ number_format($paymentHistory->sum('amount'), 2) }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Since</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @if($paymentHistory->isNotEmpty())
                            {{ $paymentHistory->last()->start_date->format('M Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Payments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $paymentHistory->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>
