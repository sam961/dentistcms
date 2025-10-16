<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <x-back-button href="{{ route('treatment-plans.index') }}" text="Back to Treatment Plans" />

            <!-- Main Header with Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                        <i class="fas fa-clipboard-list text-blue-600 mr-3"></i>
                        {{ $treatmentPlan->title }}
                    </h2>
                    <p class="text-gray-600 mt-2">Treatment plan for {{ $treatmentPlan->patient->full_name }}</p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('treatment-plans.email', $treatmentPlan) }}" method="POST" class="inline">
                        @csrf
                        <x-action-button icon="envelope" color="blue" type="submit">
                            Email to Patient
                        </x-action-button>
                    </form>
                    <x-secondary-button href="{{ route('treatment-plans.edit', $treatmentPlan) }}" icon="edit">
                        Edit Plan
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </x-slot>

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

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Plan Overview Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Plan Overview</h3>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $treatmentPlan->status_color }}-100 text-{{ $treatmentPlan->status_color }}-800">
                            {{ str_replace('_', ' ', ucfirst($treatmentPlan->status)) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $treatmentPlan->phase_color }}-100 text-{{ $treatmentPlan->phase_color }}-800">
                            {{ ucfirst($treatmentPlan->phase) }} Priority
                        </span>
                    </div>
                </div>

                <!-- Quick Status Update -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="btn-modern btn-secondary text-sm">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Change Status
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 py-1">
                        @foreach(['draft', 'presented', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'] as $status)
                            @if($status !== $treatmentPlan->status)
                                <form action="{{ route('treatment-plans.update-status', [$treatmentPlan, $status]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ str_replace('_', ' ', ucwords($status)) }}
                                    </button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Patient</label>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $treatmentPlan->patient->full_name }}</p>
                            <p class="text-sm text-gray-500">ID: #{{ $treatmentPlan->patient->id }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Dentist</label>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-md text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $treatmentPlan->dentist->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $treatmentPlan->dentist->specialization }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($treatmentPlan->description)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                    <p class="text-sm text-gray-700">{{ $treatmentPlan->description }}</p>
                </div>
            @endif

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-500">Treatment Progress</label>
                    <span class="text-sm font-medium text-gray-700">{{ $treatmentPlan->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $treatmentPlan->progress_percentage }}%"></div>
                </div>
            </div>

            <!-- Timeline Dates -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if($treatmentPlan->presented_date)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Presented</label>
                        <p class="text-sm text-gray-900">{{ $treatmentPlan->presented_date->format('M d, Y') }}</p>
                    </div>
                @endif
                @if($treatmentPlan->accepted_date)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Accepted</label>
                        <p class="text-sm text-gray-900">{{ $treatmentPlan->accepted_date->format('M d, Y') }}</p>
                    </div>
                @endif
                @if($treatmentPlan->start_date)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Started</label>
                        <p class="text-sm text-gray-900">{{ $treatmentPlan->start_date->format('M d, Y') }}</p>
                    </div>
                @endif
                @if($treatmentPlan->completion_date)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Completed</label>
                        <p class="text-sm text-gray-900">{{ $treatmentPlan->completion_date->format('M d, Y') }}</p>
                    </div>
                @endif
            </div>

            @if($treatmentPlan->notes)
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-sm text-gray-700"><strong>Notes:</strong> {{ $treatmentPlan->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Financial Summary Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Summary</h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-sm text-gray-600">Total Cost</span>
                    <span class="text-lg font-semibold text-gray-900">${{ number_format($treatmentPlan->total_cost, 2) }}</span>
                </div>

                <div class="flex justify-between items-center pb-3 border-b">
                    <span class="text-sm text-gray-600">Insurance Coverage</span>
                    <span class="text-lg font-semibold text-green-600">${{ number_format($treatmentPlan->insurance_coverage, 2) }}</span>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-medium text-gray-900">Patient Portion</span>
                    <span class="text-xl font-bold text-blue-600">${{ number_format($treatmentPlan->patient_portion, 2) }}</span>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>{{ $treatmentPlan->items->count() }} treatment item(s) in this plan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Treatment Items -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Treatment Items</h3>
            <button onclick="document.getElementById('addItemModal').classList.remove('hidden')" class="btn-modern btn-primary text-sm">
                <i class="fas fa-plus mr-2"></i>
                Add Treatment
            </button>
        </div>

        @if($treatmentPlan->items->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Treatment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tooth</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Insurance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($treatmentPlan->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->treatment->name }}</div>
                                    @if($item->notes)
                                        <div class="text-xs text-gray-500">{{ $item->notes }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($item->tooth_number)
                                        #{{ $item->tooth_number }}
                                        @if($item->tooth_surface)
                                            <span class="text-gray-500">({{ $item->tooth_surface }})</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($item->unit_cost, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($item->total_cost, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">${{ number_format($item->insurance_estimate, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">${{ number_format($item->patient_cost, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $item->status_color }}-100 text-{{ $item->status_color }}-800">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($item->status === 'pending')
                                            <form action="{{ route('treatment-plans.update-item-status', [$treatmentPlan, $item, 'in_progress']) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:text-blue-900" title="Start">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($item->status === 'in_progress')
                                            <form action="{{ route('treatment-plans.update-item-status', [$treatmentPlan, $item, 'completed']) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Complete">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('treatment-plans.remove-item', [$treatmentPlan, $item]) }}" method="POST" class="inline" onsubmit="return confirm('Remove this treatment from the plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Remove">
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
        @else
            <div class="text-center py-8">
                <i class="fas fa-procedures text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-600">No treatments added yet. Click "Add Treatment" to begin.</p>
            </div>
        @endif
    </div>

    <!-- Add Item Modal -->
    <div id="addItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-lg shadow-lg rounded-2xl bg-white">
            <div class="mt-3">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Add Treatment to Plan</h3>

                <form action="{{ route('treatment-plans.add-item', $treatmentPlan) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Treatment *</label>
                            <select name="treatment_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select Treatment</option>
                                @foreach(\App\Models\Treatment::orderBy('name')->get() as $treatment)
                                    <option value="{{ $treatment->id }}">{{ $treatment->name }} - ${{ number_format($treatment->price, 2) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tooth Number</label>
                                <input type="text" name="tooth_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 14">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Surface</label>
                                <input type="text" name="tooth_surface" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Occlusal">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" name="quantity" min="1" value="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Estimate</label>
                                <input type="number" name="insurance_estimate" step="0.01" min="0" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('addItemModal').classList.add('hidden')" class="btn-modern btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn-modern btn-primary">
                            Add Treatment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-sidebar-layout>
