<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                    Register New Patient
                </h2>
                <p class="text-gray-600 mt-2">Add a new patient to the system</p>
            </div>
            <a href="{{ route('patients.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Patients
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                Patient Registration Form
            </h3>
        </div>
        <div class="p-6">
                    <form method="POST" action="{{ route('patients.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            </div>

                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('first_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('last_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('date_of_birth')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
                                <select name="gender" id="gender" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Information -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('address')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('city')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('postal_code')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Emergency Contact -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Emergency Contact</h3>
                            </div>

                            <div>
                                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('emergency_contact_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                                <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('emergency_contact_phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Medical Information -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Medical Information</h3>
                            </div>

                            <div class="col-span-2">
                                <label for="medical_history" class="block text-sm font-medium text-gray-700">Medical History</label>
                                <textarea name="medical_history" id="medical_history" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('medical_history') }}</textarea>
                                @error('medical_history')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies</label>
                                <textarea name="allergies" id="allergies" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('allergies') }}</textarea>
                                @error('allergies')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="current_medications" class="block text-sm font-medium text-gray-700">Current Medications</label>
                                <textarea name="current_medications" id="current_medications" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('current_medications') }}</textarea>
                                @error('current_medications')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Insurance Information -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Insurance Information</h3>
                            </div>

                            <div>
                                <label for="insurance_provider" class="block text-sm font-medium text-gray-700">Insurance Provider</label>
                                <input type="text" name="insurance_provider" id="insurance_provider" value="{{ old('insurance_provider') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('insurance_provider')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700">Policy Number</label>
                                <input type="text" name="insurance_policy_number" id="insurance_policy_number" value="{{ old('insurance_policy_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('insurance_policy_number')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Register Patient
                            </button>
                        </div>
                    </form>
        </div>
    </div>
</x-app-sidebar-layout>