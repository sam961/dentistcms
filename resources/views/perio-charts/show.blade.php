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
                        <i class="fas fa-teeth-open text-teal-600 mr-3"></i>
                        Periodontal Chart
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
                    <a href="{{ route('perio-charts.edit', $perioChart) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('perio-charts.print', $perioChart) }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-purple-700 hover:to-purple-800 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5" target="_blank">
                        <i class="fas fa-print mr-2"></i>
                        Print
                    </a>
                </div>
            </div>
        </div>

        <!-- Chart Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user text-blue-600 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-600">Patient</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $perioChart->patient->full_name }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-md text-green-600 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-600">Dentist</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $perioChart->dentist->name }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-calendar text-indigo-600 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-600">Chart Date</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($perioChart->chart_date)->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-tooth text-purple-600 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-600">Chart Type</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ ucfirst($perioChart->chart_type) }}
                            ({{ $perioChart->chart_type === 'adult' ? '32' : '20' }} teeth)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Bleeding on Probing</p>
                        <p class="text-3xl font-bold text-red-700 mt-2">{{ $perioChart->bleeding_percentage }}%</p>
                        <p class="text-xs text-red-600 mt-1">
                            @if($perioChart->bleeding_percentage < 10)
                                Excellent
                            @elseif($perioChart->bleeding_percentage < 20)
                                Good
                            @elseif($perioChart->bleeding_percentage < 30)
                                Fair
                            @else
                                Needs Attention
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-droplet text-red-400 text-4xl"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-600">Plaque Index</p>
                        <p class="text-3xl font-bold text-yellow-700 mt-2">{{ $perioChart->plaque_percentage }}%</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            @if($perioChart->plaque_percentage < 20)
                                Excellent
                            @elseif($perioChart->plaque_percentage < 40)
                                Good
                            @elseif($perioChart->plaque_percentage < 60)
                                Fair
                            @else
                                Needs Improvement
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-bacteria text-yellow-400 text-4xl"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Avg Pocket Depth</p>
                        <p class="text-3xl font-bold text-blue-700 mt-2">{{ $perioChart->average_pocket_depth }}mm</p>
                        <p class="text-xs text-blue-600 mt-1">
                            @if($perioChart->average_pocket_depth < 3)
                                Healthy
                            @elseif($perioChart->average_pocket_depth < 4)
                                Mild
                            @elseif($perioChart->average_pocket_depth < 6)
                                Moderate
                            @else
                                Severe
                            @endif
                        </p>
                    </div>
                    <i class="fas fa-ruler-vertical text-blue-400 text-4xl"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Overall Severity</p>
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
                    <i class="fas fa-exclamation-triangle text-gray-400 text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($perioChart->notes)
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    <i class="fas fa-notes-medical text-blue-600 mr-2"></i>
                    Clinical Notes
                </h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $perioChart->notes }}</p>
            </div>
        @endif

        <!-- Detailed Measurements by Quadrant -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                Detailed Measurements
            </h3>

            @php
                $quadrants = [
                    1 => ['name' => 'Upper Right', 'teeth' => $perioChart->chart_type === 'adult' ? range(1, 8) : range(1, 5)],
                    2 => ['name' => 'Upper Left', 'teeth' => $perioChart->chart_type === 'adult' ? range(9, 16) : range(6, 10)],
                    3 => ['name' => 'Lower Left', 'teeth' => $perioChart->chart_type === 'adult' ? range(17, 24) : range(11, 15)],
                    4 => ['name' => 'Lower Right', 'teeth' => $perioChart->chart_type === 'adult' ? range(25, 32) : range(16, 20)],
                ];
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($quadrants as $quadNum => $quadrant)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <h4 class="font-semibold text-gray-800 mb-4">
                            Quadrant {{ $quadNum }} - {{ $quadrant['name'] }}
                        </h4>
                        <div class="space-y-3">
                            @foreach($quadrant['teeth'] as $toothNum)
                                @php
                                    $measurement = $perioChart->measurements->where('tooth_number', $toothNum)->first();
                                @endphp
                                @if($measurement)
                                    <div class="flex items-center gap-3 p-3 rounded-lg {{ $measurement->missing ? 'bg-red-50' : ($measurement->implant ? 'bg-purple-50' : 'bg-gray-50') }}">
                                        <div class="w-12 text-center">
                                            <div class="text-lg font-bold text-gray-800">{{ $toothNum }}</div>
                                        </div>

                                        @if($measurement->missing)
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-red-700">Missing Tooth</span>
                                            </div>
                                        @elseif($measurement->implant)
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-purple-700">Implant</span>
                                            </div>
                                        @else
                                            <div class="flex-1 grid grid-cols-3 gap-2 text-xs">
                                                <div>
                                                    <span class="text-gray-600">Max PD:</span>
                                                    <span class="font-semibold {{ $measurement->max_pocket_depth >= 6 ? 'text-red-600' : ($measurement->max_pocket_depth >= 4 ? 'text-orange-600' : 'text-green-600') }}">
                                                        {{ $measurement->max_pocket_depth ?? 'N/A' }}mm
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">Mobility:</span>
                                                    <span class="font-semibold">{{ $measurement->mobility }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">Furcation:</span>
                                                    <span class="font-semibold">{{ $measurement->furcation }}</span>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                @if(collect([
                                                    $measurement->bop_mb, $measurement->bop_b, $measurement->bop_db,
                                                    $measurement->bop_ml, $measurement->bop_l, $measurement->bop_dl
                                                ])->filter()->count() > 0)
                                                    <i class="fas fa-droplet text-red-500" title="Bleeding on Probing"></i>
                                                @endif
                                                @if(collect([
                                                    $measurement->plaque_mb, $measurement->plaque_b, $measurement->plaque_db,
                                                    $measurement->plaque_ml, $measurement->plaque_l, $measurement->plaque_dl
                                                ])->filter()->count() > 0)
                                                    <i class="fas fa-bacteria text-yellow-500" title="Plaque Present"></i>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center bg-white rounded-2xl shadow-sm p-6">
            <a href="{{ route('patients.perio-charts', $perioChart->patient) }}" class="inline-flex items-center px-5 py-3 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Charts
            </a>
            <div class="flex gap-3">
                <a href="{{ route('perio-charts.edit', $perioChart) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Chart
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="{{ route('perio-charts.print', $perioChart) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200" target="_blank">
                    <i class="fas fa-print mr-2"></i>
                    Print
                    <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                </a>
                <div x-data="{ showDeleteModal: false }">
                    <button @click="showDeleteModal = true"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-trash mr-2"></i>
                        Delete
                    </button>

                    <!-- Delete Confirmation Modal -->
                    <div x-show="showDeleteModal"
                         x-cloak
                         @click.self="showDeleteModal = false"
                         class="fixed inset-0 z-50 overflow-y-auto"
                         style="display: none;">
                        <!-- Backdrop -->
                        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                        <!-- Modal Content -->
                        <div class="flex min-h-screen items-center justify-center p-4">
                            <div @click.away="showDeleteModal = false"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full p-6">

                                <!-- Warning Icon -->
                                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                                </div>

                                <!-- Title -->
                                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">
                                    Delete Periodontal Chart?
                                </h3>

                                <!-- Description -->
                                <div class="mb-6 space-y-3">
                                    <p class="text-gray-600 text-center">
                                        You are about to permanently delete this periodontal chart:
                                    </p>
                                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-red-500">
                                        <p class="text-sm text-gray-700">
                                            <i class="fas fa-user text-blue-600 mr-2"></i>
                                            <strong>Patient:</strong> {{ $perioChart->patient->full_name }}
                                        </p>
                                        <p class="text-sm text-gray-700 mt-1">
                                            <i class="fas fa-calendar text-indigo-600 mr-2"></i>
                                            <strong>Chart Date:</strong> {{ \Carbon\Carbon::parse($perioChart->chart_date)->format('F d, Y') }}
                                        </p>
                                        <p class="text-sm text-gray-700 mt-1">
                                            <i class="fas fa-tooth text-purple-600 mr-2"></i>
                                            <strong>Measurements:</strong> {{ $perioChart->measurements->count() }} teeth recorded
                                        </p>
                                    </div>
                                    <p class="text-red-600 text-sm text-center font-semibold">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        This action cannot be undone. All measurements will be permanently deleted.
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <button @click="showDeleteModal = false"
                                            type="button"
                                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                                        <i class="fas fa-times mr-2"></i>
                                        Cancel
                                    </button>
                                    <form action="{{ route('perio-charts.destroy', $perioChart) }}"
                                          method="POST"
                                          class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                            <i class="fas fa-trash mr-2"></i>
                                            Delete Chart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>
