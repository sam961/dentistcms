<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="font-bold text-3xl text-gray-900 leading-tight">
            <i class="fas fa-tooth text-blue-600 mr-3"></i>
            Dental Chart - {{ $patient->full_name }}
        </h2>
        <p class="text-gray-600 mt-2">Interactive dental chart showing tooth conditions and treatment history</p>
    </div>
            <a href="{{ route('patients.show', $patient) }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Patient Profile
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2">Patient Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Date of Birth:</span>
                <span class="ml-2 font-medium">{{ $patient->date_of_birth->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="text-gray-600">Age:</span>
                <span class="ml-2 font-medium">{{ $patient->date_of_birth->age }} years</span>
            </div>
            <div>
                <span class="text-gray-600">Chart Type:</span>
                <span class="ml-2 font-medium" id="chartType">Adult (Permanent)</span>
            </div>
        </div>
    </div>

    <div class="mb-4 flex flex-wrap gap-2">
        <button onclick="toggleChartType()" class="btn-modern btn-secondary text-sm">
            <i class="fas fa-exchange-alt mr-2"></i>
            Switch to Primary Teeth
        </button>
        <div class="flex items-center gap-4 ml-auto">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                <span class="text-sm">Healthy</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span class="text-sm">Cavity</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                <span class="text-sm">Filled</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
                <span class="text-sm">Crown</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                <span class="text-sm">Root Canal</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-500 rounded mr-2"></div>
                <span class="text-sm">Missing</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-indigo-500 rounded mr-2"></div>
                <span class="text-sm">Implant</span>
            </div>
        </div>
    </div>

    <!-- Dental Chart -->
    <div class="relative mx-auto" style="max-width: 800px;">
        <!-- Adult Teeth Chart -->
        <div id="adultChart" class="dental-chart">
            <!-- Upper Teeth -->
            <div class="teeth-row upper-teeth mb-4">
                <div class="flex justify-center gap-1">
                    @for($i = 1; $i <= 16; $i++)
                        @php
                            $toothData = $toothRecords->get((string)$i)?->first();
                            $condition = $toothData?->condition ?? 'healthy';
                            $color = \App\Models\ToothRecord::getConditionColor($condition);
                        @endphp
                        <div class="tooth-container">
                            <button
                                onclick="showToothHistory('{{ $i }}')"
                                class="tooth tooth-{{ $color }} relative hover:scale-110 transition-transform"
                                data-tooth="{{ $i }}"
                                title="Tooth #{{ $i }}"
                            >
                                <span class="tooth-number">{{ $i }}</span>
                                @if($toothData && $toothData->condition !== 'healthy')
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-yellow-400 rounded-full"></span>
                                @endif
                            </button>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Lower Teeth -->
            <div class="teeth-row lower-teeth">
                <div class="flex justify-center gap-1">
                    @for($i = 32; $i >= 17; $i--)
                        @php
                            $toothData = $toothRecords->get((string)$i)?->first();
                            $condition = $toothData?->condition ?? 'healthy';
                            $color = \App\Models\ToothRecord::getConditionColor($condition);
                        @endphp
                        <div class="tooth-container">
                            <button
                                onclick="showToothHistory('{{ $i }}')"
                                class="tooth tooth-{{ $color }} relative hover:scale-110 transition-transform"
                                data-tooth="{{ $i }}"
                                title="Tooth #{{ $i }}"
                            >
                                <span class="tooth-number">{{ $i }}</span>
                                @if($toothData && $toothData->condition !== 'healthy')
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-yellow-400 rounded-full"></span>
                                @endif
                            </button>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Primary Teeth Chart (Hidden by default) -->
        <div id="primaryChart" class="dental-chart hidden">
            <!-- Upper Primary Teeth -->
            <div class="teeth-row upper-teeth mb-4">
                <div class="flex justify-center gap-2">
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'] as $tooth)
                        @php
                            $toothData = $toothRecords->get($tooth)?->first();
                            $condition = $toothData?->condition ?? 'healthy';
                            $color = \App\Models\ToothRecord::getConditionColor($condition);
                        @endphp
                        <div class="tooth-container">
                            <button
                                onclick="showToothHistory('{{ $tooth }}')"
                                class="tooth tooth-primary tooth-{{ $color }} relative hover:scale-110 transition-transform"
                                data-tooth="{{ $tooth }}"
                                title="Primary Tooth {{ $tooth }}"
                            >
                                <span class="tooth-number">{{ $tooth }}</span>
                                @if($toothData && $toothData->condition !== 'healthy')
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-yellow-400 rounded-full"></span>
                                @endif
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Lower Primary Teeth -->
            <div class="teeth-row lower-teeth">
                <div class="flex justify-center gap-2">
                    @foreach(['T', 'S', 'R', 'Q', 'P', 'O', 'N', 'M', 'L', 'K'] as $tooth)
                        @php
                            $toothData = $toothRecords->get($tooth)?->first();
                            $condition = $toothData?->condition ?? 'healthy';
                            $color = \App\Models\ToothRecord::getConditionColor($condition);
                        @endphp
                        <div class="tooth-container">
                            <button
                                onclick="showToothHistory('{{ $tooth }}')"
                                class="tooth tooth-primary tooth-{{ $color }} relative hover:scale-110 transition-transform"
                                data-tooth="{{ $tooth }}"
                                title="Primary Tooth {{ $tooth }}"
                            >
                                <span class="tooth-number">{{ $tooth }}</span>
                                @if($toothData && $toothData->condition !== 'healthy')
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-yellow-400 rounded-full"></span>
                                @endif
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tooth Details Modal -->
<div id="toothModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900" id="modalToothTitle">Tooth Details</h3>
                    <p class="text-gray-600 mt-1" id="modalToothName"></p>
                </div>
                <button onclick="closeToothModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="toothHistoryContent" class="mt-4">
                <!-- History will be loaded here -->
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button onclick="openAddRecordForm()" class="btn-modern btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Add Record
                </button>
                <button onclick="closeToothModal()" class="btn-modern btn-secondary">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Record Form Modal -->
<div id="addRecordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-lg shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Add Tooth Record</h3>

            <form id="addRecordForm" onsubmit="saveToothRecord(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Condition</label>
                        <select name="condition" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="healthy">Healthy</option>
                            <option value="cavity">Cavity</option>
                            <option value="filled">Filled</option>
                            <option value="crown">Crown</option>
                            <option value="root_canal">Root Canal</option>
                            <option value="missing">Missing</option>
                            <option value="implant">Implant</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Treatment Date</label>
                        <input type="date" name="treatment_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Surface</label>
                        <select name="surface" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Surface</option>
                            <option value="mesial">Mesial</option>
                            <option value="distal">Distal</option>
                            <option value="occlusal">Occlusal</option>
                            <option value="buccal">Buccal</option>
                            <option value="lingual">Lingual</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                        <select name="severity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Severity</option>
                            <option value="mild">Mild</option>
                            <option value="moderate">Moderate</option>
                            <option value="severe">Severe</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="submit" class="btn-modern btn-primary">
                        Save Record
                    </button>
                    <button type="button" onclick="closeAddRecordForm()" class="btn-modern btn-secondary">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .tooth {
        width: 40px;
        height: 50px;
        border: 2px solid #ddd;
        border-radius: 8px 8px 12px 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: white;
        position: relative;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.3s;
    }

    .tooth-primary {
        width: 35px;
        height: 45px;
    }

    .tooth-number {
        color: #333;
    }

    .tooth-green {
        background-color: #10b981;
        border-color: #059669;
    }

    .tooth-red {
        background-color: #ef4444;
        border-color: #dc2626;
    }

    .tooth-blue {
        background-color: #3b82f6;
        border-color: #2563eb;
    }

    .tooth-purple {
        background-color: #a855f7;
        border-color: #9333ea;
    }

    .tooth-orange {
        background-color: #f97316;
        border-color: #ea580c;
    }

    .tooth-gray {
        background-color: #6b7280;
        border-color: #4b5563;
    }

    .tooth-indigo {
        background-color: #6366f1;
        border-color: #4f46e5;
    }

    .tooth:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .upper-teeth .tooth {
        border-radius: 12px 12px 8px 8px;
    }

    .lower-teeth .tooth {
        border-radius: 8px 8px 12px 12px;
    }
</style>

<script>
    let currentToothNumber = null;
    let currentChartType = 'adult';

    function toggleChartType() {
        const adultChart = document.getElementById('adultChart');
        const primaryChart = document.getElementById('primaryChart');
        const chartTypeText = document.getElementById('chartType');
        const toggleButton = event.target;

        if (currentChartType === 'adult') {
            adultChart.classList.add('hidden');
            primaryChart.classList.remove('hidden');
            chartTypeText.textContent = 'Child (Primary)';
            toggleButton.innerHTML = '<i class="fas fa-exchange-alt mr-2"></i>Switch to Permanent Teeth';
            currentChartType = 'primary';
        } else {
            primaryChart.classList.add('hidden');
            adultChart.classList.remove('hidden');
            chartTypeText.textContent = 'Adult (Permanent)';
            toggleButton.innerHTML = '<i class="fas fa-exchange-alt mr-2"></i>Switch to Primary Teeth';
            currentChartType = 'adult';
        }
    }

    async function showToothHistory(toothNumber) {
        currentToothNumber = toothNumber;
        const modal = document.getElementById('toothModal');
        const modalTitle = document.getElementById('modalToothTitle');
        const modalToothName = document.getElementById('modalToothName');
        const historyContent = document.getElementById('toothHistoryContent');

        modalTitle.textContent = `Tooth #${toothNumber}`;
        historyContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i></div>';
        modal.classList.remove('hidden');

        try {
            const response = await fetch(`/patients/{{ $patient->id }}/dental-chart/tooth/${toothNumber}`);
            const data = await response.json();

            modalToothName.textContent = data.tooth_name;

            if (data.records.length === 0) {
                historyContent.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-tooth text-4xl mb-3"></i>
                        <p>No treatment history for this tooth</p>
                    </div>
                `;
            } else {
                let historyHtml = '<div class="space-y-3 max-h-96 overflow-y-auto">';
                data.records.forEach(record => {
                    const conditionColor = getConditionBadgeColor(record.condition);
                    historyHtml += `
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${conditionColor}-100 text-${conditionColor}-800">
                                    ${record.condition.replace('_', ' ').toUpperCase()}
                                </span>
                                <span class="text-sm text-gray-500">${record.treatment_date || 'No date'}</span>
                            </div>
                            ${record.treatment ? `
                                <div class="mb-2">
                                    <span class="font-medium text-sm">Treatment:</span>
                                    <span class="text-sm text-gray-700 ml-1">${record.treatment.name}</span>
                                </div>
                            ` : ''}
                            ${record.dentist ? `
                                <div class="mb-2">
                                    <span class="font-medium text-sm">Dentist:</span>
                                    <span class="text-sm text-gray-700 ml-1">${record.dentist.name}</span>
                                </div>
                            ` : ''}
                            ${record.surface ? `
                                <div class="mb-2">
                                    <span class="font-medium text-sm">Surface:</span>
                                    <span class="text-sm text-gray-700 ml-1">${record.surface}</span>
                                </div>
                            ` : ''}
                            ${record.severity ? `
                                <div class="mb-2">
                                    <span class="font-medium text-sm">Severity:</span>
                                    <span class="text-sm text-gray-700 ml-1">${record.severity}</span>
                                </div>
                            ` : ''}
                            ${record.notes ? `
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">${record.notes}</p>
                                </div>
                            ` : ''}
                        </div>
                    `;
                });
                historyHtml += '</div>';
                historyContent.innerHTML = historyHtml;
            }
        } catch (error) {
            historyContent.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                    <p>Error loading tooth history</p>
                </div>
            `;
        }
    }

    function getConditionBadgeColor(condition) {
        const colors = {
            'healthy': 'green',
            'cavity': 'red',
            'filled': 'blue',
            'crown': 'purple',
            'root_canal': 'orange',
            'missing': 'gray',
            'implant': 'indigo'
        };
        return colors[condition] || 'gray';
    }

    function closeToothModal() {
        document.getElementById('toothModal').classList.add('hidden');
        currentToothNumber = null;
    }

    function openAddRecordForm() {
        document.getElementById('addRecordModal').classList.remove('hidden');
    }

    function closeAddRecordForm() {
        document.getElementById('addRecordModal').classList.add('hidden');
        document.getElementById('addRecordForm').reset();
    }

    async function saveToothRecord(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`/patients/{{ $patient->id }}/dental-chart/tooth/${currentToothNumber}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                closeAddRecordForm();
                showToothHistory(currentToothNumber);
                // Refresh the tooth color on the chart
                location.reload();
            } else {
                alert('Error saving record');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error saving record');
        }
    }
</script>
</x-app-sidebar-layout>