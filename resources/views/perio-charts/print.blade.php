<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periodontal Chart - {{ $perioChart->patient->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-white p-8">
    <!-- Print Button -->
    <div class="no-print mb-6 flex justify-end">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center">
            <i class="fas fa-print mr-2"></i>
            Print
        </button>
    </div>

    <!-- Header -->
    <div class="border-b-4 border-blue-600 pb-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Periodontal Chart</h1>
                <p class="text-lg text-gray-700">{{ $perioChart->patient->full_name }}</p>
                <p class="text-sm text-gray-600">Chart Date: {{ \Carbon\Carbon::parse($perioChart->chart_date)->format('F d, Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Chart Type: <span class="font-semibold">{{ ucfirst($perioChart->chart_type) }}</span></p>
                <p class="text-sm text-gray-600">Dentist: <span class="font-semibold">{{ $perioChart->dentist->name }}</span></p>
                <p class="text-sm text-gray-600">Printed: {{ now()->format('F d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Summary Statistics</h2>
        <div class="grid grid-cols-4 gap-4">
            <div class="border-2 border-red-300 bg-red-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Bleeding on Probing</p>
                <p class="text-3xl font-bold text-red-700">{{ $perioChart->bleeding_percentage }}%</p>
            </div>
            <div class="border-2 border-yellow-300 bg-yellow-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Plaque Index</p>
                <p class="text-3xl font-bold text-yellow-700">{{ $perioChart->plaque_percentage }}%</p>
            </div>
            <div class="border-2 border-blue-300 bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Avg Pocket Depth</p>
                <p class="text-3xl font-bold text-blue-700">{{ $perioChart->average_pocket_depth }}mm</p>
            </div>
            <div class="border-2 border-gray-300 bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Overall Severity</p>
                <p class="text-3xl font-bold text-gray-700">{{ ucfirst($perioChart->severity_level) }}</p>
            </div>
        </div>
    </div>

    <!-- Clinical Notes -->
    @if($perioChart->notes)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Clinical Notes</h2>
            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                <p class="text-gray-700 whitespace-pre-line">{{ $perioChart->notes }}</p>
            </div>
        </div>
    @endif

    <!-- Detailed Measurements -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Detailed Measurements</h2>

        @php
            $quadrants = [
                1 => ['name' => 'Upper Right (UR)', 'teeth' => $perioChart->chart_type === 'adult' ? range(1, 8) : range(1, 5)],
                2 => ['name' => 'Upper Left (UL)', 'teeth' => $perioChart->chart_type === 'adult' ? range(9, 16) : range(6, 10)],
                3 => ['name' => 'Lower Left (LL)', 'teeth' => $perioChart->chart_type === 'adult' ? range(17, 24) : range(11, 15)],
                4 => ['name' => 'Lower Right (LR)', 'teeth' => $perioChart->chart_type === 'adult' ? range(25, 32) : range(16, 20)],
            ];
        @endphp

        @foreach($quadrants as $quadNum => $quadrant)
            <div class="mb-6 page-break-inside-avoid">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 bg-gray-100 px-4 py-2 rounded">
                    Quadrant {{ $quadNum }}: {{ $quadrant['name'] }}
                </h3>
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-3 py-2">Tooth</th>
                            <th class="border border-gray-300 px-2 py-2">Status</th>
                            <th class="border border-gray-300 px-2 py-2" colspan="6">Pocket Depth (mm)</th>
                            <th class="border border-gray-300 px-2 py-2" colspan="6">Gingival Margin (mm)</th>
                            <th class="border border-gray-300 px-2 py-2">Mob</th>
                            <th class="border border-gray-300 px-2 py-2">Furc</th>
                            <th class="border border-gray-300 px-2 py-2">BOP</th>
                            <th class="border border-gray-300 px-2 py-2">Plaque</th>
                        </tr>
                        <tr class="bg-gray-100 text-xs">
                            <th class="border border-gray-300 px-2 py-1">#</th>
                            <th class="border border-gray-300 px-2 py-1">-</th>
                            <th class="border border-gray-300 px-1 py-1">MB</th>
                            <th class="border border-gray-300 px-1 py-1">B</th>
                            <th class="border border-gray-300 px-1 py-1">DB</th>
                            <th class="border border-gray-300 px-1 py-1">ML</th>
                            <th class="border border-gray-300 px-1 py-1">L</th>
                            <th class="border border-gray-300 px-1 py-1">DL</th>
                            <th class="border border-gray-300 px-1 py-1">MB</th>
                            <th class="border border-gray-300 px-1 py-1">B</th>
                            <th class="border border-gray-300 px-1 py-1">DB</th>
                            <th class="border border-gray-300 px-1 py-1">ML</th>
                            <th class="border border-gray-300 px-1 py-1">L</th>
                            <th class="border border-gray-300 px-1 py-1">DL</th>
                            <th class="border border-gray-300 px-2 py-1">-</th>
                            <th class="border border-gray-300 px-2 py-1">-</th>
                            <th class="border border-gray-300 px-2 py-1">-</th>
                            <th class="border border-gray-300 px-2 py-1">-</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quadrant['teeth'] as $toothNum)
                            @php
                                $measurement = $perioChart->measurements->where('tooth_number', $toothNum)->first();
                            @endphp
                            @if($measurement)
                                <tr class="{{ $measurement->missing ? 'bg-red-50' : ($measurement->implant ? 'bg-purple-50' : '') }}">
                                    <td class="border border-gray-300 px-3 py-2 text-center font-bold">{{ $toothNum }}</td>
                                    <td class="border border-gray-300 px-2 py-2 text-center text-xs">
                                        @if($measurement->missing)
                                            <span class="text-red-600 font-semibold">Missing</span>
                                        @elseif($measurement->implant)
                                            <span class="text-purple-600 font-semibold">Implant</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <!-- Pocket Depth -->
                                    <td class="border border-gray-300 px-1 py-2 text-center {{ $measurement->pd_mb >= 6 ? 'bg-red-200' : ($measurement->pd_mb >= 4 ? 'bg-orange-200' : ($measurement->pd_mb >= 3 ? 'bg-yellow-200' : '')) }}">
                                        {{ $measurement->pd_mb ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-1 py-2 text-center {{ $measurement->pd_b >= 6 ? 'bg-red-200' : ($measurement->pd_b >= 4 ? 'bg-orange-200' : ($measurement->pd_b >= 3 ? 'bg-yellow-200' : '')) }}">
                                        {{ $measurement->pd_b ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-1 py-2 text-center {{ $measurement->pd_db >= 6 ? 'bg-red-200' : ($measurement->pd_db >= 4 ? 'bg-orange-200' : ($measurement->pd_db >= 3 ? 'bg-yellow-200' : '')) }}">
                                        {{ $measurement->pd_db ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-1 py-2 text-center {{ $measurement->pd_ml >= 6 ? 'bg-red-200' : ($measurement->pd_ml >= 4 ? 'bg-orange-200' : ($measurement->pd_ml >= 3 ? 'bg-yellow-200' : '')) }}">
                                        {{ $measurement->pd_ml ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-1 py-2 text-center {{ $measurement->pd_l >= 6 ? 'bg-red-200' : ($measurement->pd_l >= 4 ? 'bg-orange-200' : ($measurement->pd_l >= 3 ? 'bg-yellow-200' : '')) }}">
                                        {{ $measurement->pd_l ?? '-' }}
                                    </td>
                                    <td class="border border-gray-300 px-1 py-2 text-center {{ $measurement->pd_dl >= 6 ? 'bg-red-200' : ($measurement->pd_dl >= 4 ? 'bg-orange-200' : ($measurement->pd_dl >= 3 ? 'bg-yellow-200' : '')) }}">
                                        {{ $measurement->pd_dl ?? '-' }}
                                    </td>
                                    <!-- Gingival Margin -->
                                    <td class="border border-gray-300 px-1 py-2 text-center">{{ $measurement->gm_mb ?? '-' }}</td>
                                    <td class="border border-gray-300 px-1 py-2 text-center">{{ $measurement->gm_b ?? '-' }}</td>
                                    <td class="border border-gray-300 px-1 py-2 text-center">{{ $measurement->gm_db ?? '-' }}</td>
                                    <td class="border border-gray-300 px-1 py-2 text-center">{{ $measurement->gm_ml ?? '-' }}</td>
                                    <td class="border border-gray-300 px-1 py-2 text-center">{{ $measurement->gm_l ?? '-' }}</td>
                                    <td class="border border-gray-300 px-1 py-2 text-center">{{ $measurement->gm_dl ?? '-' }}</td>
                                    <!-- Mobility & Furcation -->
                                    <td class="border border-gray-300 px-2 py-2 text-center">{{ $measurement->mobility }}</td>
                                    <td class="border border-gray-300 px-2 py-2 text-center">{{ $measurement->furcation }}</td>
                                    <!-- BOP Count -->
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        {{ collect([
                                            $measurement->bop_mb, $measurement->bop_b, $measurement->bop_db,
                                            $measurement->bop_ml, $measurement->bop_l, $measurement->bop_dl
                                        ])->filter()->count() }}/6
                                    </td>
                                    <!-- Plaque Count -->
                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                        {{ collect([
                                            $measurement->plaque_mb, $measurement->plaque_b, $measurement->plaque_db,
                                            $measurement->plaque_ml, $measurement->plaque_l, $measurement->plaque_dl
                                        ])->filter()->count() }}/6
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <!-- Legend -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Legend</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <h3 class="font-semibold text-gray-800 mb-2">Pocket Depth Colors</h3>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 border border-gray-300"></div>
                        <span class="text-sm">< 3mm - Healthy</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-yellow-200 border border-gray-300"></div>
                        <span class="text-sm">3-4mm - Mild</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-orange-200 border border-gray-300"></div>
                        <span class="text-sm">4-6mm - Moderate</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-red-200 border border-gray-300"></div>
                        <span class="text-sm">≥ 6mm - Severe</span>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-2">Abbreviations</h3>
                <div class="space-y-1 text-sm">
                    <p><strong>MB:</strong> Mesio-Buccal, <strong>B:</strong> Buccal, <strong>DB:</strong> Disto-Buccal</p>
                    <p><strong>ML:</strong> Mesio-Lingual, <strong>L:</strong> Lingual, <strong>DL:</strong> Disto-Lingual</p>
                    <p><strong>BOP:</strong> Bleeding on Probing (count of 6 sites)</p>
                    <p><strong>Mob:</strong> Mobility (0-3), <strong>Furc:</strong> Furcation (0-3)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="border-t-2 border-gray-300 pt-4 text-center text-sm text-gray-600">
        <p>This is a confidential dental record. Do not share without patient consent.</p>
        <p class="mt-1">Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
