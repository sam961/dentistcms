<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-clinic-medical text-blue-600 mr-3"></i>
                    Manage Clients
                </h2>
                <p class="text-gray-600 mt-2">Manage all dental clinic clients and their subscriptions</p>
            </div>
            <a href="{{ route('admin.tenants.create') }}" class="btn-modern btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add New Client
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-100 rounded-xl">
                            <i class="fas fa-clinic-medical text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $tenants->total() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Clients</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-100 rounded-xl">
                            <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $tenants->where('status', 'active')->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Active Clients</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-yellow-100 rounded-xl">
                            <i class="fas fa-pause-circle text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $tenants->where('status', 'inactive')->count() + $tenants->where('status', 'suspended')->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Inactive/Suspended</div>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl mr-4 shadow-lg">
                            <i class="fas fa-list text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">All Clients</h3>
                            <p class="text-sm text-gray-600">View and manage all dental clinic clients</p>
                        </div>
                    </div>
                </div>

                @if($tenants->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Clinic Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statistics
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tenants as $tenant)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white font-bold text-lg">{{ substr($tenant->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $tenant->subdomain ? $tenant->subdomain . '.' . config('app.domain') : 'No subdomain' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $tenant->email ?? 'No email' }}</div>
                                            <div class="text-sm text-gray-500">{{ $tenant->phone ?? 'No phone' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                    <i class="fas fa-users mr-1"></i> {{ $tenant->users_count }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                    <i class="fas fa-user-injured mr-1"></i> {{ $tenant->patients_count }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                                    <i class="fas fa-user-md mr-1"></i> {{ $tenant->dentists_count }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                                                    <i class="fas fa-calendar mr-1"></i> {{ $tenant->appointments_count }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                @if($tenant->status === 'active') bg-green-100 text-green-800
                                                @elseif($tenant->status === 'inactive') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                @if($tenant->status === 'active')
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                @elseif($tenant->status === 'inactive')
                                                    <i class="fas fa-pause-circle mr-1"></i>
                                                @else
                                                    <i class="fas fa-ban mr-1"></i>
                                                @endif
                                                {{ ucfirst($tenant->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $tenant->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.tenants.show', $tenant) }}" class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this client? All associated data will be permanently deleted!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                        {{ $tenants->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <i class="fas fa-clinic-medical text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Clients Yet</h3>
                        <p class="text-gray-500 mb-6">Get started by creating your first dental clinic client.</p>
                        <a href="{{ route('admin.tenants.create') }}" class="btn-modern btn-primary inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add New Client
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
