<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Edit Subscription
                </h2>
                <p class="text-gray-600 mt-2">Update your subscription settings</p>
            </div>
            <a href="{{ route('subscriptions.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Subscriptions
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form method="POST" action="{{ route('subscriptions.update', $subscription) }}">
            @csrf
            @method('PUT')

            <!-- Current Plan Info -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $subscription->plan_name }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-semibold text-gray-900 ml-2">{{ $subscription->formatted_amount }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Billing Cycle:</span>
                        <span class="font-semibold text-gray-900 ml-2">{{ ucfirst($subscription->billing_cycle) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 bg-{{ $subscription->status_badge_color }}-100 text-{{ $subscription->status_badge_color }}-800 rounded-full text-xs font-semibold ml-2">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Auto Renewal -->
                <div class="md:col-span-2">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="auto_renewal" value="1" {{ $subscription->auto_renewal ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Enable Auto-Renewal</span>
                            <p class="text-xs text-gray-500">Automatically renew subscription at the end of billing cycle</p>
                        </div>
                    </label>
                    @error('auto_renewal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="md:col-span-2">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" id="payment_method"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Select payment method</option>
                        <option value="credit_card" {{ $subscription->payment_method === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="debit_card" {{ $subscription->payment_method === 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                        <option value="paypal" {{ $subscription->payment_method === 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="bank_transfer" {{ $subscription->payment_method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subscription Dates Info -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-600 mb-1">Start Date</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $subscription->start_date->format('M d, Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-600 mb-1">End Date</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $subscription->end_date->format('M d, Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-600 mb-1">Next Renewal</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $subscription->renewal_date->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('subscriptions.index') }}" class="btn-modern btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-modern btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Update Subscription
                </button>
            </div>
        </form>
    </div>
</x-app-sidebar-layout>
