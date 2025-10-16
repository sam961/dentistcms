<x-app-sidebar-layout>
    <div class="mb-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-teeth-open text-blue-600 mr-3"></i>
                    Periodontal Charting
                </h2>
                <p class="text-gray-600 mt-2">Track and monitor periodontal health over time</p>
            </div>
            <a href="{{ route('perio-charts.create') }}" class="btn-modern btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                New Perio Chart
            </a>
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

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('perio-charts.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Patient</label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Patient name..."
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Patient Filter -->
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select id="patient_id"
                            name="patient_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Patients</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dentist Filter -->
                <div>
                    <label for="dentist_id" class="block text-sm font-medium text-gray-700 mb-2">Dentist</label>
                    <select id="dentist_id"
                            name="dentist_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Dentists</option>
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->id }}" {{ request('dentist_id') == $dentist->id ? 'selected' : '' }}>
                                {{ $dentist->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date"
                           id="date_from"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date"
                           id="date_to"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Filter Buttons -->
                <div class="lg:col-span-5 flex gap-2">
                    <button type="submit" class="btn-modern btn-primary inline-flex items-center">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('perio-charts.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Charts Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            @if($perioCharts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Chart Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dentist
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statistics
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Severity
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($perioCharts as $chart)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $chart->patient->full_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ID: {{ $chart->patient->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($chart->chart_date)->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $chart->dentist->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $chart->chart_type === 'adult' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ ucfirst($chart->chart_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex flex-col gap-1">
                                            <span><i class="fas fa-droplet text-red-500 mr-1"></i> BOP: {{ $chart->bleeding_percentage }}%</span>
                                            <span><i class="fas fa-bacteria text-yellow-500 mr-1"></i> Plaque: {{ $chart->plaque_percentage }}%</span>
                                            <span><i class="fas fa-ruler-vertical text-blue-500 mr-1"></i> Avg PD: {{ $chart->average_pocket_depth }}mm</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $severityColors = [
                                                'healthy' => 'bg-green-100 text-green-800',
                                                'mild' => 'bg-yellow-100 text-yellow-800',
                                                'moderate' => 'bg-orange-100 text-orange-800',
                                                'severe' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $severityColors[$chart->severity_level] }}">
                                            {{ ucfirst($chart->severity_level) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('perio-charts.show', $chart) }}"
                                               class="text-blue-600 hover:text-blue-900"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('perio-charts.edit', $chart) }}"
                                               class="text-indigo-600 hover:text-indigo-900"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('perio-charts.print', $chart) }}"
                                               class="text-gray-600 hover:text-gray-900"
                                               title="Print"
                                               target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <form action="{{ route('perio-charts.destroy', $chart) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this perio chart?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $perioCharts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-teeth-open text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Perio Charts Found</h3>
                    <p class="text-gray-500 mb-4">Get started by creating your first periodontal chart.</p>
                    <a href="{{ route('perio-charts.create') }}" class="btn-modern btn-primary inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Perio Chart
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-sidebar-layout>
