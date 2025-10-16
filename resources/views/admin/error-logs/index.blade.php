<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                    Error Logs & Crash Reports
                </h2>
                <p class="text-gray-600 mt-2">Monitor and manage system errors across all clinics</p>
            </div>
            <div class="flex gap-3">
                @if($errorLogs->total() > 0)
                    <form method="POST" action="{{ route('admin.error-logs.clear-resolved') }}" onsubmit="return confirm('Clear all resolved error logs?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-gray-700 transition shadow-lg">
                            <i class="fas fa-trash mr-2"></i>
                            Clear Resolved
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 mr-3 mt-1"></i>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow-lg p-6 border-2 border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-600 text-sm font-semibold uppercase">New Errors</p>
                            <p class="text-4xl font-bold text-red-900 mt-2">{{ $newCount }}</p>
                        </div>
                        <div class="p-4 bg-red-200 rounded-2xl">
                            <i class="fas fa-bell text-red-600 text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl shadow-lg p-6 border-2 border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-600 text-sm font-semibold uppercase">Critical</p>
                            <p class="text-4xl font-bold text-orange-900 mt-2">{{ $criticalCount }}</p>
                        </div>
                        <div class="p-4 bg-orange-200 rounded-2xl">
                            <i class="fas fa-exclamation-circle text-orange-600 text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg p-6 border-2 border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-sm font-semibold uppercase">Total Logs</p>
                            <p class="text-4xl font-bold text-blue-900 mt-2">{{ $errorLogs->total() }}</p>
                        </div>
                        <div class="p-4 bg-blue-200 rounded-2xl">
                            <i class="fas fa-list text-blue-600 text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                @php
                    $filtersActive = request()->has('tenant_id') || request()->has('level') || request()->has('status');
                @endphp
                @if($filtersActive)
                    <div class="mb-4 px-4 py-2 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <p class="text-sm text-blue-700 font-medium">
                            <i class="fas fa-filter mr-2"></i>
                            Filters are active -
                            @if(request('tenant_id'))
                                <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold mr-1">
                                    Clinic: {{ $tenants->find(request('tenant_id'))->name ?? 'Unknown' }}
                                </span>
                            @endif
                            @if(request('level'))
                                <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold mr-1">
                                    Level: {{ ucfirst(request('level')) }}
                                </span>
                            @endif
                            @if(request('status'))
                                <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-semibold mr-1">
                                    Status: {{ ucfirst(request('status')) }}
                                </span>
                            @endif
                        </p>
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.error-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Clinic</label>
                        <select name="tenant_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Clinics</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                        <select name="level" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Levels</option>
                            <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>Error</option>
                            <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="ignored" {{ request('status') == 'ignored' ? 'selected' : '' }}>Ignored</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.error-logs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Error Logs Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                @if($errorLogs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Occurrences</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Clinic</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Level</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Message</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($errorLogs as $log)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium">{{ \Carbon\Carbon::parse($log->last_occurred)->format('M d, Y') }}</div>
                                            <div class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($log->last_occurred)->format('H:i:s') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-purple-100 text-purple-800">
                                                <i class="fas fa-redo mr-1"></i> {{ $log->occurrence_count }}x
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->tenant)
                                                <a href="{{ route('admin.tenants.show', $log->tenant) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                    {{ $log->tenant->name }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->level === 'critical')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> Critical
                                                </span>
                                            @elseif($log->level === 'warning')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Warning
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-orange-100 text-orange-800">
                                                    <i class="fas fa-bug mr-1"></i> Error
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-md truncate" title="{{ $log->message }}">
                                                {{ Str::limit($log->message, 80) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" data-status-cell>
                                            @if($log->status === 'new')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-bell mr-1"></i> New
                                                </span>
                                            @elseif($log->status === 'acknowledged')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                                    <i class="fas fa-eye mr-1"></i> Acknowledged
                                                </span>
                                            @elseif($log->status === 'resolved')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Resolved
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">
                                                    <i class="fas fa-ban mr-1"></i> Ignored
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" data-actions-cell>
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('admin.error-logs.show', $log->latest_id) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i> View
                                                </a>

                                                @if($log->status !== 'resolved')
                                                    <form method="POST" action="{{ route('admin.error-logs.update-status', $log->latest_id) }}" class="inline" data-ajax="true">
                                                        @csrf
                                                        <input type="hidden" name="status" value="resolved">
                                                        <button type="submit" class="text-green-600 hover:text-green-900 font-semibold" title="Mark as Resolved">
                                                            <i class="fas fa-check-circle"></i> Resolve
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($log->status === 'new')
                                                    <form method="POST" action="{{ route('admin.error-logs.update-status', $log->latest_id) }}" class="inline" data-ajax="true">
                                                        @csrf
                                                        <input type="hidden" name="status" value="ignored">
                                                        <button type="submit" class="text-gray-500 hover:text-gray-700" title="Ignore">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $errorLogs->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                        <p class="text-xl font-semibold text-gray-900 mb-2">No Error Logs Found</p>
                        <p class="text-gray-600">All systems are running smoothly!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // AJAX handler for resolve and ignore actions
        document.addEventListener('DOMContentLoaded', function() {
            // Handle all forms with data-ajax attribute
            document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const url = this.action;
                    const row = this.closest('tr');
                    const statusCell = row.querySelector('[data-status-cell]');
                    const actionsCell = row.querySelector('[data-actions-cell]');

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        if (response.ok) {
                            const newStatus = formData.get('status');

                            // Update status badge
                            if (newStatus === 'resolved') {
                                statusCell.innerHTML = `
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Resolved
                                    </span>
                                `;
                                // Remove resolve and ignore buttons
                                actionsCell.querySelectorAll('form[data-ajax="true"]').forEach(f => f.remove());
                            } else if (newStatus === 'ignored') {
                                statusCell.innerHTML = `
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-ban mr-1"></i> Ignored
                                    </span>
                                `;
                                // Remove ignore button, keep resolve button
                                this.remove();
                            }

                            // Show success toast
                            showToast('Status updated successfully!', 'success');
                        } else {
                            showToast('Failed to update status', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('An error occurred', 'error');
                    }
                });
            });
        });

        // Simple toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            toast.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</x-app-layout>
