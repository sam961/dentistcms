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
                const data = await response.json();
                // Success! Data saved without page reload
                console.log('Saved:', data);
            }
        } catch (error) {
            console.error('Error saving:', error);
            alert('Error saving measurement. Please try again.');
        } finally {
            this.saving = false;
        }
    }
}" class="bg-white rounded-2xl shadow-sm p-6">

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
        @foreach($toothNumbers as $toothNum)
            @php $m = $measurements->get($toothNum); @endphp
            <template x-if="selectedTooth === {{ $toothNum }}">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">
                            <i class="fas fa-tooth text-blue-600 mr-2"></i>
                            Tooth #{{ $toothNum }} Measurements
                        </h3>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       {{ $m && $m->missing ? 'checked' : '' }}
                                       @change="updateMeasurement({{ $toothNum }}, 'missing', $event.target.checked)"
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">Missing</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       {{ $m && $m->implant ? 'checked' : '' }}
                                       @change="updateMeasurement({{ $toothNum }}, 'implant', $event.target.checked)"
                                       class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <span class="ml-2 text-sm text-gray-700">Implant</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Pocket Depth -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-ruler-vertical text-blue-600 mr-2"></i>
                                Pocket Depth (mm)
                            </h4>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach(['mb', 'b', 'db', 'ml', 'l', 'dl'] as $site)
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1 uppercase">{{ $site }}</label>
                                        <input type="number"
                                               value="{{ $m ? $m->{'pd_'.$site} : '' }}"
                                               @change="updateMeasurement({{ $toothNum }}, 'pd_{{ $site }}', $event.target.value)"
                                               min="0" max="15"
                                               class="w-full rounded-lg border-gray-300 text-center focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Gingival Margin -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-arrows-alt-v text-green-600 mr-2"></i>
                                Gingival Margin (mm)
                            </h4>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach(['mb', 'b', 'db', 'ml', 'l', 'dl'] as $site)
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1 uppercase">{{ $site }}</label>
                                        <input type="number"
                                               value="{{ $m ? $m->{'gm_'.$site} : '' }}"
                                               @change="updateMeasurement({{ $toothNum }}, 'gm_{{ $site }}', $event.target.value)"
                                               min="-10" max="10"
                                               class="w-full rounded-lg border-gray-300 text-center focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bleeding on Probing -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-droplet text-red-600 mr-2"></i>
                                Bleeding on Probing
                            </h4>
                            <div class="grid grid-cols-6 gap-2">
                                @foreach(['mb', 'b', 'db', 'ml', 'l', 'dl'] as $site)
                                    <label class="flex flex-col items-center p-2 border rounded-lg cursor-pointer {{ $m && $m->{'bop_'.$site} ? 'bg-red-50 border-red-300' : 'bg-gray-50 border-gray-300' }}">
                                        <input type="checkbox"
                                               {{ $m && $m->{'bop_'.$site} ? 'checked' : '' }}
                                               @change="updateMeasurement({{ $toothNum }}, 'bop_{{ $site }}', $event.target.checked)"
                                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="text-xs mt-1 uppercase">{{ $site }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Plaque Index -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-bacteria text-yellow-600 mr-2"></i>
                                Plaque Index
                            </h4>
                            <div class="grid grid-cols-6 gap-2">
                                @foreach(['mb', 'b', 'db', 'ml', 'l', 'dl'] as $site)
                                    <label class="flex flex-col items-center p-2 border rounded-lg cursor-pointer {{ $m && $m->{'plaque_'.$site} ? 'bg-yellow-50 border-yellow-300' : 'bg-gray-50 border-gray-300' }}">
                                        <input type="checkbox"
                                               {{ $m && $m->{'plaque_'.$site} ? 'checked' : '' }}
                                               @change="updateMeasurement({{ $toothNum }}, 'plaque_{{ $site }}', $event.target.checked)"
                                               class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                        <span class="text-xs mt-1 uppercase">{{ $site }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Mobility & Furcation -->
                        <div class="space-y-4 lg:col-span-2">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-arrows-alt text-orange-600 mr-2"></i>
                                        Mobility (0-3)
                                    </label>
                                    <select @change="updateMeasurement({{ $toothNum }}, 'mobility', $event.target.value)"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="0" {{ $m && $m->mobility == 0 ? 'selected' : '' }}>0 - None</option>
                                        <option value="1" {{ $m && $m->mobility == 1 ? 'selected' : '' }}>1 - Slight</option>
                                        <option value="2" {{ $m && $m->mobility == 2 ? 'selected' : '' }}>2 - Moderate</option>
                                        <option value="3" {{ $m && $m->mobility == 3 ? 'selected' : '' }}>3 - Severe</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-sitemap text-purple-600 mr-2"></i>
                                        Furcation (0-3)
                                    </label>
                                    <select @change="updateMeasurement({{ $toothNum }}, 'furcation', $event.target.value)"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="0" {{ $m && $m->furcation == 0 ? 'selected' : '' }}>0 - None</option>
                                        <option value="1" {{ $m && $m->furcation == 1 ? 'selected' : '' }}>1 - Class I</option>
                                        <option value="2" {{ $m && $m->furcation == 2 ? 'selected' : '' }}>2 - Class II</option>
                                        <option value="3" {{ $m && $m->furcation == 3 ? 'selected' : '' }}>3 - Class III</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save indicator -->
                    <div x-show="saving" class="mt-4 flex items-center justify-center text-blue-600">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        <span class="text-sm">Saving...</span>
                    </div>
                </div>
            </template>
        @endforeach
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
