<x-app-sidebar-layout>
    <div class="mb-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('perio-charts.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                        <i class="fas fa-columns text-blue-600 mr-3"></i>
                        Compare Periodontal Charts
                    </h2>
                    <p class="text-gray-600 mt-2">{{ $chart1->patient->full_name }} - Progress Comparison</p>
                </div>
            </div>
        </div>

        <!-- Comparison Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Chart 1 Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border-2 border-blue-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                        First Chart
                    </h3>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                        {{ \Carbon\Carbon::parse($chart1->chart_date)->format('M d, Y') }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <p class="text-xs text-gray-600">BOP</p>
                        <p class="text-2xl font-bold text-red-700">{{ $chart1->bleeding_percentage }}%</p>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded-lg">
                        <p class="text-xs text-gray-600">Plaque</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $chart1->plaque_percentage }}%</p>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-600">Avg PD</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $chart1->average_pocket_depth }}mm</p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Severity: <span class="font-semibold">{{ ucfirst($chart1->severity_level) }}</span></p>
                    <p class="text-sm text-gray-600">Dentist: <span class="font-semibold">{{ $chart1->dentist->name }}</span></p>
                </div>
            </div>

            <!-- Chart 2 Info -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border-2 border-green-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                        Second Chart
                    </h3>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                        {{ \Carbon\Carbon::parse($chart2->chart_date)->format('M d, Y') }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <p class="text-xs text-gray-600">BOP</p>
                        <p class="text-2xl font-bold text-red-700">{{ $chart2->bleeding_percentage }}%</p>
                        @php
                            $bopDiff = $chart2->bleeding_percentage - $chart1->bleeding_percentage;
                        @endphp
                        <p class="text-xs {{ $bopDiff < 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $bopDiff > 0 ? '+' : '' }}{{ $bopDiff }}%
                        </p>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded-lg">
                        <p class="text-xs text-gray-600">Plaque</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $chart2->plaque_percentage }}%</p>
                        @php
                            $plaqueDiff = $chart2->plaque_percentage - $chart1->plaque_percentage;
                        @endphp
                        <p class="text-xs {{ $plaqueDiff < 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $plaqueDiff > 0 ? '+' : '' }}{{ $plaqueDiff }}%
                        </p>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-600">Avg PD</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $chart2->average_pocket_depth }}mm</p>
                        @php
                            $pdDiff = $chart2->average_pocket_depth - $chart1->average_pocket_depth;
                        @endphp
                        <p class="text-xs {{ $pdDiff < 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $pdDiff > 0 ? '+' : '' }}{{ number_format($pdDiff, 1) }}mm
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Severity: <span class="font-semibold">{{ ucfirst($chart2->severity_level) }}</span></p>
                    <p class="text-sm text-gray-600">Dentist: <span class="font-semibold">{{ $chart2->dentist->name }}</span></p>
                </div>
            </div>
        </div>

        <!-- Progress Indicators -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                Overall Progress
            </h3>
            @php
                $bopChange = $chart2->bleeding_percentage - $chart1->bleeding_percentage;
                $plaqueChange = $chart2->plaque_percentage - $chart1->plaque_percentage;
                $pdChange = $chart2->average_pocket_depth - $chart1->average_pocket_depth;
                $improvementCount = 0;
                if ($bopChange < 0) $improvementCount++;
                if ($plaqueChange < 0) $improvementCount++;
                if ($pdChange < 0) $improvementCount++;
            @endphp

            @if($improvementCount >= 2)
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-green-800">Excellent Progress!</h4>
                            <p class="text-sm text-green-700 mt-1">Patient showing significant improvement in periodontal health.</p>
                        </div>
                    </div>
                </div>
            @elseif($improvementCount === 1)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-yellow-800">Mixed Results</h4>
                            <p class="text-sm text-yellow-700 mt-1">Some improvement noted, but some areas need more attention.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-red-400 text-2xl"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-red-800">Needs Attention</h4>
                            <p class="text-sm text-red-700 mt-1">Periodontal health has declined. Review treatment plan and patient compliance.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Tooth-by-Tooth Comparison -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-tooth text-blue-600 mr-2"></i>
                Tooth-by-Tooth Comparison
            </h3>

            @php
                $quadrants = [
                    1 => ['name' => 'Upper Right', 'teeth' => $chart1->chart_type === 'adult' ? range(1, 8) : range(1, 5)],
                    2 => ['name' => 'Upper Left', 'teeth' => $chart1->chart_type === 'adult' ? range(9, 16) : range(6, 10)],
                    3 => ['name' => 'Lower Left', 'teeth' => $chart1->chart_type === 'adult' ? range(17, 24) : range(11, 15)],
                    4 => ['name' => 'Lower Right', 'teeth' => $chart1->chart_type === 'adult' ? range(25, 32) : range(16, 20)],
                ];
            @endphp

            <div class="space-y-6">
                @foreach($quadrants as $quadNum => $quadrant)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <h4 class="font-semibold text-gray-800 mb-4">
                            Quadrant {{ $quadNum }} - {{ $quadrant['name'] }}
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="text-xs text-gray-600 border-b">
                                        <th class="py-2 text-center">Tooth</th>
                                        <th class="py-2 text-center">Chart 1<br>Max PD</th>
                                        <th class="py-2 text-center">Chart 2<br>Max PD</th>
                                        <th class="py-2 text-center">Change</th>
                                        <th class="py-2 text-center">Mobility</th>
                                        <th class="py-2 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quadrant['teeth'] as $toothNum)
                                        @php
                                            $m1 = $chart1->measurements->where('tooth_number', $toothNum)->first();
                                            $m2 = $chart2->measurements->where('tooth_number', $toothNum)->first();
                                        @endphp
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 text-center font-bold text-gray-800">{{ $toothNum }}</td>
                                            <td class="py-3 text-center">
                                                @if($m1 && !$m1->missing && !$m1->implant)
                                                    <span class="font-semibold">{{ $m1->max_pocket_depth ?? 'N/A' }}mm</span>
                                                @elseif($m1 && $m1->missing)
                                                    <span class="text-red-600 text-xs">Missing</span>
                                                @elseif($m1 && $m1->implant)
                                                    <span class="text-purple-600 text-xs">Implant</span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($m2 && !$m2->missing && !$m2->implant)
                                                    <span class="font-semibold">{{ $m2->max_pocket_depth ?? 'N/A' }}mm</span>
                                                @elseif($m2 && $m2->missing)
                                                    <span class="text-red-600 text-xs">Missing</span>
                                                @elseif($m2 && $m2->implant)
                                                    <span class="text-purple-600 text-xs">Implant</span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($m1 && $m2 && !$m1->missing && !$m2->missing && !$m1->implant && !$m2->implant)
                                                    @php
                                                        $pdDiff = ($m2->max_pocket_depth ?? 0) - ($m1->max_pocket_depth ?? 0);
                                                    @endphp
                                                    @if($pdDiff != 0)
                                                        <span class="font-semibold {{ $pdDiff < 0 ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $pdDiff > 0 ? '+' : '' }}{{ $pdDiff }}mm
                                                            @if($pdDiff < 0)
                                                                <i class="fas fa-arrow-down ml-1"></i>
                                                            @else
                                                                <i class="fas fa-arrow-up ml-1"></i>
                                                            @endif
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500">-</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($m2 && !$m2->missing)
                                                    <span class="text-xs">{{ $m2->mobility }}</span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center">
                                                <div class="flex justify-center gap-1">
                                                    @if($m2 && !$m2->missing && !$m2->implant)
                                                        @if(collect([$m2->bop_mb, $m2->bop_b, $m2->bop_db, $m2->bop_ml, $m2->bop_l, $m2->bop_dl])->filter()->count() > 0)
                                                            <i class="fas fa-droplet text-red-500 text-xs" title="Bleeding"></i>
                                                        @endif
                                                        @if(collect([$m2->plaque_mb, $m2->plaque_b, $m2->plaque_db, $m2->plaque_ml, $m2->plaque_l, $m2->plaque_dl])->filter()->count() > 0)
                                                            <i class="fas fa-bacteria text-yellow-500 text-xs" title="Plaque"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                <a href="{{ route('perio-charts.show', $chart1) }}" class="btn-modern btn-secondary">
                    View Chart 1
                </a>
                <a href="{{ route('perio-charts.show', $chart2) }}" class="btn-modern btn-secondary">
                    View Chart 2
                </a>
                <button onclick="window.print()" class="btn-modern btn-primary">
                    <i class="fas fa-print mr-2"></i>
                    Print Comparison
                </button>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>
