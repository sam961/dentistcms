<x-app-sidebar-layout>
    <div class="mb-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('perio-charts.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="flex-1">
                    <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                        <i class="fas fa-edit text-blue-600 mr-3"></i>
                        Edit Periodontal Chart
                    </h2>
                    <p class="text-gray-600 mt-2">
                        {{ $perioChart->patient->full_name }} - {{ \Carbon\Carbon::parse($perioChart->chart_date)->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('perio-charts.show', $perioChart) }}" class="btn-modern btn-secondary inline-flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        View
                    </a>
                    <a href="{{ route('perio-charts.print', $perioChart) }}" class="btn-modern btn-secondary inline-flex items-center" target="_blank">
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
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Chart Information</h3>
            <form method="POST" action="{{ route('perio-charts.update', $perioChart) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Patient -->
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
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
                        <label for="dentist_id" class="block text-sm font-medium text-gray-700 mb-2">Dentist</label>
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
                        <label for="chart_date" class="block text-sm font-medium text-gray-700 mb-2">Chart Date</label>
                        <input type="date" id="chart_date" name="chart_date" value="{{ $perioChart->chart_date }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full btn-modern btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Update Info
                        </button>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Chart notes...">{{ $perioChart->notes }}</textarea>
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

        <!-- Livewire Perio Chart Editor -->
        @livewire('perio-chart-editor', ['perioChartId' => $perioChart->id])

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between items-center bg-white rounded-2xl shadow-sm p-6">
            <a href="{{ route('perio-charts.index') }}" class="btn-modern btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
            <div class="flex gap-2">
                <a href="{{ route('perio-charts.show', $perioChart) }}" class="btn-modern btn-primary">
                    <i class="fas fa-eye mr-2"></i>
                    View Chart
                </a>
                <a href="{{ route('perio-charts.print', $perioChart) }}" class="btn-modern btn-secondary" target="_blank">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </a>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>
