<x-app-sidebar-layout>
    <div class="mb-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                @if($selectedPatientId)
                    <a href="{{ route('patients.show', $selectedPatientId) }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                @else
                    <a href="{{ route('perio-charts.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                @endif
                <div>
                    <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                        <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                        New Periodontal Chart
                    </h2>
                    <p class="text-gray-600 mt-2">Create a new periodontal charting session</p>
                </div>
            </div>
        </div>

        <!-- Patient Info Banner (when pre-selected) -->
        @if($selectedPatientId)
            @php
                $selectedPatient = $patients->firstWhere('id', $selectedPatientId);
            @endphp
            @if($selectedPatient)
                <div class="bg-gradient-to-r from-teal-50 to-blue-50 border-l-4 border-teal-500 rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-user-circle text-teal-600"></i>
                                Creating chart for: {{ $selectedPatient->full_name }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-id-badge text-gray-400 mr-1"></i>
                                Patient ID: #{{ $selectedPatient->id }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-birthday-cake text-gray-400 mr-1"></i>
                                Age: {{ $selectedPatient->age }} years
                                <span class="mx-2">•</span>
                                <i class="fas fa-venus-mars text-gray-400 mr-1"></i>
                                {{ ucfirst($selectedPatient->gender) }}
                            </p>
                        </div>
                        <a href="{{ route('patients.show', $selectedPatient) }}"
                           class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                            <i class="fas fa-arrow-right mr-1"></i>
                            View Profile
                        </a>
                    </div>
                </div>
            @endif
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <form method="POST" action="{{ route('perio-charts.store') }}">
                @csrf

                <!-- Hidden input for pre-selected patient -->
                @if($selectedPatientId)
                    <input type="hidden" name="patient_id" value="{{ $selectedPatientId }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-{{ $selectedPatientId ? '3' : '2' }} gap-6">
                    <!-- Patient Selection (only show if not pre-selected) -->
                    @if(!$selectedPatientId)
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Patient <span class="text-red-500">*</span>
                            </label>
                            <select id="patient_id"
                                    name="patient_id"
                                    required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('patient_id') border-red-500 @enderror">
                                <option value="">Select a patient...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                            {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Dentist Selection -->
                    <div>
                        <label for="dentist_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Dentist <span class="text-red-500">*</span>
                        </label>
                        <select id="dentist_id"
                                name="dentist_id"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('dentist_id') border-red-500 @enderror">
                            <option value="">Select a dentist...</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}" {{ old('dentist_id') == $dentist->id ? 'selected' : '' }}>
                                    {{ $dentist->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('dentist_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Chart Date -->
                    <div>
                        <label for="chart_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Chart Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               id="chart_date"
                               name="chart_date"
                               value="{{ old('chart_date', date('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('chart_date') border-red-500 @enderror">
                        @error('chart_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Chart Type -->
                    <div>
                        <label for="chart_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Chart Type <span class="text-red-500">*</span>
                        </label>
                        <select id="chart_type"
                                name="chart_type"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('chart_type') border-red-500 @enderror">
                            <option value="adult" {{ old('chart_type', 'adult') === 'adult' ? 'selected' : '' }}>
                                Adult (32 teeth)
                            </option>
                            <option value="primary" {{ old('chart_type') === 'primary' ? 'selected' : '' }}>
                                Primary (20 teeth)
                            </option>
                        </select>
                        @error('chart_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea id="notes"
                              name="notes"
                              rows="4"
                              placeholder="Enter any additional notes or observations..."
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Optional: General observations, patient concerns, or clinical notes</p>
                </div>

                <!-- Info Box -->
                <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                After creating the chart, you'll be redirected to the interactive editor where you can enter detailed measurements for each tooth.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex items-center justify-end gap-4">
                    @if($selectedPatientId)
                        <a href="{{ route('patients.show', $selectedPatientId) }}" class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    @else
                        <a href="{{ route('perio-charts.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    @endif
                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-teal-600 to-teal-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-teal-700 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-check-circle mr-2"></i>
                        Create Chart
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- What's Next Section -->
        <div class="mt-6 bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                What happens next?
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">1</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Create Chart</h4>
                        <p class="text-sm text-gray-600">Fill out the basic information above</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">2</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Enter Measurements</h4>
                        <p class="text-sm text-gray-600">Use the interactive editor to record detailed measurements</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">3</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Save & Review</h4>
                        <p class="text-sm text-gray-600">Review statistics and print if needed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize Select2 for better dropdowns (if available)
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#patient_id, #dentist_id').select2({
                placeholder: 'Select...',
                allowClear: true
            });
        }
    </script>
    @endpush
</x-app-sidebar-layout>
