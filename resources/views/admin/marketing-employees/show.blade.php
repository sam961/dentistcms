<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    {{ $marketingEmployee->name }} - Performance Dashboard
                </h2>
                <p class="text-gray-600 mt-1">{{ $marketingEmployee->employee_code }} | {{ $marketingEmployee->email }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.marketing-employees.edit', $marketingEmployee) }}" class="btn-modern btn-secondary inline-flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Employee
                </a>
                <a href="{{ route('admin.marketing-employees.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 mt-1 mr-3"></i>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Clients -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Clients</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalClients }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-xl p-4">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Clients -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Clients</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $activeClients }}</p>
                        </div>
                        <div class="bg-green-100 rounded-xl p-4">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                            <p class="text-3xl font-bold text-purple-600 mt-2">${{ number_format($totalRevenue, 2) }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-xl p-4">
                            <i class="fas fa-dollar-sign text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Commissions -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Commissions</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">${{ number_format($totalCommissions, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $marketingEmployee->commission_percentage }}% rate</p>
                        </div>
                        <div class="bg-yellow-100 rounded-xl p-4">
                            <i class="fas fa-hand-holding-usd text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-id-card text-gray-600 mr-2"></i>
                    Employee Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Employee Code</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $marketingEmployee->employee_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="mt-1">
                            @if($marketingEmployee->status === 'active')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                            @elseif($marketingEmployee->status === 'inactive')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-pause-circle mr-1"></i> Inactive
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Terminated
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hire Date</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $marketingEmployee->hire_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Commission Rate</p>
                        <p class="text-lg font-semibold text-blue-600 mt-1">{{ $marketingEmployee->commission_percentage }}%</p>
                    </div>
                </div>
                @if($marketingEmployee->notes)
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm text-gray-600">Notes</p>
                        <p class="text-gray-900 mt-2">{{ $marketingEmployee->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Acquired Clients Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-building text-gray-600 mr-2"></i>
                        Acquired Clients ({{ $totalClients }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acquired Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($marketingEmployee->tenants as $tenant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">{{ substr($tenant->name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $tenant->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($tenant->status === 'active')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($tenant->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($tenant->subscription_status === 'active')
                                            <span class="text-sm text-green-600 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i> Active
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">
                                                {{ ucfirst($tenant->subscription_status ?? 'none') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $tenant->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-4"></i>
                                            <p class="text-lg">No clients acquired yet</p>
                                            <p class="text-sm mt-2">Clients will appear here once they are assigned to this employee</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
