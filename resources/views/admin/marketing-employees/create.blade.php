<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                    {{ __('Add Marketing Employee') }}
                </h2>
                <p class="text-gray-600 mt-1">Create a new marketing team member</p>
            </div>
            <a href="{{ route('admin.marketing-employees.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('admin.marketing-employees.store') }}" method="POST">
                    @csrf

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
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                        placeholder="John Doe">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                        placeholder="john@example.com">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                                        placeholder="+1 (555) 000-0000">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Employee Code -->
                                <div>
                                    <label for="employee_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        Employee Code <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="employee_code" id="employee_code" value="{{ old('employee_code') }}" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('employee_code') border-red-500 @enderror"
                                        placeholder="MKT001">
                                    @error('employee_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Commission Percentage -->
                                <div>
                                    <label for="commission_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                        Commission Percentage <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="commission_percentage" id="commission_percentage" value="{{ old('commission_percentage', 10) }}" required
                                            min="0" max="100" step="0.01"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('commission_percentage') border-red-500 @enderror"
                                            placeholder="10">
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
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="terminated" {{ old('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
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
                                    <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required
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
                                    <input type="date" name="termination_date" id="termination_date" value="{{ old('termination_date') }}"
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
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                                placeholder="Any additional information about the employee...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 border-t">
                        <a href="{{ route('admin.marketing-employees.index') }}" class="btn-modern btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-modern btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Create Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
