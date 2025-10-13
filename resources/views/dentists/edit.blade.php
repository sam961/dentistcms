<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Dentist Profile') }}
            </h2>
            <a href="{{ route('dentists.show', $dentist) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('dentists.update', $dentist) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $dentist->first_name) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $dentist->last_name) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $dentist->email) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $dentist->phone) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="license_number" class="block text-sm font-medium text-gray-700">License Number</label>
                                    <input type="text" id="license_number" name="license_number" value="{{ old('license_number', $dentist->license_number) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('license_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                                    <select id="specialization" name="specialization" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Specialization</option>
                                        <option value="General Dentistry" {{ old('specialization', $dentist->specialization) == 'General Dentistry' ? 'selected' : '' }}>General Dentistry</option>
                                        <option value="Orthodontics" {{ old('specialization', $dentist->specialization) == 'Orthodontics' ? 'selected' : '' }}>Orthodontics</option>
                                        <option value="Oral Surgery" {{ old('specialization', $dentist->specialization) == 'Oral Surgery' ? 'selected' : '' }}>Oral Surgery</option>
                                        <option value="Endodontics" {{ old('specialization', $dentist->specialization) == 'Endodontics' ? 'selected' : '' }}>Endodontics</option>
                                        <option value="Periodontics" {{ old('specialization', $dentist->specialization) == 'Periodontics' ? 'selected' : '' }}>Periodontics</option>
                                        <option value="Prosthodontics" {{ old('specialization', $dentist->specialization) == 'Prosthodontics' ? 'selected' : '' }}>Prosthodontics</option>
                                        <option value="Pediatric Dentistry" {{ old('specialization', $dentist->specialization) == 'Pediatric Dentistry' ? 'selected' : '' }}>Pediatric Dentistry</option>
                                        <option value="Cosmetic Dentistry" {{ old('specialization', $dentist->specialization) == 'Cosmetic Dentistry' ? 'selected' : '' }}>Cosmetic Dentistry</option>
                                    </select>
                                    @error('specialization')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="years_of_experience" class="block text-sm font-medium text-gray-700">Years of Experience</label>
                                    <input type="number" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience', $dentist->years_of_experience) }}" min="0" max="50" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('years_of_experience')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="qualifications" class="block text-sm font-medium text-gray-700">Qualifications</label>
                                    <textarea id="qualifications" name="qualifications" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., DDS, Harvard School of Dental Medicine, Board Certified">{{ old('qualifications', $dentist->qualifications) }}</textarea>
                                    @error('qualifications')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Working Schedule</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="working_days" class="block text-sm font-medium text-gray-700 mb-3">Working Days</label>
                                    @php
                                        $selectedDays = old('working_days', $dentist->working_days ?? []);
                                    @endphp
                                    <div class="space-y-2">
                                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <div class="flex items-center">
                                                <input type="checkbox" id="day_{{ strtolower($day) }}" name="working_days[]" value="{{ $day }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                    {{ is_array($selectedDays) && in_array($day, $selectedDays) ? 'checked' : '' }}>
                                                <label for="day_{{ strtolower($day) }}" class="ml-2 block text-sm text-gray-900">{{ $day }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('working_days')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label for="working_hours_start" class="block text-sm font-medium text-gray-700">Start Time</label>
                                            <input type="time" id="working_hours_start" name="working_hours_start" value="{{ old('working_hours_start', $dentist->working_hours_start ? $dentist->working_hours_start->format('H:i') : '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @error('working_hours_start')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="working_hours_end" class="block text-sm font-medium text-gray-700">End Time</label>
                                            <input type="time" id="working_hours_end" name="working_hours_end" value="{{ old('working_hours_end', $dentist->working_hours_end ? $dentist->working_hours_end->format('H:i') : '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @error('working_hours_end')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Employment Status</label>
                                <select id="status" name="status" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="active" {{ old('status', $dentist->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $dentist->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Warning Message for Dentists with Appointments -->
                        @if($dentist->appointments()->count() > 0)
                            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Warning:</strong> This dentist has {{ $dentist->appointments()->count() }} associated appointment(s). 
                                            Changes to personal information will be reflected in all related records.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('dentists.show', $dentist) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>