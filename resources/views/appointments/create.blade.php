<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Schedule New Appointment</h1>
                <p class="text-sm text-gray-500 mt-1">Select available time slots to prevent scheduling conflicts</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('appointments.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Appointments
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="appointmentScheduler()" class="space-y-6">
        <!-- Progress Indicator -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div :class="step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                        <span :class="step >= 1 ? 'text-blue-600 font-medium' : 'text-gray-500'">Patient & Dentist</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-200"></div>
                    <div class="flex items-center space-x-2">
                        <div :class="step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                        <span :class="step >= 2 ? 'text-blue-600 font-medium' : 'text-gray-500'">Date & Time</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-200"></div>
                    <div class="flex items-center space-x-2">
                        <div :class="step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                        <span :class="step >= 3 ? 'text-blue-600 font-medium' : 'text-gray-500'">Details & Confirm</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('appointments.store') }}" method="POST" @submit.prevent="submitForm">
                    @csrf

                    <!-- Step 1: Patient & Dentist Selection -->
                    <div x-show="step === 1" class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Select Patient & Dentist</h3>
                            <p class="text-sm text-gray-500">Choose the patient and dentist for this appointment</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Patient Selection -->
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                                <select x-model="formData.patient_id" @change="validateStep1" id="patient_id" name="patient_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dentist Selection -->
                            <div>
                                <label for="dentist_id" class="block text-sm font-medium text-gray-700 mb-2">Dentist</label>
                                <select x-model="formData.dentist_id" @change="validateStep1" id="dentist_id" name="dentist_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select Dentist</option>
                                    @foreach($dentists as $dentist)
                                        <option value="{{ $dentist->id }}">Dr. {{ $dentist->full_name }}</option>
                                    @endforeach
                                </select>
                                @error('dentist_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="button" @click="nextStep" :disabled="!step1Valid" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium">
                                    Continue to Date & Time
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Date & Time Selection -->
                    <div x-show="step === 2" class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Select Date & Time</h3>
                                <p class="text-sm text-gray-500">Choose an available time slot</p>
                            </div>
                            <button type="button" @click="previousStep" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Date Selection -->
                            <div>
                                <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
                                <input type="date"
                                       x-model="formData.appointment_date"
                                       @change="loadAvailableSlots"
                                       :min="new Date().toISOString().split('T')[0]"
                                       id="appointment_date"
                                       name="appointment_date"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                @error('appointment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Duration Selection -->
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                                <select x-model="formData.duration" @change="loadAvailableSlots" id="duration" name="duration" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select Duration</option>
                                    <option value="30">30 minutes</option>
                                    <option value="45">45 minutes</option>
                                    <option value="60">1 hour</option>
                                    <option value="90">1.5 hours</option>
                                    <option value="120">2 hours</option>
                                </select>
                                @error('duration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Available Time Slots -->
                            <div x-show="formData.appointment_date && formData.duration">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Available Time Slots</label>

                                <!-- Loading State -->
                                <div x-show="loadingSlots" class="flex items-center justify-center py-8">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                    <span class="ml-3 text-gray-600">Loading available slots...</span>
                                </div>

                                <!-- No Slots Available -->
                                <div x-show="!loadingSlots && availableSlots.length === 0 && formData.appointment_date" class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 mb-2">No available slots for this date</p>
                                    <p class="text-sm text-gray-400">
                                        <span x-show="formData.duration >= 60">Long appointments require more available consecutive time.</span>
                                        Please try selecting a different date or shorter duration.
                                    </p>
                                </div>

                                <!-- Available Slots Grid -->
                                <div x-show="!loadingSlots && availableSlots.length > 0" class="space-y-4">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">
                                            <span x-text="availableSlots.length"></span> available slots for <span x-text="formData.duration"></span>-minute appointments
                                        </span>
                                        <span class="text-blue-600 font-medium">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Select your preferred time
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                        <template x-for="slot in availableSlots" :key="slot.time">
                                            <button type="button"
                                                    @click="selectTimeSlot(slot)"
                                                    :class="formData.appointment_time === slot.time ?
                                                        'bg-blue-600 text-white border-blue-600 shadow-lg' :
                                                        'bg-white text-gray-700 border-gray-300 hover:border-blue-300 hover:bg-blue-50 hover:shadow-md'"
                                                    class="p-3 border-2 rounded-xl text-center font-medium transition-all duration-200 transform hover:scale-105">
                                                <div x-text="slot.display" class="text-sm font-semibold"></div>
                                                <div x-show="formData.appointment_time === slot.time" class="text-xs mt-1 opacity-90">
                                                    Selected
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Hidden input for selected time -->
                                <input type="hidden" x-model="formData.appointment_time" name="appointment_time">
                            </div>

                            <div class="flex justify-between">
                                <button type="button" @click="previousStep" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                                    <i class="fas fa-arrow-left mr-2"></i>Previous
                                </button>
                                <button type="button" @click="nextStep" :disabled="!formData.appointment_time" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium">
                                    Continue to Details
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Details & Confirmation -->
                    <div x-show="step === 3" class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Appointment Details</h3>
                                <p class="text-sm text-gray-500">Add treatments and notes, then confirm</p>
                            </div>
                            <button type="button" @click="previousStep" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select x-model="formData.status" id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="scheduled">Scheduled</option>
                                    <option value="confirmed">Confirmed</option>
                                </select>
                            </div>

                            <!-- Treatments -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Treatments</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-48 overflow-y-auto">
                                    @foreach($treatments as $treatment)
                                        <div class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" id="treatment_{{ $treatment->id }}" name="treatments[]" value="{{ $treatment->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="treatment_{{ $treatment->id }}" class="ml-3 flex-1 text-sm">
                                                <div class="font-medium text-gray-900">{{ $treatment->name }}</div>
                                                <div class="text-gray-500">${{ number_format($treatment->price, 2) }}</div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea x-model="formData.notes" id="notes" name="notes" rows="4" placeholder="Add any special notes or instructions..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
                            </div>

                            <div class="flex justify-between">
                                <button type="button" @click="previousStep" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                                    <i class="fas fa-arrow-left mr-2"></i>Previous
                                </button>
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium">
                                    <i class="fas fa-check mr-2"></i>Create Appointment
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar Information -->
            <div class="space-y-6">
                <!-- Selected Details Summary -->
                <div x-show="step > 1" class="bg-white rounded-2xl shadow-sm p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Appointment Summary</h4>
                    <div class="space-y-3">
                        <div x-show="formData.patient_id">
                            <div class="text-sm text-gray-500">Patient</div>
                            <div class="font-medium text-gray-900" x-text="getPatientName(formData.patient_id)"></div>
                        </div>
                        <div x-show="formData.dentist_id">
                            <div class="text-sm text-gray-500">Dentist</div>
                            <div class="font-medium text-gray-900" x-text="getDentistName(formData.dentist_id)"></div>
                        </div>
                        <div x-show="formData.appointment_date">
                            <div class="text-sm text-gray-500">Date</div>
                            <div class="font-medium text-gray-900" x-text="formatDate(formData.appointment_date)"></div>
                        </div>
                        <div x-show="formData.appointment_time">
                            <div class="text-sm text-gray-500">Time</div>
                            <div class="font-medium text-gray-900" x-text="formatTime(formData.appointment_time)"></div>
                        </div>
                        <div x-show="formData.duration">
                            <div class="text-sm text-gray-500">Duration</div>
                            <div class="font-medium text-gray-900" x-text="formData.duration + ' minutes'"></div>
                        </div>
                    </div>
                </div>

                <!-- Dentist Schedule (when date is selected) -->
                <div x-show="formData.dentist_id && formData.appointment_date && step === 2" class="bg-white rounded-2xl shadow-sm p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Today's Schedule</h4>
                    <div x-show="loadingSchedule" class="flex items-center justify-center py-4">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-sm text-gray-600">Loading...</span>
                    </div>
                    <div x-show="!loadingSchedule && dentistSchedule.length === 0" class="text-center py-4">
                        <p class="text-sm text-gray-500">No appointments scheduled</p>
                    </div>
                    <div x-show="!loadingSchedule" class="space-y-2">
                        <template x-for="appointment in dentistSchedule" :key="appointment.id">
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="text-sm font-medium text-gray-900" x-text="appointment.patient_name"></div>
                                    <div class="text-xs text-gray-500" x-text="appointment.display_time"></div>
                                </div>
                                <div class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs" x-text="appointment.duration + 'min'"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-blue-50 rounded-2xl p-6">
                    <h4 class="font-semibold text-blue-900 mb-3">
                        <i class="fas fa-lightbulb mr-2"></i>Tips
                    </h4>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li>• Available slots are shown in 30-minute intervals</li>
                        <li>• Weekend appointments are not available</li>
                        <li>• Conflicts with existing appointments are automatically avoided</li>
                        <li>• You can select treatments during the final step</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function appointmentScheduler() {
            return {
                step: 1,
                step1Valid: false,
                loadingSlots: false,
                loadingSchedule: false,
                availableSlots: [],
                dentistSchedule: [],
                formData: {
                    patient_id: '{{ old("patient_id") }}',
                    dentist_id: '{{ old("dentist_id") }}',
                    appointment_date: '{{ old("appointment_date") }}',
                    appointment_time: '{{ old("appointment_time") }}',
                    duration: '{{ old("duration", "30") }}',
                    status: '{{ old("status", "scheduled") }}',
                    notes: '{{ old("notes") }}'
                },

                patients: @json($patients),
                dentists: @json($dentists),

                validateStep1() {
                    this.step1Valid = this.formData.patient_id && this.formData.dentist_id;
                },

                nextStep() {
                    if (this.step < 3) {
                        this.step++;
                    }
                },

                previousStep() {
                    if (this.step > 1) {
                        this.step--;
                    }
                },

                async loadAvailableSlots() {
                    if (!this.formData.dentist_id || !this.formData.appointment_date || !this.formData.duration) {
                        return;
                    }

                    this.loadingSlots = true;
                    this.availableSlots = [];

                    try {
                        const response = await fetch(`/api/appointments/available-slots?dentist_id=${this.formData.dentist_id}&date=${this.formData.appointment_date}&duration=${this.formData.duration}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        // The backend now considers duration when filtering slots
                        this.availableSlots = data.available_slots || [];

                        // Debug logging (temporary)
                        if (data.debug) {
                            console.log('Debug info:', data.debug);
                            console.log('Available slots:', this.availableSlots.length);
                        }

                        // Also load dentist schedule
                        this.loadDentistSchedule();
                    } catch (error) {
                        console.error('Error loading available slots:', error);
                    } finally {
                        this.loadingSlots = false;
                    }
                },

                async loadDentistSchedule() {
                    if (!this.formData.dentist_id || !this.formData.appointment_date) {
                        return;
                    }

                    this.loadingSchedule = true;

                    try {
                        const response = await fetch(`/api/dentists/${this.formData.dentist_id}/schedule?date=${this.formData.appointment_date}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        this.dentistSchedule = data.appointments || [];
                    } catch (error) {
                        console.error('Error loading dentist schedule:', error);
                    } finally {
                        this.loadingSchedule = false;
                    }
                },

                selectTimeSlot(slot) {
                    this.formData.appointment_time = slot.time;
                },

                getPatientName(patientId) {
                    const patient = this.patients.find(p => p.id == patientId);
                    return patient ? patient.full_name : '';
                },

                getDentistName(dentistId) {
                    const dentist = this.dentists.find(d => d.id == dentistId);
                    return dentist ? `Dr. ${dentist.full_name}` : '';
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

                formatTime(time) {
                    if (!time) return '';
                    const [hours, minutes] = time.split(':');
                    const date = new Date();
                    date.setHours(parseInt(hours), parseInt(minutes));
                    return date.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                },

                submitForm(event) {
                    // Let the form submit naturally
                    event.target.submit();
                }
            };
        }
    </script>
</x-app-sidebar-layout>