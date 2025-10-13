<x-app-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Dentist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('dentists.store') }}" method="POST">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
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
                                    <input type="text" id="license_number" name="license_number" value="{{ old('license_number') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('license_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                                    <select id="specialization" name="specialization" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Specialization</option>
                                        <option value="General Dentistry" {{ old('specialization') == 'General Dentistry' ? 'selected' : '' }}>General Dentistry</option>
                                        <option value="Orthodontics" {{ old('specialization') == 'Orthodontics' ? 'selected' : '' }}>Orthodontics</option>
                                        <option value="Oral Surgery" {{ old('specialization') == 'Oral Surgery' ? 'selected' : '' }}>Oral Surgery</option>
                                        <option value="Endodontics" {{ old('specialization') == 'Endodontics' ? 'selected' : '' }}>Endodontics</option>
                                        <option value="Periodontics" {{ old('specialization') == 'Periodontics' ? 'selected' : '' }}>Periodontics</option>
                                        <option value="Prosthodontics" {{ old('specialization') == 'Prosthodontics' ? 'selected' : '' }}>Prosthodontics</option>
                                        <option value="Pediatric Dentistry" {{ old('specialization') == 'Pediatric Dentistry' ? 'selected' : '' }}>Pediatric Dentistry</option>
                                        <option value="Cosmetic Dentistry" {{ old('specialization') == 'Cosmetic Dentistry' ? 'selected' : '' }}>Cosmetic Dentistry</option>
                                    </select>
                                    @error('specialization')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="years_of_experience" class="block text-sm font-medium text-gray-700">Years of Experience</label>
                                    <input type="number" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience') }}" min="0" max="50" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('years_of_experience')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="qualifications" class="block text-sm font-medium text-gray-700">Qualifications</label>
                                    <textarea id="qualifications" name="qualifications" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., DDS, Harvard School of Dental Medicine, Board Certified">{{ old('qualifications') }}</textarea>
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
                                    <div class="space-y-2">
                                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <div class="flex items-center">
                                                <input type="checkbox" id="day_{{ strtolower($day) }}" name="working_days[]" value="{{ $day }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                    {{ is_array(old('working_days')) && in_array($day, old('working_days')) ? 'checked' : '' }}>
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
                                            <input type="time" id="working_hours_start" name="working_hours_start" value="{{ old('working_hours_start', '09:00') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @error('working_hours_start')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="working_hours_end" class="block text-sm font-medium text-gray-700">End Time</label>
                                            <input type="time" id="working_hours_end" name="working_hours_end" value="{{ old('working_hours_end', '17:00') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
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
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('dentists.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Add Dentist
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>