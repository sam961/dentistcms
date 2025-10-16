<x-app-sidebar-layout>
    <div class="mb-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('patients.show', $patient) }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="flex-1">
                    <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                        <i class="fas fa-teeth-open text-teal-600 mr-3"></i>
                        Periodontal Charts
                    </h2>
                    <p class="text-gray-600 mt-2">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        {{ $patient->full_name }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-birthday-cake text-gray-400 mr-1"></i>
                        Age: {{ $patient->age }}
                        <span class="mx-2">•</span>
                        <i class="fas fa-venus-mars text-gray-400 mr-1"></i>
                        {{ ucfirst($patient->gender) }}
                    </p>
                </div>
                <a href="{{ route('perio-charts.create') }}?patient_id={{ $patient->id }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-teal-700 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-plus-circle mr-2"></i>
                    New Chart
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

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

        @if($patient->perioCharts->count() > 0)
            <!-- Statistics Overview -->
            @php
                $latestChart = $patient->perioCharts->first();
            @endphp
            <div class="mb-6 bg-gradient-to-br from-teal-50 to-blue-50 border border-teal-200 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-line text-teal-600 mr-2"></i>
                    Latest Assessment
                    <span class="text-sm font-normal text-gray-600 ml-2">
                        ({{ \Carbon\Carbon::parse($latestChart->chart_date)->format('M d, Y') }})
                    </span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <p class="text-sm text-gray-600 mb-1">Bleeding on Probing</p>
                        <p class="text-3xl font-bold text-red-600">{{ $latestChart->bleeding_percentage }}%</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($latestChart->bleeding_percentage < 10)
                                Excellent
                            @elseif($latestChart->bleeding_percentage < 20)
                                Good
                            @elseif($latestChart->bleeding_percentage < 30)
                                Fair
                            @else
                                Needs Attention
                            @endif
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <p class="text-sm text-gray-600 mb-1">Plaque Index</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $latestChart->plaque_percentage }}%</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($latestChart->plaque_percentage < 20)
                                Excellent
                            @elseif($latestChart->plaque_percentage < 40)
                                Good
                            @elseif($latestChart->plaque_percentage < 60)
                                Fair
                            @else
                                Needs Improvement
                            @endif
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <p class="text-sm text-gray-600 mb-1">Avg Pocket Depth</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $latestChart->average_pocket_depth }}mm</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($latestChart->average_pocket_depth < 3)
                                Healthy
                            @elseif($latestChart->average_pocket_depth < 4)
                                Mild
                            @elseif($latestChart->average_pocket_depth < 6)
                                Moderate
                            @else
                                Severe
                            @endif
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <p class="text-sm text-gray-600 mb-1">Overall Severity</p>
                        @php
                            $severityColors = [
                                'healthy' => 'text-green-600',
                                'mild' => 'text-yellow-600',
                                'moderate' => 'text-orange-600',
                                'severe' => 'text-red-600',
                            ];
                        @endphp
                        <p class="text-3xl font-bold {{ $severityColors[$latestChart->severity_level] }}">
                            {{ ucfirst($latestChart->severity_level) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Chart History -->
            <div class="bg-white rounded-2xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-history text-blue-600 mr-2"></i>
                        Chart History
                        <span class="text-sm font-normal text-gray-600 ml-2">({{ $patient->perioCharts->count() }} total)</span>
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($patient->perioCharts as $chart)
                            <div class="border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-200 hover:border-teal-300">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($chart->chart_date)->format('M d, Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-user-md text-gray-400 mr-1"></i>
                                            Dr. {{ $chart->dentist->full_name }}
                                        </p>
                                    </div>
                                    @php
                                        $severityBadges = [
                                            'healthy' => 'bg-green-100 text-green-800',
                                            'mild' => 'bg-yellow-100 text-yellow-800',
                                            'moderate' => 'bg-orange-100 text-orange-800',
                                            'severe' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $severityBadges[$chart->severity_level] }}">
                                        {{ ucfirst($chart->severity_level) }}
                                    </span>
                                </div>

                                <!-- Statistics -->
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    <div class="bg-red-50 rounded-lg p-2 text-center">
                                        <p class="text-lg font-bold text-red-600">{{ $chart->bleeding_percentage }}%</p>
                                        <p class="text-xs text-gray-600">BOP</p>
                                    </div>
                                    <div class="bg-yellow-50 rounded-lg p-2 text-center">
                                        <p class="text-lg font-bold text-yellow-600">{{ $chart->plaque_percentage }}%</p>
                                        <p class="text-xs text-gray-600">Plaque</p>
                                    </div>
                                    <div class="bg-blue-50 rounded-lg p-2 text-center">
                                        <p class="text-lg font-bold text-blue-600">{{ $chart->average_pocket_depth }}mm</p>
                                        <p class="text-xs text-gray-600">Avg PD</p>
                                    </div>
                                </div>

                                <!-- Notes Preview -->
                                @if($chart->notes)
                                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-gray-600">
                                            <i class="fas fa-notes-medical mr-1"></i>
                                            {{ Str::limit($chart->notes, 60) }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-2" x-data="{ showDeleteModal: false }">
                                    <a href="{{ route('perio-charts.show', $chart) }}" class="flex-1 text-center inline-flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                                        <i class="fas fa-eye mr-1.5"></i>
                                        View
                                    </a>
                                    <a href="{{ route('perio-charts.edit', $chart) }}" class="flex-1 text-center inline-flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                                        <i class="fas fa-edit mr-1.5"></i>
                                        Edit
                                    </a>
                                    <button @click="showDeleteModal = true" class="px-3 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                                        <i class="fas fa-trash"></i>
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
                                                        You are about to permanently delete this chart:
                                                    </p>
                                                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-red-500">
                                                        <p class="text-sm text-gray-700">
                                                            <i class="fas fa-calendar text-teal-500 mr-2"></i>
                                                            <strong>Date:</strong> {{ \Carbon\Carbon::parse($chart->chart_date)->format('F d, Y') }}
                                                        </p>
                                                        <p class="text-sm text-gray-700 mt-1">
                                                            <i class="fas fa-user-md text-gray-400 mr-2"></i>
                                                            <strong>Dentist:</strong> Dr. {{ $chart->dentist->full_name }}
                                                        </p>
                                                        <p class="text-sm text-gray-700 mt-1">
                                                            <i class="fas fa-tooth text-purple-600 mr-2"></i>
                                                            <strong>Measurements:</strong> {{ $chart->measurements->count() }} teeth recorded
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
                                                    <form action="{{ route('perio-charts.destroy', $chart) }}"
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
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 bg-teal-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-teeth-open text-teal-400 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Periodontal Charts Yet</h3>
                    <p class="text-gray-600 mb-6">
                        Start tracking {{ $patient->first_name }}'s periodontal health by creating the first chart.
                        This will help monitor pocket depths, bleeding, plaque levels, and overall gum health over time.
                    </p>
                    <a href="{{ route('perio-charts.create') }}?patient_id={{ $patient->id }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-teal-700 hover:to-teal-800 transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Create First Chart
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-sidebar-layout>
