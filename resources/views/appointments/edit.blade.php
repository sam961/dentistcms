<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Edit Appointment
                </h2>
                <p class="text-gray-600 mt-2">Modify appointment details and verify availability</p>
            </div>
            <a href="{{ route('appointments.show', $appointment) }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Details
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" x-data="appointmentEdit">
        <!-- Progress Steps -->
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center space-x-8">
                    <div class="flex items-center" :class="currentStep >= 1 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2"
                             :class="currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                            1
                        </div>
                        <span class="font-medium">Basic Info</span>
                    </div>
                    <div class="flex items-center" :class="currentStep >= 2 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2"
                             :class="currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                            2
                        </div>
                        <span class="font-medium">Time Selection</span>
                    </div>
                    <div class="flex items-center" :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-400'">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2"
                             :class="currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                            3
                        </div>
                        <span class="font-medium">Review & Update</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('appointments.update', $appointment) }}" method="POST" @submit="handleSubmit">
            @csrf
            @method('PUT')

            <!-- Step 1: Basic Information -->
            <div x-show="currentStep === 1" class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <!-- Patient Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-user text-blue-600 mr-2"></i>
                                Patient
                            </label>
                            <select x-model="formData.patient_id" @change="updatePatientInfo" name="patient_id" required
                                   class="input-modern w-full">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                            data-name="{{ $patient->full_name }}"
                                            data-phone="{{ $patient->phone }}"
                                            data-email="{{ $patient->email }}"
                                            {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dentist Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-user-md text-blue-600 mr-2"></i>
                                Dentist
                            </label>
                            <select x-model="formData.dentist_id" @change="updateDentistInfo" name="dentist_id" required
                                   class="input-modern w-full">
                                <option value="">Select Dentist</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist->id }}"
                                            data-name="Dr. {{ $dentist->full_name }}"
                                            data-specialization="{{ $dentist->specialization }}"
                                            {{ $appointment->dentist_id == $dentist->id ? 'selected' : '' }}>
                                        Dr. {{ $dentist->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dentist_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date and Duration -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                    Date
                                </label>
                                <input type="date" x-model="formData.appointment_date" name="appointment_date" required
                                       class="input-modern w-full"
                                       value="{{ $appointment->appointment_date->format('Y-m-d') }}"
                                       min="{{ date('Y-m-d') }}">
                                @error('appointment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">
                                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                                    Duration
                                </label>
                                <select x-model="formData.duration" name="duration" required class="input-modern w-full">
                                    <option value="">Duration</option>
                                    <option value="15" {{ $appointment->duration == 15 ? 'selected' : '' }}>15 minutes</option>
                                    <option value="30" {{ $appointment->duration == 30 ? 'selected' : '' }}>30 minutes</option>
                                    <option value="45" {{ $appointment->duration == 45 ? 'selected' : '' }}>45 minutes</option>
                                    <option value="60" {{ $appointment->duration == 60 ? 'selected' : '' }}>1 hour</option>
                                    <option value="90" {{ $appointment->duration == 90 ? 'selected' : '' }}>1.5 hours</option>
                                    <option value="120" {{ $appointment->duration == 120 ? 'selected' : '' }}>2 hours</option>
                                </select>
                                @error('duration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Status
                            </label>
                            <select x-model="formData.status" name="status" class="input-modern w-full">
                                <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ $appointment->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no_show" {{ $appointment->status == 'no_show' ? 'selected' : '' }}>No Show</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Selection Summary -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Selection</h3>

                        <div class="space-y-4">
                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900" x-text="selectedPatient.name || '{{ $appointment->patient->full_name }}'">{{ $appointment->patient->full_name }}</p>
                                    <p class="text-xs text-gray-500" x-text="selectedPatient.phone || '{{ $appointment->patient->phone }}'">{{ $appointment->patient->phone }}</p>
                                </div>
                            </div>

                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-md text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900" x-text="selectedDentist.name || 'Dr. {{ $appointment->dentist->full_name }}'">Dr. {{ $appointment->dentist->full_name }}</p>
                                    <p class="text-xs text-gray-500" x-text="selectedDentist.specialization || '{{ $appointment->dentist->specialization }}'">{{ $appointment->dentist->specialization }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="button" @click="nextStep"
                            :disabled="!canProceedToStep2"
                            :class="canProceedToStep2 ? 'btn-modern btn-primary' : 'btn-modern opacity-50 cursor-not-allowed'"
                            class="inline-flex items-center">
                        Continue to Time Selection
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Time Selection -->
            <div x-show="currentStep === 2" class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Available Time Slots</h3>
                            <button type="button" @click="loadAvailableSlots"
                                   class="btn-elegant bg-blue-100 text-blue-700 hover:bg-blue-200 text-sm">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Refresh Slots
                            </button>
                        </div>

                        <!-- Loading State -->
                        <div x-show="loading" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="mt-2 text-gray-600">Loading available slots...</p>
                        </div>

                        <!-- Error State -->
                        <div x-show="error && !loading" class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl mb-2"></i>
                            <p class="text-red-700" x-text="error"></p>
                        </div>

                        <!-- Time Slots -->
                        <div x-show="!loading && !error && availableSlots.length > 0" class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                            <template x-for="slot in availableSlots" :key="slot">
                                <button type="button"
                                       @click="selectTimeSlot(slot)"
                                       :class="formData.appointment_time === slot ?
                                               'bg-blue-600 text-white border-blue-600' :
                                               'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                       class="px-4 py-3 text-sm font-medium border rounded-lg transition-colors duration-200">
                                    <span x-text="formatTime(slot)"></span>
                                </button>
                            </template>
                        </div>

                        <!-- No Slots Available -->
                        <div x-show="!loading && !error && availableSlots.length === 0 && formData.dentist_id && formData.appointment_date && formData.duration"
                             class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">No available slots for this date</p>
                            <p class="text-sm text-gray-500 mt-1">Please try selecting a different date or shorter duration</p>
                        </div>

                        <input type="hidden" name="appointment_time" :value="formData.appointment_time">
                        @error('appointment_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Selection Summary -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointment Summary</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase font-semibold">Date & Time</label>
                                <p class="text-sm font-medium text-gray-900">
                                    <span x-text="formatDate(formData.appointment_date) || '{{ $appointment->appointment_date->format('M d, Y') }}'">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                                    <template x-if="formData.appointment_time">
                                        <span x-text="' at ' + formatTime(formData.appointment_time)"></span>
                                    </template>
                                    <template x-if="!formData.appointment_time">
                                        <span> at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</span>
                                    </template>
                                </p>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 uppercase font-semibold">Duration</label>
                                <p class="text-sm font-medium text-gray-900">
                                    <span x-text="(formData.duration || '{{ $appointment->duration }}') + ' minutes'">{{ $appointment->duration }} minutes</span>
                                </p>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 uppercase font-semibold">Patient</label>
                                <p class="text-sm font-medium text-gray-900" x-text="selectedPatient.name || '{{ $appointment->patient->full_name }}'">{{ $appointment->patient->full_name }}</p>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 uppercase font-semibold">Dentist</label>
                                <p class="text-sm font-medium text-gray-900" x-text="selectedDentist.name || 'Dr. {{ $appointment->dentist->full_name }}'">Dr. {{ $appointment->dentist->full_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" @click="prevStep" class="btn-modern btn-secondary inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Basic Info
                    </button>

                    <button type="button" @click="nextStep"
                            :disabled="!formData.appointment_time"
                            :class="formData.appointment_time ? 'btn-modern btn-primary' : 'btn-modern opacity-50 cursor-not-allowed'"
                            class="inline-flex items-center">
                        Review & Update
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Review and Confirm -->
            <div x-show="currentStep === 3" class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Treatments and Notes -->
                    <div class="space-y-6">
                        <!-- Treatments -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-procedures text-blue-600 mr-2"></i>
                                Treatments
                            </label>
                            @php
                                $selectedTreatments = old('treatments', $appointment->treatments->pluck('id')->toArray());
                            @endphp
                            <div class="grid grid-cols-1 gap-3 max-h-64 overflow-y-auto">
                                @foreach($treatments as $treatment)
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                                        <input type="checkbox" name="treatments[]" value="{{ $treatment->id }}"
                                              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                              {{ is_array($selectedTreatments) && in_array($treatment->id, $selectedTreatments) ? 'checked' : '' }}>
                                        <div class="ml-3 flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $treatment->name }}</div>
                                            <div class="text-xs text-gray-500">${{ number_format($treatment->price, 2) }} • {{ $treatment->duration_minutes ?? $treatment->duration }} min</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('treatments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-sticky-note text-blue-600 mr-2"></i>
                                Notes
                            </label>
                            <textarea name="notes" id="notes" rows="4"
                                     class="input-modern w-full resize-none"
                                     placeholder="Add any special notes or instructions...">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Final Summary -->
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointment Summary</h3>

                        <div class="space-y-4">
                            <!-- Patient Info -->
                            <div class="bg-white rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900" x-text="selectedPatient.name || '{{ $appointment->patient->full_name }}'">{{ $appointment->patient->full_name }}</p>
                                        <p class="text-sm text-gray-600" x-text="selectedPatient.phone || '{{ $appointment->patient->phone }}'">{{ $appointment->patient->phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Dentist Info -->
                            <div class="bg-white rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-user-md text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900" x-text="selectedDentist.name || 'Dr. {{ $appointment->dentist->full_name }}'">Dr. {{ $appointment->dentist->full_name }}</p>
                                        <p class="text-sm text-gray-600" x-text="selectedDentist.specialization || '{{ $appointment->dentist->specialization }}'">{{ $appointment->dentist->specialization }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Details -->
                            <div class="bg-white rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">Appointment Details</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Date:</span>
                                        <span class="font-medium" x-text="formatDate(formData.appointment_date) || '{{ $appointment->appointment_date->format('M d, Y') }}'">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Time:</span>
                                        <span class="font-medium">
                                            <template x-if="formData.appointment_time">
                                                <span x-text="formatTime(formData.appointment_time)"></span>
                                            </template>
                                            <template x-if="!formData.appointment_time">
                                                <span>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</span>
                                            </template>
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duration:</span>
                                        <span class="font-medium" x-text="(formData.duration || '{{ $appointment->duration }}') + ' minutes'">{{ $appointment->duration }} minutes</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="font-medium capitalize" x-text="formData.status || '{{ $appointment->status }}'">{{ ucfirst($appointment->status) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" @click="prevStep" class="btn-modern btn-secondary inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Time Selection
                    </button>

                    <button type="submit" class="btn-modern btn-primary inline-flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Appointment
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('appointmentEdit', () => ({
                currentStep: 1,
                loading: false,
                error: null,
                availableSlots: [],
                formData: {
                    patient_id: '{{ $appointment->patient_id }}',
                    dentist_id: '{{ $appointment->dentist_id }}',
                    appointment_date: '{{ $appointment->appointment_date->format('Y-m-d') }}',
                    appointment_time: '{{ $appointment->appointment_time }}',
                    duration: '{{ $appointment->duration }}',
                    status: '{{ $appointment->status }}'
                },
                selectedPatient: {
                    name: '{{ $appointment->patient->full_name }}',
                    phone: '{{ $appointment->patient->phone }}',
                    email: '{{ $appointment->patient->email }}'
                },
                selectedDentist: {
                    name: 'Dr. {{ $appointment->dentist->full_name }}',
                    specialization: '{{ $appointment->dentist->specialization }}'
                },

                get canProceedToStep2() {
                    return this.formData.patient_id && this.formData.dentist_id &&
                           this.formData.appointment_date && this.formData.duration;
                },

                nextStep() {
                    if (this.currentStep < 3) {
                        this.currentStep++;
                        if (this.currentStep === 2) {
                            this.loadAvailableSlots();
                        }
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },

                updatePatientInfo(event) {
                    const option = event.target.selectedOptions[0];
                    if (option && option.value) {
                        this.selectedPatient = {
                            name: option.dataset.name,
                            phone: option.dataset.phone,
                            email: option.dataset.email
                        };
                    }
                },

                updateDentistInfo(event) {
                    const option = event.target.selectedOptions[0];
                    if (option && option.value) {
                        this.selectedDentist = {
                            name: option.dataset.name,
                            specialization: option.dataset.specialization
                        };
                    }
                },

                async loadAvailableSlots() {
                    if (!this.formData.dentist_id || !this.formData.appointment_date || !this.formData.duration) {
                        return;
                    }

                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch(`/api/appointments/available-slots?dentist_id=${this.formData.dentist_id}&date=${this.formData.appointment_date}&duration=${this.formData.duration}&exclude_appointment={{ $appointment->id }}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.availableSlots = data.slots || [];
                        } else {
                            this.error = data.message || 'Failed to load available slots';
                            this.availableSlots = [];
                        }
                    } catch (error) {
                        this.error = 'Error loading available slots';
                        this.availableSlots = [];
                        console.error('Error:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                selectTimeSlot(slot) {
                    this.formData.appointment_time = slot;
                },

                formatTime(time) {
                    if (!time) return '';
                    const [hours, minutes] = time.split(':');
                    const hour = parseInt(hours);
                    const period = hour >= 12 ? 'PM' : 'AM';
                    const displayHour = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
                    return `${displayHour}:${minutes} ${period}`;
                },

                formatDate(date) {
                    if (!date) return '';
                    return new Date(date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },

                handleSubmit(event) {
                    // Form will submit normally
                }
            }))
        });
    </script>
</x-app-sidebar-layout>