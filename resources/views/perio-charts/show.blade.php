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
                        <i class="fas fa-teeth-open text-blue-600 mr-3"></i>
                        Periodontal Chart
                    </h2>
                    <p class="text-gray-600 mt-2">
                        {{ $perioChart->patient->full_name }} - {{ \Carbon\Carbon::parse($perioChart->chart_date)->format('M d, Y') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('perio-charts.edit', $perioChart) }}" class="btn-modern btn-primary inline-flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('perio-charts.print', $perioChart) }}" class="btn-modern btn-secondary inline-flex items-center" target="_blank">
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
            <a href="{{ route('perio-charts.index') }}" class="btn-modern btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
            </a>
            <div class="flex gap-2">
                <a href="{{ route('perio-charts.edit', $perioChart) }}" class="btn-modern btn-primary">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Chart
                </a>
                <a href="{{ route('perio-charts.print', $perioChart) }}" class="btn-modern btn-secondary" target="_blank">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </a>
                <form action="{{ route('perio-charts.destroy', $perioChart) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this perio chart? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern bg-red-600 hover:bg-red-700 text-white">
                        <i class="fas fa-trash mr-2"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>
