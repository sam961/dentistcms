<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <x-back-button href="{{ route('treatment-plans.index') }}" text="Back to Treatment Plans" />

            <!-- Main Header -->
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    Create Treatment Plan
                </h2>
                <p class="text-gray-600 mt-2">Design a comprehensive treatment plan for your patient</p>
            </div>
        </div>
    </x-slot>

    <form action="{{ route('treatment-plans.store') }}" method="POST" x-data="treatmentPlanForm()">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Plan Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Patient -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Patient <span class="text-red-500">*</span>
                    </label>
                    <select name="patient_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('patient_id') border-red-500 @enderror">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ (old('patient_id', $selectedPatientId ?? null) == $patient->id) ? 'selected' : '' }}>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Dentist <span class="text-red-500">*</span>
                    </label>
                    <select name="dentist_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('dentist_id') border-red-500 @enderror">
                        <option value="">Select Dentist</option>
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->id }}" {{ old('dentist_id') == $dentist->id ? 'selected' : '' }}>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Plan Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror" placeholder="e.g., Complete Dental Restoration">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phase -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Treatment Phase <span class="text-red-500">*</span>
                    </label>
                    <select name="phase" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('phase') border-red-500 @enderror">
                        <option value="immediate" {{ old('phase') == 'immediate' ? 'selected' : '' }}>Immediate - Urgent Treatment</option>
                        <option value="soon" {{ old('phase') == 'soon' ? 'selected' : '' }}>Soon - Recommended</option>
                        <option value="future" {{ old('phase') == 'future' ? 'selected' : '' }}>Future - Long-term</option>
                        <option value="optional" {{ old('phase') == 'optional' ? 'selected' : '' }}>Optional - Elective</option>
                    </select>
                    @error('phase')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <input type="number" name="priority" value="{{ old('priority', 1) }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">1 = highest priority</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Initial Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="presented" {{ old('status') == 'presented' ? 'selected' : '' }}>Presented</option>
                    </select>
                </div>

                <!-- Description (full width) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" placeholder="Describe the treatment plan objectives...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes (full width) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Treatment Items -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Treatment Items</h3>
                    <p class="text-sm text-gray-600">Add procedures to this treatment plan</p>
                </div>
                <button type="button" @click="addItem()" class="btn-modern btn-primary text-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Add Treatment
                </button>
            </div>

            <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
                <i class="fas fa-procedures text-4xl mb-3 opacity-50"></i>
                <p>No treatments added yet. Click "Add Treatment" to begin.</p>
            </div>

            <div x-show="items.length > 0" class="space-y-4">
                <template x-for="(item, index) in items" :key="index">
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-medium text-gray-900">Treatment Item #<span x-text="index + 1"></span></h4>
                            <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Treatment *</label>
                                <select :name="'items[' + index + '][treatment_id]'" x-model="item.treatment_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Treatment</option>
                                    @foreach($treatments as $treatment)
                                        <option value="{{ $treatment->id }}">{{ $treatment->name }} - ${{ number_format($treatment->price, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" min="1" value="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tooth Number</label>
                                <input type="text" :name="'items[' + index + '][tooth_number]'" x-model="item.tooth_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 14">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tooth Surface</label>
                                <input type="text" :name="'items[' + index + '][tooth_surface]'" x-model="item.tooth_surface" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Occlusal">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Estimate ($)</label>
                                <input type="number" :name="'items[' + index + '][insurance_estimate]'" x-model="item.insurance_estimate" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                            </div>

                            <div class="lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <input type="text" :name="'items[' + index + '][notes]'" x-model="item.notes" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Any special notes for this treatment...">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('treatment-plans.index') }}" class="btn-modern btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-modern btn-primary">
                <i class="fas fa-save mr-2"></i>
                Create Treatment Plan
            </button>
        </div>
    </form>

    <script>
        function treatmentPlanForm() {
            return {
                items: [],

                addItem() {
                    this.items.push({
                        treatment_id: '',
                        quantity: 1,
                        tooth_number: '',
                        tooth_surface: '',
                        insurance_estimate: 0,
                        notes: ''
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                }
            }
        }
    </script>
</x-app-sidebar-layout>
