<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                    {{ __('Appointments') }}
                </h2>
                <p class="text-gray-600 mt-2">Manage appointment schedules and bookings</p>
            </div>
            <x-action-button href="{{ route('appointments.create') }}" icon="calendar-plus" color="blue">
                Schedule New Appointment
            </x-action-button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6" x-data="appointmentFilters()">
                <div class="flex flex-col gap-6">
                    <!-- Status Filters and Calendar on Same Row -->
                    <div class="flex flex-col xl:flex-row gap-6">
                        <!-- Status Filter Section -->
                        <div class="flex-1">
                            <!-- Active Filters Display -->
                            <div class="flex flex-wrap gap-2 items-center mb-4">
                                <!-- Status Filter Badge -->
                                <div x-show="selectedStatus !== 'all'" class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                    <i class="fas fa-tag mr-2"></i>
                                    <span x-text="selectedStatus.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                                    <button @click="selectedStatus = 'all'; applyFilters()" class="ml-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <!-- Date Filter Badge -->
                                <div x-show="selectedDate !== formatDate(new Date())" class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                                    <i class="fas fa-calendar mr-2"></i>
                                    <span x-text="formatDisplayDate(selectedDate)"></span>
                                    <button @click="selectedDate = formatDate(new Date()); applyFilters()" class="ml-2 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <!-- Clear All Filters -->
                                <div x-show="selectedStatus !== 'all' || selectedDate !== formatDate(new Date())"
                                     class="inline-flex items-center">
                                    <button @click="clearAllFilters()" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Clear all filters
                                    </button>
                                </div>
                            </div>

                            <!-- Status Filter Tags -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-filter text-gray-500"></i>
                                    <span class="text-sm font-medium text-gray-700">Filter by status:</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                            <!-- All Status Tag -->
                            <button @click="selectedStatus = 'all'; applyFilters()"
                                    :class="selectedStatus === 'all' ?
                                        'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg transform scale-105' :
                                        'bg-white text-gray-700 border-2 border-gray-200 hover:border-blue-300 hover:bg-blue-50'"
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer">
                                <i class="fas fa-list mr-2"></i>
                                All
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full"
                                      :class="selectedStatus === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600'">
                                    {{ \App\Models\Appointment::count() }}
                                </span>
                            </button>

                            <!-- Confirmed Status Tag -->
                            <button @click="selectedStatus = 'confirmed'; applyFilters()"
                                    :class="selectedStatus === 'confirmed' ?
                                        'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg transform scale-105' :
                                        'bg-green-50 text-green-700 border-2 border-green-200 hover:border-green-400 hover:bg-green-100'"
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer">
                                <div class="w-2 h-2 rounded-full mr-2" :class="selectedStatus === 'confirmed' ? 'bg-white' : 'bg-green-500'"></div>
                                Confirmed
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full"
                                      :class="selectedStatus === 'confirmed' ? 'bg-white/20 text-white' : 'bg-green-100 text-green-700'">
                                    {{ \App\Models\Appointment::where('status', 'confirmed')->count() }}
                                </span>
                            </button>

                            <!-- In Progress Status Tag -->
                            <button @click="selectedStatus = 'in_progress'; applyFilters()"
                                    :class="selectedStatus === 'in_progress' ?
                                        'bg-gradient-to-r from-yellow-500 to-orange-500 text-white shadow-lg transform scale-105' :
                                        'bg-yellow-50 text-yellow-700 border-2 border-yellow-200 hover:border-yellow-400 hover:bg-yellow-100'"
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer">
                                <div class="w-2 h-2 rounded-full mr-2" :class="selectedStatus === 'in_progress' ? 'bg-white' : 'bg-yellow-500'"></div>
                                In Progress
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full"
                                      :class="selectedStatus === 'in_progress' ? 'bg-white/20 text-white' : 'bg-yellow-100 text-yellow-700'">
                                    {{ \App\Models\Appointment::where('status', 'in_progress')->count() }}
                                </span>
                            </button>

                            <!-- Completed Status Tag -->
                            <button @click="selectedStatus = 'completed'; applyFilters()"
                                    :class="selectedStatus === 'completed' ?
                                        'bg-gradient-to-r from-gray-500 to-slate-600 text-white shadow-lg transform scale-105' :
                                        'bg-gray-50 text-gray-700 border-2 border-gray-200 hover:border-gray-400 hover:bg-gray-100'"
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer">
                                <div class="w-2 h-2 rounded-full mr-2" :class="selectedStatus === 'completed' ? 'bg-white' : 'bg-gray-500'"></div>
                                Completed
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full"
                                      :class="selectedStatus === 'completed' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">
                                    {{ \App\Models\Appointment::where('status', 'completed')->count() }}
                                </span>
                            </button>

                            <!-- Cancelled Status Tag -->
                            <button @click="selectedStatus = 'cancelled'; applyFilters()"
                                    :class="selectedStatus === 'cancelled' ?
                                        'bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-lg transform scale-105' :
                                        'bg-red-50 text-red-700 border-2 border-red-200 hover:border-red-400 hover:bg-red-100'"
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer">
                                <div class="w-2 h-2 rounded-full mr-2" :class="selectedStatus === 'cancelled' ? 'bg-white' : 'bg-red-500'"></div>
                                Cancelled
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full"
                                      :class="selectedStatus === 'cancelled' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-700'">
                                    {{ \App\Models\Appointment::where('status', 'cancelled')->count() }}
                                </span>
                            </button>

                            <!-- No Show Status Tag -->
                            <button @click="selectedStatus = 'no_show'; applyFilters()"
                                    :class="selectedStatus === 'no_show' ?
                                        'bg-gradient-to-r from-purple-500 to-violet-600 text-white shadow-lg transform scale-105' :
                                        'bg-purple-50 text-purple-700 border-2 border-purple-200 hover:border-purple-400 hover:bg-purple-100'"
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer">
                                <div class="w-2 h-2 rounded-full mr-2" :class="selectedStatus === 'no_show' ? 'bg-white' : 'bg-purple-500'"></div>
                                No Show
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full"
                                      :class="selectedStatus === 'no_show' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-700'">
                                    {{ \App\Models\Appointment::where('status', 'no_show')->count() }}
                                </span>
                            </button>
                                </div>
                            </div>
                        </div>

                        <!-- Calendar Widget -->
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-4 max-w-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                    Select Date
                                </h3>
                                <button @click="selectToday()"
                                        :class="selectedDate === formatDate(new Date()) ?
                                            'bg-blue-600 text-white' :
                                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="text-xs px-3 py-1 rounded-full font-medium transition-colors">
                                    Today
                                </button>
                            </div>

                            <!-- Flatpickr Calendar Container -->
                            <div id="calendar-container"></div>

                            <!-- Selected Date Display -->
                            <div x-show="selectedDate !== formatDate(new Date())"
                                 class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-check text-blue-600"></i>
                                        <span class="text-sm font-medium text-blue-800">Selected:</span>
                                        <span class="text-sm text-blue-700" x-text="formatDisplayDate(selectedDate)"></span>
                                    </div>
                                    <button @click="selectToday()"
                                            class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flatpickr CSS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                <!-- Flatpickr JS -->
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

                <script>
                    function appointmentFilters() {
                        return {
                            selectedStatus: '{{ request('status', 'all') }}',
                            selectedDate: '{{ request('date', date('Y-m-d')) }}',
                            calendar: null,

                            init() {
                                this.initCalendar();
                            },

                            initCalendar() {
                                const self = this;
                                this.calendar = flatpickr("#calendar-container", {
                                    inline: true,
                                    defaultDate: this.selectedDate,
                                    dateFormat: "Y-m-d",
                                    theme: "light",
                                    showMonths: 1,
                                    onChange: function(selectedDates, dateStr, instance) {
                                        self.selectedDate = dateStr;
                                        self.applyFilters();
                                    }
                                });
                            },

                            formatDate(date) {
                                return date.toISOString().split('T')[0];
                            },

                            formatDisplayDate(dateString) {
                                const date = new Date(dateString);
                                const today = new Date();
                                const tomorrow = new Date(today);
                                tomorrow.setDate(today.getDate() + 1);

                                if (dateString === this.formatDate(today)) {
                                    return 'Today';
                                } else if (dateString === this.formatDate(tomorrow)) {
                                    return 'Tomorrow';
                                } else {
                                    return date.toLocaleDateString('en-US', {
                                        month: 'short',
                                        day: 'numeric',
                                        year: date.getFullYear() !== today.getFullYear() ? 'numeric' : undefined
                                    });
                                }
                            },

                            selectToday() {
                                const today = this.formatDate(new Date());
                                this.selectedDate = today;
                                if (this.calendar) {
                                    this.calendar.setDate(today);
                                }
                                this.applyFilters();
                            },

                            clearAllFilters() {
                                this.selectedStatus = 'all';
                                this.selectedDate = this.formatDate(new Date());
                                this.applyFilters();
                            },

                            applyFilters() {
                                const url = new URL(window.location);

                                // Handle status parameter
                                if (this.selectedStatus && this.selectedStatus !== 'all') {
                                    url.searchParams.set('status', this.selectedStatus);
                                } else {
                                    url.searchParams.delete('status');
                                }

                                // Handle date parameter
                                const todayString = this.formatDate(new Date());
                                if (this.selectedDate && this.selectedDate !== todayString) {
                                    url.searchParams.set('date', this.selectedDate);
                                } else {
                                    url.searchParams.delete('date');
                                }

                                window.location.href = url.toString();
                            }
                        }
                    }
                </script>
            </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                    Appointment Schedule
                </h3>
                <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm">
                    {{ $appointments->total() }} Appointments
                </span>
            </div>
        </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dentist</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($appointments as $appointment)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-calendar text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $appointment->appointment_date->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500 flex items-center">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900 flex items-center mb-1">
                                            <i class="fas fa-user text-gray-400 mr-2"></i>
                                            {{ $appointment->patient->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                                            {{ $appointment->patient->phone }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <i class="fas fa-user-md text-gray-400 mr-2"></i>
                                            Dr. {{ $appointment->dentist->full_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <i class="fas fa-procedures text-gray-400 mr-2"></i>
                                            {{ $appointment->type }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold
                                            @if($appointment->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($appointment->status === 'completed') bg-gray-100 text-gray-800
                                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                                            @elseif($appointment->status === 'in_progress') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            <div class="w-2 h-2 rounded-full mr-2
                                                @if($appointment->status === 'confirmed') bg-green-500
                                                @elseif($appointment->status === 'completed') bg-gray-500
                                                @elseif($appointment->status === 'cancelled') bg-red-500
                                                @elseif($appointment->status === 'in_progress') bg-yellow-500
                                                @else bg-gray-500
                                                @endif"></div>
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('appointments.show', $appointment) }}" class="btn-elegant bg-blue-100 text-blue-700 hover:bg-blue-200 !px-3 !py-2 text-xs">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="btn-elegant bg-indigo-100 text-indigo-700 hover:bg-indigo-200 !px-3 !py-2 text-xs">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            @if($appointment->status !== 'cancelled')
                                                <form action="{{ route('appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn-elegant bg-red-100 text-red-700 hover:bg-red-200 !px-3 !py-2 text-xs" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                                        <i class="fas fa-times mr-1"></i>
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No appointments found. <a href="{{ route('appointments.create') }}" class="text-blue-600 hover:text-blue-900">Schedule the first appointment</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
        @if($appointments->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</x-app-sidebar-layout>