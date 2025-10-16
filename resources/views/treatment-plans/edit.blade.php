<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <x-back-button href="{{ route('treatment-plans.show', $treatmentPlan) }}" text="Back to Treatment Plan" />

            <!-- Main Header -->
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Edit Treatment Plan
                </h2>
                <p class="text-gray-600 mt-2">Modify treatment plan for {{ $treatmentPlan->patient->full_name }}</p>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('treatment-plans.update', $treatmentPlan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Plan Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Patient -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient</label>
                    <select name="patient_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('patient_id') border-red-500 @enderror">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $treatmentPlan->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }} (ID: #{{ $patient->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dentist -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dentist</label>
                    <select name="dentist_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('dentist_id') border-red-500 @enderror">
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->id }}" {{ old('dentist_id', $treatmentPlan->dentist_id) == $dentist->id ? 'selected' : '' }}>
                                {{ $dentist->full_name }}{{ $dentist->specialization ? ' - ' . $dentist->specialization : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('dentist_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plan Title</label>
                    <input type="text" name="title" value="{{ old('title', $treatmentPlan->title) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phase -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Treatment Phase</label>
                    <select name="phase" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('phase') border-red-500 @enderror">
                        <option value="immediate" {{ old('phase', $treatmentPlan->phase) == 'immediate' ? 'selected' : '' }}>Immediate - Urgent Treatment</option>
                        <option value="soon" {{ old('phase', $treatmentPlan->phase) == 'soon' ? 'selected' : '' }}>Soon - Recommended</option>
                        <option value="future" {{ old('phase', $treatmentPlan->phase) == 'future' ? 'selected' : '' }}>Future - Long-term</option>
                        <option value="optional" {{ old('phase', $treatmentPlan->phase) == 'optional' ? 'selected' : '' }}>Optional - Elective</option>
                    </select>
                    @error('phase')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <input type="number" name="priority" value="{{ old('priority', $treatmentPlan->priority) }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="draft" {{ old('status', $treatmentPlan->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="presented" {{ old('status', $treatmentPlan->status) == 'presented' ? 'selected' : '' }}>Presented</option>
                        <option value="accepted" {{ old('status', $treatmentPlan->status) == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ old('status', $treatmentPlan->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="in_progress" {{ old('status', $treatmentPlan->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $treatmentPlan->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $treatmentPlan->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Description (full width) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $treatmentPlan->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes (full width) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $treatmentPlan->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Date Fields -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Timeline Dates</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Presented Date</label>
                    <input type="date" name="presented_date" value="{{ old('presented_date', $treatmentPlan->presented_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Accepted Date</label>
                    <input type="date" name="accepted_date" value="{{ old('accepted_date', $treatmentPlan->accepted_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $treatmentPlan->start_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Completion Date</label>
                    <input type="date" name="completion_date" value="{{ old('completion_date', $treatmentPlan->completion_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Current Items -->
        @if($treatmentPlan->items->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Treatment Items</h3>
                <p class="text-sm text-gray-600 mb-4">Manage items from the plan details page. This form only updates plan information.</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Treatment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tooth</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($treatmentPlan->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->treatment->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($item->tooth_number)
                                            #{{ $item->tooth_number }}
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">${{ number_format($item->total_cost, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $item->status_color }}-100 text-{{ $item->status_color }}-800">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('treatment-plans.show', $treatmentPlan) }}" class="btn-modern btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-modern btn-primary">
                <i class="fas fa-save mr-2"></i>
                Update Treatment Plan
            </button>
        </div>
    </form>
</x-app-sidebar-layout>
