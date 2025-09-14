<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-procedures text-blue-600 mr-3"></i>
                    {{ __('Treatments') }}
                </h2>
                <p class="text-gray-600 mt-2">Manage dental treatment types and procedures</p>
            </div>
            <a href="{{ route('treatments.create') }}" class="btn-modern btn-primary inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add New Treatment
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
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

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" placeholder="Search treatments..." class="input-modern pl-10 w-full">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <select class="input-modern">
                            <option>All Categories</option>
                            <option>Preventive</option>
                            <option>Restorative</option>
                            <option>Cosmetic</option>
                            <option>Orthodontics</option>
                            <option>Oral Surgery</option>
                        </select>
                        <button class="btn-elegant bg-gray-100 text-gray-700 hover:bg-gray-200">
                            <i class="fas fa-filter mr-2"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-tooth text-blue-600 mr-2"></i>
                            Treatment Catalog
                        </h3>
                        <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm">
                            {{ \App\Models\Treatment::count() }} Total Treatments
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Treatment</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Follow-up</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach(\App\Models\Treatment::all() as $treatment)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-teal-600 rounded-xl flex items-center justify-center mr-3">
                                                <i class="fas fa-tooth text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $treatment->name }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($treatment->description, 40) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold
                                            @if($treatment->category === 'Preventive') bg-green-100 text-green-800
                                            @elseif($treatment->category === 'Restorative') bg-blue-100 text-blue-800
                                            @elseif($treatment->category === 'Cosmetic') bg-purple-100 text-purple-800
                                            @elseif($treatment->category === 'Orthodontics') bg-indigo-100 text-indigo-800
                                            @elseif($treatment->category === 'Oral Surgery') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $treatment->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900 flex items-center">
                                            <i class="fas fa-dollar-sign text-gray-400 mr-2"></i>
                                            ${{ number_format($treatment->price, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                                            {{ $treatment->duration }} min
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($treatment->requires_followup)
                                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Yes
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                No
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($treatment->is_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold bg-green-100 text-green-800">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600">
                                                <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('treatments.show', $treatment) }}" class="btn-elegant bg-blue-100 text-blue-700 hover:bg-blue-200 !px-3 !py-2 text-xs">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                            <a href="{{ route('treatments.edit', $treatment) }}" class="btn-elegant bg-indigo-100 text-indigo-700 hover:bg-indigo-200 !px-3 !py-2 text-xs">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-elegant bg-red-100 text-red-700 hover:bg-red-200 !px-3 !py-2 text-xs" onclick="return confirm('Are you sure you want to delete this treatment?')">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    </div>
</x-app-sidebar-layout>