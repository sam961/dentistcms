<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dentist Profile') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('dentists.edit', $dentist) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit Profile
                </a>
                <a href="{{ route('dentists.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Back to Dentists
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Dentist Profile Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-start space-x-6">
                            <div class="w-24 h-24 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-3xl font-bold text-white">
                                    {{ strtoupper(substr($dentist->first_name, 0, 1) . substr($dentist->last_name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold text-gray-900">Dr. {{ $dentist->full_name }}</h3>
                                <p class="text-lg text-indigo-600 font-semibold">{{ $dentist->specialization ?: 'General Dentistry' }}</p>
                                <p class="text-sm text-gray-500">{{ $dentist->years_of_experience }} years of experience</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $dentist->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $dentist->status === 'active' ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V3m0 4v5m0-5h4m0 0V3m0 4v5M9 12h6"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-600">Total Appointments</p>
                                    <p class="text-lg font-semibold text-blue-900">{{ $dentist->appointments()->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-600">This Month</p>
                                    <p class="text-lg font-semibold text-green-900">{{ $dentist->appointments()->whereMonth('appointment_date', now()->month)->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-purple-600">Unique Patients</p>
                                    <p class="text-lg font-semibold text-purple-900">{{ $dentist->appointments()->distinct('patient_id')->count('patient_id') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-orange-600">Medical Records</p>
                                    <p class="text-lg font-semibold text-orange-900">{{ $dentist->medicalRecords()->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Contact & Personal Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Email</p>
                                <p class="text-gray-900">{{ $dentist->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Phone</p>
                                <p class="text-gray-900">{{ $dentist->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Details</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-600">License Number</p>
                                <p class="text-gray-900 font-mono">{{ $dentist->license_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Specialization</p>
                                <p class="text-gray-900">{{ $dentist->specialization ?: 'General Dentistry' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Years of Experience</p>
                                <p class="text-gray-900">{{ $dentist->years_of_experience ?: 'Not specified' }}</p>
                            </div>
                            @if($dentist->qualifications)
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Qualifications</p>
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $dentist->qualifications }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Working Schedule</h3>
                        <div class="space-y-4">
                            @if($dentist->working_days)
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Working Days</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($dentist->working_days as $day)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $day }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($dentist->working_hours_start && $dentist->working_hours_end)
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Working Hours</p>
                                    <p class="text-gray-900">{{ $dentist->working_hours_start->format('g:i A') }} - {{ $dentist->working_hours_end->format('g:i A') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <!-- Recent Appointments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Appointments</h3>
                    @if($dentist->appointments()->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Treatments</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($dentist->appointments()->with(['patient', 'treatments'])->latest('appointment_date')->limit(10)->get() as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $appointment->patient->phone }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    @if($appointment->treatments->count() > 0)
                                                        {{ $appointment->treatments->pluck('name')->join(', ') }}
                                                    @else
                                                        No treatments specified
                                                    @endif
                                                </div>
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
                        @if($dentist->appointments()->count() > 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">Showing 10 most recent appointments. Total: {{ $dentist->appointments()->count() }}</p>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500">No appointments scheduled yet.</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dentists.edit', $dentist) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium">
                            Edit Profile
                        </a>
                        
                        @if($dentist->status === 'active')
                            <form action="{{ route('dentists.update', $dentist) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    Deactivate Dentist
                                </button>
                            </form>
                        @else
                            <form action="{{ route('dentists.update', $dentist) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    Activate Dentist
                                </button>
                            </form>
                        @endif

                        @if($dentist->appointments()->count() === 0)
                            <form action="{{ route('dentists.destroy', $dentist) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium" onclick="return confirm('Are you sure you want to delete this dentist profile? This action cannot be undone.')">
                                    Delete Profile
                                </button>
                            </form>
                        @else
                            <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded text-sm font-medium cursor-not-allowed" disabled title="Cannot delete dentist with existing appointments">
                                Delete Profile
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>