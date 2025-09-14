<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-notes-medical text-blue-600 mr-3"></i>
                    {{ __('Medical Records') }}
                </h2>
                <p class="text-gray-600 mt-2">View and manage patient medical history</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-file-medical text-blue-600 mr-2"></i>
                    Patient Medical History
                </h3>
                <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm">
                    {{ \App\Models\MedicalRecord::count() }} Total Records
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Visit Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dentist</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Chief Complaint</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Diagnosis</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse(\App\Models\MedicalRecord::with(['patient', 'dentist'])->get() as $record)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 flex items-center">
                                    <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                    {{ $record->visit_date->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">
                                            {{ strtoupper(substr($record->patient->first_name, 0, 1) . substr($record->patient->last_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ $record->patient->full_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 flex items-center">
                                    <i class="fas fa-user-md text-gray-400 mr-2"></i>
                                    Dr. {{ $record->dentist->full_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 flex items-start">
                                    <i class="fas fa-comment-medical text-gray-400 mr-2 mt-0.5"></i>
                                    {{ Str::limit($record->chief_complaint, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 flex items-start">
                                    <i class="fas fa-stethoscope text-gray-400 mr-2 mt-0.5"></i>
                                    {{ Str::limit($record->diagnosis, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('medical-records.show', $record) }}" class="btn-elegant bg-blue-100 text-blue-700 hover:bg-blue-200 !px-3 !py-2 text-xs">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No medical records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-sidebar-layout>