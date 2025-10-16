<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-users text-blue-600 mr-3"></i>
                    {{ __('Patients') }}
                </h2>
                <p class="text-gray-600 mt-2">Manage your patient database and records</p>
            </div>
            <x-action-button href="{{ route('patients.create') }}" icon="user-plus" color="blue">
                Add New Patient
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
        <form method="GET" action="{{ route('patients.index') }}">
            <!-- Main Search Bar -->
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search patients by name, email, phone, or ID..."
                        class="w-full pl-10 pr-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-base shadow-sm hover:shadow-md placeholder:text-gray-400"
                        autofocus>
                </div>
            </div>

            <!-- Filters Row -->
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <!-- Gender Filter -->
                <div class="flex-1 sm:flex-none sm:w-52">
                    <select
                        name="gender"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm bg-white">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- City Filter -->
                <div class="flex-1 sm:flex-none sm:w-52">
                    <select
                        name="city"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm bg-white">
                        <option value="">All Cities</option>
                        @foreach(\App\Models\Patient::whereNotNull('city')->distinct('city')->pluck('city')->sort() as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Spacer to push buttons to the right -->
                <div class="flex-1 hidden sm:block"></div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-search mr-2"></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'gender', 'city']))
                        <a
                            href="{{ route('patients.index') }}"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            <i class="fas fa-times mr-2"></i>
                            Clear
                        </a>
                    @endif
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'gender', 'city']))
                <div class="mt-4 flex items-center gap-2 flex-wrap">
                    <span class="text-sm text-gray-600 font-medium">Active Filters:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Search: "{{ request('search') }}"
                            <a href="{{ route('patients.index', array_diff_key(request()->all(), ['search' => ''])) }}" class="ml-2 hover:text-blue-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('gender'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Gender: {{ ucfirst(request('gender')) }}
                            <a href="{{ route('patients.index', array_diff_key(request()->all(), ['gender' => ''])) }}" class="ml-2 hover:text-purple-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('city'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            City: {{ request('city') }}
                            <a href="{{ route('patients.index', array_diff_key(request()->all(), ['city' => ''])) }}" class="ml-2 hover:text-green-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                </div>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-list text-blue-600 mr-2"></i>
                            Patient Directory
                        </h3>
                        <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm">
                            @if(request()->hasAny(['search', 'gender', 'city']))
                                {{ $patients->total() }} of {{ \App\Models\Patient::count() }} Patients
                            @else
                                {{ $patients->total() }} Total Patients
                            @endif
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($patients as $patient)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                                                <span class="text-white font-bold text-sm">
                                                    {{ strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $patient->full_name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Patient ID: #{{ $patient->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center mb-1">
                                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                            {{ $patient->email }}
                                        </div>
                                        <div class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                                            {{ $patient->phone }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center mb-1">
                                            <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                            {{ $patient->age }} years old
                                        </div>
                                        <div class="text-sm text-gray-600 flex items-center">
                                            <i class="fas fa-venus-mars text-gray-400 mr-2"></i>
                                            {{ ucfirst($patient->gender ?? 'Not specified') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                            {{ $patient->city ?: 'Not specified' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('patients.show', $patient) }}" class="btn-elegant bg-blue-100 text-blue-700 hover:bg-blue-200 !px-3 !py-2 text-xs">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                            <a href="{{ route('patients.edit', $patient) }}" class="btn-elegant bg-indigo-100 text-indigo-700 hover:bg-indigo-200 !px-3 !py-2 text-xs">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-elegant bg-red-100 text-red-700 hover:bg-red-200 !px-3 !py-2 text-xs" onclick="return confirm('Are you sure you want to delete this patient?')">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No patients found. <a href="{{ route('patients.create') }}" class="text-blue-600 hover:text-blue-900">Add the first patient</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
            @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</x-app-sidebar-layout>