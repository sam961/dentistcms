<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-user-md text-blue-600 mr-3"></i>
                    {{ __('Dentists') }}
                </h2>
                <p class="text-gray-600 mt-2">Manage dental professionals and staff members</p>
            </div>
            <x-action-button href="{{ route('dentists.create') }}" icon="user-md" color="teal">
                Add New Dentist
            </x-action-button>
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
                            <input type="text" placeholder="Search dentists..." class="input-modern pl-10 w-full">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <select class="input-modern">
                            <option>All Specializations</option>
                            <option>General Dentistry</option>
                            <option>Orthodontics</option>
                            <option>Periodontics</option>
                            <option>Endodontics</option>
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
                            <i class="fas fa-stethoscope text-blue-600 mr-2"></i>
                            Medical Staff Directory
                        </h3>
                        <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm">
                            {{ \App\Models\Dentist::count() }} Total Dentists
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Doctor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Specialization</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Experience</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse(\App\Models\Dentist::all() as $dentist)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center mr-3">
                                                <span class="text-white font-bold text-sm">
                                                    {{ strtoupper(substr($dentist->first_name, 0, 1) . substr($dentist->last_name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">Dr. {{ $dentist->full_name }}</div>
                                                <div class="text-xs text-gray-500">
                                                    <i class="fas fa-id-card mr-1"></i>
                                                    License: {{ $dentist->license_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <i class="fas fa-graduation-cap text-gray-400 mr-2"></i>
                                            {{ $dentist->specialization }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <i class="fas fa-trophy text-gray-400 mr-2"></i>
                                            {{ $dentist->years_of_experience }} years
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center mb-1">
                                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                            {{ $dentist->email }}
                                        </div>
                                        <div class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                                            {{ $dentist->phone }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($dentist->status === 'active')
                                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold bg-green-100 text-green-800">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold bg-red-100 text-red-800">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('dentists.show', $dentist) }}" class="btn-elegant bg-blue-100 text-blue-700 hover:bg-blue-200 !px-3 !py-2 text-xs">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                            <a href="{{ route('dentists.edit', $dentist) }}" class="btn-elegant bg-indigo-100 text-indigo-700 hover:bg-indigo-200 !px-3 !py-2 text-xs">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('dentists.destroy', $dentist) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-elegant bg-red-100 text-red-700 hover:bg-red-200 !px-3 !py-2 text-xs" onclick="return confirm('Are you sure you want to delete this dentist?')">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No dentists found. <a href="{{ route('dentists.create') }}" class="text-blue-600 hover:text-blue-900">Add the first dentist</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
    </div>
</x-app-sidebar-layout>