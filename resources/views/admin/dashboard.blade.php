<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    <i class="fas fa-crown text-yellow-600 mr-2"></i>
                    {{ __('Super Admin Dashboard') }}
                </h2>
                <p class="text-gray-600 mt-1">Manage all dental clinic clients and tenants</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.tenants.create') }}" class="btn-modern btn-primary inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    New Client
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Quick Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Clients Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 via-blue-500 to-purple-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-blue-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-blue-500 to-blue-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-building text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-blue-100 to-blue-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-blue-700 text-xs font-bold tracking-wider">TOTAL</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">{{ $stats['total_clients'] }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Total Clients</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-green-100 rounded-full">
                                    <i class="fas fa-chart-line text-green-600 text-xs animate-pulse"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">All clinics</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Clients Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-emerald-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-emerald-500 to-teal-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-check-circle text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-emerald-100 to-teal-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-emerald-700 text-xs font-bold tracking-wider">ACTIVE</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">{{ $stats['active_clients'] }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Active Clients</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-emerald-100 rounded-full">
                                    <i class="fas fa-heartbeat text-emerald-600 text-xs animate-pulse"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">Operating</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inactive Clients Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-400 via-orange-500 to-yellow-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-amber-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-amber-500 to-orange-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-pause-circle text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-amber-100 to-orange-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-amber-700 text-xs font-bold tracking-wider">INACTIVE</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">{{ $stats['inactive_clients'] }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Inactive Clients</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-amber-100 rounded-full">
                                    <i class="fas fa-clock text-amber-600 text-xs"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">On hold</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suspended Clients Card -->
                <div class="group relative transform transition-all duration-500 hover:scale-105 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-400 via-pink-500 to-red-600 rounded-3xl blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-500 animate-pulse"></div>
                    <div class="relative bg-white/95 backdrop-blur-xl rounded-3xl p-6 shadow-2xl border border-white/20">
                        <div class="flex items-center justify-between mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 bg-rose-400 rounded-2xl blur-lg opacity-60 animate-pulse"></div>
                                <div class="relative bg-gradient-to-br from-rose-500 to-pink-600 p-3.5 rounded-2xl shadow-lg transform transition-transform group-hover:rotate-6">
                                    <i class="fas fa-ban text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-rose-100 to-pink-200 px-3 py-1.5 rounded-full shadow-inner">
                                <span class="text-rose-700 text-xs font-bold tracking-wider">SUSPENDED</span>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-pink-600 mb-1 transition-all duration-300 group-hover:scale-110 origin-left">{{ $stats['suspended_clients'] }}</div>
                            <div class="text-sm font-semibold text-gray-700 mb-3">Suspended Clients</div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center justify-center w-6 h-6 bg-rose-100 rounded-full animate-pulse">
                                    <i class="fas fa-exclamation-triangle text-rose-600 text-xs"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-600">Needs attention</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl mr-4 shadow-lg">
                                <i class="fas fa-clinic-medical text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">All Clients</h3>
                                <p class="text-sm text-gray-600">Manage dental clinic tenants</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tenants->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clinic Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tenants as $tenant)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-600 flex items-center justify-center">
                                                        <span class="text-white font-bold text-sm">{{ substr($tenant->name, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                                    @if($tenant->subdomain)
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ $tenant->getUrl() }}" target="_blank" class="hover:text-blue-600">
                                                                {{ $tenant->subdomain }}.{{ config('app.domain') }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $tenant->email }}</div>
                                            <div class="text-sm text-gray-500">{{ $tenant->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                            <div class="flex items-center space-x-3">
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-users text-blue-500 mr-1"></i>
                                                    {{ $tenant->users_count }}
                                                </span>
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-user-injured text-green-500 mr-1"></i>
                                                    {{ $tenant->patients_count }}
                                                </span>
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-user-md text-purple-500 mr-1"></i>
                                                    {{ $tenant->dentists_count }}
                                                </span>
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-calendar text-orange-500 mr-1"></i>
                                                    {{ $tenant->appointments_count }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $tenant->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.tenants.show', $tenant) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this client? This will delete all associated data!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
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
                @else
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <i class="fas fa-clinic-medical text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No clients yet</h3>
                        <p class="text-gray-500 mb-4">Get started by creating your first dental clinic client.</p>
                        <a href="{{ route('admin.tenants.create') }}" class="btn-modern btn-primary inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Create First Client
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
