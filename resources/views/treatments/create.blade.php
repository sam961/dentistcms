<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    Create New Treatment
                </h2>
                <p class="text-gray-600 mt-2">Add a new treatment to the catalog</p>
            </div>
            <a href="{{ route('treatments.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Treatments
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-procedures text-blue-600 mr-2"></i>
                    Treatment Details Form
                </h3>
            </div>
            <div class="p-6">
                    <form action="{{ route('treatments.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Treatment Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Treatment Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Describe the treatment procedure, benefits, and any important notes...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                                    <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                    <select id="duration_minutes" name="duration_minutes" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Duration</option>
                                        <option value="15" {{ old('duration_minutes') == '15' ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ old('duration_minutes') == '30' ? 'selected' : '' }}>30 minutes</option>
                                        <option value="45" {{ old('duration_minutes') == '45' ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ old('duration_minutes') == '60' ? 'selected' : '' }}>1 hour</option>
                                        <option value="90" {{ old('duration_minutes') == '90' ? 'selected' : '' }}>1.5 hours</option>
                                        <option value="120" {{ old('duration_minutes') == '120' ? 'selected' : '' }}>2 hours</option>
                                        <option value="180" {{ old('duration_minutes') == '180' ? 'selected' : '' }}>3 hours</option>
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
                                        <option value="preventive" {{ old('category') == 'preventive' ? 'selected' : '' }}>Preventive</option>
                                        <option value="restorative" {{ old('category') == 'restorative' ? 'selected' : '' }}>Restorative</option>
                                        <option value="cosmetic" {{ old('category') == 'cosmetic' ? 'selected' : '' }}>Cosmetic</option>
                                        <option value="orthodontic" {{ old('category') == 'orthodontic' ? 'selected' : '' }}>Orthodontic</option>
                                        <option value="endodontic" {{ old('category') == 'endodontic' ? 'selected' : '' }}>Endodontic</option>
                                        <option value="periodontic" {{ old('category') == 'periodontic' ? 'selected' : '' }}>Periodontic</option>
                                        <option value="oral_surgery" {{ old('category') == 'oral_surgery' ? 'selected' : '' }}>Oral Surgery</option>
                                        <option value="emergency" {{ old('category') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="is_active" name="is_active" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Requirements -->
                            <div>
                                <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements & Preparations</label>
                                <textarea id="requirements" name="requirements" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Any special requirements, preparations, or contraindications...">{{ old('requirements') }}</textarea>
                                @error('requirements')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('treatments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Create Treatment
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>