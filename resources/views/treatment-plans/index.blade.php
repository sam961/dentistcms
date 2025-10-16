<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-clipboard-list text-blue-600 mr-3"></i>
                    Treatment Plans
                </h2>
                <p class="text-gray-600 mt-2">Manage patient treatment plans and track progress</p>
            </div>
            <x-action-button href="{{ route('treatment-plans.create') }}" icon="plus" color="purple">
                New Treatment Plan
            </x-action-button>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm p-6">
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
        <div class="mb-6 flex flex-wrap gap-3">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Statuses</option>
                <option value="draft">Draft</option>
                <option value="presented">Presented</option>
                <option value="accepted">Accepted</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>

            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Phases</option>
                <option value="immediate">Immediate</option>
                <option value="soon">Soon</option>
                <option value="future">Future</option>
                <option value="optional">Optional</option>
            </select>
        </div>

        <!-- Treatment Plans Table -->
        @if($treatmentPlans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patient
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phase
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progress
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Cost
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dentist
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($treatmentPlans as $plan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $plan->patient->full_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: #{{ $plan->patient->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $plan->title }}</div>
                                    @if($plan->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $plan->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $plan->phase_color }}-100 text-{{ $plan->phase_color }}-800">
                                        {{ ucfirst($plan->phase) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $plan->status_color }}-100 text-{{ $plan->status_color }}-800">
                                        {{ str_replace('_', ' ', ucfirst($plan->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-1 mr-3">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $plan->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-600 font-medium">{{ $plan->progress_percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${{ number_format($plan->total_cost, 2) }}</div>
                                    <div class="text-sm text-gray-500">Patient: ${{ number_format($plan->patient_portion, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $plan->dentist->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('treatment-plans.show', $plan) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('treatment-plans.edit', $plan) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('treatment-plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this treatment plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
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
            <div class="mt-6">
                {{ $treatmentPlans->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Treatment Plans Yet</h3>
                <p class="text-gray-600 mb-6">Get started by creating your first treatment plan.</p>
                <a href="{{ route('treatment-plans.create') }}" class="btn-modern btn-primary inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Treatment Plan
                </a>
            </div>
        @endif
    </div>
</x-app-sidebar-layout>
