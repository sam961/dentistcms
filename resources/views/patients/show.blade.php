<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ $patient->full_name }}
                </h2>
                <p class="text-gray-600 mt-1">Patient ID: #{{ $patient->id }} • Age: {{ $patient->age }} years</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Schedule Appointment
                </a>
                <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Patient
                </a>
                <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Patients
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Patient Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Personal Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                                    <p class="text-sm text-gray-900 font-medium">{{ $patient->full_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Date of Birth</label>
                                    <p class="text-sm text-gray-900">{{ $patient->date_of_birth->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Gender</label>
                                    <p class="text-sm text-gray-900 capitalize">{{ $patient->gender }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Age</label>
                                    <p class="text-sm text-gray-900">{{ $patient->age }} years</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                    <p class="text-sm text-gray-900">
                                        <a href="mailto:{{ $patient->email }}" class="text-blue-600 hover:text-blue-800">{{ $patient->email }}</a>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                                    <p class="text-sm text-gray-900">
                                        <a href="tel:{{ $patient->phone }}" class="text-blue-600 hover:text-blue-800">{{ $patient->phone }}</a>
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                                    <p class="text-sm text-gray-900">{{ $patient->address }}</p>
                                    <p class="text-sm text-gray-900">{{ $patient->city }}, {{ $patient->postal_code }}</p>
                                </div>
                                @if($patient->emergency_contact_name)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">Emergency Contact</label>
                                        <p class="text-sm text-gray-900">{{ $patient->emergency_contact_name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">Emergency Phone</label>
                                        <p class="text-sm text-gray-900">
                                            <a href="tel:{{ $patient->emergency_contact_phone }}" class="text-blue-600 hover:text-blue-800">{{ $patient->emergency_contact_phone }}</a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Medical Information
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Medical History</label>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $patient->medical_history ?: 'No medical history recorded' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Allergies</label>
                                <p class="text-sm text-gray-900 bg-red-50 p-3 rounded-lg">{{ $patient->allergies ?: 'No known allergies' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Current Medications</label>
                                <p class="text-sm text-gray-900 bg-yellow-50 p-3 rounded-lg">{{ $patient->current_medications ?: 'No current medications' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Information -->
                    @if($patient->insurance_provider)
                        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Insurance Information
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">Insurance Provider</label>
                                        <p class="text-sm text-gray-900 font-medium">{{ $patient->insurance_provider }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">Policy Number</label>
                                        <p class="text-sm text-gray-900 font-mono">{{ $patient->insurance_policy_number }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Recent Appointments -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Appointments</h3>
                        </div>
                        <div class="p-6">
                            @if($patient->appointments->count() > 0)
                                <div class="space-y-3">
                                    @foreach($patient->appointments->take(5) as $appointment)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $appointment->type }}</p>
                                                <p class="text-xs text-gray-500">{{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</p>
                                                <p class="text-xs text-gray-400">Dr. {{ $appointment->dentist->full_name }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                @if($appointment->status === 'completed') bg-green-100 text-green-800
                                                @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-800
                                                @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('patients.appointments', $patient) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                        View All Appointments
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No appointments yet</p>
                                    <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="mt-2 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                        Schedule First Appointment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Outstanding Invoices -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Outstanding Invoices</h3>
                        </div>
                        <div class="p-6">
                            @if($patient->invoices->where('status', '!=', 'paid')->count() > 0)
                                <div class="space-y-3">
                                    @foreach($patient->invoices->where('status', '!=', 'paid')->take(3) as $invoice)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                                <p class="text-xs text-gray-500">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">${{ number_format($invoice->balance, 2) }}</p>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    @if($invoice->status === 'overdue') bg-red-100 text-red-800
                                                    @elseif($invoice->status === 'sent') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('invoices.index') }}?patient_id={{ $patient->id }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                        View All Invoices
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No outstanding invoices</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Medical Records Summary -->
                    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Medical Records</h3>
                        </div>
                        <div class="p-6">
                            @if($patient->medicalRecords->count() > 0)
                                <div class="space-y-3">
                                    @foreach($patient->medicalRecords->take(3) as $record)
                                        <div class="p-3 border border-gray-200 rounded-lg">
                                            <p class="text-sm font-medium text-gray-900">{{ $record->visit_date->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ Str::limit($record->diagnosis, 50) }}</p>
                                            <p class="text-xs text-gray-400">Dr. {{ $record->dentist->full_name }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('patients.medical-records', $patient) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                        View All Records
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No medical records</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </div>
</x-app-sidebar-layout>