<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                    {{ __('Edit Marketing Employee') }}
                </h2>
                <p class="text-gray-600 mt-1">Update {{ $marketingEmployee->name }}'s information</p>
            </div>
            <a href="{{ route('admin.marketing-employees.show', $marketingEmployee) }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('admin.marketing-employees.update', $marketingEmployee) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-6">
                        <!-- Employee Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-user text-gray-600 mr-2"></i>
                                Employee Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $marketingEmployee->name) }}" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $marketingEmployee->email) }}" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $marketingEmployee->phone) }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Employee Code (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Employee Code
                                    </label>
                                    <div class="flex items-center px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                        <i class="fas fa-lock text-gray-400 mr-3"></i>
                                        <span class="text-sm text-gray-700 font-mono font-semibold">{{ $marketingEmployee->employee_code }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Employee code cannot be changed</p>
                                </div>

                                <!-- Commission Percentage -->
                                <div>
                                    <label for="commission_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                        Commission Percentage <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="commission_percentage" id="commission_percentage" value="{{ old('commission_percentage', $marketingEmployee->commission_percentage) }}" required
                                            min="0" max="100" step="0.01"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('commission_percentage') border-red-500 @enderror">
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                                    </div>
                                    @error('commission_percentage')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Employment Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-calendar text-gray-600 mr-2"></i>
                                Employment Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                        <option value="active" {{ old('status', $marketingEmployee->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $marketingEmployee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="terminated" {{ old('status', $marketingEmployee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Hire Date -->
                                <div>
                                    <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hire Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', $marketingEmployee->hire_date->format('Y-m-d')) }}" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('hire_date') border-red-500 @enderror">
                                    @error('hire_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Termination Date -->
                                <div>
                                    <label for="termination_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Termination Date (Optional)
                                    </label>
                                    <input type="date" name="termination_date" id="termination_date" value="{{ old('termination_date', $marketingEmployee->termination_date?->format('Y-m-d')) }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('termination_date') border-red-500 @enderror">
                                    @error('termination_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-sticky-note text-gray-600 mr-2"></i>
                                Additional Notes
                            </h3>
                            <textarea name="notes" id="notes" rows="4"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $marketingEmployee->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 border-t">
                        <a href="{{ route('admin.marketing-employees.show', $marketingEmployee) }}" class="btn-modern btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-modern btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
