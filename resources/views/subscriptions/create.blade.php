<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    New Subscription
                </h2>
                <p class="text-gray-600 mt-2">Subscribe to a plan to continue using Dentist CMS</p>
            </div>
            <a href="{{ route('subscriptions.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Subscriptions
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form method="POST" action="{{ route('subscriptions.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Plan Name -->
                <div class="md:col-span-2">
                    <label for="plan_name" class="block text-sm font-medium text-gray-700 mb-2">Plan Name *</label>
                    <input type="text" name="plan_name" id="plan_name" value="{{ old('plan_name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    @error('plan_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Plan Description -->
                <div class="md:col-span-2">
                    <label for="plan_description" class="block text-sm font-medium text-gray-700 mb-2">Plan Description</label>
                    <textarea name="plan_description" id="plan_description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">{{ old('plan_description') }}</textarea>
                    @error('plan_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (USD) *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" required
                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Billing Cycle -->
                <div>
                    <label for="billing_cycle" class="block text-sm font-medium text-gray-700 mb-2">Billing Cycle *</label>
                    <select name="billing_cycle" id="billing_cycle" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="monthly" {{ old('billing_cycle') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('billing_cycle') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="yearly" {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    @error('billing_cycle')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" id="payment_method" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Select payment method</option>
                        <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="debit_card" {{ old('payment_method') === 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                        <option value="paypal" {{ old('payment_method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transaction ID -->
                <div>
                    <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">Transaction ID (Optional)</label>
                    <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    @error('transaction_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Your subscription will be activated immediately and will auto-renew based on the selected billing cycle.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('subscriptions.index') }}" class="btn-modern btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-modern btn-primary">
                    <i class="fas fa-check mr-2"></i>
                    Activate Subscription
                </button>
            </div>
        </form>
    </div>
</x-app-sidebar-layout>
