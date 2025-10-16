<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                    Calendar Timeline
                </h2>
                <p class="text-gray-600 mt-2">Visual timeline showing confirmed appointments and doctor availability</p>
            </div>
            <a href="{{ route('appointments.create') }}" class="btn-modern btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                New Appointment
            </a>
        </div>
    </x-slot>

    <!-- Controls Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <!-- Timeline Info -->
            <div class="flex items-center gap-2">
                <i class="fas fa-stream text-blue-600 text-lg"></i>
                <span class="text-lg font-semibold text-gray-800">Timeline View</span>
                <span class="text-sm text-gray-500">- Confirmed appointments only</span>
            </div>

            <!-- Date Navigation -->
            <div class="flex items-center gap-2">
                <button onclick="navigateDate('prev')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="navigateDate('today')" class="px-4 py-2 hover:bg-gray-100 rounded-lg font-medium">
                    Today
                </button>
                <input type="date" id="datePicker" value="{{ $selectedDate }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <button onclick="navigateDate('next')" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Doctor Filter -->
            <div class="relative">
                <button onclick="toggleDoctorFilter()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-user-md"></i>
                    <span id="filterText">All Doctors</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div id="doctorFilter" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                    <div class="p-3">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-medium text-sm">Filter by Doctor</span>
                            <button onclick="clearDoctorFilter()" class="text-xs text-blue-600 hover:text-blue-800">Clear All</button>
                        </div>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($dentists as $dentist)
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" value="{{ $dentist->id }}"
                                           class="dentist-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           onchange="updateDoctorFilter()">
                                    <span class="text-sm">{{ $dentist->full_name }}</span>
                                    <span class="ml-auto text-xs text-gray-500">{{ $dentist->specialization }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline View -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="height: calc(100vh - 280px);">
        <div class="p-4 border-b bg-gray-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold" id="timelineTitle"></h3>
                <div class="flex gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                        <span>Available Slots</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span>Confirmed Appointments</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-auto h-full">
            <div id="timelineContainer" class="min-w-full">
                <div class="flex items-center justify-center p-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mr-3"></i>
                    <span class="text-gray-600">Loading timeline...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Details Modal -->
    <div id="appointmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-lg shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Appointment Details</h3>
                    <button onclick="closeAppointmentModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="appointmentDetails" class="space-y-3">
                    <!-- Details will be populated here -->
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button onclick="closeAppointmentModal()" class="btn-modern btn-secondary">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

<style>
    table {
        border-collapse: collapse;
    }

    table td, table th {
        box-sizing: border-box;
    }

    .timeline-slot {
        height: 60px;
        border: 1px solid #e5e7eb;
        position: relative;
        transition: all 0.2s;
        padding: 0 !important;
        margin: 0;
    }

    .timeline-slot.occupied:hover {
        background-color: #f3f4f6;
    }

    .timeline-slot.occupied {
        cursor: pointer;
    }

    .timeline-slot.available {
        background-color: #f0fdf4;
        border-color: #bbf7d0;
        cursor: default;
    }

    .timeline-appointment {
        background-color: #ef4444;
        color: white;
        padding: 6px 4px;
        border-radius: 4px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .time-header {
        position: sticky;
        left: 0;
        background: white;
        z-index: 10;
        border-right: 2px solid #e5e7eb;
    }

    .dentist-header {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        border-bottom: 2px solid #e5e7eb;
    }

    .dentist-header th {
        white-space: nowrap;
        box-sizing: border-box;
    }
</style>

<script>
    let currentDate = '{{ $selectedDate }}';
    let selectedDentists = [];

    document.addEventListener('DOMContentLoaded', function() {
        initializeCalendar();
        loadTimeline();

        document.getElementById('datePicker').addEventListener('change', function(e) {
            currentDate = e.target.value;
            loadTimeline();
        });
    });

    function initializeCalendar() {
        updateDateDisplay();
    }

    function navigateDate(direction) {
        const date = new Date(currentDate);

        switch(direction) {
            case 'prev':
                date.setDate(date.getDate() - 1);
                break;
            case 'next':
                date.setDate(date.getDate() + 1);
                break;
            case 'today':
                currentDate = new Date().toISOString().split('T')[0];
                document.getElementById('datePicker').value = currentDate;
                loadTimeline();
                return;
        }

        currentDate = date.toISOString().split('T')[0];
        document.getElementById('datePicker').value = currentDate;
        loadTimeline();
    }

    function updateDateDisplay() {
        const date = new Date(currentDate);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = date.toLocaleDateString('en-US', options);

        if (document.getElementById('timelineTitle')) {
            document.getElementById('timelineTitle').textContent = formattedDate;
        }
    }

    function toggleDoctorFilter() {
        const filter = document.getElementById('doctorFilter');
        filter.classList.toggle('hidden');
    }

    function updateDoctorFilter() {
        selectedDentists = [];
        document.querySelectorAll('.dentist-checkbox:checked').forEach(cb => {
            selectedDentists.push(cb.value);
        });

        const filterText = document.getElementById('filterText');
        if (selectedDentists.length === 0) {
            filterText.textContent = 'All Doctors';
        } else if (selectedDentists.length === 1) {
            filterText.textContent = '1 Doctor Selected';
        } else {
            filterText.textContent = `${selectedDentists.length} Doctors Selected`;
        }

        loadTimeline();
    }

    function clearDoctorFilter() {
        document.querySelectorAll('.dentist-checkbox').forEach(cb => cb.checked = false);
        updateDoctorFilter();
    }

    async function loadTimeline() {
        updateDateDisplay();

        const params = new URLSearchParams({
            date: currentDate,
            dentists: selectedDentists.join(',')
        });

        try {
            const response = await fetch(`/api/calendar/timeline?${params}`);
            const data = await response.json();

            renderTimeline(data);
        } catch (error) {
            console.error('Error loading timeline:', error);
            const container = document.getElementById('timelineContainer');
            container.innerHTML = '<div class="p-8 text-center text-red-500">Error loading timeline data</div>';
        }
    }

    function renderTimeline(data) {
        const container = document.getElementById('timelineContainer');

        if (!data.timeline || data.timeline.length === 0) {
            container.innerHTML = '<div class="p-8 text-center text-gray-500">No doctors selected or available</div>';
            return;
        }

        // Build timeline header with time slots
        let html = '<table class="w-full border-collapse" style="table-layout: fixed;">';

        // Time header row
        html += '<thead><tr class="dentist-header">';
        html += '<th class="time-header p-3 text-left bg-gray-50 font-medium text-sm" style="width: 150px; min-width: 150px;">Doctor</th>';

        // Generate time headers (9 AM to 5 PM in 15-minute slots)
        for (let hour = 9; hour < 17; hour++) {
            html += `<th class="text-left bg-gray-50 text-[10px] font-medium border-l pl-1 pr-0 py-1" style="width: 50px;">${hour}:00</th>`;
            html += `<th class="text-left bg-gray-50 text-[10px] font-medium border-l pl-1 pr-0 py-1" style="width: 50px;">${hour}:15</th>`;
            html += `<th class="text-left bg-gray-50 text-[10px] font-medium border-l pl-1 pr-0 py-1" style="width: 50px;">${hour}:30</th>`;
            html += `<th class="text-left bg-gray-50 text-[10px] font-medium border-l border-r-2 pl-1 pr-0 py-1" style="width: 50px;">${hour}:45</th>`;
        }
        html += '</tr></thead><tbody>';

        // Render each dentist's timeline
        data.timeline.forEach(dentistData => {
            html += '<tr class="border-t hover:bg-gray-50">';

            // Dentist info cell
            html += `<td class="time-header p-3 bg-white border-r-2" style="width: 150px;">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: ${dentistData.dentist.color}"></div>
                    <div>
                        <div class="font-medium text-sm">${dentistData.dentist.name}</div>
                        <div class="text-xs text-gray-500">${dentistData.dentist.specialization}</div>
                        <div class="text-xs text-gray-400 mt-1">${dentistData.totalAppointments} confirmed (${dentistData.occupancyRate}%)</div>
                    </div>
                </div>
            </td>`;

            // Render time slots
            let skipSlots = 0;
            dentistData.timeSlots.forEach((slot, index) => {
                if (skipSlots > 0) {
                    skipSlots--;
                    return;
                }

                if (slot.appointment) {
                    const spans = slot.appointment.spans || 1;
                    skipSlots = spans - 1;

                    html += `<td colspan="${spans}" class="p-0 border-l relative" style="width: ${spans * 50}px;">
                        <div class="timeline-appointment cursor-pointer"
                             onclick="showAppointmentDetails(${JSON.stringify(slot.appointment).replace(/"/g, '&quot;')})">
                            <div class="text-center">
                                <div class="font-semibold text-xs">${slot.appointment.patient}</div>
                                <div class="text-[10px] opacity-90">${slot.appointment.treatment}</div>
                                <div class="text-[10px] opacity-75">${slot.appointment.time || ''}</div>
                            </div>
                        </div>
                    </td>`;
                } else {
                    html += `<td class="timeline-slot available border-l" style="width: 50px;"></td>`;
                }
            });

            html += '</tr>';
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    function showAppointmentDetails(appointment) {
        const modal = document.getElementById('appointmentModal');
        const details = document.getElementById('appointmentDetails');

        details.innerHTML = `
            <div class="space-y-2">
                <div><span class="font-medium">Patient:</span> ${appointment.patient}</div>
                <div><span class="font-medium">Treatment:</span> ${appointment.treatment}</div>
                <div><span class="font-medium">Duration:</span> ${appointment.duration} minutes</div>
                <div><span class="font-medium">Status:</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        CONFIRMED
                    </span>
                </div>
            </div>
        `;

        modal.classList.remove('hidden');
    }

    function closeAppointmentModal() {
        document.getElementById('appointmentModal').classList.add('hidden');
    }


    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const filter = document.getElementById('doctorFilter');
        const button = event.target.closest('button');

        if (!filter.contains(event.target) && (!button || !button.onclick || !button.onclick.toString().includes('toggleDoctorFilter'))) {
            filter.classList.add('hidden');
        }
    });
</script>

</x-app-sidebar-layout>