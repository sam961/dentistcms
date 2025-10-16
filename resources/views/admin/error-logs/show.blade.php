<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-bug text-red-600 mr-3"></i>
                    Error Log Details
                </h2>
                <p class="text-gray-600 mt-2">Detailed information about this error</p>
            </div>
            <a href="{{ route('admin.error-logs.index') }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Error Logs
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Error Status Card -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($errorLog->level === 'critical') bg-red-100 text-red-800
                            @elseif($errorLog->level === 'error') bg-orange-100 text-orange-800
                            @elseif($errorLog->level === 'warning') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ strtoupper($errorLog->level) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($errorLog->status === 'new') bg-purple-100 text-purple-800
                            @elseif($errorLog->status === 'acknowledged') bg-blue-100 text-blue-800
                            @elseif($errorLog->status === 'resolved') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($errorLog->status) }}
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $errorLog->message }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="far fa-clock mr-1"></i>
                        {{ $errorLog->created_at->format('F d, Y g:i A') }}
                        ({{ $errorLog->created_at->diffForHumans() }})
                    </p>
                </div>
            </div>

            <!-- Error Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-xs text-gray-500 mb-1">Tenant</div>
                    <div class="font-semibold text-gray-900">
                        @if($errorLog->tenant)
                            {{ $errorLog->tenant->name }}
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-xs text-gray-500 mb-1">User</div>
                    <div class="font-semibold text-gray-900">
                        @if($errorLog->user)
                            {{ $errorLog->user->name }}
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-xs text-gray-500 mb-1">URL</div>
                    <div class="font-mono text-xs text-gray-900 truncate" title="{{ $errorLog->url }}">
                        {{ $errorLog->url ?? 'N/A' }}
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-xs text-gray-500 mb-1">IP Address</div>
                    <div class="font-mono text-sm text-gray-900">
                        {{ $errorLog->ip_address ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Update Status Form -->
            <form action="{{ route('admin.error-logs.update-status', $errorLog) }}" method="POST" class="border-t pt-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="acknowledged" {{ $errorLog->status === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                            <option value="resolved" {{ $errorLog->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="ignored" {{ $errorLog->status === 'ignored' ? 'selected' : '' }}>Ignored</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <input type="text" name="notes" value="{{ $errorLog->notes }}"
                               placeholder="Add notes about this error..."
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-4 flex gap-3">
                    <button type="submit" class="btn-modern btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Update Status
                    </button>
                    <form action="{{ route('admin.error-logs.destroy', $errorLog) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this error log?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-modern bg-red-50 text-red-600 hover:bg-red-100">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </form>
        </div>

        <!-- Exception Details -->
        @if($errorLog->exception)
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                    Exception Details
                </h3>
                <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-sm text-green-400 font-mono">{{ $errorLog->exception }}</pre>
                </div>
            </div>
        @endif

        <!-- Stack Trace -->
        @if($errorLog->stack_trace)
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-layer-group text-blue-600 mr-2"></i>
                    Stack Trace
                </h3>
                <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-xs text-gray-300 font-mono">{{ $errorLog->stack_trace }}</pre>
                </div>
            </div>
        @endif

        <!-- Context Data -->
        @if($errorLog->context)
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                    Context Data
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-sm text-gray-700 font-mono">{{ json_encode($errorLog->context, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
