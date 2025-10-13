<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Appointment Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('appointments.edit', $appointment) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit Appointment
                </a>
                <a href="{{ route('appointments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Back to Appointments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Appointment Overview Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Appointment Overview</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $appointment->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $appointment->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $appointment->status === 'no_show' ? 'bg-purple-100 text-purple-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V3m0 4v5m0-5h4m0 0V3m0 4v5M9 12h6"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-600">Date & Time</p>
                                    <p class="text-lg font-semibold text-blue-900">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                                    <p class="text-sm text-blue-700">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
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
                                    <p class="text-lg font-semibold text-green-900">{{ $appointment->duration }} min</p>
                                    <p class="text-sm text-green-700">{{ \Carbon\Carbon::parse($appointment->appointment_time)->addMinutes($appointment->duration)->format('g:i A') }} end</p>
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
                                    <p class="text-sm font-medium text-purple-600">Patient</p>
                                    <p class="text-lg font-semibold text-purple-900">{{ $appointment->patient->full_name }}</p>
                                    <p class="text-sm text-purple-700">{{ $appointment->patient->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-indigo-600">Dentist</p>
                                    <p class="text-lg font-semibold text-indigo-900">Dr. {{ $appointment->dentist->full_name }}</p>
                                    <p class="text-sm text-indigo-700">{{ $appointment->dentist->specialization }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Treatments -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Scheduled Treatments</h3>
                        @if($appointment->treatments->count() > 0)
                            <div class="space-y-4">
                                @php $totalCost = 0; @endphp
                                @foreach($appointment->treatments as $treatment)
                                    @php $totalCost += $treatment->price; @endphp
                                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $treatment->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $treatment->description }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Duration: {{ $treatment->duration_minutes }} minutes</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">${{ number_format($treatment->price, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="border-t pt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900">Total Estimated Cost:</span>
                                        <span class="text-lg font-bold text-indigo-600">${{ number_format($totalCost, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500">No treatments scheduled for this appointment.</p>
                        @endif
                    </div>
                </div>

                <!-- Patient & Dentist Info -->
                <div class="space-y-6">
                    <!-- Patient Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Name</p>
                                    <p class="text-gray-900">{{ $appointment->patient->full_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Phone</p>
                                    <p class="text-gray-900">{{ $appointment->patient->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Email</p>
                                    <p class="text-gray-900">{{ $appointment->patient->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Age</p>
                                    <p class="text-gray-900">{{ $appointment->patient->age }} years old</p>
                                </div>
                                <div class="pt-2">
                                    <a href="{{ route('patients.show', $appointment->patient) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        View Patient Profile →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dentist Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dentist Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Name</p>
                                    <p class="text-gray-900">Dr. {{ $appointment->dentist->full_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Specialization</p>
                                    <p class="text-gray-900">{{ $appointment->dentist->specialization }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Phone</p>
                                    <p class="text-gray-900">{{ $appointment->dentist->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Experience</p>
                                    <p class="text-gray-900">{{ $appointment->dentist->years_experience }} years</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($appointment->notes)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $appointment->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        @if($appointment->status !== 'completed')
                            <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif
                        
                        @if($appointment->status !== 'cancelled')
                            <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    Cancel Appointment
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium" onclick="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.')">
                                Delete Appointment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>