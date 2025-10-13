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
                            <!-- Searchable Patient Selection -->
                            <div class="relative">
                                <label for="patient_search" class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-model="patientSearch"
                                        @click="patientDropdownOpen = true"
                                        @input="patientDropdownOpen = true"
                                        placeholder="Search patient by name or email..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    >
                                    <input type="hidden" name="patient_id" x-model="formData.patient_id" required>

                                    <!-- Dropdown List -->
                                    <div
                                        x-show="patientDropdownOpen && filteredPatients.length > 0"
                                        @click.away="patientDropdownOpen = false"
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-xl shadow-lg max-h-60 overflow-auto"
                                    >
                                        <template x-for="patient in filteredPatients" :key="patient.id">
                                            <div
                                                @click="selectPatient(patient)"
                                                class="px-4 py-3 cursor-pointer hover:bg-blue-50 border-b border-gray-100 transition-colors"
                                            >
                                                <div class="font-medium text-gray-900" x-text="patient.full_name"></div>
                                                <div class="text-xs text-gray-500 mt-0.5" x-text="patient.email"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Searchable Dentist Selection -->
                            <div class="relative">
                                <label for="dentist_search" class="block text-sm font-medium text-gray-700 mb-2">Dentist *</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-model="dentistSearch"
                                        @click="dentistDropdownOpen = true"
                                        @input="dentistDropdownOpen = true"
                                        placeholder="Search dentist by name or specialization..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    >
                                    <input type="hidden" name="dentist_id" x-model="formData.dentist_id" required>

                                    <!-- Dropdown List -->
                                    <div
                                        x-show="dentistDropdownOpen && filteredDentists.length > 0"
                                        @click.away="dentistDropdownOpen = false"
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-xl shadow-lg max-h-60 overflow-auto"
                                    >
                                        <template x-for="dentist in filteredDentists" :key="dentist.id">
                                            <div
                                                @click="selectDentist(dentist)"
                                                class="px-4 py-3 cursor-pointer hover:bg-blue-50 border-b border-gray-100 transition-colors"
                                            >
                                                <div class="font-medium text-gray-900">Dr. <span x-text="dentist.full_name"></span></div>
                                                <div class="text-xs text-gray-500 mt-0.5" x-text="dentist.specialization"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
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
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (15-minute intervals)</label>
                                <select x-model="formData.duration" @change="loadAvailableSlots" id="duration" name="duration" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select Duration</option>
                                    <option value="15">15 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option value="45">45 minutes</option>
                                    <option value="60">1 hour</option>
                                    <option value="75">1 hour 15 minutes</option>
                                    <option value="90">1 hour 30 minutes</option>
                                    <option value="105">1 hour 45 minutes</option>
                                    <option value="120">2 hours</option>
                                    <option value="135">2 hours 15 minutes</option>
                                    <option value="150">2 hours 30 minutes</option>
                                    <option value="165">2 hours 45 minutes</option>
                                    <option value="180">3 hours</option>
                                </select>
                                @error('duration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Visual Timeline Slot Picker -->
                            <div x-show="formData.appointment_date && formData.duration">
                                <div class="flex items-center justify-between mb-4">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fas fa-calendar-day mr-2 text-blue-600"></i>
                                        Select Your Time Slot
                                    </label>
                                    <div class="flex items-center space-x-4 text-xs">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded mr-1.5"></div>
                                            <span class="text-gray-600">Available</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-blue-600 rounded mr-1.5"></div>
                                            <span class="text-gray-600">Selected</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-blue-200 rounded mr-1.5"></div>
                                            <span class="text-gray-600">End Time</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-red-500 rounded mr-1.5"></div>
                                            <span class="text-gray-600">Booked</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-gray-300 rounded mr-1.5"></div>
                                            <span class="text-gray-600">Break</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Loading State -->
                                <div x-show="loadingSlots" class="flex items-center justify-center py-12 bg-gray-50 rounded-xl">
                                    <div class="text-center">
                                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mx-auto mb-3"></div>
                                        <span class="text-gray-600 text-sm">Loading timeline...</span>
                                    </div>
                                </div>

                                <!-- Debug Info Panel -->
                                <div x-show="!loadingSlots && timelineSlots.length > 0" class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="fas fa-info-circle text-yellow-600"></i>
                                        <span class="font-semibold text-gray-800">Timeline Status</span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Total slots:</span>
                                            <span class="font-semibold ml-1" x-text="timelineSlots.length"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Booked slots:</span>
                                            <span class="font-semibold ml-1 text-red-600" x-text="timelineSlots.filter(s => s.status === 'booked').length"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Appointments:</span>
                                            <span class="font-semibold ml-1" x-text="appointmentBlocks.length"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Visual Timeline with Merged Blocks -->
                                <div x-show="!loadingSlots && timelineSlots.length > 0"
                                     class="bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto"
                                     style="max-height: 70vh;">

                                    <!-- Timeline Grid -->
                                    <div class="relative" :style="`height: ${timelineSlots.length * 48}px`">
                                        <!-- Time Labels Column -->
                                        <div class="flex h-full">
                                            <div class="w-24 flex-shrink-0"></div>
                                            <div class="flex-1 relative bg-gradient-to-b from-gray-50 to-white rounded-lg border-2 border-gray-200">

                                                <!-- Time Grid Lines (every 15 minutes) -->
                                                <template x-for="(slot, index) in timelineSlots" :key="'grid-' + index">
                                                    <div
                                                        :style="`top: ${(index * 48)}px`"
                                                        :class="index % 4 === 0 ? 'border-t-2 border-gray-400' : 'border-t border-gray-200'"
                                                        class="absolute left-0 right-0">
                                                    </div>
                                                </template>

                                                <!-- Clickable Slot Zones -->
                                                <template x-for="(slot, index) in timelineSlots" :key="'slot-' + index">
                                                    <button
                                                        type="button"
                                                        @click="selectTimelineSlot(slot)"
                                                        :disabled="slot.status === 'booked' || slot.status === 'break'"
                                                        :style="`top: ${(index * 48)}px; height: 48px`"
                                                        :class="{
                                                            'bg-green-50 hover:bg-green-100 cursor-pointer': slot.status === 'available' && !isSlotInSelection(slot.time),
                                                            'bg-blue-100 border-l-4 border-blue-600': isSlotInSelection(slot.time),
                                                            'bg-red-50 cursor-not-allowed': slot.status === 'booked',
                                                            'bg-gray-100 cursor-not-allowed': slot.status === 'break'
                                                        }"
                                                        class="absolute left-0 right-0 transition-all duration-200"
                                                        :title="slot.tooltip">
                                                    </button>
                                                </template>

                                                <!-- Appointment Blocks (Overlaid) -->
                                                <template x-for="block in appointmentBlocks" :key="'block-' + block.id">
                                                    <div
                                                        :style="`top: ${(block.start_minutes / 15) * 48}px; height: ${(block.duration / 15) * 48}px`"
                                                        class="absolute left-0 right-0 mx-3 bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-xl shadow-lg border-2 border-red-800 z-10 pointer-events-none overflow-hidden">
                                                        <div class="p-3 text-white h-full flex flex-col justify-center">
                                                            <div class="font-bold text-base flex items-center">
                                                                <i class="fas fa-user-circle mr-2 text-lg"></i>
                                                                <span x-text="block.patient_name"></span>
                                                            </div>
                                                            <div class="text-sm opacity-95 mt-1.5 flex items-center">
                                                                <i class="fas fa-clock mr-1.5"></i>
                                                                <span x-text="block.start_display + ' - ' + block.end_display"></span>
                                                            </div>
                                                            <div class="text-xs opacity-90 mt-1">
                                                                <span class="bg-red-900 bg-opacity-50 px-2 py-1 rounded">
                                                                    <span x-text="block.duration"></span> minutes
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Break Time Block -->
                                                <div
                                                    :style="`top: ${16 * 48}px; height: ${4 * 48}px`"
                                                    class="absolute left-0 right-0 mx-3 bg-gradient-to-br from-gray-300 to-gray-400 rounded-xl border-2 border-gray-500 z-5 pointer-events-none flex items-center justify-center">
                                                    <div class="text-gray-700 font-semibold flex items-center">
                                                        <i class="fas fa-coffee mr-2 text-lg"></i>
                                                        Lunch Break (12:00 PM - 1:00 PM)
                                                    </div>
                                                </div>

                                                <!-- Selection Preview -->
                                                <div
                                                    x-show="selectedSlotStartTime"
                                                    :style="`top: ${getSelectedBlockTop()}px; height: ${getSelectedBlockHeight()}px`"
                                                    class="absolute left-0 right-0 mx-3 bg-blue-500 bg-opacity-60 rounded-xl border-3 border-blue-700 z-20 pointer-events-none flex items-center justify-center shadow-xl">
                                                    <div class="text-white font-bold text-base flex items-center">
                                                        <i class="fas fa-check-circle mr-2 text-lg"></i>
                                                        Selected: <span x-text="formData.duration"></span> min
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Time Labels (Absolute positioned) - Every 15 minutes -->
                                        <div class="absolute left-0 top-0 pointer-events-none">
                                            <template x-for="(slot, index) in timelineSlots" :key="'label-' + index">
                                                <div
                                                    :style="`top: ${(index * 48)}px`"
                                                    class="absolute w-24 text-xs font-medium text-gray-600 pr-2 text-right bg-white bg-opacity-95 py-0.5 rounded"
                                                    style="transform: translateY(-10px)">
                                                    <span x-text="slot.display"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- No Slots Message -->
                                <div x-show="!loadingSlots && timelineSlots.length === 0" class="text-center py-12 bg-gray-50 rounded-xl">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-600 font-medium mb-2">No Schedule Available</p>
                                    <p class="text-sm text-gray-500">The dentist is not working on this date or all slots are booked.</p>
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
                            <!-- Appointment Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Appointment Type</label>
                                <select x-model="formData.type" id="type" name="type" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select Type</option>
                                    <option value="Checkup">Checkup</option>
                                    <option value="Cleaning">Cleaning</option>
                                    <option value="Consultation">Consultation</option>
                                    <option value="Treatment">Treatment</option>
                                    <option value="Follow-up">Follow-up</option>
                                    <option value="Emergency">Emergency</option>
                                </select>
                            </div>

                            <!-- Reason -->
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit</label>
                                <textarea x-model="formData.reason" id="reason" name="reason" rows="3" required placeholder="e.g., Toothache, routine checkup, cavity filling..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select x-model="formData.status" id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="confirmed">Confirmed</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="no_show">No Show</option>
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
                timelineSlots: [],
                appointmentBlocks: [],
                dentistSchedule: [],
                selectedSlotStartTime: null,

                // Search state
                patientSearch: '',
                dentistSearch: '',
                patientDropdownOpen: false,
                dentistDropdownOpen: false,

                formData: {
                    patient_id: '{{ old("patient_id", $preselectedData["patient_id"] ?? "") }}',
                    dentist_id: '{{ old("dentist_id", $preselectedData["dentist_id"] ?? "") }}',
                    appointment_date: '{{ old("appointment_date", $preselectedData["appointment_date"] ?? "") }}',
                    appointment_time: '{{ old("appointment_time", $preselectedData["appointment_time"] ?? "") }}',
                    duration: '{{ old("duration", $preselectedData["duration"] ?? "30") }}',
                    type: '{{ old("type", "Consultation") }}',
                    reason: '{{ old("reason") }}',
                    status: '{{ old("status", "confirmed") }}',
                    notes: '{{ old("notes") }}'
                },

                patients: @json($patients),
                dentists: @json($dentists),

                // Computed property for filtered patients
                get filteredPatients() {
                    if (!this.patientSearch) {
                        return this.patients;
                    }
                    const search = this.patientSearch.toLowerCase();
                    return this.patients.filter(patient =>
                        patient.first_name.toLowerCase().includes(search) ||
                        patient.last_name.toLowerCase().includes(search) ||
                        patient.full_name.toLowerCase().includes(search) ||
                        (patient.email && patient.email.toLowerCase().includes(search))
                    );
                },

                // Computed property for filtered dentists
                get filteredDentists() {
                    if (!this.dentistSearch) {
                        return this.dentists;
                    }
                    const search = this.dentistSearch.toLowerCase();
                    return this.dentists.filter(dentist =>
                        dentist.first_name.toLowerCase().includes(search) ||
                        dentist.last_name.toLowerCase().includes(search) ||
                        dentist.full_name.toLowerCase().includes(search) ||
                        (dentist.specialization && dentist.specialization.toLowerCase().includes(search))
                    );
                },

                init() {
                    // If we have preselected data, validate step 1 and potentially load slots
                    if (this.formData.patient_id && this.formData.dentist_id) {
                        // Set search fields with selected names
                        const patient = this.patients.find(p => p.id == this.formData.patient_id);
                        const dentist = this.dentists.find(d => d.id == this.formData.dentist_id);

                        if (patient) {
                            this.patientSearch = patient.full_name;
                        }
                        if (dentist) {
                            this.dentistSearch = `Dr. ${dentist.full_name}`;
                        }

                        this.validateStep1();

                        // If we also have date and duration, load available slots
                        if (this.formData.appointment_date && this.formData.duration) {
                            this.$nextTick(() => {
                                this.loadAvailableSlots();
                            });
                        }
                    }
                },

                selectPatient(patient) {
                    this.formData.patient_id = patient.id;
                    this.patientSearch = patient.full_name;
                    this.patientDropdownOpen = false;
                    this.validateStep1();
                },

                selectDentist(dentist) {
                    this.formData.dentist_id = dentist.id;
                    this.dentistSearch = `Dr. ${dentist.full_name}`;
                    this.dentistDropdownOpen = false;
                    this.validateStep1();
                },

                validateStep1() {
                    this.step1Valid = this.formData.patient_id && this.formData.dentist_id;
                },

                nextStep() {
                    if (this.step < 3) {
                        this.step++;
                        // Load timeline when entering step 2
                        if (this.step === 2) {
                            this.loadAvailableSlots();
                        }
                    }
                },

                previousStep() {
                    if (this.step > 1) {
                        this.step--;
                        // Reload timeline when going back to step 2
                        if (this.step === 2) {
                            this.loadAvailableSlots();
                        }
                    }
                },

                async loadAvailableSlots() {
                    if (!this.formData.dentist_id || !this.formData.appointment_date || !this.formData.duration) {
                        return;
                    }

                    this.loadingSlots = true;
                    this.timelineSlots = [];
                    this.selectedSlotStartTime = null;
                    this.formData.appointment_time = '';

                    try {
                        const response = await fetch(`/api/appointments/timeline-slots?dentist_id=${this.formData.dentist_id}&date=${this.formData.appointment_date}&duration=${this.formData.duration}`, {
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

                        // Debug logging
                        console.log('=== TIMELINE API RESPONSE ===');
                        console.log('Date:', data.date);
                        console.log('Total appointments found:', data.debug?.total_appointments_found);
                        console.log('Booked slots count:', data.debug?.booked_slots_count);
                        console.log('Timeline slots received:', data.timeline_slots?.length);
                        console.log('Appointment blocks:', data.appointment_blocks);

                        // Show sample of booked slots
                        const bookedSlots = data.timeline_slots?.filter(s => s.status === 'booked');
                        console.log('Booked slots:', bookedSlots);

                        // Build timeline slots (15-minute intervals)
                        this.timelineSlots = data.timeline_slots || [];
                        this.appointmentBlocks = data.appointment_blocks || [];

                        console.log('Timeline slots set to:', this.timelineSlots.length, 'slots');
                        console.log('Appointment blocks set to:', this.appointmentBlocks.length, 'blocks');

                        // Also load dentist schedule for sidebar
                        this.loadDentistSchedule();
                    } catch (error) {
                        console.error('Error loading timeline slots:', error);
                        // Fallback: generate basic timeline if API fails
                        this.generateFallbackTimeline();
                    } finally {
                        this.loadingSlots = false;
                    }
                },

                generateFallbackTimeline() {
                    // Generate 8 AM to 6 PM slots (15-minute intervals)
                    this.timelineSlots = [];
                    for (let hour = 8; hour < 18; hour++) {
                        for (let minute = 0; minute < 60; minute += 15) {
                            const time = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
                            const displayHour = hour > 12 ? hour - 12 : hour;
                            const period = hour >= 12 ? 'PM' : 'AM';
                            this.timelineSlots.push({
                                time: time,
                                display: `${displayHour}:${String(minute).padStart(2, '0')} ${period}`,
                                status: 'available',
                                info: '',
                                tooltip: 'Available slot'
                            });
                        }
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

                selectTimelineSlot(slot) {
                    if (slot.status !== 'available') {
                        return;
                    }

                    // Check if we have enough consecutive available slots for the duration
                    const duration = parseInt(this.formData.duration);
                    const slotsNeeded = duration / 15; // Each slot is 15 minutes

                    const slotIndex = this.timelineSlots.findIndex(s => s.time === slot.time);

                    // Check if all needed consecutive slots are available
                    let allAvailable = true;
                    for (let i = 0; i < slotsNeeded; i++) {
                        if (slotIndex + i >= this.timelineSlots.length ||
                            this.timelineSlots[slotIndex + i].status !== 'available') {
                            allAvailable = false;
                            break;
                        }
                    }

                    if (!allAvailable) {
                        alert(`Not enough consecutive time available for a ${duration}-minute appointment. Please select a different time or reduce the duration.`);
                        return;
                    }

                    // Set the selected time
                    this.selectedSlotStartTime = slot.time;
                    this.formData.appointment_time = slot.time;
                },

                isSlotSelected(slotTime) {
                    if (!this.selectedSlotStartTime || !this.formData.duration) {
                        return false;
                    }

                    const duration = parseInt(this.formData.duration);
                    const slotsNeeded = duration / 15;

                    const startIndex = this.timelineSlots.findIndex(s => s.time === this.selectedSlotStartTime);
                    const currentIndex = this.timelineSlots.findIndex(s => s.time === slotTime);

                    return currentIndex >= startIndex && currentIndex < (startIndex + slotsNeeded);
                },

                isSlotEndBoundary(slotTime) {
                    if (!this.selectedSlotStartTime || !this.formData.duration) {
                        return false;
                    }

                    const duration = parseInt(this.formData.duration);
                    const slotsNeeded = duration / 15;

                    const startIndex = this.timelineSlots.findIndex(s => s.time === this.selectedSlotStartTime);
                    const currentIndex = this.timelineSlots.findIndex(s => s.time === slotTime);

                    // The end boundary is the slot right after the last selected slot
                    return currentIndex === (startIndex + slotsNeeded);
                },

                isSlotInSelection(slotTime) {
                    if (!this.selectedSlotStartTime || !this.formData.duration) {
                        return false;
                    }

                    const duration = parseInt(this.formData.duration);
                    const slotsNeeded = duration / 15;

                    const startIndex = this.timelineSlots.findIndex(s => s.time === this.selectedSlotStartTime);
                    const currentIndex = this.timelineSlots.findIndex(s => s.time === slotTime);

                    return currentIndex >= startIndex && currentIndex < (startIndex + slotsNeeded);
                },

                getSelectedBlockTop() {
                    if (!this.selectedSlotStartTime) return 0;
                    const startIndex = this.timelineSlots.findIndex(s => s.time === this.selectedSlotStartTime);
                    return startIndex * 48;
                },

                getSelectedBlockHeight() {
                    if (!this.formData.duration) return 0;
                    const duration = parseInt(this.formData.duration);
                    return (duration / 15) * 48;
                },

                selectTimeSlot(slot) {
                    // Fallback for old slot system
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