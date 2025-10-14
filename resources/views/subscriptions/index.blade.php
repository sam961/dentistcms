<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-history text-blue-600 mr-3"></i>
                    Subscription History
                </h2>
                <p class="text-gray-600 mt-2">View your clinic's subscription history</p>
            </div>
        </div>
    </x-slot>

    <!-- Subscription History -->
    @if($tenant->subscriptionHistory->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tenant->subscriptionHistory as $history)
                            @php
                                $isCurrent = $tenant->subscription_status === 'active'
                                    && $tenant->subscription_starts_at
                                    && $history->starts_at
                                    && $tenant->subscription_starts_at->equalTo($history->starts_at)
                                    && $history->action !== 'expired';

                                $monthsDuration = null;
                                if ($history->starts_at && $history->ends_at) {
                                    $monthsDuration = $history->starts_at->diffInMonths($history->ends_at);
                                }
                            @endphp

                            <tr class="hover:bg-gray-50 {{ $isCurrent ? 'bg-blue-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $history->starts_at ? $history->starts_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $history->ends_at ? $history->ends_at->format('M d, Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($monthsDuration !== null)
                                        {{ $monthsDuration }} {{ Str::plural('month', $monthsDuration) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    ${{ number_format($history->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Subscription History</h3>
            <p class="text-gray-600">Your subscription history will appear here once subscriptions are created.</p>
        </div>
    @endif
</x-app-sidebar-layout>
