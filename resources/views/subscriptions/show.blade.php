<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-file-invoice text-blue-600 mr-3"></i>
                    Subscription Details
                </h2>
                <p class="text-gray-600 mt-2">View subscription information and payment details</p>
            </div>
            <a href="{{ route('subscriptions.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Subscriptions
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-8">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-2xl font-bold mb-2">{{ $subscription->plan_name }}</h3>
                    @if($subscription->plan_description)
                        <p class="text-blue-100">{{ $subscription->plan_description }}</p>
                    @endif
                </div>
                <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl text-sm font-semibold uppercase">
                    {{ $subscription->status }}
                </span>
            </div>
        </div>

        <!-- Details Section -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Payment Information -->
                <div>
                    <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-dollar-sign text-blue-600 mr-2"></i>
                        Payment Information
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Amount</span>
                            <span class="font-semibold text-gray-900">{{ $subscription->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Currency</span>
                            <span class="font-semibold text-gray-900">{{ $subscription->currency }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Billing Cycle</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($subscription->billing_cycle) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Payment Method</span>
                            <span class="font-semibold text-gray-900">{{ $subscription->payment_method ?? 'N/A' }}</span>
                        </div>
                        @if($subscription->transaction_id)
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Transaction ID</span>
                                <span class="font-mono text-sm text-gray-900">{{ $subscription->transaction_id }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Subscription Timeline -->
                <div>
                    <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                        Timeline
                    </h4>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Start Date</span>
                            <span class="font-semibold text-gray-900">{{ $subscription->start_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">End Date</span>
                            <span class="font-semibold text-gray-900">{{ $subscription->end_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Renewal Date</span>
                            <span class="font-semibold text-gray-900">{{ $subscription->renewal_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Auto-Renewal</span>
                            <span class="font-semibold {{ $subscription->auto_renewal ? 'text-green-600' : 'text-red-600' }}">
                                {{ $subscription->auto_renewal ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Days Until Renewal</span>
                            <span class="font-semibold text-gray-900">
                                @php
                                    $daysUntil = $subscription->daysUntilRenewal();
                                @endphp
                                @if($daysUntil > 0)
                                    {{ $daysUntil }} days
                                @elseif($daysUntil === 0)
                                    Today
                                @else
                                    {{ abs($daysUntil) }} days overdue
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Subscription Status</h4>
                <div class="flex items-center gap-4">
                    @php
                        $statusColors = [
                            'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'check-circle'],
                            'expired' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'times-circle'],
                            'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'ban'],
                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'clock'],
                        ];
                        $statusInfo = $statusColors[$subscription->status] ?? $statusColors['pending'];
                    @endphp
                    <div class="w-16 h-16 {{ $statusInfo['bg'] }} rounded-xl flex items-center justify-center">
                        <i class="fas fa-{{ $statusInfo['icon'] }} {{ $statusInfo['text'] }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Current Status</p>
                        <p class="text-xl font-bold {{ $statusInfo['text'] }}">{{ ucfirst($subscription->status) }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                @if($subscription->status === 'active')
                    <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn-modern btn-primary">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Subscription
                    </a>
                @endif

                @if($subscription->isExpired() && $subscription->status !== 'cancelled')
                    <form method="POST" action="{{ route('subscriptions.renew', $subscription) }}">
                        @csrf
                        <button type="submit" class="btn-modern btn-primary">
                            <i class="fas fa-sync mr-2"></i>
                            Renew Subscription
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-sidebar-layout>
