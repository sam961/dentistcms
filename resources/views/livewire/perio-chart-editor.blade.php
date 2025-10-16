<div class="bg-white rounded-2xl shadow-sm p-6">
    <!-- Debug Info -->
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm">
        <strong>Livewire Debug:</strong> Component is loaded! Current Quadrant: <span class="font-bold text-blue-600">{{ $quadrant }}</span> |
        Selected Tooth: <span class="font-bold text-blue-600">{{ $selectedTooth ?? 'None' }}</span>
    </div>

    <!-- Quadrant Selector -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Quadrant (Click buttons below)</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button wire:click="changeQuadrant(1)"
                    type="button"
                    class="p-4 rounded-lg border-2 transition-all {{ $quadrant === 1 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                <div class="text-center">
                    <div class="text-2xl font-bold {{ $quadrant === 1 ? 'text-blue-600' : 'text-gray-600' }}">Q1</div>
                    <div class="text-sm text-gray-600 mt-1">Upper Right</div>
                    <div class="text-xs text-gray-500">
                        {{ $perioChart->chart_type === 'adult' ? 'Teeth 1-8' : 'Teeth 1-5' }}
                    </div>
                </div>
            </button>

            <button wire:click="changeQuadrant(2)"
                    type="button"
                    class="p-4 rounded-lg border-2 transition-all {{ $quadrant === 2 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                <div class="text-center">
                    <div class="text-2xl font-bold {{ $quadrant === 2 ? 'text-blue-600' : 'text-gray-600' }}">Q2</div>
                    <div class="text-sm text-gray-600 mt-1">Upper Left</div>
                    <div class="text-xs text-gray-500">
                        {{ $perioChart->chart_type === 'adult' ? 'Teeth 9-16' : 'Teeth 6-10' }}
                    </div>
                </div>
            </button>

            <button wire:click="changeQuadrant(3)"
                    type="button"
                    class="p-4 rounded-lg border-2 transition-all {{ $quadrant === 3 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                <div class="text-center">
                    <div class="text-2xl font-bold {{ $quadrant === 3 ? 'text-blue-600' : 'text-gray-600' }}">Q3</div>
                    <div class="text-sm text-gray-600 mt-1">Lower Left</div>
                    <div class="text-xs text-gray-500">
                        {{ $perioChart->chart_type === 'adult' ? 'Teeth 17-24' : 'Teeth 11-15' }}
                    </div>
                </div>
            </button>

            <button wire:click="changeQuadrant(4)"
                    type="button"
                    class="p-4 rounded-lg border-2 transition-all {{ $quadrant === 4 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                <div class="text-center">
                    <div class="text-2xl font-bold {{ $quadrant === 4 ? 'text-blue-600' : 'text-gray-600' }}">Q4</div>
                    <div class="text-sm text-gray-600 mt-1">Lower Right</div>
                    <div class="text-xs text-gray-500">
                        {{ $perioChart->chart_type === 'adult' ? 'Teeth 25-32' : 'Teeth 16-20' }}
                    </div>
                </div>
            </button>
        </div>
    </div>

    <!-- Teeth Grid for Selected Quadrant -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Teeth in Quadrant {{ $quadrant }}</h3>
        <div class="grid grid-cols-4 md:grid-cols-8 gap-3">
            @foreach($quadrantTeeth as $toothNumber)
                @php
                    $measurement = $measurements->get($toothNumber);
                    $isSelected = $selectedTooth === $toothNumber;
                    $bgColor = 'bg-gray-100';
                    if ($measurement) {
                        if ($measurement->missing) {
                            $bgColor = 'bg-red-100';
                        } elseif ($measurement->implant) {
                            $bgColor = 'bg-purple-100';
                        } elseif ($measurement->max_pocket_depth >= 6) {
                            $bgColor = 'bg-red-200';
                        } elseif ($measurement->max_pocket_depth >= 4) {
                            $bgColor = 'bg-orange-200';
                        } elseif ($measurement->max_pocket_depth >= 3) {
                            $bgColor = 'bg-yellow-200';
                        } elseif ($measurement->max_pocket_depth > 0) {
                            $bgColor = 'bg-green-100';
                        }
                    }
                @endphp
                <button wire:click="selectTooth({{ $toothNumber }})"
                        type="button"
                        class="p-4 rounded-lg border-2 transition-all {{ $isSelected ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-300 hover:border-blue-400' }} {{ $bgColor }}">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-800">{{ $toothNumber }}</div>
                        @if($measurement && $measurement->max_pocket_depth)
                            <div class="text-xs text-gray-600 mt-1">{{ $measurement->max_pocket_depth }}mm</div>
                        @endif
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Measurement Input Form -->
    @if($selectedTooth && $measurements->has($selectedTooth))
        @php
            $measurement = $measurements->get($selectedTooth);
        @endphp
        <div class="border-t-2 border-gray-200 pt-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-tooth text-blue-600 mr-2"></i>
                    Tooth #{{ $selectedTooth }} Measurements
                </h3>
                <div class="flex gap-2">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox"
                               wire:click="toggleBoolean({{ $selectedTooth }}, 'missing')"
                               {{ $measurement->missing ? 'checked' : '' }}
                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Missing</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox"
                               wire:click="toggleBoolean({{ $selectedTooth }}, 'implant')"
                               {{ $measurement->implant ? 'checked' : '' }}
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
                                       wire:change="updateMeasurement({{ $selectedTooth }}, 'pd_{{ $site }}', $event.target.value)"
                                       value="{{ $measurement->{'pd_'.$site} }}"
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
                                       wire:change="updateMeasurement({{ $selectedTooth }}, 'gm_{{ $site }}', $event.target.value)"
                                       value="{{ $measurement->{'gm_'.$site} }}"
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
                            <label class="flex flex-col items-center p-2 border rounded-lg cursor-pointer {{ $measurement->{'bop_'.$site} ? 'bg-red-50 border-red-300' : 'bg-gray-50 border-gray-300' }}">
                                <input type="checkbox"
                                       wire:click="toggleBoolean({{ $selectedTooth }}, 'bop_{{ $site }}')"
                                       {{ $measurement->{'bop_'.$site} ? 'checked' : '' }}
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
                            <label class="flex flex-col items-center p-2 border rounded-lg cursor-pointer {{ $measurement->{'plaque_'.$site} ? 'bg-yellow-50 border-yellow-300' : 'bg-gray-50 border-gray-300' }}">
                                <input type="checkbox"
                                       wire:click="toggleBoolean({{ $selectedTooth }}, 'plaque_{{ $site }}')"
                                       {{ $measurement->{'plaque_'.$site} ? 'checked' : '' }}
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
                            <select wire:change="updateMeasurement({{ $selectedTooth }}, 'mobility', $event.target.value)"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="0" {{ $measurement->mobility == 0 ? 'selected' : '' }}>0 - None</option>
                                <option value="1" {{ $measurement->mobility == 1 ? 'selected' : '' }}>1 - Slight</option>
                                <option value="2" {{ $measurement->mobility == 2 ? 'selected' : '' }}>2 - Moderate</option>
                                <option value="3" {{ $measurement->mobility == 3 ? 'selected' : '' }}>3 - Severe</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-sitemap text-purple-600 mr-2"></i>
                                Furcation (0-3)
                            </label>
                            <select wire:change="updateMeasurement({{ $selectedTooth }}, 'furcation', $event.target.value)"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="0" {{ $measurement->furcation == 0 ? 'selected' : '' }}>0 - None</option>
                                <option value="1" {{ $measurement->furcation == 1 ? 'selected' : '' }}>1 - Class I</option>
                                <option value="2" {{ $measurement->furcation == 2 ? 'selected' : '' }}>2 - Class II</option>
                                <option value="3" {{ $measurement->furcation == 3 ? 'selected' : '' }}>3 - Class III</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auto-save indicator -->
            <div wire:loading class="mt-4 flex items-center justify-center text-blue-600">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                <span class="text-sm">Saving...</span>
            </div>
        </div>
    @else
        <div class="border-t-2 border-gray-200 pt-6 text-center text-gray-500">
            <i class="fas fa-hand-pointer text-4xl mb-3"></i>
            <p>Select a tooth to enter measurements</p>
        </div>
    @endif

    <!-- Legend -->
    <div class="mt-6 pt-6 border-t-2 border-gray-200">
        <h4 class="font-semibold text-gray-800 mb-3">Color Legend</h4>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-green-100 border border-gray-300 rounded"></div>
                <span class="text-sm text-gray-700">Healthy (&lt; 3mm)</span>
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
