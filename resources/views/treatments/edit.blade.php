<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Treatment') }}
            </h2>
            <a href="{{ route('treatments.show', $treatment) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Back to Details
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('treatments.update', $treatment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Treatment Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Treatment Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $treatment->name) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Describe the treatment procedure, benefits, and any important notes...">{{ old('description', $treatment->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                                    <input type="number" id="price" name="price" value="{{ old('price', $treatment->price) }}" step="0.01" min="0" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                    <select id="duration_minutes" name="duration_minutes" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Duration</option>
                                        <option value="15" {{ old('duration_minutes', $treatment->duration_minutes) == '15' ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ old('duration_minutes', $treatment->duration_minutes) == '30' ? 'selected' : '' }}>30 minutes</option>
                                        <option value="45" {{ old('duration_minutes', $treatment->duration_minutes) == '45' ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ old('duration_minutes', $treatment->duration_minutes) == '60' ? 'selected' : '' }}>1 hour</option>
                                        <option value="90" {{ old('duration_minutes', $treatment->duration_minutes) == '90' ? 'selected' : '' }}>1.5 hours</option>
                                        <option value="120" {{ old('duration_minutes', $treatment->duration_minutes) == '120' ? 'selected' : '' }}>2 hours</option>
                                        <option value="180" {{ old('duration_minutes', $treatment->duration_minutes) == '180' ? 'selected' : '' }}>3 hours</option>
                                    </select>
                                    @error('duration_minutes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                    <select id="category" name="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Category</option>
                                        <option value="preventive" {{ old('category', $treatment->category) == 'preventive' ? 'selected' : '' }}>Preventive</option>
                                        <option value="restorative" {{ old('category', $treatment->category) == 'restorative' ? 'selected' : '' }}>Restorative</option>
                                        <option value="cosmetic" {{ old('category', $treatment->category) == 'cosmetic' ? 'selected' : '' }}>Cosmetic</option>
                                        <option value="orthodontic" {{ old('category', $treatment->category) == 'orthodontic' ? 'selected' : '' }}>Orthodontic</option>
                                        <option value="endodontic" {{ old('category', $treatment->category) == 'endodontic' ? 'selected' : '' }}>Endodontic</option>
                                        <option value="periodontic" {{ old('category', $treatment->category) == 'periodontic' ? 'selected' : '' }}>Periodontic</option>
                                        <option value="oral_surgery" {{ old('category', $treatment->category) == 'oral_surgery' ? 'selected' : '' }}>Oral Surgery</option>
                                        <option value="emergency" {{ old('category', $treatment->category) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="is_active" name="is_active" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="1" {{ old('is_active', $treatment->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $treatment->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Requirements -->
                            <div>
                                <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements & Preparations</label>
                                <textarea id="requirements" name="requirements" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Any special requirements, preparations, or contraindications...">{{ old('requirements', $treatment->requirements) }}</textarea>
                                @error('requirements')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Warning Message for Treatments with Appointments -->
                        @if($treatment->appointments()->count() > 0)
                            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Warning:</strong> This treatment has {{ $treatment->appointments()->count() }} associated appointment(s). 
                                            Changes to pricing may affect future billing and reporting.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('treatments.show', $treatment) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update Treatment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>