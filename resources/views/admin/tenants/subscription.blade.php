<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                    Subscription Management
                </h2>
                <p class="text-gray-600 mt-2">Manage subscription for {{ $tenant->name }}</p>
            </div>
            <a href="{{ route('admin.tenants.show', $tenant) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Tenant
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Current Subscription Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Status Card -->
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Subscription Status</h3>
                        <i class="fas fa-info-circle text-blue-200"></i>
                    </div>

                    @if($tenant->subscription_status === 'active' && $tenant->subscription_ends_at)
                        <div class="mb-3">
                            <div class="text-blue-200 text-xs mb-1">Status</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500">
                                <i class="fas fa-check-circle mr-1"></i>
                                Active
                            </span>
                        </div>
                        <div>
                            <div class="text-blue-200 text-xs mb-1">Expires On</div>
                            <div class="text-xl font-bold">
                                {{ $tenant->subscription_ends_at->format('M d, Y') }}
                            </div>
                            @php
                                $daysLeft = $tenant->daysUntilExpiration();
                            @endphp
                            @if($daysLeft !== null)
                                <div class="text-blue-200 text-sm mt-1">
                                    {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} remaining
                                </div>
                            @endif
                        </div>
                    @else
                        <div>
                            <div class="text-blue-200 text-xs mb-1">Status</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500">
                                <i class="fas fa-times-circle mr-1"></i>
                                No Active Subscription
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Duration Card -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Duration</h3>
                        <i class="fas fa-clock text-gray-400"></i>
                    </div>

                    @if($tenant->subscription_starts_at && $tenant->subscription_ends_at)
                        @php
                            $totalMonths = $tenant->subscription_starts_at->diffInMonths($tenant->subscription_ends_at);
                        @endphp
                        <div class="text-3xl font-bold text-gray-900">
                            {{ $totalMonths }} <span class="text-lg font-normal text-gray-600">{{ Str::plural('month', $totalMonths) }}</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-2">
                            {{ $tenant->subscription_starts_at->format('M d, Y') }} - {{ $tenant->subscription_ends_at->format('M d, Y') }}
                        </div>
                    @else
                        <div class="text-2xl font-bold text-gray-400">-</div>
                        <div class="text-sm text-gray-500 mt-2">No active subscription</div>
                    @endif
                </div>

                <!-- Stats Card -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                        <i class="fas fa-chart-line text-gray-400"></i>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Total Subscriptions</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $tenant->subscriptionHistory->count() }}</div>
                        </div>
                        @if($tenant->last_payment_date)
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Last Payment</div>
                                <div class="text-sm font-semibold text-gray-900">{{ $tenant->last_payment_date->format('M d, Y') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subscription History Section -->
            <div>
                <!-- Create Subscription Button -->
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-history text-gray-600 mr-2"></i>
                        Subscription History
                    </h3>
                    <button type="button"
                            onclick="document.getElementById('createForm').classList.toggle('hidden')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 shadow">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Create Subscription
                    </button>
                </div>

                <!-- Create Subscription Form (Hidden by default) -->
                <div id="createForm" class="hidden mb-6 bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-3 border-b border-green-200">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-plus-circle text-green-600 mr-2"></i>
                                Create New Subscription
                            </h4>
                            <button type="button"
                                    onclick="document.getElementById('createForm').classList.add('hidden')"
                                    class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.tenants.subscription.store', $tenant) }}" class="p-6">
                        @csrf

                        @if($errors->any())
                            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                                <div class="flex">
                                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-red-800">Please fix the following errors:</p>
                                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-600">$</span>
                                    <input type="number" name="custom_amount"
                                           value="{{ old('custom_amount') }}"
                                           step="0.01" min="0" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm @error('custom_amount') border-red-500 @enderror"
                                           placeholder="99.00">
                                </div>
                            </div>

                            <!-- Duration -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Duration *</label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="custom_duration_months"
                                           value="{{ old('custom_duration_months') }}"
                                           min="1" max="60" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm @error('custom_duration_months') border-red-500 @enderror"
                                           placeholder="12">
                                    <span class="text-gray-600 text-sm">months</span>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <input type="text" name="notes"
                                       value="{{ old('notes') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm"
                                       placeholder="e.g., Client paid cash">
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 shadow">
                                <i class="fas fa-check-circle mr-2"></i>
                                Create Subscription
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Subscription History Table -->
                @if($tenant->subscriptionHistory->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">End</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tenant->subscriptionHistory as $history)
                                        @php
                                            // Check if this is the current active subscription
                                            $isCurrent = $tenant->subscription_status === 'active'
                                                && $tenant->subscription_starts_at
                                                && $history->starts_at
                                                && $tenant->subscription_starts_at->equalTo($history->starts_at);

                                            // Calculate duration
                                            $monthsDuration = null;
                                            if ($history->starts_at && $history->ends_at) {
                                                $monthsDuration = $history->starts_at->diffInMonths($history->ends_at);
                                            }

                                            // Determine subscription status (active or expired)
                                            $subscriptionStatus = 'expired';
                                            if ($history->ends_at && $history->ends_at->isFuture()) {
                                                $subscriptionStatus = 'active';
                                            }
                                        @endphp

                                        <!-- Regular Row -->
                                        <tr class="hover:bg-gray-50 {{ $isCurrent ? 'bg-blue-50' : '' }}">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $history->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $history->starts_at ? $history->starts_at->format('M d, Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $history->ends_at ? $history->ends_at->format('M d, Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                @if($monthsDuration !== null)
                                                    {{ $monthsDuration }} {{ Str::plural('mo', $monthsDuration) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                ${{ number_format($history->amount, 2) }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($subscriptionStatus === 'active') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($subscriptionStatus) }}
                                                    @if($isCurrent)
                                                        <span class="ml-1">(Current)</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">
                                                {{ $history->notes ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                @if($isCurrent)
                                                    <button type="button"
                                                            onclick="document.getElementById('editForm{{ $history->id }}').classList.toggle('hidden')"
                                                            class="text-blue-600 hover:text-blue-900 font-medium">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Edit
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Edit Form Row (Hidden by default) -->
                                        @if($isCurrent)
                                            <tr id="editForm{{ $history->id }}" class="hidden bg-blue-100">
                                                <td colspan="8" class="px-4 py-4">
                                                    <form method="POST" action="{{ route('admin.tenants.subscription.update', $tenant) }}" class="bg-white rounded-lg p-4 shadow">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="flex justify-between items-center mb-4">
                                                            <h5 class="font-semibold text-gray-800">
                                                                <i class="fas fa-edit text-blue-600 mr-2"></i>
                                                                Edit Current Subscription
                                                            </h5>
                                                            <button type="button"
                                                                    onclick="document.getElementById('editForm{{ $history->id }}').classList.add('hidden')"
                                                                    class="text-gray-500 hover:text-gray-700">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                            <!-- Amount -->
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                                                                <div class="flex items-center space-x-2">
                                                                    <span class="text-gray-600">$</span>
                                                                    <input type="number" name="custom_amount"
                                                                           value="{{ $history->amount }}"
                                                                           step="0.01" min="0" required
                                                                           class="block w-full rounded-md border-gray-300 shadow-sm"
                                                                           placeholder="99.00">
                                                                </div>
                                                            </div>

                                                            <!-- Duration -->
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-2">Duration *</label>
                                                                <div class="flex items-center space-x-2">
                                                                    <input type="number" name="custom_duration_months"
                                                                           value="{{ $monthsDuration }}"
                                                                           min="1" max="60" required
                                                                           class="block w-full rounded-md border-gray-300 shadow-sm"
                                                                           placeholder="12">
                                                                    <span class="text-gray-600 text-sm">months from start</span>
                                                                </div>
                                                            </div>

                                                            <!-- Notes -->
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                                                <input type="text" name="notes"
                                                                       value="{{ $history->notes }}"
                                                                       class="block w-full rounded-md border-gray-300 shadow-sm"
                                                                       placeholder="e.g., Extended duration">
                                                            </div>
                                                        </div>

                                                        <div class="flex justify-end mt-4">
                                                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 shadow">
                                                                <i class="fas fa-save mr-2"></i>
                                                                Update Subscription
                                                            </button>
                                                        </div>
                                                    </form>

                                                    <!-- Separate Delete Form (Outside the update form) -->
                                                    <form method="POST" action="{{ route('admin.tenants.subscription.destroy', $tenant) }}"
                                                          class="bg-white rounded-lg p-4 shadow mt-4"
                                                          onsubmit="return confirm('Are you sure you want to remove this subscription? This action cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="start_date" value="{{ $history->starts_at->toDateTimeString() }}">
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-sm text-gray-600">
                                                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                                                Removing this subscription will immediately deactivate the tenant's access.
                                                            </p>
                                                            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 shadow">
                                                                <i class="fas fa-trash mr-2"></i>
                                                                Remove Subscription
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Subscriptions Yet</h3>
                        <p class="text-gray-600 mb-6">Create the first subscription to get started.</p>
                        <button type="button"
                                onclick="document.getElementById('createForm').classList.remove('hidden')"
                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 shadow">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Create First Subscription
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
