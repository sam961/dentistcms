<x-app-sidebar-layout>
    <div class="mb-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('patients.perio-charts', $perioChart->patient) }}" class="text-gray-600 hover:text-gray-900 hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex-1">
                    <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                        <i class="fas fa-edit text-blue-600 mr-3"></i>
                        Edit Periodontal Chart
                    </h2>
                    <p class="text-gray-600 mt-2">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        {{ $perioChart->patient->full_name }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-calendar text-gray-400 mr-1"></i>
                        {{ \Carbon\Carbon::parse($perioChart->chart_date)->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('perio-charts.show', $perioChart) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-teal-700 hover:to-teal-800 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-eye mr-2"></i>
                        View
                    </a>
                    <a href="{{ route('perio-charts.print', $perioChart) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-purple-700 hover:to-purple-800 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5" target="_blank">
                        <i class="fas fa-print mr-2"></i>
                        Print
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
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

        <!-- Basic Info Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Chart Information</h3>
                    <p class="text-sm text-gray-500 mt-1">Update patient, dentist, date, or notes</p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle"></i>
                    <span>Changes save immediately</span>
                </div>
            </div>

            <form method="POST" action="{{ route('perio-charts.update', $perioChart) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Patient -->
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-blue-500 mr-1"></i>
                            Patient
                        </label>
                        <select id="patient_id" name="patient_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $perioChart->patient_id == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dentist -->
                    <div>
                        <label for="dentist_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-md text-green-500 mr-1"></i>
                            Dentist
                        </label>
                        <select id="dentist_id" name="dentist_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}" {{ $perioChart->dentist_id == $dentist->id ? 'selected' : '' }}>
                                    {{ $dentist->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chart Date -->
                    <div>
                        <label for="chart_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-purple-500 mr-1"></i>
                            Chart Date
                        </label>
                        <input type="date" id="chart_date" name="chart_date" value="{{ $perioChart->chart_date }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-notes-medical text-orange-500 mr-1"></i>
                        Clinical Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter any clinical observations, patient concerns, or treatment notes...">{{ $perioChart->notes }}</textarea>
                </div>

                <!-- Enhanced Save Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Update Chart Information
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Bleeding on Probing</p>
                        <p class="text-3xl font-bold text-red-700 mt-2">{{ $perioChart->bleeding_percentage }}%</p>
                    </div>
                    <i class="fas fa-droplet text-red-400 text-3xl"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-600">Plaque Index</p>
                        <p class="text-3xl font-bold text-yellow-700 mt-2">{{ $perioChart->plaque_percentage }}%</p>
                    </div>
                    <i class="fas fa-bacteria text-yellow-400 text-3xl"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Avg Pocket Depth</p>
                        <p class="text-3xl font-bold text-blue-700 mt-2">{{ $perioChart->average_pocket_depth }}mm</p>
                    </div>
                    <i class="fas fa-ruler-vertical text-blue-400 text-3xl"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Severity Level</p>
                        @php
                            $severityColors = [
                                'healthy' => 'text-green-700',
                                'mild' => 'text-yellow-700',
                                'moderate' => 'text-orange-700',
                                'severe' => 'text-red-700',
                            ];
                        @endphp
                        <p class="text-2xl font-bold mt-2 {{ $severityColors[$perioChart->severity_level] }}">
                            {{ ucfirst($perioChart->severity_level) }}
                        </p>
                    </div>
                    <i class="fas fa-exclamation-triangle text-gray-400 text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Alpine.js Perio Chart Editor -->
        @include('perio-charts.partials.alpine-editor', ['perioChart' => $perioChart])

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between items-center bg-white rounded-2xl shadow-sm p-6">
            <a href="{{ route('patients.perio-charts', $perioChart->patient) }}" class="inline-flex items-center px-5 py-3 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Charts
            </a>
            <div class="flex gap-3">
                <a href="{{ route('perio-charts.show', $perioChart) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-teal-700 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-eye mr-2"></i>
                    View Chart
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="{{ route('perio-charts.print', $perioChart) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200" target="_blank">
                    <i class="fas fa-print mr-2"></i>
                    Print
                    <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>
