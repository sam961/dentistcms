<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-procedures text-blue-600 mr-3"></i>
                    Treatment Details
                </h2>
                <p class="text-gray-600 mt-2">View and manage treatment information</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('treatments.edit', $treatment) }}" class="btn-modern btn-primary inline-flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Treatment
                </a>
                <a href="{{ route('treatments.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Treatments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Treatment Overview -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $treatment->name }}</h3>
                            @if($treatment->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mt-2">
                                    {{ ucfirst(str_replace('_', ' ', $treatment->category)) }}
                                </span>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-indigo-600">${{ number_format($treatment->price, 2) }}</div>
                            <div class="text-sm text-gray-500">{{ $treatment->duration_minutes }} minutes</div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2
                                {{ $treatment->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $treatment->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-600">Price</p>
                                    <p class="text-lg font-semibold text-blue-900">${{ number_format($treatment->price, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-600">Duration</p>
                                    <p class="text-lg font-semibold text-green-900">{{ $treatment->duration_minutes }} min</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-purple-600">Category</p>
                                    <p class="text-sm font-semibold text-purple-900">{{ $treatment->category ? ucfirst(str_replace('_', ' ', $treatment->category)) : 'General' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-orange-600">Total Appointments</p>
                                    <p class="text-lg font-semibold text-orange-900">{{ $treatment->appointments()->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($treatment->description)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Description</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $treatment->description }}</p>
                            </div>
                        </div>
                    @endif

                    @if($treatment->requirements)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Requirements & Preparations</h4>
                            <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $treatment->requirements }}</p>
                            </div>
                        </div>
                    @endif
                </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Appointments with This Treatment</h3>
                    @if($treatment->appointments()->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dentist</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($treatment->appointments()->with(['patient', 'dentist'])->latest('appointment_date')->limit(10)->get() as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $appointment->patient->phone }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Dr. {{ $appointment->dentist->full_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $appointment->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $appointment->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $appointment->status === 'no-show' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('appointments.show', $appointment) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($treatment->appointments()->count() > 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">Showing 10 most recent appointments. Total: {{ $treatment->appointments()->count() }}</p>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500">No appointments have been scheduled with this treatment yet.</p>
                    @endif
                </div>
            </div>

        <!-- Actions -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('treatments.edit', $treatment) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium">
                            Edit Treatment
                        </a>
                        
                        @if($treatment->is_active)
                            <form action="{{ route('treatments.update', $treatment) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="0">
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    Deactivate Treatment
                                </button>
                            </form>
                        @else
                            <form action="{{ route('treatments.update', $treatment) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="1">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    Activate Treatment
                                </button>
                            </form>
                        @endif

                        @if($treatment->appointments()->count() === 0)
                            <form action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium" onclick="return confirm('Are you sure you want to delete this treatment? This action cannot be undone.')">
                                    Delete Treatment
                                </button>
                            </form>
                        @else
                            <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded text-sm font-medium cursor-not-allowed" disabled title="Cannot delete treatment with existing appointments">
                                Delete Treatment
                            </button>
                        @endif
                    </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>