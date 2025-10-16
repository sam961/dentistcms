<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('invoices.edit', $invoice) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit Invoice
                </a>
                <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Print Invoice
                </button>
                <a href="{{ route('invoices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Back to Invoices
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Invoice Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6" id="invoice-content">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">INVOICE</h1>
                            <p class="text-lg text-indigo-600 font-semibold mt-2">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">Dental Hub</div>
                            <p class="text-sm text-gray-600 mt-1">Dental Practice Management</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-2
                                {{ $invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $invoice->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Invoice Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <!-- Bill To -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Bill To:</h3>
                            <div class="space-y-1">
                                <p class="font-medium text-gray-900">{{ $invoice->patient->full_name }}</p>
                                <p class="text-sm text-gray-600">{{ $invoice->patient->email }}</p>
                                <p class="text-sm text-gray-600">{{ $invoice->patient->phone }}</p>
                                @if($invoice->patient->address)
                                    <p class="text-sm text-gray-600">{{ $invoice->patient->address }}</p>
                                @endif
                                @if($invoice->patient->city || $invoice->patient->state || $invoice->patient->zip_code)
                                    <p class="text-sm text-gray-600">
                                        {{ $invoice->patient->city }}{{ $invoice->patient->city && ($invoice->patient->state || $invoice->patient->zip_code) ? ', ' : '' }}
                                        {{ $invoice->patient->state }} {{ $invoice->patient->zip_code }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Invoice Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Invoice Information:</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Issue Date:</span>
                                    <span class="text-sm text-gray-900">{{ $invoice->issue_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Due Date:</span>
                                    <span class="text-sm text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</span>
                                </div>
                                @if($invoice->payment_method)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Payment Method:</span>
                                        <span class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</span>
                                    </div>
                                @endif
                                @if($invoice->appointment)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Related Appointment:</span>
                                        <span class="text-sm text-gray-900">{{ $invoice->appointment->appointment_date->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Payment Summary:</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Subtotal:</span>
                                    <span class="text-sm text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Tax:</span>
                                    <span class="text-sm text-gray-900">${{ number_format($invoice->tax_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold border-t pt-2">
                                    <span class="text-gray-900">Total:</span>
                                    <span class="text-indigo-600">${{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoice->items as $item)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->description }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="text-sm font-medium text-gray-900">${{ number_format($item->total_price, 2) }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Subtotal:</th>
                                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-900">${{ number_format($invoice->subtotal, 2) }}</th>
                                    </tr>
                                    @if($invoice->tax_amount > 0)
                                        <tr>
                                            <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Tax:</th>
                                            <th class="px-6 py-3 text-right text-sm font-medium text-gray-900">${{ number_format($invoice->tax_amount, 2) }}</th>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th colspan="3" class="px-6 py-3 text-right text-lg font-bold text-gray-900">Total:</th>
                                        <th class="px-6 py-3 text-right text-lg font-bold text-indigo-600">${{ number_format($invoice->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($invoice->notes)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $invoice->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Terms -->
                    <div class="border-t pt-6">
                        <div class="text-center text-sm text-gray-600">
                            <p>Thank you for your business!</p>
                            <p class="mt-2">Payment is due within 30 days of invoice date unless otherwise specified.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg print:hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        @if($invoice->status === 'pending')
                            <form action="{{ route('invoices.update', $invoice) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="paid">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    Mark as Paid
                                </button>
                            </form>
                        @endif

                        @if($invoice->status === 'pending')
                            <form action="{{ route('invoices.update', $invoice) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="overdue">
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    Mark as Overdue
                                </button>
                            </form>
                        @endif

                        @if($invoice->status !== 'cancelled')
                            <form action="{{ route('invoices.update', $invoice) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium" onclick="return confirm('Are you sure you want to cancel this invoice?')">
                                    Cancel Invoice
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('invoices.edit', $invoice) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium">
                            Edit Invoice
                        </a>

                        <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm font-medium">
                            Print Invoice
                        </button>

                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium" onclick="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')">
                                Delete Invoice
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .print\:hidden {
                display: none !important;
            }
            
            body * {
                visibility: hidden;
            }
            
            #invoice-content, #invoice-content * {
                visibility: visible;
            }
            
            #invoice-content {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</x-app-sidebar-layout>