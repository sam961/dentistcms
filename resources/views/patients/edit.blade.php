<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Edit Patient: {{ $patient->full_name }}
                </h2>
                <p class="text-gray-600 mt-1">Update patient information and medical details</p>
            </div>
            <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Patient
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                <div class="p-8">
                    <form method="POST" action="{{ route('patients.update', $patient) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Personal Information -->
                            <div class="col-span-2">
                                <div class="flex items-center mb-6">
                                    <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-900">Personal Information</h3>
                                </div>
                            </div>

                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $patient->first_name) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('first_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $patient->last_name) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('last_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('date_of_birth')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                                <select name="gender" id="gender" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Information -->
                            <div class="col-span-2 mt-8">
                                <div class="flex items-center mb-6">
                                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-900">Contact Information</h3>
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $patient->email) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $patient->phone) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $patient->address) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('address')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $patient->city) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('city')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $patient->postal_code) }}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('postal_code')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Emergency Contact -->
                            <div class="col-span-2 mt-8">
                                <div class="flex items-center mb-6">
                                    <svg class="w-6 h-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-900">Emergency Contact</h3>
                                </div>
                            </div>

                            <div>
                                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('emergency_contact_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Phone</label>
                                <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('emergency_contact_phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Medical Information -->
                            <div class="col-span-2 mt-8">
                                <div class="flex items-center mb-6">
                                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-900">Medical Information</h3>
                                </div>
                            </div>

                            <div class="col-span-2">
                                <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">Medical History</label>
                                <textarea name="medical_history" id="medical_history" rows="3"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                    placeholder="Enter any relevant medical conditions, surgeries, or health concerns">{{ old('medical_history', $patient->medical_history) }}</textarea>
                                @error('medical_history')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">Allergies</label>
                                <textarea name="allergies" id="allergies" rows="2"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors bg-red-50"
                                    placeholder="List all known allergies (medications, foods, materials, etc.)">{{ old('allergies', $patient->allergies) }}</textarea>
                                @error('allergies')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="current_medications" class="block text-sm font-medium text-gray-700 mb-2">Current Medications</label>
                                <textarea name="current_medications" id="current_medications" rows="2"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                    placeholder="List all current medications including dosages">{{ old('current_medications', $patient->current_medications) }}</textarea>
                                @error('current_medications')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Insurance Information -->
                            <div class="col-span-2 mt-8">
                                <div class="flex items-center mb-6">
                                    <svg class="w-6 h-6 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-gray-900">Insurance Information</h3>
                                </div>
                            </div>

                            <div>
                                <label for="insurance_provider" class="block text-sm font-medium text-gray-700 mb-2">Insurance Provider</label>
                                <input type="text" name="insurance_provider" id="insurance_provider" value="{{ old('insurance_provider', $patient->insurance_provider) }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                    placeholder="e.g., Blue Cross Blue Shield">
                                @error('insurance_provider')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700 mb-2">Policy Number</label>
                                <input type="text" name="insurance_policy_number" id="insurance_policy_number" value="{{ old('insurance_policy_number', $patient->insurance_policy_number) }}"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors font-mono"
                                    placeholder="Enter policy number">
                                @error('insurance_policy_number')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-between pt-8 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                * Required fields
                            </div>
                            <div class="flex space-x-4">
                                <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-400 transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Patient
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>