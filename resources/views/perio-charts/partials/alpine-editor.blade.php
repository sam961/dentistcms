@php
    $measurements = $perioChart->measurements->keyBy('tooth_number');
    $toothNumbers = $perioChart->chart_type === 'adult' ? range(1, 32) : range(1, 20);
@endphp

<div x-data="{
    quadrant: 1,
    selectedTooth: null,
    chartId: {{ $perioChart->id }},
    saving: false,

    getQuadrantTeeth() {
        const isAdult = '{{ $perioChart->chart_type }}' === 'adult';
        const quadrants = {
            1: isAdult ? [1,2,3,4,5,6,7,8] : [1,2,3,4,5],
            2: isAdult ? [9,10,11,12,13,14,15,16] : [6,7,8,9,10],
            3: isAdult ? [17,18,19,20,21,22,23,24] : [11,12,13,14,15],
            4: isAdult ? [25,26,27,28,29,30,31,32] : [16,17,18,19,20]
        };
        return quadrants[this.quadrant] || [];
    },

    selectTooth(tooth) {
        this.selectedTooth = tooth;
    },

    changeQuadrant(q) {
        this.quadrant = q;
        this.selectedTooth = null;
    },

    async updateMeasurement(toothNumber, field, value) {
        this.saving = true;
        try {
            const response = await fetch(`/perio-charts/${this.chartId}/measurement`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    tooth_number: toothNumber,
                    field: field,
                    value: value
                })
            });

            if (response.ok) {
                // Refresh the page to show updated stats
                location.reload();
            }
        } catch (error) {
            console.error('Error saving:', error);
        } finally {
            this.saving = false;
        }
    }
}" class="bg-white rounded-2xl shadow-sm p-6">

    <!-- Debug Info -->
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm">
        <strong>✅ Alpine.js Editor:</strong> Quadrant: <span class="font-bold text-green-600" x-text="quadrant"></span> |
        Selected Tooth: <span class="font-bold text-green-600" x-text="selectedTooth || 'None'"></span>
    </div>

    <!-- Quadrant Selector -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Quadrant (Click Below!)</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button @click="changeQuadrant(1)" type="button"
                    class="p-4 rounded-lg border-2 transition-all"
                    :class="quadrant === 1 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                <div class="text-center">
                    <div class="text-2xl font-bold" :class="quadrant === 1 ? 'text-blue-600' : 'text-gray-600'">Q1</div>
                    <div class="text-sm text-gray-600 mt-1">Upper Right</div>
                    <div class="text-xs text-gray-500">
                        {{ $perioChart->chart_type === 'adult' ? 'Teeth 1-8' : 'Teeth 1-5' }}
                    </div>
                </div>
            </button>

            <button @click="changeQuadrant(2)" type="button"
                    class="p-4 rounded-lg border-2 transition-all"
                    :class="quadrant === 2 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                <div class="text-center">
                    <div class="text-2xl font-bold" :class="quadrant === 2 ? 'text-blue-600' : 'text-gray-600'">Q2</div>
                    <div class="text-sm text-gray-600 mt-1">Upper Left</div>
                    <div class="text-xs text-gray-500">
                        {{ $perioChart->chart_type === 'adult' ? 'Teeth 9-16' : 'Teeth 6-10' }}
                    </div>
                </div>
            </button>

            <button @click="changeQuadrant(3)" type="button"
                    class="p-4 rounded-lg border-2 transition-all"
                    :class="quadrant === 3 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                <div class="text-center">
                    <div class="text-2xl font-bold" :class="quadrant === 3 ? 'text-blue-600' : 'text-gray-600'">Q3</div>
                    <div class="text-sm text-gray-600 mt-1">Lower Left</div>
                    <div class="text-xs text-gray-500">
                        {{ $perioChart->chart_type === 'adult' ? 'Teeth 17-24' : 'Teeth 11-15' }}
                    </div>
                </div>
            </button>

            <button @click="changeQuadrant(4)" type="button"
                    class="p-4 rounded-lg border-2 transition-all"
                    :class="quadrant === 4 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'">
                <div class="text-center">
                    <div class="text-2xl font-bold" :class="quadrant === 4 ? 'text-blue-600' : 'text-gray-600'">Q4</div>
                    <div class="text-sm text-gray-600 mt-1">Lower Right</div>
                    <div class="text-xs text-gray-500">
                        {{ $perioChart->chart_type === 'adult' ? 'Teeth 25-32' : 'Teeth 16-20' }}
                    </div>
                </div>
            </button>
        </div>
    </div>

    <!-- Teeth Grid -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            Teeth in Quadrant <span x-text="quadrant"></span>
        </h3>
        <div class="grid grid-cols-4 md:grid-cols-8 gap-3">
            <template x-for="tooth in getQuadrantTeeth()" :key="tooth">
                @foreach($toothNumbers as $toothNum)
                    @php
                        $m = $measurements->get($toothNum);
                        $bgColor = 'bg-gray-100';
                        if ($m) {
                            if ($m->missing) $bgColor = 'bg-red-100';
                            elseif ($m->implant) $bgColor = 'bg-purple-100';
                            elseif ($m->max_pocket_depth >= 6) $bgColor = 'bg-red-200';
                            elseif ($m->max_pocket_depth >= 4) $bgColor = 'bg-orange-200';
                            elseif ($m->max_pocket_depth >= 3) $bgColor = 'bg-yellow-200';
                            elseif ($m->max_pocket_depth > 0) $bgColor = 'bg-green-100';
                        }
                    @endphp
                @endforeach
                <button @click="selectTooth(tooth)" type="button"
                        x-show="getQuadrantTeeth().includes(tooth)"
                        class="p-4 rounded-lg border-2 transition-all {{ $bgColor }}"
                        :class="selectedTooth === tooth ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-300 hover:border-blue-400'">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-800" x-text="tooth"></div>
                    </div>
                </button>
            </template>
        </div>
    </div>

    <!-- Measurement Form -->
    <div x-show="selectedTooth" class="border-t-2 border-gray-200 pt-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">
            <i class="fas fa-tooth text-blue-600 mr-2"></i>
            Tooth #<span x-text="selectedTooth"></span> Measurements
        </h3>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <p class="text-sm text-yellow-700">
                <i class="fas fa-tools mr-2"></i>
                <strong>Full measurement form coming next!</strong> For now, click teeth to select them.
                The complete form with all measurement fields will be added shortly.
            </p>
        </div>

        <!-- Save indicator -->
        <div x-show="saving" class="mt-4 flex items-center justify-center text-blue-600">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            <span class="text-sm">Saving...</span>
        </div>
    </div>

    <div x-show="!selectedTooth" class="border-t-2 border-gray-200 pt-6 text-center text-gray-500">
        <i class="fas fa-hand-pointer text-4xl mb-3"></i>
        <p>Select a tooth above to enter measurements</p>
    </div>

    <!-- Legend -->
    <div class="mt-6 pt-6 border-t-2 border-gray-200">
        <h4 class="font-semibold text-gray-800 mb-3">Color Legend</h4>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-green-100 border border-gray-300 rounded"></div>
                <span class="text-sm text-gray-700">Healthy (< 3mm)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-yellow-200 border border-gray-300 rounded"></div>
                <span class="text-sm text-gray-700">Mild (3-4mm)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-orange-200 border border-gray-300 rounded"></div>
                <span class="text-sm text-gray-700">Moderate (4-6mm)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-red-200 border border-gray-300 rounded"></div>
                <span class="text-sm text-gray-700">Severe (≥ 6mm)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-red-100 border border-gray-300 rounded"></div>
                <span class="text-sm text-gray-700">Missing</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-purple-100 border border-gray-300 rounded"></div>
                <span class="text-sm text-gray-700">Implant</span>
            </div>
        </div>
    </div>
</div>
